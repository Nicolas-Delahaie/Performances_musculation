<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="src/styles/mainPage.css">
        <link rel="stylesheet" href="src/styles/index.css">
        <script src="src/scripts/index.js" async defer></script>
    </head>
<?php
    session_start();

    if (!isset($_SESSION["userId"])){
        //Utilisateur par defaut
        $nonConnecte = true;
    }
    else{
        $nonConnecte = false;
    }

    try{
        $_GET["for"] = "php";

        if (!$nonConnecte){
            //Nom de l utilisateur
            $_GET["query"] = "getUserName";
            $user = include"src/scripts/dbAccess.php";
            
            //Programmes prives lies au programme actuel
            $_GET["query"] = "getPrivatesPrograms";
            $privatePrograms = include"src/scripts/dbAccess.php";
        
            //Exercices
            if (isset($_GET["searchValue"]) and $_GET["searchValue"] != ""){
                //On va trier uniquement les exercices lies a la recherche
                $_GET["query"] = "getAllExersisesInformations";
                $exersisesDB = include"src/scripts/dbAccess.php";
                $exersises = [];
                foreach($exersisesDB as $exersiseDB){
                    if (mb_stripos($exersiseDB["titre_affiche"], $_GET["searchValue"]) !== false){
                        //Le titre de l exercice contient la recherche
                        array_push($exersises, $exersiseDB);
                    }
                }
            }
            else{
                $_GET["query"] = "getExersisesInformations";
                $exersises = include"src/scripts/dbAccess.php";
            }
        }
        else{
            $user = [];            
            $privatePrograms = [];
            $exersises = [];
                
        }

        //Programmes par defauts
        $_GET["query"] = "getDefaultPrograms";
        $defaultPrograms = include"src/scripts/dbAccess.php";
    }
    catch(Exception $e){
        echo $e->getMessage()    ;
    }

    // -- H E A D E R --
    echo "
    <body>        
        <header>
            <img src='src/datas/img/assets/logo.jpg' id='logo'>
            <h1>Peng Records</h1>

            <nav>
                <menu>";
    if (!$nonConnecte){
        echo "
        <p>".$user."</p>
        <a href='src/scripts/deconnexion.php'>Se deconnecter</a>
        <a>Paramètres</a>";
    }
    else{
        echo "<a href='src/pageIdentification.php'>Connexion</a>";
    }
                    
    echo "
                </menu>
            </nav>
        </header>
        <main>
            <section id='programsZone'>";

    // -- P R O G R A M M E S --
    foreach ($defaultPrograms as $program){
        echo "<a href='./src/scripts/programChange.php?program=".$program["id_PROGRAMME"]."'>".$program["nom"]."</a>";
    }
    foreach ($privatePrograms as $program){
        echo "<a href='./src/scripts/programChange.php?program=".$program["id_PROGRAMME"]."'>".$program["nom"]."</a>";
    }
    echo "<a>+</a>";

    echo '  </section>
            
            <form id="researchZone" method="GET">
                <input id="searchBar" name="searchValue" type="text" placeholder="Rechercher un exercice">
                <button type="submit">Valider</button>
            </form>

            <section id="exercisesZone">';

    // -- Z O N E   E X E R C I C E S --
    foreach($exersises as $exersise){
        //Etoile
        // if ($exersise["favorite"]){
        //     $nomImageEtoile = "etoilePleine.svg";
        // }
        // else{
        //     $nomImageEtoile = "etoileVide.svg";
        // }
        $nomImageEtoile = "etoilePleine.svg";

        //Poids souleve
        if ($exersise["auPoidsDeCorps"]){
            if ($exersise["kilos"] == 0){
                //Poids du corps non leste
                $poids = strval($exersise["repetitions"])." reps";
            }
            else{
                //Poids du corps leste
                $poids = strval($exersise["repetitions"])." x (PDC + ".strval($exersise["kilos"])." kg)";
            }
        }
        else{
            //Poids brutes
            $poids = strval($exersise["repetitions"])." x ".strval($exersise["kilos"])." kg";
        }

        echo '
        <section class="exersises" onclick="clicOnExersise(`'.$exersise["id_EXERCICE"].'`)">
            <img class="imgExersise" src="src/datas/img/exercices/'.$exersise["titre"].'.png">
            <section class="exersiseDescription">
                <section class="exersiseTitleZone">
                    <h2>'.$exersise["titre_affiche"].'</h2>
                    <img src="src/datas/img/assets/'.$nomImageEtoile.'" class="imgStar">
                </section>
                <p>'.$poids.'</p>
            </section>
        </section>';

    }

    // -- Z O N E   D O S S I E R S --
    echo '  <section class="directories">
                <h2>PECS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            <section class="directories">
                <h2>BICEPS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            <section class="directories">
                <h2>QUADRICEPS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>


            <section class="directories">
                <h2>PECS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            <section class="directories">
                <h2>BICEPS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            <section class="directories">
                <h2>QUADRICEPS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>
            <section class="directories">
                <h2>PECS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            <section class="directories">
                <h2>BICEPS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            <section class="directories">
                <h2>QUADRICEPS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>
            <section class="directories">
                <h2>PECS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            <section class="directories">
                <h2>BICEPS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            <section class="directories">
                <h2>QUADRICEPS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>
            <section class="directories">
                <h2>PECS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            <section class="directories">
                <h2>BICEPS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            <section class="directories">
                <h2>QUADRICEPS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>
            <section class="directories">
                <h2>PECS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            <section class="directories">
                <h2>BICEPS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            <section class="directories">
                <h2>QUADRICEPS</h2>
                <img src="src/datas/img/assets/imgDossier.png" class="imgDir">
            </section>

            </section>
            </main>
            </body>
            </html>';

?>