<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ca">
<head>
	<meta charset="UTF-8">
	<title>Admin</title>
</head>
<body>
	<h1>Benvingut Admin <?php echo $_SESSION['username']; ?></h1>
	<a href="logout.php"><button>Logout</button></a>
</body>
</html>

