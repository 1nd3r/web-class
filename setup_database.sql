-- SparkNest Services Database Setup
-- This script creates the database and all necessary tables for the cleaning business

-- Create the database
CREATE DATABASE IF NOT EXISTS sparknest;
USE sparknest;

-- Create services table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    icon_class VARCHAR(100),
    price_range VARCHAR(100),
    duration VARCHAR(100),
    features TEXT,
    sort_order INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_location VARCHAR(255),
    testimonial_text TEXT NOT NULL,
    service_type VARCHAR(100),
    rating INT CHECK (rating >= 1 AND rating <= 5),
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    property_type VARCHAR(100),
    service_type VARCHAR(100) NOT NULL,
    urgency VARCHAR(50),
    preferred_date DATE,
    preferred_time VARCHAR(50),
    property_size VARCHAR(100),
    frequency VARCHAR(100),
    special_requirements TEXT,
    access_instructions TEXT,
    additional_services JSON,
    status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create contact_messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    phone VARCHAR(50),
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create equipment table
CREATE TABLE IF NOT EXISTS equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    specifications TEXT,
    image_url VARCHAR(500),
    sort_order INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create service_areas table
CREATE TABLE IF NOT EXISTS service_areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    area_name VARCHAR(255) NOT NULL,
    description TEXT,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create pricing table
