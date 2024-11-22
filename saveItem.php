<?php
$conn = new mysqli("localhost", "Lance", "LHei4016#", "fantasy_football_manager");

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

$index = isset($_POST['index']) ? (int)$_POST['index'] : -1;

$stmt = $conn->prepare("UPDATE players SET name=?, team=?, number=?, status=?, position=? WHERE id=?");
$stmt->bind_param("ssissi", $_POST['name'], $_POST['team'], $_POST['number'], $_POST['status'], $_POST['position'], $index);
$stmt->execute();

echo "Record updated!";

$conn->close();

/* OLD JSON CODE
$data = json_decode(file_get_contents("data.json"), true); // Get contents of file and store in string

$index = isset($_POST['index']) ? $_POST['index'] : -1; // Get index from post, if not set we use -1, go straight to error message

if ($index >= 0 && $index < count($data)) // Check for valid index
{
    // Save New Data From Post body
    $data[$index] = 
    [
        "name" => $_POST['name'],
        "team" => $_POST['team'],
        "number" => (int)$_POST['number'],
        "status" => $_POST['status'],
        "position" => $_POST['position']
    ];
    file_put_contents("data.json", json_encode($data, JSON_PRETTY_PRINT)); // Save back to file
}else 
{
    echo json_encode(["error" => "Invalid index"]); // Error if array index out of bounds
}
*/
?>
