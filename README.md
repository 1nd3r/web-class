# SparkNest Services - Professional Cleaning & Maintenance Website

A comprehensive website for SparkNest Services, a professional home cleaning and maintenance service provider based in Nairobi, Kenya.

## 🌟 Features

### Business Information
- **Company Name**: SparkNest Services
- **Business Type**: Professional home cleaning, deep sanitization, and maintenance services
- **Location**: Eco Plaza, 2nd Floor, Mombasa Road, Nairobi, Kenya
- **Contact**: +254 727 110 245 | hello@sparknest.co.ke

### Website Pages
1. **Home** (`index.html`) - Company overview and quick booking
2. **About Us** (`about.html`) - Company story, mission, and values
3. **Services** (`services.html`) - Detailed service offerings and pricing
4. **Our Equipment** (`fleet.html`) - Professional equipment showcase
5. **Book Now** (`booking.html`) - Service booking form
6. **Testimonials** (`testimonials.html`) - Customer reviews and feedback
7. **Contact** (`contact.html`) - Contact information and inquiry form

### Services Offered
- General House Cleaning
- Deep Cleaning & Fumigation
- Carpet & Upholstery Cleaning
- Post-Construction Clean-up
- Office & Commercial Cleaning
- Handyman Services (plumbing, electrical, painting)

### Technical Features
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Form Validation**: Client-side and server-side validation
- **Database Integration**: MySQL database for storing bookings and inquiries
- **Contact Management**: Automated email notifications
- **Professional UI/UX**: Clean, modern design focused on user experience

## 🚀 Setup Instructions

### Prerequisites
- Web server (Apache/Nginx)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- XAMPP/WAMP/MAMP (for local development)

### Installation Steps

1. **Clone/Download the Project**
   ```bash
   # Place all files in your web server directory
   # e.g., htdocs/ for XAMPP
   ```

2. **Database Setup**
   ```bash
   # Import the database schema
   mysql -u root -p < setup_database.sql
   
   # Or run the setup script
   php setup_database.php
   ```

3. **Configure Database Connection**
   - Edit `config/database.php`
   - Update database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'sparknest');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```

4. **Web Server Configuration**
   - Ensure PHP is enabled
   - Set proper file permissions
   - Configure email settings (optional)

5. **Test the Website**
   - Open `http://localhost/sparknest/` in your browser
   - Test the booking and contact forms
   - Verify database connectivity

## 📁 File Structure

```
sparknest-website/
├── index.html              # Homepage
├── about.html              # About Us page
├── services.html           # Services page
├── fleet.html              # Equipment page
├── booking.html            # Booking form
├── testimonials.html       # Customer testimonials
├── contact.html            # Contact page
├── contact-handler.php     # Form processing
├── config/
│   └── database.php        # Database configuration
├── css/
│   └── style.css           # Main stylesheet
├── js/
│   └── validation.js       # Form validation
├── images/                 # Website images
├── setup_database.php      # Database setup script
├── setup_database.sql      # Database schema
└── README.md               # This file
```

## 🗄️ Database Schema

### Tables
- **services** - Service offerings and details
- **testimonials** - Customer reviews and ratings
- **bookings** - Service booking requests
- **contact_messages** - Contact form submissions
- **equipment** - Professional equipment inventory
- **service_areas** - Geographic service coverage
- **pricing** - Service pricing information

### Sample Data
The database includes sample data for:
- 6 main service categories
- 6 customer testimonials
- 8 equipment categories
- 8 service areas in Nairobi
- Comprehensive pricing structure

## 📧 Form Processing

### Contact Forms
- **Contact Form**: General inquiries and questions
- **Booking Form**: Service booking requests
- **Quick Contact**: Homepage sidebar form

### Features
- Real-time validation
- Server-side processing
- Email notifications
- Database storage
- Success/error messaging

## 🎨 Customization

### Business Information
Update the following files with your business details:
- All HTML files (company name, contact info)
- `config/database.php` (database settings)
- `contact-handler.php` (email addresses)

### Styling
- Modify `css/style.css` for design changes
- Update color scheme, fonts, and layout
- Add custom images to `images/` directory

### Content
- Edit HTML files to update service descriptions
- Modify pricing in `setup_database.sql`
- Update testimonials and equipment information

## 🔧 Technical Details

### Frontend
- **HTML5**: Semantic markup
- **CSS3**: Responsive design, modern styling
- **JavaScript**: Form validation, user interactions
- **Bootstrap-like**: Custom CSS framework

### Backend
- **PHP**: Server-side processing
- **MySQL**: Data storage and retrieval
- **PDO**: Database connections
- **JSON**: API responses

### Security Features
- Input validation and sanitization
- SQL injection prevention
- XSS protection
- CSRF protection (basic)

## 📞 Support

For technical support or customization requests:
- **Email**: hello@sparknest.co.ke
- **Phone**: +254 727 110 245

## 📄 License

This project is created for SparkNest Services. All rights reserved.

---

**SparkNest Services** - Professional Home Cleaning & Maintenance  
*Eco Plaza, 2nd Floor, Mombasa Road, Nairobi, Kenya*  
*Phone: +254 727 110 245 | Email: hello@sparknest.co.ke* // Commit 12 - Create customer testimonials page
// Commit 22 - Add email notification system
// Commit 32 - Add detailed pricing structure for all services