CREATE TABLE IF NOT EXISTS pricing (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT,
    property_size VARCHAR(100),
    base_price DECIMAL(10,2),
    hourly_rate DECIMAL(10,2),
    description TEXT,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Insert sample data for services
INSERT INTO services (name, description, icon_class, price_range, duration, features, sort_order) VALUES
('General House Cleaning', 'Regular maintenance cleaning to keep your home fresh, clean, and organized', 'fas fa-home', 'KSH 3,500+', '2-4 hours', 'Kitchen cleaning, Bathroom sanitization, Living areas, Bedroom maintenance', 1),
('Deep Cleaning & Fumigation', 'Intensive cleaning services for thorough sanitization and pest control', 'fas fa-spray-can', 'KSH 8,000+', '4-6 hours', 'Deep sanitization, Pest control, Air duct cleaning, Mold remediation', 2),
('Carpet & Upholstery Cleaning', 'Professional fabric restoration services to revive and maintain your carpets and furniture', 'fas fa-couch', 'KSH 2,500+', '1-2 hours', 'Carpet deep cleaning, Upholstery restoration, Stain treatment, Fabric protection', 3),
('Post-Construction Clean-up', 'Comprehensive cleaning services after renovation or construction projects', 'fas fa-hammer', 'KSH 12,000+', '6-8 hours', 'Construction debris removal, Dust elimination, Paint splatter removal, Final touch-up', 4),
('Office & Commercial Cleaning', 'Professional cleaning services for businesses to maintain a clean work environment', 'fas fa-building', 'KSH 5,000+', '3-5 hours', 'Daily office maintenance, Conference room cleaning, Reception area care, Restroom sanitization', 5),
('Handyman Services', 'Professional maintenance and repair services to keep your property in excellent condition', 'fas fa-tools', 'KSH 1,500/hour', 'Per hour', 'Plumbing repairs, Electrical work, Painting services, Minor repairs', 6);

-- Insert sample data for testimonials
INSERT INTO testimonials (customer_name, customer_location, testimonial_text, service_type, rating, featured) VALUES
('Anita K.', 'Westlands, Nairobi', 'SparkNest turned my apartment into a 5-star suite. Highly recommend the deep cleaning package! The team was professional, thorough, and left my place spotless.', 'Deep Cleaning', 5, TRUE),
('Brian M.', 'Kileleshwa, Nairobi', 'They showed up on time, wore proper gear, and left my carpets looking brand new. The carpet cleaning service was outstanding - they removed stains I thought were permanent.', 'Carpet Cleaning', 5, TRUE),
('Susan N.', 'Lavington, Nairobi', 'Great customer service and trustworthy staff. I left them cleaning as I went to work — came back to a spotless house. They were respectful of my space and did an excellent job.', 'General Cleaning', 5, TRUE),
('James O.', 'CBD Nairobi', 'SparkNest has been handling our office cleaning for the past 6 months and the results are consistently excellent. Our workspace always looks professional.', 'Office Cleaning', 5, FALSE),
('Maria W.', 'Karen, Nairobi', 'I was concerned about using harsh chemicals around my children and pets, but SparkNest uses eco-friendly products that are just as effective. My house is clean and safe for my family.', 'General Cleaning', 5, FALSE),
('Peter K.', 'Kilimani, Nairobi', 'After our home renovation, the place was a mess with dust and debris everywhere. SparkNest\'s post-construction cleaning service was incredible - they transformed our construction site into a beautiful, livable home.', 'Post-Construction', 5, FALSE);

-- Insert sample data for equipment
INSERT INTO equipment (name, category, description, specifications, sort_order) VALUES
('Industrial Vacuum Cleaners', 'Cleaning Equipment', 'Professional-grade vacuum cleaners with HEPA filtration for deep cleaning', 'HEPA filtration, powerful suction, various attachments', 1),
('Steam Cleaners', 'Cleaning Equipment', 'High-temperature steam cleaners for sanitization and stain removal', 'High-temperature steam, sanitization capability, eco-friendly', 2),
('Portable Carpet Shampooers', 'Carpet Equipment', 'Professional carpet cleaning machines for deep carpet restoration', 'Portable design, powerful cleaning, quick drying', 3),
('Power Washers', 'Exterior Equipment', 'High-pressure cleaning equipment for exterior surfaces', 'High pressure, adjustable nozzles, professional grade', 4),
('Fogging Machines', 'Fumigation Equipment', 'Professional fumigation equipment for pest control', 'Fogging capability, safety features, professional grade', 5),
('Extension Poles & Ladders', 'Safety Equipment', 'Safe access equipment for high areas and ceilings', 'Extendable poles, safety locks, various lengths', 6),
('Microfiber Cleaning Cloths', 'Cleaning Tools', 'Lint-free, streak-free surface cleaning cloths', 'Microfiber material, reusable, streak-free', 7),
('Professional Brushes', 'Cleaning Tools', 'Various sizes for different surface types and cleaning tasks', 'Multiple sizes, durable bristles, professional quality', 8);

-- Insert sample data for service areas
INSERT INTO service_areas (area_name, description) VALUES
('Westlands', 'Primary service area in Westlands and surrounding neighborhoods'),
('Lavington', 'Comprehensive cleaning services in Lavington area'),
('Kileleshwa', 'Professional cleaning services in Kileleshwa'),
('Karen', 'Premium cleaning services in Karen and surrounding areas'),
('Kilimani', 'Reliable cleaning services in Kilimani'),
('CBD Nairobi', 'Commercial cleaning services in Nairobi Central Business District'),
('Langata', 'Professional cleaning services in Langata area'),
('Nairobi Surroundings', 'Extended service areas around Nairobi');

-- Insert sample data for pricing
INSERT INTO pricing (service_id, property_size, base_price, hourly_rate, description) VALUES
(1, 'Studio (1 room)', 3500.00, 1500.00, 'General cleaning for studio apartments'),
(1, '1 Bedroom', 4500.00, 1500.00, 'General cleaning for 1 bedroom properties'),
(1, '2 Bedroom', 5500.00, 1500.00, 'General cleaning for 2 bedroom properties'),
(1, '3 Bedroom', 7000.00, 1500.00, 'General cleaning for 3 bedroom properties'),
(1, '4+ Bedroom', 8500.00, 1500.00, 'General cleaning for large properties'),
(2, 'Studio (1 room)', 8000.00, 2000.00, 'Deep cleaning and fumigation for studio apartments'),
(2, '1 Bedroom', 10000.00, 2000.00, 'Deep cleaning and fumigation for 1 bedroom properties'),
(2, '2 Bedroom', 12000.00, 2000.00, 'Deep cleaning and fumigation for 2 bedroom properties'),
(2, '3 Bedroom', 15000.00, 2000.00, 'Deep cleaning and fumigation for 3 bedroom properties'),
(3, 'Small Carpet', 2500.00, 1000.00, 'Carpet cleaning for small areas'),
(3, 'Medium Carpet', 3500.00, 1000.00, 'Carpet cleaning for medium areas'),
(3, 'Large Carpet', 5000.00, 1000.00, 'Carpet cleaning for large areas'),
(4, 'Small Project', 12000.00, 2500.00, 'Post-construction cleaning for small projects'),
(4, 'Medium Project', 18000.00, 2500.00, 'Post-construction cleaning for medium projects'),
(4, 'Large Project', 25000.00, 2500.00, 'Post-construction cleaning for large projects'),
(5, 'Small Office', 5000.00, 1800.00, 'Office cleaning for small offices'),
(5, 'Medium Office', 8000.00, 1800.00, 'Office cleaning for medium offices'),
(5, 'Large Office', 12000.00, 1800.00, 'Office cleaning for large offices'),
(6, 'Per Hour', 0.00, 1500.00, 'Handyman services charged per hour');

-- Create indexes for better performance
CREATE INDEX idx_services_active ON services(active);
CREATE INDEX idx_testimonials_featured ON testimonials(featured);
CREATE INDEX idx_bookings_status ON bookings(status);
CREATE INDEX idx_bookings_service_type ON bookings(service_type);
CREATE INDEX idx_contact_messages_status ON contact_messages(status);
CREATE INDEX idx_equipment_category ON equipment(category);
CREATE INDEX idx_equipment_active ON equipment(active);
CREATE INDEX idx_service_areas_active ON service_areas(active);
CREATE INDEX idx_pricing_service_id ON pricing(service_id);

-- Show success message
SELECT 'SparkNest Services database setup completed successfully!' AS message;
SELECT 'Created tables: services, testimonials, bookings, contact_messages, equipment, service_areas, pricing' AS tables_created; // Commit 11 - Add client-side form validation
