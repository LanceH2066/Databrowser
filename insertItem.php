<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    try 
    {
        require_once 'includes/dbh.inc.php';

        // Insert an empty row with NULL values
        $query = "INSERT INTO players (player_name, player_team, player_number, player_status, player_position) 
                  VALUES (NULL, NULL, NULL, NULL, NULL)";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        echo json_encode(['success' => true]);

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
