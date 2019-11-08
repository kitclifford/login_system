<?php 
include('database.php');

$query = 'SELECT * FROM `users`;';
$result = mysqli_query($conn, $query);
var_dump($result);
if (mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)){
        echo 'Email: '.$row['username'].' Activation Code: '.$row['activation_code'];
        echo '<br/>';
	}
}

?>  