<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    try 
    {
        require_once 'includes/dbh.inc.php';

        // Get player details
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $player_name = filter_input(INPUT_POST, 'player_name', FILTER_SANITIZE_STRING);
        $player_team = filter_input(INPUT_POST, 'player_team', FILTER_SANITIZE_STRING);
        $player_number = filter_input(INPUT_POST, 'player_number', FILTER_VALIDATE_INT);
        $player_status = filter_input(INPUT_POST, 'player_status', FILTER_SANITIZE_STRING);
        $player_position = filter_input(INPUT_POST, 'player_position', FILTER_SANITIZE_STRING);

        // Initialize query and parameters
        $query = "UPDATE players 
                  SET player_name = :player_name, 
                      player_team = :player_team, 
                      player_number = :player_number, 
                      player_status = :player_status, 
                      player_position = :player_position";

        $params = [
            ':player_name' => $player_name,
            ':player_team' => $player_team,
            ':player_number' => $player_number,
            ':player_status' => $player_status,
            ':player_position' => $player_position,
            ':id' => $id
        ];

        // Handle file upload
        if (isset($_FILES['playerImage']) && $_FILES['playerImage']['error'] === UPLOAD_ERR_OK) 
        {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) 
            {
                mkdir($uploadDir, 0777, true);
            }

            $file = $_FILES['playerImage'];
            $fileName = basename($file['name']);
            $fileTmpName = $file['tmp_name'];
            $fileType = mime_content_type($fileTmpName);

            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($fileType, $allowedTypes)) 
            {
                echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.']);
                exit();
            }

            // Validate file size
            $maxSize = 2 * 1024 * 1024;
            if ($file['size'] > $maxSize) 
            {
                echo json_encode(['error' => 'File size exceeds 2MB.']);
                exit();
            }

            // Generate unique file name
            $uniqueName = uniqid('img_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $destination = $uploadDir . $uniqueName;

            // Move file and update query
            if (move_uploaded_file($fileTmpName, $destination)) 
            {
                $query .= ", image_path = :image_path";
                $params[':image_path'] = $destination;
            } 
            else 
            {
                echo json_encode(['error' => 'Failed to move uploaded file.']);
                exit();
            }
        }

        $query .= " WHERE id = :id";
        $stmt = $pdo->prepare($query);

        // Execute the query
        if ($stmt->execute($params)) 
        {
            echo json_encode(['success' => true, 'message' => 'Player updated successfully', 'imagePath' => $params[':image_path'] ?? null]);
        } 
        else 
        {
            echo json_encode(['error' => 'Failed to update player']);
        }
    } 
    catch (Exception $e) 
    {
        echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    }
} 
else 
{
    echo json_encode(['error' => 'Invalid request method.']);
}

/*
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
*/