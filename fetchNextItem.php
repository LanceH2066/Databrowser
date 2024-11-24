<?php
if($_SERVER["REQUEST_METHOD"] =="GET")
{
    try 
    {
        require_once 'includes/dbh.inc.php';

        $query = "SELECT COUNT(*) as total_entries FROM players";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $totalEntries = $stmt->fetch(PDO::FETCH_ASSOC)["total_entries"];

        if (isset($_GET['index'])) 
        {
            $offset = (int)$_GET['index'];
        } 
        else 
        {
            $offset = 0;
        }

        $query = "SELECT * FROM players ORDER BY id ASC LIMIT 1 OFFSET :offset";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $player = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($player) 
        {
            echo json_encode(['player' => $player, 'total_entries' => $totalEntries]);
        } 
        else 
        {
            echo json_encode(['error' => 'Player not found', 'total_entries' => $totalEntries]);
        }

        $pdo = null;
        $stsm = null;

        die();
    }    
    catch (PDOException $e) 
    {
        die("Query Failed: " . $e->getMessage());
    }
}
else
{
    header("Location: ../index.php");
    die();
}