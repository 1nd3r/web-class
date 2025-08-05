<?php
// Emmanuel Wonder Portfolio - PHP Test File
// This file tests if PHP is working correctly

echo "<h1> PHP Server Test - Emmanuel Wonder Portfolio</h1>";

echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 20px;'>";

echo "<h2> PHP is Working!</h2>";
echo "<p><strong>Server Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server:</strong> " . $_SERVER['SERVER_SOFTWARE'] ?? 'PHP Development Server' . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Current File:</strong> " . __FILE__ . "</p>";

echo "<h2>🌐 Navigation Links</h2>";
echo "<p>Your portfolio pages:</p>";
echo "<ul>";
echo "<li><a href='index.html'> Homepage (HTML)</a></li>";
echo "<li><a href='about.html'> About Me</a></li>";
echo "<li><a href='services.html'> Skills</a></li>";
echo "<li><a href='fleet.html'> Projects</a></li>";
echo "<li><a href='booking.html'> Hire Me</a></li>";
echo "<li><a href='testimonials.html'> Recommendations</a></li>";
echo "<li><a href='contact.html'> Contact</a></li>";
echo "</ul>";

echo "<h2> Server Information</h2>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr><th>Setting</th><th>Value</th></tr>";
echo "<tr><td>Server Name</td><td>" . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "</td></tr>";
echo "<tr><td>Server Port</td><td>" . ($_SERVER['SERVER_PORT'] ?? '8080') . "</td></tr>";
echo "<tr><td>Request Method</td><td>" . ($_SERVER['REQUEST_METHOD'] ?? 'GET') . "</td></tr>";
echo "<tr><td>User Agent</td><td>" . substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 50) . "...</td></tr>";
echo "</table>";

echo "<h2> Portfolio Status</h2>";
$files = ['index.html', 'about.html', 'services.html', 'fleet.html', 'booking.html', 'testimonials.html', 'contact.html'];
echo "<ul>";
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "<li> <strong>$file</strong> - Found</li>";
    } else {
        echo "<li> <strong>$file</strong> - Missing</li>";
    }
}
echo "</ul>";

echo "<p style='margin-top: 30px; padding: 10px; background-color: #f0f8ff; border-left: 4px solid #007bff;'>";
echo "<strong> Congratulations!</strong> Your PHP server is running correctly for Emmanuel Wonder's portfolio!";
echo "</p>";

echo "</div>";
$name = "Emmanuel Wonder";
?> 
