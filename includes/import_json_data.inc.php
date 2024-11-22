<?php
$jsonData = file_get_contents('data.json');
$data = json_decode($jsonData, true);

try 
{
    require_once 'dbh.inc.php';

    $query = "SELECT * FROM players";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $players = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$players)
    {
        foreach ($data['players'] as $player) 
        {
            $query = "  INSERT INTO players (player_name, player_team, player_number, player_status,player_position) 
                        VALUES (:player_name, :player_team, :player_number, :player_status, :player_position)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":player_name", $player['player_name']);
            $stmt->bindParam(":player_team", $player['player_team']);
            $stmt->bindParam(":player_number", $player['player_number']);
            $stmt->bindParam(":player_status", $player['player_status']);
            $stmt->bindParam(":player_position", $player['player_position']);
            $stmt->execute();
        }
    }
} catch (PDOException $e) 
{
    echo "Error: " . $e->getMessage();
}