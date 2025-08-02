<?php
session_start();
require_once("db.php");

// TEMP DEBUGGING:
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "User not logged in";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $time = isset($_POST['time']) ? intval($_POST['time']) : 0;
    $moves = isset($_POST['moves']) ? intval($_POST['moves']) : 0;
    $win = isset($_POST['win']) ? ($_POST['win'] == '1' || $_POST['win'] == 'true') : false;
    $date = date("Y-m-d H:i:s");

    $puzzle_size = '4x4';  
    $stmt = $conn->prepare("INSERT INTO game_stats (user_id, puzzle_size, time_taken_seconds, moves_count, win_status, game_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiss", $user_id, $puzzle_size, $time, $moves, $win, $date);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        http_response_code(500);
        echo "Error saving game";
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo "Method not allowed";
}
?>
