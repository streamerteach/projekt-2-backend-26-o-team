<?php
include "./databaseConnection.php";
include "./sessionhandler.php";



function create_user($username, $realname, $zipcode, $bio, $salary, $preference, $email, $likes, $role, $password) {

    
    $passhash = password_hash($password,null);
    
    $sql = "INSERT INTO `profiles` (`id`, `username`, `realname`, `zipcode`, `bio`, `salary`, `preference`, `email`, `likes`, `role`, `passhash`) VALUES 
    (NULL, :username, :realname, :zipcode, :bio, :salary, :preference, :email, NULL, 1, :passhash);";

$stmt = $conn->prepare($sql);
$stmt->execute([
 ':username' => $username,
 'realname' => $realname,
 ':zipcode' => $zipcode,
 ':bio' => $bio,
 ':salary' => $salary,
 ':preference' => $preference,
 ':email' => $email,
 ':passhash' => $passhash
]);

}

?>