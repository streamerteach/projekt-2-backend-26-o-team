<?php 
include "../scripts/databaseConnection.php";
$conn = create_conn();
$sql = "SELECT * FROM profiles";

$result = $conn->query($sql);

//iterate through every profile and print key columns
foreach ($result as $row) {
    echo '<div class="singleProfile">';
    echo '<h4>' . htmlspecialchars($row['username']) . '</h4>';
    echo '<p><strong>Real name:</strong> ' . htmlspecialchars($row['realname']) . '</p>';
    echo '<p><strong>Bio:</strong> ' . htmlspecialchars($row['bio']) . '</p>';
    echo '<p><strong>Salary:</strong> ' . htmlspecialchars($row['salary']) . '</p>';
    $map = ['0'=> 'All', '1'=>'Men', '2'=>'Women', '3'=>'Other'];
    echo '<p><strong>Preference:</strong> ' . ($map[$row['preference']] ?? 'Unknown') . '</p>';
    echo '<p><strong>Likes:</strong> ' . htmlspecialchars($row['likes'] ?? '0') . '</p>';
    echo '</div>';
}
?>