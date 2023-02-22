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
    $user = "Nicolas";
    $categories = ["TOUT", "PULL", "PUSH", "LEGS", "+"];
    $exersisesBdTemp = ["pushups"=>["nomAffiche"=>"Pompes" ,"favorite"=>true, "bodyWeight"=>true, "weight"=>0, "repetitions"=>40], 
                        "deadlift_halteres"=> ["nomAffiche"=>"Soulevé de terre", "favorite"=>true, "bodyWeight"=>false, "weight"=>100, "repetitions"=>3],
                        "overhead_press"=> ["nomAffiche"=>"Soulevé militaire à la barre", "favorite"=>false, "bodyWeight"=>false, "weight"=>60, "repetitions"=>2]];
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
    foreach($exersisesBdTemp as $nomExo => $exersise){
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


