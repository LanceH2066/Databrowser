<?php
$conn = new mysqli("localhost", "Lance", "LHei4016#", "fantasy_football_manager");

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE players 
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    team VARCHAR(100),
    number INT,
    status ENUM('healthy', 'injured') DEFAULT 'healthy',
    position ENUM('QB', 'RB', 'WR', 'TE', 'K', 'DEF') DEFAULT 'QB'
)";

if ($conn->query($sql) === TRUE) 
{
    echo "Table created successfully!";
} else 
{
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
