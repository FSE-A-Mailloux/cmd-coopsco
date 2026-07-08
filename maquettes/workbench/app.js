const state = {
  screens: [],
  selectedId: null,
  viewMode: "phone"
};

const ROLE_ORDER = ["famille", "gestionnaire", "admin"];
const ROLE_LABELS = {
  famille: "Famille",
  gestionnaire: "Gestionnaire",
  admin: "Admin",
  "non-classe": "Non classe"
};

const elements = {
  list: document.getElementById("screen-list"),
  count: document.getElementById("screen-count"),
  title: document.getElementById("preview-title"),
  category: document.getElementById("preview-category"),
  empty: document.getElementById("preview-empty"),
  frame: document.getElementById("preview-frame"),
  stage: document.getElementById("preview-stage"),
  viewButtons: Array.from(document.querySelectorAll("[data-view-mode]"))
};

function disableInnerFrameScroll(frameDocument) {
  if (!frameDocument || !frameDocument.head) {
    return;
  }

  const styleId = "workbench-no-inner-scroll";
  if (frameDocument.getElementById(styleId)) {
    return;
  }

  const style = frameDocument.createElement("style");
  style.id = styleId;
  style.textContent = "html, body { overflow: hidden !important; }";
  frameDocument.head.appendChild(style);
}

function syncFrameHeight() {
  const frameDocument = elements.frame.contentDocument;
  if (!frameDocument) {
    return;
  }

  disableInnerFrameScroll(frameDocument);

  const docElement = frameDocument.documentElement;
  const body = frameDocument.body;
  const nextHeight = Math.max(
    docElement ? docElement.scrollHeight : 0,
    body ? body.scrollHeight : 0,
    docElement ? docElement.offsetHeight : 0,
    body ? body.offsetHeight : 0,
    body ? Math.ceil(body.getBoundingClientRect().height) : 0
  );

  if (nextHeight > 0) {
    elements.frame.style.height = `${nextHeight + 4}px`;
  }
}

function scheduleFrameHeightSync() {
  window.requestAnimationFrame(() => {
    syncFrameHeight();
    window.setTimeout(syncFrameHeight, 80);
    window.setTimeout(syncFrameHeight, 180);
    window.setTimeout(syncFrameHeight, 320);
  });
}

function normalizeRole(role) {
 if (typeof role !== "string" || role.trim() === "") {
   return "non-classe";
 }
 return role.trim().toLowerCase();
}

function getRoleLabel(role) {
 return ROLE_LABELS[normalizeRole(role)] || role;
}

function getScreenRoles(screen) {
 if (Array.isArray(screen.roles) && screen.roles.length > 0) {
   const unique = [...new Set(screen.roles.map(normalizeRole))];
   return unique;
 }
 if (typeof screen.role === "string" && screen.role.trim() !== "") {
   return [normalizeRole(screen.role)];
 }
 return ["non-classe"];
}

function getScreenRoleLabels(screen) {
 return getScreenRoles(screen).map((role) => getRoleLabel(role));
}

function groupScreensByRole(screens) {
 return screens.reduce((groups, screen) => {
   getScreenRoles(screen).forEach((roleKey) => {
     if (!groups[roleKey]) {
       groups[roleKey] = [];
     }
     groups[roleKey].push(screen);
   });
   return groups;
 }, {});
}

function getScreenById(id) {
  return state.screens.find((screen) => screen.id === id) || null;
}

function showEmpty(title, message) {
  elements.title.textContent = title;
  elements.category.textContent = "";
  elements.empty.textContent = message;
  elements.empty.style.display = "block";
  elements.stage.classList.remove("visible");
  elements.frame.classList.remove("visible");
  elements.frame.removeAttribute("src");
  elements.frame.removeAttribute("srcdoc");
}

function updateQueryString(id, viewMode) {
  const url = new URL(window.location.href);
  url.searchParams.set("screen", id);
  url.searchParams.set("view", viewMode);
  window.history.replaceState({}, "", url.toString());
}

function setFrameSource(screen) {
  const useDirectFileMode = window.location.protocol === "file:";
  const sourceUrl = new URL(screen.source, window.location.href);
  elements.frame.style.height = "";

  if (useDirectFileMode) {
    elements.frame.removeAttribute("srcdoc");
    elements.frame.src = sourceUrl.toString();
    return;
  }

  fetch(sourceUrl.toString(), { cache: "no-store" })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Apercu indisponible");
      }
      return response.text();
    })
    .then((html) => {
      const withBase = html.includes("<head>")
        ? html.replace("<head>", `<head><base href="${sourceUrl.toString()}">`)
        : `<base href="${sourceUrl.toString()}">${html}`;
      elements.frame.srcdoc = withBase;
    })
    .catch(() => {
      showEmpty(
        "Apercu introuvable",
        "Le fichier source de cette maquette est absent ou invalide."
      );
    });
}

