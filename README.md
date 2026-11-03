# Archilo Shop

An online product ordering and inventory management system for Archilo Fashion Boutique, a small retail shop with in-store pickup (no delivery).

## Features

### Customers
- Browse products by category, view product details, and search
- Shopping cart and checkout
- Order history and order details
- Wishlist
- Register, login, and password reset by email

### Staff
- Staff dashboard
- Product management (add, edit, delete)
- Stock Checker: view and adjust stock per variant
- Order management: view orders and update their status

### Administrator
- Everything Staff can do
- Owner dashboard
- Sales reports
- Low-stock threshold settings (per item or all at once)
- Stock history log

### Planned
- [Add features you want to build later]

## User Roles
| Role | Access |
|------|--------|
| Guest | Browse and search products |
| Customer | Cart, checkout, orders, wishlist |
| Staff | Product, stock, and order management |
| Administrator | Full access, including reports and thresholds |

## Tech Stack
- Laravel (PHP)
- Tailwind CSS
- MySQL
- Apache (XAMPP)

## Installation

```bash
git clone https://github.com/dodongchua30-hue/archilo-shop.git
cd archilo-shop
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Create a MySQL database, then set `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env`. Password reset also needs your mail settings in `.env`.

```bash
php artisan migrate --seed
npm run dev
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Default Accounts
After seeding, log in as:
- Admin: `admin@archilo.test` / `password`
- Staff: `staff@archilo.test` / `password`
  

(See `database/seeders` for the passwords.)

## Screenshots
[Add images here]

## Team
IT ELEC01 Advanced Web Development, BSIT 3-Block 7, Group 2
- [Member names]

## License
For academic use.
