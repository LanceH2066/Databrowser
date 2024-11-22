<?php
if($_SERVER["REQUEST_METHOD"] =="GET")
{
    try 
    {
        require_once 'includes/dbh.inc.php';

        if(isset($_GET['index']))
        {
            $index = (int)$_GET['index']+1;
        }
        else
        {
            $index = 1;  
        }

        $query = "SELECT COUNT(*) as total_entries FROM players";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $size = $stmt->fetch(PDO::FETCH_ASSOC);

        $totalEntries = $size["total_entries"];
        $player = null;

        while(!$player && $index <= $totalEntries)
        {
            $player = getPlayer($pdo, $index);
            if ($player) 
            {
                echo json_encode(['player' => $player, 'total_entries' => $totalEntries]);
                die();
            }
            else
            {
                $index++;
            }
        }
        
        echo json_encode([
            'error' => 'Player not found',
            'total_entries' => $totalEntries,
        ]);

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

function getPlayer($pdo, $index)
{
    $query = "SELECT * FROM players WHERE id = :player_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':player_id', $index);
    $stmt->execute();
    $player = $stmt->fetch(PDO::FETCH_ASSOC);
    return $player;
}