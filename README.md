# PHP CMS ESGI

## Project structure

- `/public`: front controller and application entry point.
- `/routes`: route registration.
- `/core`: low-level framework code such as the router, autoloading, and request abstraction.
- `/app/controllers`: controllers that receive a request, call services, and choose a view or redirect.
- `/app/services`: application services and session/auth orchestration.
- `/app/models`: data access and persistence logic.
- `/app/views`: templates rendered by controllers.
- `/config`: database and application configuration.

## Architecture ideas followed by this project

This project follows a simple MVC style with a small service layer.

- `public/index.php` acts as the front controller and boots the app.
- Controllers should stay thin: they read a `Request`, call services, and return a view or redirect.
- Controllers should not read `$_GET`, `$_POST`, or `$_SESSION` directly.
- `core/Request.php` centralizes PHP globals so HTTP input is read once and passed around as an object.
- `AuthService` handles authentication rules such as credential checking and registration.
- `AuthSession` handles session state such as login persistence, current user lookup, and logout.
- Models focus on database access.
- Views should only render data given to them by controllers.

## Why this structure makes sense

- It reduces coupling between business logic and HTTP/session details.
- It makes controllers easier to test because they depend on objects instead of global state.
- It keeps session handling in one place, which is easier to maintain and secure.
- It makes future refactors easier because the web layer and the auth logic are not mixed together.

## Reading resources

These resources explain the ideas used in this project in more detail:

- Symfony HttpFoundation: https://symfony.com/doc/current/components/http_foundation/.html
- Symfony Controllers: https://symfony.com/doc/current/controller.html
- PSR-7 HTTP Message Interfaces: https://www.php-fig.org/psr/psr-7/
- Martin Fowler, Separated Presentation: https://martinfowler.com/eaaDev/SeparatedPresentation.html
- Martin Fowler, Service Layer: https://martinfowler.com/eaaCatalog/serviceLayer.html
- OWASP Session Management Cheat Sheet: https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html
