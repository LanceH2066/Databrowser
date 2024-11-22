<?php
$conn = new mysqli("localhost", "Lance", "LHei4016#", "fantasy_football_manager");

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

$index = isset($_POST['index']) ? (int)$_POST['index'] : -1;

$stmt = $conn->prepare("DELETE FROM players WHERE id=?");
$stmt->bind_param("i", $index);
$stmt->execute();

echo "Record deleted!";

$conn->close();

/*
$data = json_decode(file_get_contents("data.json"), true); // Get contents of file and store in string

$index = isset($_POST['index']) ? $_POST['index'] : -1; // Get index from post, if not set we use -1, go straight to error message

if ($index >= 0 && $index < count($data)) // Check for valid index
{
    array_splice($data, $index, 1); // Delete the item
    file_put_contents($file, json_encode($data)); // Save updated list
    $total = count($data); // Get Array Size
    echo json_encode(["total" => $total]); // Send Size Back As Response
} else 
{
    echo json_encode(["error" => "Invalid index"]); // Error if array index out of bounds
}
*/
?>
