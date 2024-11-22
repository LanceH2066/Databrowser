<?php
$conn = new mysqli("localhost", "Lance", "LHei4016#");

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE fantasy_football_manager";

if ($conn->query($sql) === TRUE) 
{
    echo "Database created successfully!";
} 
else 
{
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?>
