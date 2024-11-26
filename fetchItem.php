<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") 
{
    try 
    {
        require_once 'includes/dbh.inc.php';
        require_once 'includes/config_session.inc.php';

        // Keep total_entries updated
        $query = "SELECT COUNT(*) as total_entries FROM players";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $totalEntries = $stmt->fetch(PDO::FETCH_ASSOC)["total_entries"];

        // Get the row number from the GET request
        if (isset($_GET['index'])) 
        {
            $rowNumber = (int)$_GET['index'];
        } 
        else 
        {
            echo json_encode(['error' => 'No row number provided']);
            die();
        }
        // Get sortmode
        $sortMode = isset($_SESSION['sortMode']) ? (int) $_SESSION['sortMode'] : 0;
        $sortColumn = $sortMode === 0 ? 'id' : 'player_name';
        $offset = $rowNumber -1;
        // Query using rownumber as offset to avoid id gaps
        $query = "SELECT * FROM players ORDER BY $sortColumn ASC LIMIT 1 OFFSET $offset";
        $stmt = $pdo->prepare($query);
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
