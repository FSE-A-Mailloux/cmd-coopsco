#Requires -Version 7.0
<#
.SYNOPSIS
    Controle structurel de la matrice de couverture des use-cases legacy.

.DESCRIPTION
    Verifie que docs/legacy-coverage-matrix.md ne ment pas sur le depot.

    Le controle est volontairement STRUCTUREL et jamais SEMANTIQUE : il ne juge
    pas si un artefact reference couvre reellement le comportement legacy, ce qui
    releve de la revue humaine. Confondre les deux roles affaiblirait les deux.

.OUTPUTS
    Code de sortie 0 si aucune anomalie bloquante, 1 sinon.
    Les descentes incompletes et les arbitrages ouverts sont restitues en
    avertissement et ne font pas echouer le controle.
#>
[CmdletBinding()]
param(
    [string]$RepoRoot = (Split-Path -Parent $PSScriptRoot)
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$UseCasesPath = Join-Path $RepoRoot 'docs/legacy-use-cases.md'
$MatrixPath   = Join-Path $RepoRoot 'docs/legacy-coverage-matrix.md'
$PrdPath      = Join-Path $RepoRoot 'openspec/prd-rewrite-agnostique.md'
$SpecsDir     = Join-Path $RepoRoot 'openspec/specs'
$ScreensPath  = Join-Path $RepoRoot 'maquettes/manifest/screens.json'

# Les libelles accentues sont construits par point de code pour rester insensibles
# a l'encodage sous lequel ce script est lu.
$DecisionRepris    = 'REPRIS'
$DecisionAbandonne = "ABANDONN$([char]0xC9)"
$DecisionACadrer   = "$([char]0xC0) CADRER"
$DecisionATrancher = "$([char]0xC0) TRANCHER"
$ValidDecisions    = @($DecisionRepris, $DecisionAbandonne, $DecisionACadrer, $DecisionATrancher)

# Valeur signalant qu'aucune maquette n'est attendue, a distinguer d'une maquette absente.
$NotApplicable = 'N/A'
$EmptyMark     = [char]0x2014

$blocking = [System.Collections.Generic.List[string]]::new()
$warnings = [System.Collections.Generic.List[string]]::new()
$scoping  = [System.Collections.Generic.List[string]]::new()
$pending  = [System.Collections.Generic.List[string]]::new()

function Read-Utf8 {
    param([string]$Path)
    if (-not (Test-Path -LiteralPath $Path)) {
        throw "Fichier introuvable : $Path"
    }
    Get-Content -LiteralPath $Path -Raw -Encoding UTF8
}

function Split-Reference {
    <# Normalise une cellule de rattachement en liste de references exploitables. #>
    param([string]$Cell)
    $value = $Cell.Trim()
    if ([string]::IsNullOrWhiteSpace($value) -or $value -eq $EmptyMark -or $value -eq $NotApplicable) {
        return @()
    }
    return @($value -split ',' |
        ForEach-Object { $_.Trim().Trim('`') } |
        Where-Object { $_ -ne '' })
}

# --- Referentiels d'entree -------------------------------------------------

$useCasesRaw = Read-Utf8 $UseCasesPath
$knownUseCases = @([regex]::Matches($useCasesRaw, '(?m)^###\s+(UC-[A-Z]+-\d+)') |
    ForEach-Object { $_.Groups[1].Value })

$prdRaw = Read-Utf8 $PrdPath
$knownPrdIds = @([regex]::Matches($prdRaw, '\*\*(FR-\d+|FACT-\d+|COTIS-\d+)\*\*') |
    ForEach-Object { $_.Groups[1].Value } |
    Sort-Object -Unique)

$knownSpecs = @(Get-ChildItem -LiteralPath $SpecsDir -Directory |
    Where-Object { Test-Path -LiteralPath (Join-Path $_.FullName 'spec.md') } |
    Select-Object -ExpandProperty Name)

$knownScreens = @((Read-Utf8 $ScreensPath | ConvertFrom-Json).id)

# --- Lecture de la matrice -------------------------------------------------

$matrixRaw = Read-Utf8 $MatrixPath
$rows  = @{}
$order = [System.Collections.Generic.List[string]]::new()

foreach ($line in ($matrixRaw -split "`r?`n")) {
    if ($line -notmatch '^\|\s*(UC-[A-Z]+-\d+)\s*\|') { continue }

    # Une ligne "| a | b |" se scinde en cellules encadrees de vides : on retire les bornes.
    $parts = $line -split '\|'
    $cells = @($parts[1..($parts.Count - 2)] | ForEach-Object { $_.Trim() })

    if ($cells.Count -ne 7) {
        $blocking.Add("Ligne de matrice malformee ($($cells.Count) colonnes au lieu de 7) : $($cells[0])")
        continue
    }

    $id = $cells[0]
    if ($rows.ContainsKey($id)) {
        $blocking.Add("$id figure plusieurs fois dans la matrice")
        continue
    }

    $rows[$id] = [pscustomobject]@{
        Id            = $id
        Libelle       = $cells[1]
        Decision      = $cells[2]
        Prd           = $cells[3]
        Spec          = $cells[4]
        Maquette      = $cells[5]
        Justification = $cells[6]
    }
    $order.Add($id)
}

# --- 1. Exhaustivite -------------------------------------------------------

foreach ($id in $knownUseCases) {
    if (-not $rows.ContainsKey($id)) {
        $blocking.Add("$id est cartographie dans legacy-use-cases.md mais absent de la matrice")
    }
}

foreach ($id in $order) {
    if ($knownUseCases -notcontains $id) {
        $blocking.Add("$id figure dans la matrice mais est inconnu de legacy-use-cases.md")
    }
}

# --- 2 a 5. Controles par ligne --------------------------------------------

foreach ($id in $order) {
    $row = $rows[$id]

    # 2. Validite de la decision. Une cellule vide est une anomalie, jamais un abandon implicite.
    if ([string]::IsNullOrWhiteSpace($row.Decision)) {
        $blocking.Add("$id ne porte aucune decision")
        continue
    }
    if ($ValidDecisions -notcontains $row.Decision) {
        $blocking.Add("$id porte une decision hors des valeurs autorisees : '$($row.Decision)'")
        continue
    }

    $prdRefs      = @(Split-Reference $row.Prd)
    $specRefs     = @(Split-Reference $row.Spec)
    $maquetteRefs = @(Split-Reference $row.Maquette)

    # 3. Rattachements obligatoires selon la decision.
    if ($row.Decision -eq $DecisionRepris) {
        if ($prdRefs.Count -eq 0 -and $specRefs.Count -eq 0 -and $maquetteRefs.Count -eq 0) {
            $blocking.Add("$id est REPRIS sans referencer aucun artefact")
        }
    }
    elseif ($row.Decision -eq $DecisionAbandonne) {
        if ([string]::IsNullOrWhiteSpace($row.Justification)) {
            $blocking.Add("$id est abandonne sans justification metier")
        }
    }
    elseif ($row.Decision -eq $DecisionACadrer) {
        # Decision de principe rendue : le use-case n'est pas abandonne, mais son
        # perimetre reste a determiner. La nature du cadrage attendu est obligatoire.
        if ([string]::IsNullOrWhiteSpace($row.Justification)) {
            $blocking.Add("$id est en cadrage sans description du cadrage attendu")
        }
        $scoping.Add("$id : $($row.Libelle)")
    }
    else {
        $pending.Add("$id : $($row.Libelle)")
    }

    # 4. Existence effective des artefacts references.
    foreach ($ref in $prdRefs) {
        if ($knownPrdIds -notcontains $ref) {
            $blocking.Add("$id reference l'exigence PRD inexistante '$ref'")
        }
    }
    foreach ($ref in $specRefs) {
        if ($knownSpecs -notcontains $ref) {
            $blocking.Add("$id reference la capacite inexistante '$ref'")
        }
    }
    foreach ($ref in $maquetteRefs) {
        if ($knownScreens -notcontains $ref) {
            $blocking.Add("$id reference l'ecran inexistant '$ref'")
        }
    }

    # 5. Descente incomplete : decide mais pas entierement redescendu.
    if ($row.Decision -eq $DecisionRepris) {
        $missing = [System.Collections.Generic.List[string]]::new()
        if ($prdRefs.Count -eq 0)  { $missing.Add('PRD') }
        if ($specRefs.Count -eq 0) { $missing.Add('spec') }
        # N/A signifie qu'aucune maquette n'est attendue : ce n'est pas une descente incomplete.
        if ($maquetteRefs.Count -eq 0 -and $row.Maquette -ne $NotApplicable) {
            $missing.Add('maquette')
        }
        if ($missing.Count -gt 0) {
            $warnings.Add("$id : repris sans $($missing -join ', ')")
        }
    }
}

# --- Restitution -----------------------------------------------------------

$sep = '-' * 72

Write-Host ''
Write-Host 'Controle de couverture des use-cases legacy'
Write-Host $sep
Write-Host ('Use-cases cartographies : {0}' -f $knownUseCases.Count)
Write-Host ('Lignes de matrice       : {0}' -f $order.Count)
Write-Host ('Exigences PRD connues   : {0}' -f $knownPrdIds.Count)
Write-Host ('Capacites connues       : {0}' -f $knownSpecs.Count)
Write-Host ('Ecrans connus           : {0}' -f $knownScreens.Count)

$byDecision = $order |
    Where-Object { $ValidDecisions -contains $rows[$_].Decision } |
    Group-Object { $rows[$_].Decision } |
    Sort-Object Name
if ($byDecision) {
    Write-Host ''
    Write-Host 'Repartition des decisions'
    foreach ($group in $byDecision) {
        Write-Host ('  {0,-12} {1}' -f $group.Name, $group.Count)
    }
}

if ($warnings.Count -gt 0) {
    Write-Host ''
    Write-Host "Descentes incompletes ($($warnings.Count)) - avertissement, non bloquant" -ForegroundColor Yellow
    foreach ($w in $warnings) { Write-Host "  $w" -ForegroundColor Yellow }
}

if ($scoping.Count -gt 0) {
    Write-Host ''
    Write-Host "Cadrages en cours ($($scoping.Count)) - decision de principe rendue, perimetre a definir" -ForegroundColor Yellow
    foreach ($s in $scoping) { Write-Host "  $s" -ForegroundColor Yellow }
}

if ($pending.Count -gt 0) {
    Write-Host ''
    Write-Host "Arbitrages ouverts ($($pending.Count)) - a instruire avant cloture de jalon" -ForegroundColor Yellow
    foreach ($p in $pending) { Write-Host "  $p" -ForegroundColor Yellow }
}

Write-Host ''
if ($blocking.Count -gt 0) {
    Write-Host "Anomalies bloquantes ($($blocking.Count))" -ForegroundColor Red
    foreach ($b in $blocking) { Write-Host "  $b" -ForegroundColor Red }
    Write-Host ''
    Write-Host $sep
    Write-Host 'ECHEC : la matrice de couverture est incoherente.' -ForegroundColor Red
    exit 1
}

Write-Host $sep
Write-Host 'SUCCES : chaque use-case legacy porte une decision coherente.' -ForegroundColor Green
Write-Host 'Rappel : ce controle est structurel et ne verifie pas la couverture reelle.'
exit 0
