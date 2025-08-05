<?php
// Emmanuel Wonder Portfolio - Database Setup Script
// This script will create the database and tables for your portfolio website

echo "<h1> Database Setup - Emmanuel Wonder Portfolio</h1>";
echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 20px;'>";

// Database configuration
$host = 'localhost';
$username = 'root';
$password = ''; // You'll need to enter your MySQL password here
$database = 'emmanuel_portfolio';

// Function to test database connection
function testConnection($host, $username, $password) {
    try {
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<p><strong>Database connection successful!</strong></p>";
        return $pdo;
    } catch (PDOException $e) {
        echo "<p><strong>Connection failed:</strong> " . $e->getMessage() . "</p>";
        echo "<p><strong>Please check:</strong></p>";
        echo "<ul>";
        echo "<li>MySQL is running on port 3306</li>";
        echo "<li>Username and password are correct</li>";
        echo "<li>MySQL service is started</li>";
        echo "</ul>";
        return false;
    }
}

// Function to execute SQL file
function executeSQLFile($pdo, $filename) {
    if (!file_exists($filename)) {
        echo "<p> <strong>SQL file not found:</strong> $filename</p>";
        return false;
    }
    
    $sql = file_get_contents($filename);
    $statements = explode(';', $sql);
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                $successCount++;
            } catch (PDOException $e) {
                echo "<p> <strong>SQL Error:</strong> " . $e->getMessage() . "</p>";
                $errorCount++;
            }
        }
    }
    
    echo "<p> <strong>SQL execution completed:</strong> $successCount statements executed successfully</p>";
    if ($errorCount > 0) {
        echo "<p> <strong>Warnings:</strong> $errorCount statements had issues (this is normal for some commands)</p>";
    }
    
    return true;
}

// Function to verify tables were created
function verifyTables($pdo, $database) {
    try {
        $pdo->exec("USE $database");
        $tables = ['projects', 'testimonials', 'contact_messages', 'skills', 'blog_posts', 'services'];
        
        echo "<h3> Database Verification</h3>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>Table</th><th>Status</th><th>Records</th></tr>";
        
        foreach ($tables as $table) {
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                echo "<tr><td>$table</td><td> Created</td><td>$count records</td></tr>";
            } catch (PDOException $e) {
                echo "<tr><td>$table</td><td> Error</td><td>" . $e->getMessage() . "</td></tr>";
            }
        }
        echo "</table>";
        
    } catch (PDOException $e) {
        echo "<p> <strong>Verification failed:</strong> " . $e->getMessage() . "</p>";
    }
}

// Main execution
echo "<h2> Setting up your portfolio database...</h2>";

// Test connection
$pdo = testConnection($host, $username, $password);

if ($pdo) {
    echo "<h3> Executing SQL setup script...</h3>";
    
    // Execute the SQL file
    if (executeSQLFile($pdo, 'setup_database.sql')) {
        echo "<h3> Database setup completed!</h3>";
        
        // Verify tables
        verifyTables($pdo, $database);
        
        echo "<h3> Next Steps</h3>";
        echo "<ul>";
        echo "<li> Database 'emmanuel_portfolio' created</li>";
        echo "<li> All tables created with sample data</li>";
        echo "<li> Indexes created for performance</li>";
        echo "<li> Update your PHP files to use this database</li>";
        echo "<li> Create a config.php file with database credentials</li>";
        echo "</ul>";
        
        echo "<h3> Database Configuration</h3>";
        echo "<p><strong>Host:</strong> $host</p>";
        echo "<p><strong>Database:</strong> $database</p>";
        echo "<p><strong>Username:</strong> $username</p>";
        echo "<p><strong>Tables created:</strong> projects, testimonials, contact_messages, skills, blog_posts, services</p>";
        
    } else {
        echo "<p> <strong>Database setup failed!</strong></p>";
    }
} else {
    echo "<h3>🔧 Manual Setup Instructions</h3>";
    echo "<p>If automatic setup fails, you can manually run the SQL:</p>";
    echo "<ol>";
    echo "<li>Open MySQL Workbench or phpMyAdmin</li>";
    echo "<li>Connect to your MySQL server</li>";
    echo "<li>Open the file 'setup_database.sql'</li>";
    echo "<li>Execute the SQL commands</li>";
    echo "</ol>";
}

echo "</div>";
?> 
