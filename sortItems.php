<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    try 
    {
        require_once 'includes/dbh.inc.php';
        require_once 'includes/config_session.inc.php';

        $sortMode = (int) $_POST['sortMode'];
        $_SESSION['sortMode'] = $sortMode; // Store sortMode in session

        echo json_encode(['success' => true, 'message' => 'Sorted successfully']);

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
