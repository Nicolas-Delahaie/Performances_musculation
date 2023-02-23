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
        <script src="src/scripts.js" async defer></script>
    </head>
<?php
    /**
     * @brief Retourne le resultat de la requete mise en paremetres
     * @param $query la requete en question
     * @return ['codeRetour', ['msgErreur'], ['reponse']
     */
    function requestDB($query){
        $fonctionne = true;
        if ($fonctionne){
            return ["pushups"=>["nomAffiche"=>"Pompes" ,"favorite"=>true, "bodyWeight"=>true, "weight"=>0, "repetitions"=>40], 
                    "deadlift_halteres"=> ["nomAffiche"=>"Soulevé de terre", "favorite"=>true, "bodyWeight"=>false, "weight"=>100, "repetitions"=>3],
                    "overhead_press"=> ["nomAffiche"=>"Soulevé militaire à la barre", "favorite"=>false, "bodyWeight"=>false, "weight"=>60, "repetitions"=>2]];
        }
        else{
            throw "Impossible d'acceder a la bdd";
        }
    }

    session_start();
    $_SESSION['userId'] = 1;
    $_SESSION['category'] = 'TOUT';

    $user = "Nicolas";
    $categories = ["TOUT", "PULL", "PUSH", "LEGS", "+"];
    

    try{
        $exersises = requestDB(`SELECT E.titre, E.titre_affiche, P.repetitions, P.kilos, P.auPoidsDeCorps 
                               FROM EXERCICE E 
                               INNER JOIN PERFORMANCE P 
                               ON E.id_EXERCICE = P.id_EXERCICE
                               WHERE id_UTILISATEUR = `.strval($_SESSION['userId']).` AND
                                     id_CATEGORIE = `.$_SESSION["category"].`
                               `);
    }
    catch (string $e){
        echo $e;
    }


    

?>
    <body>        
        <header>
            <img src="src/datas/img/assets/logo.jpg" id="logo">
            <h1>Peng Records</h1>

            <nav>
                <menu> 
                    <p><?php echo $user?></p>
                    <a>Paramètres</a>
                </menu>
            </nav>
        </header>
        <main>
            <section id="categoriesZone">
<?php
    foreach ($categories as $categorie){
        echo "<a>".$categorie."</a>";
    }
?>
            </section>
            
            <form id="researchZone">
                <input type="text" id="searchBar" placeholder="Rechercher un exercice">
            </form>

            <section id="exercisesZone">
<?php
    foreach($exersises as $nomExo => $exersise){
        //Etoile
        if ($exersise["favorite"]){
            $nomImageEtoile = "etoilePleine.svg";
        }
        else{
            $nomImageEtoile = "etoileVide.svg";
        }

        //Poids souleve
        if ($exersise["bodyWeight"]){
            if ($exersise["weight"] == 0){
                //Poids du corps non leste
                $poids = strval($exersise["repetitions"])." reps";
            }
            else{
                //Poids du corps leste
                $poids = strval($exersise["repetitions"])." x (PDC + ".strval($exersise["weight"])." kg)";
            }
        }
        else{
            //Poids brutes
            $poids = strval($exersise["repetitions"])." x ".strval($exersise["weight"])." kg";
        }

        $html = '
        <section class="exersises" onclick="clicOnExersise(`'.$nomExo.'`)">
            <img class="imgExersise" src="src/datas/img/exercices/'.$nomExo.'.png">
            <section class="exersiseDescription">
                <section class="exersiseTitleZone">
                    <h2>'.$exersise["nomAffiche"].'</h2>
                    <img src="src/datas/img/assets/'.$nomImageEtoile.'" class="imgStar">
                </section>
                <p>'.$poids.'</p>
            </section>
        </section>';

        echo $html;
    }
?>
            
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
</html>


