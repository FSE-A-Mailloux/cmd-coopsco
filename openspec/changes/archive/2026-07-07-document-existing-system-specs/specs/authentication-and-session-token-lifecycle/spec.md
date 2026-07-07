## ADDED Requirements

### Requirement: Authentication MUST issue a new session token on successful login
The `login` action MUST authenticate credentials, resolve the user's default component/group context, persist a generated token in the token store, and return token plus user identity payload.

#### Scenario: Successful credential login
- **WHEN** a user provides a valid `compte_uti` and password
- **THEN** the backend creates a token record and returns `token`, `id_uti`, `compte_uti`, `cd_com`, and `tabGroupe`

#### Scenario: Invalid credential login
- **WHEN** a user provides invalid credentials
- **THEN** the backend returns `statusCode = ErrorFonctionnal` with a login/password error message

### Requirement: Activation links MUST support account validation flow
The authentication flow MUST support activation contexts (`validUser`, `validLink`) and set account validation date when validation succeeds.

#### Scenario: Activation by link code
- **WHEN** the frontend starts with `action=validLink&id=<code>`
- **THEN** the backend validates the code, marks the account as validated, and returns a connected session payload

### Requirement: Session lifecycle MUST enforce token expiry
Token validation MUST reject expired or unknown tokens and refresh token timestamp on successful authenticated requests.

#### Scenario: Token is valid
- **WHEN** an authenticated request is sent with a valid token
- **THEN** the backend reloads the user from token and updates token timestamp

#### Scenario: Token is expired
- **WHEN** an authenticated request is sent with an expired token
- **THEN** the backend returns `ErrorTokenExpirate` and the frontend schedules user deconnection
