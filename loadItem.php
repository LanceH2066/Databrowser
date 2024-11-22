<?php
// Database connection
$conn = new mysqli("localhost", "Lance", "LHei4016#", "fantasy_football_manager");

if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

// Get the index from the request
$index = isset($_GET['index']) ? (int)$_GET['index'] : 0;

// Fetch the total number of records
$result = $conn->query("SELECT COUNT(*) as total FROM players");
$total = $result->fetch_assoc()['total'];

if ($index >= 0 && $index < $total) 
{
    // Fetch the specific row at the given index
    $query = $conn->prepare("SELECT id, name, team, number, status, position FROM players LIMIT 1 OFFSET ?");
    $query->bind_param("i", $index);
    $query->execute();
    $result = $query->get_result();
    
    if ($row = $result->fetch_assoc()) 
    {
        // Add total to the response
        $row['total'] = $total;
        echo json_encode($row);
    } 
    else 
    {
        echo json_encode(["error" => "No record found at the given index."]);
    }
} 
else 
{
    echo json_encode(["error" => "Index out of bounds", "total" => $total]);
}

// Close the connection
$conn->close();

/* OLD JSON CODE
$data = json_decode(file_get_contents("data.json"), true);
$index = isset($_GET['index']) ? (int)$_GET['index'] : 0;

if ($index >= 0 && $index < count($data)) 
{
    $data[$index]['total'] = count($data); // Add total count
    echo json_encode($data[$index]);
} else 
{
    echo json_encode(["error" => "Index out of bounds"]);
}
*/
?>
