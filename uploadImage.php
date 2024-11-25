<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    try 
    {
        require_once 'includes/dbh.inc.php';

        // Set the upload directory
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) 
        {
            mkdir($uploadDir, 0777, true);
        }

        // Check if a file was uploaded
        if (!isset($_FILES['playerImage']) || $_FILES['playerImage']['error'] !== UPLOAD_ERR_OK) 
        {
            echo json_encode(['error' => 'File upload error.']);
            exit();
        }

        $file = $_FILES['playerImage'];
        $fileName = basename($file['name']);
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = mime_content_type($fileTmpName);

        // Validate file type (only allow images)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) 
        {
            echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.']);
            exit();
        }

        // Validate file size (limit to 2MB)
        $maxSize = 2 * 1024 * 1024;
        if ($fileSize > $maxSize) 
        {
            echo json_encode(['error' => 'File size exceeds 2MB.']);
            exit();
        }

        // Generate a unique file name to avoid collisions
        $uniqueName = uniqid('img_', true) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $destination = $uploadDir . $uniqueName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($fileTmpName, $destination)) 
        {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            $query = "UPDATE players SET image_path = :image_path WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':image_path', $destination, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'File uploaded successfully!', 'imagePath' => $destination]);
        } 
        else 
        {
            echo json_encode(['error' => 'Failed to move uploaded file.']);
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
