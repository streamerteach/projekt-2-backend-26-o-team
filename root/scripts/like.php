<?php
include "./databaseConnection.php";
include "./sessionhandler.php";

$username = $_SESSION['username'];
echo ($username);
/*$sql = "SELECT `id` FROM `profiles` WHERE `username` = $username";
$result = $conn->query($sql);
$row = $result->fetch();
print_r($row);
$accountID; //empty for now
*/

$currentLikes = "SELECT likes FROM profiles WHERE profiles.id = $accountID";
$like = $currentLikes++;
$newLikes = "UPDATE profiles SET likes = $like WHERE profiles.profiles = $accountID";

?>