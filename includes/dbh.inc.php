<?php

$host = "localhost";
$dbname = "DatabrowserDB";
$dbusername = "root";
$dbpassword = "";

try 
{
    // Connect to MySQL server
    $pdo = new PDO("mysql:host=$host", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");

    // Connect to the MinesweeperDB
    $pdo->exec("USE $dbname");

    $createPlayersTable = "
    CREATE TABLE IF NOT EXISTS players (
        id INT AUTO_INCREMENT PRIMARY KEY,
        player_name VARCHAR(100),
        player_team VARCHAR(100),
        player_number INT,
        player_status ENUM('healthy', 'injured') DEFAULT 'healthy',
        player_position ENUM('QB', 'RB', 'WR', 'TE', 'K', 'DEF') DEFAULT 'QB',
        image_path VARCHAR(255)
    )";
    $pdo->exec($createPlayersTable);

} catch (PDOException $e) 
{
    echo "Connection or operation failed: " . $e->getMessage();
}
