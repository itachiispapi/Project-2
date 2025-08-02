<?php
require_once("db.php");

// Handle reset if button was clicked
if (isset($_POST['reset'])) {
    $conn->query("DELETE FROM game_stats");
    echo "<p style='color: red;'>Game stats reset.</p>";
}

// Fetch top game stats
$result = $conn->query("SELECT username, time_taken_seconds, moves_count, game_date FROM game_stats JOIN users ON game_stats.user_id = users.user_id ORDER BY time_taken_seconds ASC LIMIT 10");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin - Game Stats</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <h1> Fifteen Puzzle Admin Panel</h1>
  <table border="1">
    <tr><th>Rank</th><th>Username</th><th>Time (s)</th><th>Moves</th><th>Date</th></tr>
    <?php
    $rank = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>$rank</td><td>{$row['username']}</td><td>{$row['time_taken_seconds']}</td><td>{$row['moves_count']}</td><td>{$row['game_date']}</td></tr>";
        $rank++;
    }
    ?>
  </table>

  <form method="POST">
    <button name="reset" type="submit">Reset Game Stats</button>
  </form>
</body>
</html>
