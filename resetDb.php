<?php
// Database connection
$conn = new mysqli("localhost", "Lance", "LHei4016#", "fantasy_football_manager");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Drop the table (or database)
$sql = "DROP TABLE IF EXISTS players";
if (!$conn->query($sql)) {
    echo "Error dropping table: " . $conn->error;
    exit;
}

// Create the table again
$sql = "CREATE TABLE players 
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    team VARCHAR(100),
    number INT,
    status ENUM('healthy', 'injured') DEFAULT 'healthy',
    position ENUM('QB', 'RB', 'WR', 'TE', 'K', 'DEF') DEFAULT 'QB'
)";

if ($conn->query($sql) === TRUE) {
    echo "Table players created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
    exit;
}

// Import data from JSON
$jsonData = file_get_contents('data.json'); // Adjust the path to your JSON file
$data = json_decode($jsonData, true);

foreach ($data as $item) 
{
    $stmt = $conn->prepare("INSERT INTO players (name, team, number, status, position) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $item['name'], $item['team'], $item['number'], $item['status'], $item['position']);
    $stmt->execute();
}

echo "Data imported successfully";

// Close connection
$conn->close();
?>
