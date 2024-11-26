<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    try 
    {
        require_once 'includes/dbh.inc.php';        

        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

        $query = "DELETE FROM players WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) 
        {
            echo json_encode(['success' => true, 'message' => 'Player deleted successfully']);
        }
        else 
        {
            echo json_encode(['error' => 'Failed to delete player']);
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
