# Lillia and Rowena Furniture

Handcrafted solid timber and architectural furniture showroom, custom design portal, and workshop management system.

## Architecture Overview

- **Storefront & Client Portal**: `index.html`, `catalog.html`, `login.html`, `dashboard.html`
- **Frontend Modules**: `assets/js/` (`store.js`, `app.js`, `dashboard.js`, `login.js`, `analytics.js`, `feedback.js`)
- **PHP Backend (PSR-4 & OOP)**:
  - `src/App/Controllers/HomeControllers.php`
  - `src/App/Models/` (`BaseModel.php`, `Product.php`, `StandardProduct.php`, `CustomizedProduct.php`, `User.php`, `AnalyticsReport.php`)
  - `src/Library/` (`Database.php`, `OrderQueue.php`, `HashTable.php`, `OrderCompletion.php`, `Contracts/DataStructureInterface.php`, `Exceptions/AppException.php`)
- **Database Schema (3NF)**: `database/furniture.sql`

## Running Locally

1. Start the development server:
   ```bash
   npm run dev
   ```
2. Run the automated test suite:
   ```bash
   npm test
   ```
