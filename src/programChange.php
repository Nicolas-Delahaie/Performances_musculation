<?php
/**
 * @warning Tout le monde peut acceder aux programmes de n'importe qui
 */
if (!isset($_GET["program"])){
    throw new Exception("Aucun programme selectionne");
}
session_start();
$_SESSION['programId'] = $_GET["program"];
header("Location: ./../index.php")
?>