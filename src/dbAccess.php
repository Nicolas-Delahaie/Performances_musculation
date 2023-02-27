<?php
/**
 * @brief Retourne le resultat de la requete mise en paremetres
 * @param $_GET["query"] identifiant de la requete en question
 * @details = "userName" : Renvoie le nom lie a $_SESSION["userId"]
 * @details = "programName" : Renvoie le nom du programme selectionne dans $_SESSION["programId"]
 * @details = "defaultPrograms" : Renvoie le nom des programmes par defaut
 * @details = "privatePrograms" : Renvoie le nom des programmes crees par $_SESSION["userId"]
 * @details = "exersises" : Renvoie tous les exercices lies a $_SESSION["programId"] et a $_SESSION["userId"]
 * @return mixed
 */

if (!defined("BDD_NOM")){
    //Si c est le premier appel au script
    define("BDD_NOM", "nicolasdelahaie_site_muscu");
    define("BDD_HOTE", "mysql-nicolasdelahaie.alwaysdata.net");

    define("DB_READER_USER_ID", "294941_lecture");
    define("DB_READER_USER_PASS", "gvbh_ç75678UIJN?§/.?!:;ùù^^$=)=");

    define("DB_WRITER_USER_ID", "294941_writer");
    define("DB_WRITER_USER_PASS", "*ùjcrH_è89762à':;,");
}

//Verification des parametres
if (!isset($_SESSION["userId"])){
    //Aucun utilisateur connecte
    throw new Exception ("Impossible d'acccéder à la base de données sans etre connecté");
}
if (!isset($_GET["query"])){
    //Aucune requete mise en parametre
    throw new Exception ("Necessite une requete en parametre GET");
}

//Creation de la requete
switch ($_GET["query"]) {
    case 'userName': 
        $sql = "SELECT prenom FROM UTILISATEUR WHERE id_UTILISATEUR =".$_SESSION['userId'].";"; 
        $dimension = 1; 
        $readOnly = true;
        break;

    case 'programName':
        $sql = "SELECT nom FROM PROGRAMME WHERE id_PROGRAMME =".$_SESSION['programId'].";"; 
        $dimension = 1; 
        $readOnly = true;
        break;

    case 'defaultPrograms':
        $sql = "SELECT nom FROM PROGRAMME WHERE id_UTILISATEUR IS NULL;"; 
        $dimension = 2; 
        $readOnly = true;
        break;

    case 'privatePrograms': 
        $sql = "SELECT nom FROM PROGRAMME WHERE id_UTILISATEUR =".$_SESSION['userId'].";"; 
        $dimension = 2; 
        $readOnly = true;
        break;

    case 'exersises': 
        $sql = "SELECT E.titre, E.titre_affiche, E.auPoidsDeCorps, P.repetitions, P.kilos 
                FROM EXERCICE E 
                LEFT JOIN PERFORMANCE P ON P.id_EXERCICE = E.id_EXERCICE
                INNER JOIN EXERCICE_PROGRAMME_UTILISATEUR EPU ON EPU.id_EXERCICE = E.id_EXERCICE
                WHERE EPU.id_UTILISATEUR = ".$_SESSION['userId']." AND 
                        EPU.id_PROGRAMME = ".$_SESSION["programId"]."
                GROUP BY E.titre;"; 
        $dimension = 3; 
        $readOnly = true;
        break;

    default: 
        throw new Exception ("Requete invalide"); break;
}

//Connexion a la bdd
try{
    if ($readOnly){
        //Lecture
        $bdd = new PDO('mysql:host='.BDD_HOTE.';dbname='.BDD_NOM.';charset=utf8', DB_READER_USER_ID, DB_READER_USER_PASS);
    }
    else{
        //Ecriture
        $bdd = new PDO('mysql:host='.BDD_HOTE.';dbname='.BDD_NOM.';charset=utf8', DB_WRITER_USER_ID, DB_WRITER_USER_PASS);
    }
}
catch (Exception $e){
    throw new Exception ("Connexion a la base de données impossible");
}

//Envoi de la requete
try{
    $requette = $bdd->prepare($sql);
    $requette->execute();
    $reponses = $requette->fetchAll();
}
catch(Exception $e){
    throw new Exception ("Impossible de récupérer les données");
}

//Uniformisation de la reponse
try{
    switch ($dimension) {
        case 1:
            //Reponse = valeur unique
            $reponses = $reponses[0][0];
            break;

        case 2:
            //Reponse = liste simple
            foreach ($reponses as &$reponse){
                $reponse = $reponse[0];
            }
            break;
    }
    return $reponses;
}
catch(Exception $e){
    throw new Exception ("Probleme de troncage des donnees recuperees");
}
?>