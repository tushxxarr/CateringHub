# CateringHub

A web-based Catering Marketplace that connects catering businesses with customers to simplify food ordering for events, corporate functions, and personal gatherings. CateringHub enables merchants to showcase their menu, manage orders, and generate invoices while allowing customers to effortlessly browse and book catering services.

## Application Overview

<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/Login.png" style="width: 48%; height: auto;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/MerchantRegister.png" style="width: 48%; height: auto;">
</div>
<br>
<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/Homepage.png" style="width: 48%; height: auto;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/MerchantDashboard.png" style="width: 48%; height: auto;">
</div>
<br>
<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/DashboardFoodItems.png" style="width: 48%; height: auto;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/AddFoodItem.png" style="width: 48%; height: auto;">
</div>
<br>
<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/CustomerRegister.png" style="width: 48%; height: auto;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/CustomerDashboard.png" style="width: 48%; height: auto;">
</div>
<br>
<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/MerchantList.png" style="width: 48%; height: auto;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/MerchantMenu.png" style="width: 48%; height: auto;">
</div>
<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/Cart.png" style="width: 48%; height: auto;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/Order.png" style="width: 48%; height: auto;">
</div>
<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/OrderDetail.png" style="width: 48%; height: auto;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/Invoice.png" style="width: 48%; height: auto;">
</div>
<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/OrderHistory.png" style="width: 48%; height: auto;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/InvoiceHistory.png" style="width: 48%; height: auto;">
</div>
<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
  <img src="https://github.com/muhammadaliyusuf/CateringHub/blob/main/.ApplicationOverview/CustomerProfile.png" style="width: 48%; height: auto;">
</div>

## Features

- Multi-user roles (Admin, Merchant, Customer) with role-specific functionalities
- Merchant profile management with company information and branding
- Customer profile management for streamlined ordering
- Food category organization and management
- Comprehensive food item listings with images, descriptions, and pricing
- Order management system with status tracking
- Invoice generation and payment tracking
- Dashboard interfaces customized for each user role
- Search and filter functionality for food items and merchants
- Order history and tracking for customers

## Prerequisites

- PHP >= 8.0
- Composer
- MySQL >= 5.7
- Node.js and NPM (for frontend assets)

## Installation Steps

1. **Clone the repository:**
```bash
git clone https://github.com/muhammadaliyusuf/CateringHub
cd catering-hub
```

2. **Install PHP dependencies:**
```bash
composer install
```

3. **Create environment file:**
```bash
cp .env.example .env
```

4. **Generate application key:**
```bash
php artisan key:generate
```

5. **Configure database connection in `.env` file:**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=catering_hub
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Run database migrations:**
```bash
php artisan migrate
```

7. **Run database seeders:**
```bash
php artisan db:seed
```

8. **Set up storage link for file uploads:**
```bash
php artisan storage:link
```

9. **Install frontend dependencies:**
```bash
npm install
```

## Additional Setup

Since the `/vendor` directory is not included in version control (as specified in `.gitignore`), make sure to:

1. Run `composer update` after cloning to install all required dependencies
2. If you encounter any issues with dependencies, try:
   - Clearing composer cache: `composer clear-cache`
   - Removing `composer.lock` and running `composer install` again

## Usage

1. Start the Laravel development server:
```bash
php artisan serve
```

2. Access the application in your browser at `http://localhost:8000`

3. Login with the default admin credentials:
```
Email: admin@cateringhub.com
Password: admin123
```

## Database Schema

The database structure includes the following key tables:

### Users and Profiles

- **users**: Core user information with role-based access control
- **merchant_profiles**: Detailed information for catering businesses
- **customer_profiles**: Information for customers ordering catering services

### Food Management

- **food_categories**: Categories to organize food items
- **food_items**: Individual food offerings with descriptions and pricing

### Order Processing

- **orders**: Main order information including delivery details
- **order_items**: Individual items within an order
- **invoices**: Financial records for completed orders

### Relationships

- Each **merchant** can offer multiple **food items**
- **Food items** belong to **food categories**
- **Customers** place **orders** with **merchants**
- Each **order** contains multiple **order items**
- Each **order** generates an **invoice**

## Troubleshooting

- **Image upload issues**:
  - Check directory permissions for storage/app/public
  - Verify symbolic link creation with `php artisan storage:link`

- **Database connection errors**:
  - Verify credentials in `.env` file
  - Check if MySQL server is running
  - Ensure database exists before migration

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Contact

Muhammad Ali Yusuf - muhammadaliyusuff22@gmail.com
