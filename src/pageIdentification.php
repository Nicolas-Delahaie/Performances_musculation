<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs des champs d'identification
    $mail = $_POST["username"];
    $mdpSaisi = $_POST["password"];

    //Recuperation du mdp
    $_GET["for"] = "php";
    $_GET["query"] = "getPass";
    $user = include"scripts/dbAccess.php";

    // Vérifier si les identifiants sont valides
    if ($user != null){
        if ($user["mot_de_passe"] == $mdpSaisi) {
            // Rediriger l'utilisateur vers une page sécurisée
            $_SESSION["userId"] = $user["id_UTILISATEUR"];
            $_SESSION['programId'] = 1;                     //Mise par defaut sur le premier programme
            
            header("Location: ../index.php");
            exit;
        }
        else {
            // Afficher un message d'erreur si les identifiants sont invalides
            $error_message = "Mot de passe invalide. Veuillez réessayer.";
        }
    }
    else {
        // Afficher un message d'erreur si les identifiants sont invalides
        $error_message = "Adresse inconnue. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Page d'identification</title>
    <link rel="stylesheet" href="styles/pageIdentification.css">
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
<h1>Identification</h1>
    <?php if (isset($error_message)) { ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username"><br>
        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password"><br>
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>
