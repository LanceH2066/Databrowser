<?php
// Database connection
$conn = new mysqli("localhost", "Lance", "LHei4016#", "fantasy_football_manager");

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the insert query
$stmt = $conn->prepare("INSERT INTO players (name, team, number, status, position) VALUES ('', '', 0, 'healthy', 'QB')");
$stmt->execute();

// Return the ID of the new entry as JSON
// Assuming you're using a MySQL database with auto-incrementing primary keys
$newIndex = $conn->insert_id;
echo json_encode(["newIndex" => $newIndex]);

// Close the connection
$stmt->close();
$conn->close();

/*
$file = 'data.json';
$items = json_decode(file_get_contents($file), true);

// Create a blank new item
$newItem = 
[
    "name" => "",
    "team" => "",
    "number" => "",
    "status" => "healthy",
    "position" => "QB"
];

// Append the new item
$items[] = $newItem;
$newIndex = count($items) - 1; // Get the new item's index

// Save the updated items list
file_put_contents($file, json_encode($items));

// Return the new index in JSON
echo json_encode(["newIndex" => $newIndex]);
*/
?>
