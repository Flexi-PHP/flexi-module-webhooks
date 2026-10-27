# WebHooks Module

The WebHooks module exposes a `POST /trigger-event` route. It reads the JSON payload from the request attribute, creates a Flexi event from its `event`, `fired_by`, and optional `data` fields, and dispatches it through the event bus. Invalid payload handling returns HTTP 400.

The route uses `jwt_auth_middleware`, so this module requires the Auth module and a valid JWT configuration. Route configuration is in `Config/routes.json`.

Install `flexi/flexi-module-webhooks` and activate `WebHooks` with its dependencies. It supports PHP 7.4+, Flexi Core `^3.2`, and `flexi/contracts` `^5.2.1`. Validate payload shape and authorization policy in the application before exposing this endpoint publicly.

## Tests

Install the development dependencies with `composer install`, then run `vendor/bin/phpunit tests` from the module root.
