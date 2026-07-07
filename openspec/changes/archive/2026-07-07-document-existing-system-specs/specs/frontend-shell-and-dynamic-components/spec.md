## ADDED Requirements

### Requirement: Frontend shell MUST provide a stable application frame
The frontend MUST bootstrap a single-page AngularJS shell with a fixed header, authentication area, menu area, and main component container.

#### Scenario: Initial shell rendering
- **WHEN** the user opens `app/index.html`
- **THEN** the application renders login controls, menu container, and a dynamic component host in the main area

#### Scenario: Startup route resolution
- **WHEN** the URL does not contain a supported `action` query parameter
- **THEN** the application loads the default component

### Requirement: Component navigation MUST be stack-based
The frontend MUST manage component transitions through a stack that supports push, replace of current params, back navigation, and return-to-specific-component behavior.

#### Scenario: Loading a component
- **WHEN** `loadComponent` is invoked with a component code
- **THEN** the frontend resolves component metadata through `getComponent` and updates current menu, parameters, and active component

#### Scenario: Returning to previous component
- **WHEN** the user triggers a return action
- **THEN** the frontend pops the current component from the stack and reloads the previous component context

### Requirement: Frontend state MUST expose loading and error feedback
The shell MUST expose a loading indicator during webservice calls and display user-facing error messages when a call fails or a component include fails.

#### Scenario: Webservice request lifecycle
- **WHEN** a frontend service call starts and then completes
- **THEN** the loading flag is raised during the call and cleared after completion

#### Scenario: Include failure
- **WHEN** a dynamic component template cannot be loaded
- **THEN** the frontend sets an explicit error message and clears the menu list
