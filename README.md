# CateringHub

A comprehensive platform that connects catering businesses with customers, streamlining the process of ordering food for events, gatherings, and corporate functions. CateringHub enables merchants to showcase their food offerings while allowing customers to browse, order, and manage their catering needs effortlessly.

## Application Overview

<!-- Replace these placeholder images with actual screenshots of your application once available -->
<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
  <img src="screenshots/homepage.png" style="width: 48%; height: auto;" alt="CateringHub Homepage">
  <img src="screenshots/food-listings.png" style="width: 48%; height: auto;" alt="Food Listings">
</div>
<br>
<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
  <img src="screenshots/merchant-dashboard.png" style="width: 48%; height: auto;" alt="Merchant Dashboard">
  <img src="screenshots/order-process.png" style="width: 48%; height: auto;" alt="Order Process">
</div>
<br>
<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
  <img src="screenshots/admin-panel.png" style="width: 48%; height: auto;" alt="Admin Panel">
  <img src="screenshots/customer-orders.png" style="width: 48%; height: auto;" alt="Customer Orders">
</div>

## Features

- Multi-user roles (Admin, Merchant, Customer) with role-specific functionalities
- Merchant profile management with company information and branding
- Customer profile management for streamlined ordering
- Food category organization and management
- Comprehensive food item listings with images, descriptions, and pricing
- Order management system with status tracking
- Invoice generation and payment tracking
- Responsive design for desktop and mobile access
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

10. **Build frontend assets:**
```bash
npm run dev
```

## Additional Setup

- Configure mail settings in `.env` file for order notifications and invoices
- Set up queue workers for background processing if needed:
```bash
php artisan queue:work
```

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

- **Order processing errors**:
  - Check logs at storage/logs/laravel.log
  - Verify database connections and constraints

- **User registration issues**:
  - Ensure mail configuration is correct for verification emails
  - Check validation rules in registration controllers

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contact

Muhammad Ali Yusuf - muhammadaliyusuff22@gmail.com
Project Link: [https://github.com/yourusername/catering-hub](https://github.com/yourusername/catering-hub)