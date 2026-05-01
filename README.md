# TokenRedemption

TokenRedemption is a Laravel application for managing an employee token reward program. The employee-facing landing page and dashboard now use Inertia.js with React, while the admin area remains Blade-based. Employees can view available products, redeem products with tokens, and track their redemption requests. Admin users can manage products, users, token balances, and customer orders.

## Stack

- Laravel 12
- PHP 8.2+
- Inertia.js with React 19
- Vite
- MySQL
- Bootstrap for React pages and admin Blade views
- Laravel Breeze authentication
- `aacotroneo/laravel-saml2` for future SAML/ADFS SSO support

## Main Features

- Employee login and dashboard
- React/Inertia public landing page
- React/Inertia employee rewards dashboard
- Product catalog with token costs, stock, images, and availability
- Token wallet balances
- Product redemption flow
- Order creation with transaction IDs
- Admin dashboard
- Admin product management
- Admin user role management
- Admin token grants
- Admin order management
- Order detail view with status, tracking number, and cancel actions
- Early SAML/ADFS SSO scaffolding

## Local Setup

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Create the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Configure the database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tokenredemption
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
```

Start the development servers:

```bash
npm run dev
php artisan serve
```

The app will usually be available at:

```text
http://127.0.0.1:8000
```

## Frontend Structure

The public employee experience is mounted through Inertia and React:

- `resources/views/app.blade.php` is the Inertia root Blade view.
- `resources/js/app.jsx` creates the React/Inertia app.
- `resources/js/Layouts/PublicLayout.jsx` provides the shared public navigation and footer.
- `resources/js/Pages/Home.jsx` renders `/`.
- `resources/js/Pages/Dashboard.jsx` renders `/dashboard`, including metrics, product cards, redemption actions, and product details.

Vite compiles `resources/css/app.css` and `resources/js/app.jsx` through `vite.config.js`. Keep `npm run dev` running while developing React/Inertia screens.

The admin panel still uses Blade templates under `resources/views/admin` and the shared admin layout in `resources/views/layouts/admin.blade.php`.

## Important Routes

Employee routes:

- `/`
- `/login`
- `/dashboard`
- `/profile`
- `/products/{product}/redeem`

Admin routes:

- `/admin/login`
- `/admin/dashboard`
- `/admin/products`
- `/admin/orders`
- `/admin/orders/{order}`
- `/admin/users`
- `/admin/tokens`

SAML package routes:

- `/saml2/{idpName}/login`
- `/saml2/{idpName}/acs`
- `/saml2/{idpName}/metadata`
- `/saml2/{idpName}/sls`

## Admin Access

Admin access uses the `admin` guard, backed by the `users` table. Users must have one of these roles to access the admin area:

- `admin`
- `hype`

Normal employee users should use:

- `employee`

## Order Management

Orders are created when an employee redeems a product. Each order stores:

- `transaction_id`
- `user_id`
- `product_id`
- `tokens_spent`
- `status`
- `tracking_number`
- timestamps

The admin order detail page allows staff to:

- View customer and product details
- Update order status
- Add or update tracking number
- Cancel an order

## Tracking Numbers

The app currently stores tracking numbers manually. If automatic carrier labels are needed later, integrate a shipping API such as:

- FedEx Ship API
- UPS Shipping API
- EasyPost
- Shippo
- ShipEngine

The normal flow would be:

```text
Redeem product
Create local order
Create shipment through shipping API
Save returned tracking number on the order
Show tracking number to user/admin
```

## ADFS / SAML SSO Status

This project has partial SAML/ADFS scaffolding, but it is not fully production-ready for ADFS yet.

Already present:

- `aacotroneo/laravel-saml2` is installed.
- SAML routes are registered.
- `App\Http\Controllers\Auth\SsoController` exists.
- `App\Listeners\SamlLoginListener` is registered for SAML login events.
- `config/sso.php` exists for high-level SSO settings.

Known gaps before ADFS can be used:

- `SSO_ENABLED` is currently disabled by default.
- No `/login/sso` route is currently registered.
- `SsoController::redirect()` calls `route('saml2_login')` without the required `idpName` parameter.
- The SAML package reads `SAML2_TEST_*` environment keys, while `config/sso.php` reads `SAML_*` keys.
- The `users` table needs SSO identity columns such as `external_id` and `idp`.
- SSO-created users need a nullable password or generated placeholder password.
- Role mapping should be aligned to this app's roles: `employee`, `hype`, and `admin`.

Recommended ADFS implementation tasks:

1. Add a migration for `users.external_id`, `users.idp`, and nullable password support if needed.
2. Add `external_id` and `idp` to `App\Models\User::$fillable`.
3. Add a `/login/sso` route pointing to `SsoController::redirect`.
4. Update `SsoController::redirect()` to call the SAML route with an IdP name, for example `test` or `adfs`.
5. Rename the SAML IdP config from `test` to `adfs`, or configure the existing `test` IdP with real ADFS values.
6. Add the correct ADFS metadata, SSO URL, entity ID, and x509 certificate.
7. Confirm claim names for email, display name, NameID, and role.
8. Test `/saml2/{idpName}/metadata` with ADFS relying party trust setup.
9. Test login through `/saml2/{idpName}/login`.

Example environment keys for the current package config:

```env
SSO_ENABLED=true
SSO_DRIVER=saml

SAML2_TEST_IDP_ENTITYID=https://adfs.example.com/adfs/services/trust
SAML2_TEST_IDP_SSO_URL=https://adfs.example.com/adfs/ls/
SAML2_TEST_IDP_SL_URL=
SAML2_TEST_IDP_x509="PASTE_CERTIFICATE_HERE"

SSO_MAP_ID=nameid
SSO_MAP_EMAIL=email
SSO_MAP_NAME=name
SSO_MAP_ROLE=
```

## Useful Commands

List admin order routes:

```bash
php artisan route:list --path=admin/orders
```

List SAML routes:

```bash
php artisan route:list --path=saml2
```

List registered events:

```bash
php artisan event:list
```

Clear cached framework files:

```bash
php artisan optimize:clear
```

Compile and verify Blade templates:

```bash
php artisan view:cache
php artisan view:clear
```

## Notes

- Keep `.env` out of source control.
- Do not commit real ADFS certificates, API keys, or shipping API credentials.
- The admin and employee login systems currently share the `users` table.
- Before enabling ADFS in production, test with a dedicated non-admin employee account first.
