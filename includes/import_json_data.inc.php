<?php
$jsonData = file_get_contents('tables/data.json');
$data = json_decode($jsonData, true);

try 
{
    require_once 'dbh.inc.php';

    $query = "SELECT * FROM players";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $players = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$players)       // check if there is already data, if so then dont import
    {
        foreach ($data['players'] as $player) 
        {
            $query = "  INSERT INTO players (player_name, player_team, player_number, player_status, player_position, image_path) 
                        VALUES (:player_name, :player_team, :player_number, :player_status, :player_position, :image_path)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":player_name", $player['player_name']);
            $stmt->bindParam(":player_team", $player['player_team']);
            $stmt->bindParam(":player_number", $player['player_number']);
            $stmt->bindParam(":player_status", $player['player_status']);
            $stmt->bindParam(":player_position", $player['player_position']);
            $stmt->bindParam(":image_path", $player['image_path']);
            $stmt->execute();
        }
    }
} catch (PDOException $e) 
{
    echo "Error: " . $e->getMessage();
}