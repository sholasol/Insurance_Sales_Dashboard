



<?php 
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'agency');

$con=mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE );
if(!$con){
	die("Connection Failed".mysqli_connect_error());
	}
	else {
		//echo "successful";
		}

?>
