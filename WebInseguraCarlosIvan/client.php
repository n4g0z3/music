<?php
session_start();
require 'conexion.php';  // Incloure la connexió a la BD

$id_usuari = $_SESSION['id_u'];  // Suposant que s'ha guardat l'ID de l'usuari a la sessió

// Comprovar si s'ha enviat un missatge
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['missatge'])) {
	$missatge = $conn->real_escape_string($_POST['missatge']);
	$data = date("Y-m-d H:i:s");

	// Inserir missatge a la base de dades
	$sql = "INSERT INTO comentaris (missatge, data, id_u) VALUES ('$missatge', '$data', '$id_usuari')";
    
	if ($conn->query($sql) === TRUE) {
		echo "<p style='color:green;'>Missatge guardat correctament!</p>";
	} else {
    	echo "<p style='color:red;'>Error en guardar el missatge: " . $conn->error . "</p>";
	}
}

// Comprovar si s'ha pujat un fitxer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['fitxer'])) {
	$target_dir = "uploads/";  // Directori per emmagatzemar els fitxers pujats
	$target_file = $target_dir . basename($_FILES["fitxer"]["name"]);
	$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
	$uploadOk = 1;
    
	// Si tot està bé, pujem el fitxer
	if ($uploadOk == 1) {
    	if (move_uploaded_file($_FILES["fitxer"]["tmp_name"], $target_file)) {
        	echo "<p style='color:green;'>El fitxer s'ha pujat correctament.</p>";
       	 
        	// Guardem la ruta del fitxer a la base de dades
        	$data_upload = date("Y-m-d H:i:s");
        	$sql = "INSERT INTO fitxers (ruta_fitxer, tipus_fitxer, data, id_u) VALUES ('$target_file', '$fileType', '$data_upload', '$id_usuari')";
       	 
        	if ($conn->query($sql) === TRUE) {
            	echo "<p style='color:green;'>Ruta del fitxer guardada a la base de dades.</p>";
        	} else {
            	echo "<p style='color:red;'>Error en guardar la ruta del fitxer a la base de dades: " . $conn->error . "</p>";
        	}
    	} else {
        	echo "<p style='color:red;'>Hi ha hagut un error en pujar el fitxer.</p>";
    	}
	}
}

// Obtenir els missatges de l'usuari
$sql = "SELECT missatge, data FROM comentaris WHERE id_u = '$id_usuari' ORDER BY data DESC";
$result = $conn->query($sql);

// Obtenir els fitxers de l'usuari
$sql_files = "SELECT ruta_fitxer, tipus_fitxer FROM fitxers WHERE id_u = '$id_usuari' ORDER BY data DESC";
$files_result = $conn->query($sql_files);

?>

<!DOCTYPE html>
<html lang="ca">
<head>
	<meta charset="UTF-8">
	<title>Client</title>
</head>
<body>
	<h1>Benvingut Client <?php echo $_SESSION['email']; ?></h1>

	<!-- Formulari per afegir un missatge -->
	<h2>Escriu un missatge:</h2>
	<form method="post">
    	<input type="text" name="missatge" required>
    	<input type="submit" value="Enviar">
	</form>

	<!-- Formulari per pujar una imatge -->
	<h2>Puja una imatge:</h2>
	<form method="post" enctype="multipart/form-data">
    	<input type="file" name="fitxer" required>
    	<input type="submit" value="Pujar imatge">
	</form>

	<!-- Mostrar les imatges pujades -->
	<h3>Imatges pujades:</h3>
	<?php
	if ($files_result->num_rows > 0) {
    	while ($row = $files_result->fetch_assoc()) {
        	echo "<img src='" . $row["ruta_fitxer"] . "' alt='Imatge' width='200' height='auto'><br>";
    	}
	} else {
    	echo "<p>No hi ha imatges pujades.</p>";
	}
	?>

	<!-- Mostrar els missatges de l'usuari -->
	<h2>Els teus missatges:</h2>
	<ul>
    	<?php
    	if ($result->num_rows > 0) {
        	while($row = $result->fetch_assoc()) {
            	echo $row["missatge"];;
        	}
    	} else {
        	echo "<li>No hi ha missatges.</li>";
    	}
    	?>
	</ul>

	<a href="logout.php"><button>Logout</button></a>
</body>
</html>

<?php $conn->close(); ?>