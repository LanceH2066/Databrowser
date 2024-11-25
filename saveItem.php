<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    try 
    {
        require_once 'includes/dbh.inc.php';

        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
        $player_name = filter_input(INPUT_POST, 'player_name', FILTER_SANITIZE_STRING);
        $player_team = filter_input(INPUT_POST, 'player_team', FILTER_SANITIZE_STRING);
        $player_number = filter_input(INPUT_POST, 'player_number', FILTER_VALIDATE_INT);
        $player_status = filter_input(INPUT_POST, 'player_status', FILTER_SANITIZE_STRING);
        $player_position = filter_input(INPUT_POST, 'player_position', FILTER_SANITIZE_STRING);

        $query = "UPDATE players 
                  SET player_name = :player_name, 
                      player_team = :player_team, 
                      player_number = :player_number, 
                      player_status = :player_status, 
                      player_position = :player_position 
                  WHERE id = :id";
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':player_name', $player_name, PDO::PARAM_STR);
        $stmt->bindParam(':player_team', $player_team, PDO::PARAM_STR);
        $stmt->bindParam(':player_number', $player_number, PDO::PARAM_INT);
        $stmt->bindParam(':player_status', $player_status, PDO::PARAM_STR);
        $stmt->bindParam(':player_position', $player_position, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) 
        {
            echo "<h1>SUCCESS!</h1>";
            echo json_encode(['success' => true, 'message' => 'Player updated successfully']);
        }
        else 
        {
            echo json_encode(['error' => 'Failed to update player']);
        }

        $pdo = null;
        $stmt = null;

        die();
    } 
    catch (PDOException $e) 
    {
        echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
    }
} 
else 
{
    header("Location: ../index.php");
    die();
}