function renderPreview(screen) {
  const roleText = getScreenRoleLabels(screen).join(", ");
  elements.title.textContent = screen.title;
  elements.category.textContent = `${roleText} - ${screen.category} - ${screen.id}`;
  elements.empty.style.display = "none";
  elements.stage.classList.add("visible");
  elements.frame.classList.add("visible");
  setFrameSource(screen);
}

function setViewMode(mode, options = {}) {
  const { updateUrl = true } = options;
  const nextMode = mode === "desktop" ? "desktop" : "phone";
  state.viewMode = nextMode;

  elements.stage.classList.remove("mode-phone", "mode-desktop");
  elements.stage.classList.add(`mode-${nextMode}`);

  elements.viewButtons.forEach((button) => {
    const isActive = button.dataset.viewMode === nextMode;
    button.classList.toggle("active", isActive);
    button.setAttribute("aria-pressed", isActive ? "true" : "false");
  });

  if (updateUrl && state.selectedId) {
    updateQueryString(state.selectedId, nextMode);
  }
}

function selectScreen(id, options = {}) {
  const { updateUrl = true } = options;
  const screen = getScreenById(id);

  if (!screen) {
    showEmpty(
      "Ecran invalide",
      "La selection demandee n'existe pas dans le manifest."
    );
    state.selectedId = null;
    renderNavigation();
    return;
  }

  state.selectedId = screen.id;
  renderNavigation();
  renderPreview(screen);

  if (updateUrl) {
    updateQueryString(screen.id, state.viewMode);
  }
}

function createScreenButton(screen) {
  const button = document.createElement("button");
  button.type = "button";
  button.className = "screen-button";
  if (screen.id === state.selectedId) {
    button.classList.add("active");
  }
  button.innerHTML = `<strong>${screen.title}</strong><small>${screen.category} - ${screen.id}</small>`;
  button.addEventListener("click", () => selectScreen(screen.id));
  return button;
}

function renderNavigation() {
  elements.list.innerHTML = "";

  const grouped = groupScreensByRole(state.screens);
  const groupedRoles = Object.keys(grouped);
  const orderedRoles = [
    ...ROLE_ORDER.filter((role) => groupedRoles.includes(role)),
    ...groupedRoles.filter((role) => !ROLE_ORDER.includes(role)).sort()
  ];

  orderedRoles.forEach((role) => {
    const section = document.createElement("section");
    section.className = "screen-group";

    const title = document.createElement("h3");
    title.className = "screen-group-title";
    title.textContent = getRoleLabel(role);
    section.appendChild(title);

    grouped[role].forEach((screen) => {
      section.appendChild(createScreenButton(screen));
    });

    elements.list.appendChild(section);
  });
}

function getInitialSelection() {
  const selectedFromQuery = new URL(window.location.href).searchParams.get("screen");
  if (selectedFromQuery) {
    return selectedFromQuery;
  }
  return state.screens.length > 0 ? state.screens[0].id : null;
}

function getInitialViewMode() {
  const viewFromQuery = new URL(window.location.href).searchParams.get("view");
  if (viewFromQuery === "desktop" || viewFromQuery === "phone") {
    return viewFromQuery;
  }
  return "phone";
}

async function loadManifest() {
  if (Array.isArray(window.SCREEN_MANIFEST) && window.SCREEN_MANIFEST.length > 0) {
    return window.SCREEN_MANIFEST;
  }

  const response = await fetch("../manifest/screens.json", { cache: "no-store" });
  if (!response.ok) {
    throw new Error("Manifest inaccessible");
  }
  return response.json();
}

async function initializeWorkbench() {
  try {
    state.screens = await loadManifest();
  } catch (error) {
    showEmpty(
      "Manifest indisponible",
      "Impossible de charger la liste des ecrans maquettes."
    );
    return;
  }

  elements.count.textContent = `${state.screens.length} ecrans`;
  setViewMode(getInitialViewMode(), { updateUrl: false });
  renderNavigation();

  const initialSelection = getInitialSelection();
  if (!initialSelection) {
    showEmpty("Aucun ecran", "Le manifest ne contient aucun ecran.");
    return;
  }

  selectScreen(initialSelection, { updateUrl: false });
}

elements.frame.addEventListener("load", scheduleFrameHeightSync);
window.addEventListener("resize", scheduleFrameHeightSync);

elements.viewButtons.forEach((button) => {
  button.addEventListener("click", () => {
    setViewMode(button.dataset.viewMode || "phone");
    scheduleFrameHeightSync();
  });
});

initializeWorkbench();
