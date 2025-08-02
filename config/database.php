<?php
// SparkNest Services - Database Configuration
// This file contains database connection settings and helper functions

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'sparknest');
define('DB_USER', 'root');
define('DB_PASS', ''); // Update this with your MySQL password

// PDO connection class
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserialization
    private function __wakeup() {}
}

// Helper functions for common database operations
class SparkNestDB {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get all services
    public function getServices($active = true) {
        $sql = "SELECT * FROM services WHERE active = ? ORDER BY sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$active]);
        return $stmt->fetchAll();
    }
    
    // Get service by ID
    public function getService($id) {
        $sql = "SELECT * FROM services WHERE id = ? AND active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Get all testimonials
    public function getTestimonials($featured = null) {
        $sql = "SELECT * FROM testimonials WHERE 1=1";
        $params = [];
        
        if ($featured !== null) {
            $sql .= " AND featured = ?";
            $params[] = $featured;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Get featured testimonials
    public function getFeaturedTestimonials() {
        return $this->getTestimonials(true);
    }
    
    // Get all equipment
    public function getEquipment($category = null) {
        $sql = "SELECT * FROM equipment WHERE active = 1";
        $params = [];
        
        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY sort_order ASC, name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Get equipment by category
    public function getEquipmentByCategory() {
        $equipment = $this->getEquipment();
        $categorized = [];
        
        foreach ($equipment as $item) {
            $category = $item['category'];
            if (!isset($categorized[$category])) {
                $categorized[$category] = [];
            }
            $categorized[$category][] = $item;
        }
        
        return $categorized;
    }
    
    // Save booking request
    public function saveBooking($data) {
        $sql = "INSERT INTO bookings (first_name, last_name, email, phone, address, property_type, 
                service_type, urgency, preferred_date, preferred_time, property_size, frequency, 
                special_requirements, access_instructions, additional_services, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['phone'],
            $data['address'],
            $data['propertyType'],
            $data['serviceType'],
            $data['urgency'],
            $data['preferredDate'],
            $data['preferredTime'],
            $data['propertySize'],
            $data['frequency'],
            $data['specialRequirements'],
            $data['accessInstructions'],
            json_encode($data['additionalServices'] ?? [])
        ]);
    }
    
    // Get bookings (for admin)
    public function getBookings($status = null) {
        $sql = "SELECT * FROM bookings WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Update booking status
    public function updateBookingStatus($id, $status) {
        $sql = "UPDATE bookings SET status = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }
    
    // Save contact message
    public function saveContactMessage($name, $email, $subject, $message, $phone = null) {
        $sql = "INSERT INTO contact_messages (name, email, subject, message, phone, status, created_at) 
                VALUES (?, ?, ?, ?, ?, 'new', NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $email, $subject, $message, $phone]);
    }
    
    // Get contact messages (for admin)
    public function getContactMessages($status = null) {
        $sql = "SELECT * FROM contact_messages WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Update message status
    public function updateMessageStatus($id, $status) {
        $sql = "UPDATE contact_messages SET status = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }
    
    // Get service areas
    public function getServiceAreas() {
        $sql = "SELECT * FROM service_areas WHERE active = 1 ORDER BY area_name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get pricing information
    public function getPricing() {
        $sql = "SELECT * FROM pricing WHERE active = 1 ORDER BY service_id, property_size ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get pricing by service
    public function getPricingByService($serviceId) {
        $sql = "SELECT * FROM pricing WHERE service_id = ? AND active = 1 ORDER BY property_size ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$serviceId]);
        return $stmt->fetchAll();
    }
    
    // Get database statistics
    public function getStats() {
        $stats = [];
        
        // Count bookings
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM bookings");
        $stats['bookings'] = $stmt->fetch()['count'];
        
        // Count pending bookings
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'");
        $stats['pending_bookings'] = $stmt->fetch()['count'];
        
        // Count testimonials
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM testimonials");
        $stats['testimonials'] = $stmt->fetch()['count'];
        
        // Count services
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM services WHERE active = 1");
        $stats['services'] = $stmt->fetch()['count'];
        
        // Count new messages
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM contact_messages WHERE status = 'new'");
        $stats['new_messages'] = $stmt->fetch()['count'];
        
        // Count equipment
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM equipment WHERE active = 1");
        $stats['equipment'] = $stmt->fetch()['count'];
        
        return $stats;
    }
    
    // Search testimonials
    public function searchTestimonials($search) {
        $sql = "SELECT * FROM testimonials WHERE 
                customer_name LIKE ? OR 
                testimonial_text LIKE ? OR 
                service_type LIKE ?
                ORDER BY created_at DESC";
        $searchTerm = "%$search%";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
    
    // Get recent testimonials
    public function getRecentTestimonials($limit = 5) {
        $sql = "SELECT * FROM testimonials ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}

// Initialize database connection
try {
    $sparkNestDB = new SparkNestDB();
} catch (Exception $e) {
    // Log error or display user-friendly message
    error_log("Database connection error: " . $e->getMessage());
}
?> // Commit 8 - Create equipment showcase page
// Commit 18 - Create all necessary database tables
// Commit 28 - Add SEO optimization and meta tags
// Commit 38 - Add emergency services information
// Commit 48 - Add team information and professional staff details
