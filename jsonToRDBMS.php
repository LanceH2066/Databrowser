<?php
$file = 'data.json';
$data = json_decode(file_get_contents($file), true);

$conn = new mysqli("localhost", "Lance", "LHei4016#", "fantasy_football_manager");

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

foreach ($data as $item) 
{
    $stmt = $conn->prepare("INSERT INTO players (name, team, number, status, position) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $item['name'], $item['team'], $item['number'], $item['status'], $item['position']);
    $stmt->execute();
}

echo "Data imported successfully!";
$conn->close();
?>
