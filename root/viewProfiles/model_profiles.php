<?php 
$sql = "SELECT * FROM profiles";

$result = $conn->query($sql);

$row = $result->fetch();

print_r($row);
print("<p>".$row['realname']."</p>");
?>