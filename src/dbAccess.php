<?php
/**
 * @brief Retourne le resultat de la requete mise en paremetres
 * @param $_GET["query"] identifiant de la requete en question
 * @param $_GET["for"] "js" ou "php"
 * @details = "getUserName" : Renvoie le nom lie a $_SESSION["userId"]
 * @details = "getCurrentProgramName" : Renvoie le nom du programme courrant dans $_SESSION["programId"]
 * @details = "getDefaultPrograms" : Renvoie le nom et l id des programmes par defaut
 * @details = "getPrivatesPrograms" : Renvoie le nom et l id des programmes crees par $_SESSION["userId"]
 * @details = "getExersiseInformations" : Renvoie les informations liees de l exercice $_GET["idExersise"] de l utilisateur $_SESSION["userId"]
 * @details = "getExersisesInformations" : Renvoie les informations de tous les exercices du programme $_SESSION["programId"] de l utilisateur $_SESSION["userId"]
 * @return mixed
 * @warning Avec getExersiseInformations, tout le monde peut acceder aux exos de n importe qui
 */

if (!defined("typeReponseClass")){
    define("typeReponseClass","ui");
    class TypeReponse {
        const NB_TUPLES_SIMPLE = "1";
        const NB_TUPLES_X = "X";
        const NB_VALEURS_SIMPLE = "1";
        const NB_VALEURS_X = "X";
    
        public string $nbTuples;
        public string $nbValeurs;
    
        public function __construct(string $nbTuples, string $nbValeurs) {
            $this->nbTuples = $nbTuples;
            $this->nbValeurs = $nbValeurs;
        }
    }
}

if (!defined("BDD_NOM")){
    //Si c est le premier appel au script
    define("BDD_NOM", "nicolasdelahaie_site_muscu");
    define("BDD_HOTE", "mysql-nicolasdelahaie.alwaysdata.net");

    define("DB_READER_USER_ID", "294941_lecture");
    define("DB_READER_USER_PASS", "gvbh_ç75678UIJN?§/.?!:;ùù^^$=)=");

    define("DB_WRITER_USER_ID", "294941_writer");
    define("DB_WRITER_USER_PASS", "*ùjcrH_è89762à':;,");
}

try{
    //Verification des parametres
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["userId"])){
        //Aucun utilisateur connecte
        throw new Exception ("Impossible d'acccéder à la base de données sans etre connecté");
    }       
    if (!isset($_GET["for"])){
        //Doit preciser le type de retour
        throw new Exception ('Le script doit savoir quel type de donnee il doit renvoyer dans $_GET["for"] (php par defaut)');
    }
    if (!isset($_GET["query"])){
        //Aucune requete mise en parametre
        throw new Exception ("Necessite une requete en parametre GET");
    }
    
    //Creation de la requete
    switch ($_GET["query"]) {
        case 'getUserName': 
            $sql = "SELECT prenom FROM UTILISATEUR WHERE id_UTILISATEUR =".$_SESSION['userId'].";"; 
            $typeReponse = new TypeReponse("1","1");
            $get = true;
            break;
    
        case 'getCurrentProgramName':
            $sql = "SELECT nom FROM PROGRAMME WHERE id_PROGRAMME =".$_SESSION['programId'].";"; 
            $typeReponse = new TypeReponse("1","1");
            $get = true;
            break;
    
        case 'getDefaultPrograms':
            $sql = "SELECT id_PROGRAMME,nom FROM PROGRAMME WHERE id_UTILISATEUR IS NULL;"; 
            $typeReponse = new TypeReponse("X","X");
            $get = true;
            break;
    
        case 'getPrivatesPrograms': 
            $sql = "SELECT id_PROGRAMME,nom FROM PROGRAMME WHERE id_UTILISATEUR =".$_SESSION['userId'].";"; 
            $typeReponse = new TypeReponse("X","X");
            $get = true;
            break;
    
        case 'getExersiseInformations': 
            if (!isset($_GET["idExersise"])){
                throw new Exception ('Un parametre de requete est attendu'); break;
            }
            $sql = "SELECT E.titre, E.titre_affiche, E.auPoidsDeCorps, P.repetitions, P.kilos 
                    FROM EXERCICE E 
                    LEFT JOIN PERFORMANCE P ON P.id_EXERCICE = E.id_EXERCICE
                    INNER JOIN EXERCICE_PROGRAMME_UTILISATEUR EPU ON EPU.id_EXERCICE = E.id_EXERCICE
                    WHERE EPU.id_UTILISATEUR = ".$_SESSION['userId']." AND
                          E.id_EXERCICE = ".$_GET["idExersise"]."
                    GROUP BY E.titre;";

            $typeReponse = new TypeReponse("1","X"); 
            $get = true;
            break;
    
        case 'getExersisesInformations': 
            $sql = "SELECT E.id_EXERCICE, E.titre, E.titre_affiche, E.auPoidsDeCorps, P.repetitions, P.kilos 
                    FROM EXERCICE E 
                    LEFT JOIN PERFORMANCE P ON P.id_EXERCICE = E.id_EXERCICE
                    INNER JOIN EXERCICE_PROGRAMME_UTILISATEUR EPU ON EPU.id_EXERCICE = E.id_EXERCICE
                    WHERE EPU.id_UTILISATEUR = ".$_SESSION['userId']." AND 
                            EPU.id_PROGRAMME = ".$_SESSION["programId"]."
                    GROUP BY E.titre;"; 
            $typeReponse = new TypeReponse("X","X");
            $get = true;
            break;
            
        default:
            throw new Exception ('Requete "'.$_GET["query"].'" invalide'); break;
    }
    
    //Connexion a la bdd
    try{
        if ($get){
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
        if ($get){
            $reponses = $requette->fetchAll();
        }
    }
    catch(Exception $e){
        throw new Exception ("Impossible de récupérer les données");
    }
    
    //Uniformisation de la reponse
    if ($get){
        //Tronque le resultat
        try{
            if ($typeReponse->nbTuples == "1"){
                if ($typeReponse->nbValeurs == "1"){
                    //Valeur unique
                    $reponses = $reponses[0][0];
                }
                else if ($typeReponse->nbValeurs == "X"){
                    //Valeur complexe
                    $reponses = $reponses[0];
                }
            }
            else if ($typeReponse->nbTuples == "X"){
                if ($typeReponse->nbValeurs == "1"){
                    //Liste simple
                    foreach ($reponses as &$reponse){
                        $reponse = $reponse[0];
                    }
                }
            }

            //Renvoie le resultat
            if ($_GET["for"] == "js"){
                //Retourne le resultat en JSON
                echo json_encode(["status"=>"success","datas"=>$reponses]);
            }
            else{
                //Retourne le resultat en php
                return $reponses;
            }
        }
        catch(Exception $e){
            throw new Exception ("Probleme de troncage des donnees recuperees");
        }
    }
}
catch(Exception $e){
    if ($_GET["for"] == "js"){
        //Retourne l erreur en JSON
        echo json_encode(["status"=>"fail","message"=>$e->getMessage()]);
    }
    else{
        //Renvoie l erreur en php
        throw $e;
    }
}

?>