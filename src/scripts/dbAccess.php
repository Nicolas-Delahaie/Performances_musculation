<?php
/**
 * @brief Retourne le resultat de la requete mise en paremetres
 * @param $_GET["query"] identifiant de la requete en question
 * @param $_GET["for"] "js" ou "php"
 * @details = "getUserName" : Renvoie le nom lie a $_SESSION["userId"]
 * @details = "getDefaultPrograms" : Renvoie le nom et l id des programmes par defaut
 * @details = "getPrivatesPrograms" : Renvoie le nom et l id des programmes crees par $_SESSION["userId"]
 * @details = "getExersiseInformations" : Renvoie les informations liees de l exercice $_GET["idExersise"] de l utilisateur $_SESSION["userId"]
 * @details = "getExersisesInformations" : Renvoie les informations de tous les exercices du programme $_SESSION["programId"] de l utilisateur $_SESSION["userId"]
 * @detials = "getAllExersisesInformations" : Renvoie les informations de tous les exercices (sauf ceux créés par les autres utilisateurs)
 * @details = "addPerf" : Ajoute une performance a la bdd a partir de GET(["idExo"], ["datePerf"], ["repetitions"], ["poids"])
 * @details = "getPass" : Recupere le mot de passe crypte de $mail
 * @return mixed
 */


$get;           //Indique si la requete doit renvoyer un resultat
$notInMemory;   //Indique si la reponse demandee est deja en memoire
$erreur = false;

//Declarations
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
if (!isset($_SESSION["programsInMemory"])){
    $_SESSION["programsInMemory"] = [];
}
if (!isset($_SESSION["exersisesIdsInMemory"]) or !isset($_SESSION["exersisesInMemory"])){
    $_SESSION["exersisesIdsInMemory"] = [];
    $_SESSION["exersisesInMemory"] = [];
}

//Envoi de la requete
try{
    //Verification des parametres
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_GET["query"])){
        //Aucune requete mise en parametre
        throw new Exception ("Necessite une requete en parametre GET");
    }
    if (!isset($_SESSION["userId"])){
        //Aucun utilisateur connecte
        if ($_GET["query"] != "getPass" and 
            $_GET["query"] != "getDefaultPrograms"){
            //Sauf dans le cas ou aucun utilisateur n a besoins d etre connecte pour executer la requete
            throw new Exception ("Impossible d'accceder aux donnees sans etre connecte");
        }
    }       
    if (!isset($_GET["for"])){
        //Doit preciser le type de retour
        throw new Exception ('Le script doit savoir quel type de donnee il doit renvoyer dans $_GET["for"] (php par defaut)');
    }
    

    //Analyse des parametres (requetage ou recuperation)
    switch ($_GET["query"]) {
        case 'getUserName': 
            //Definie le type de reponse
            $get = true;
            $typeReponse = new TypeReponse("1","1");

            //Definie la maniere pour recuperer l information
            if (true){
                //N existe pas en memoire
                $notInMemory = true;
                $sql = "SELECT prenom FROM UTILISATEUR WHERE id_UTILISATEUR =".$_SESSION['userId'].";"; 
            }
            else{
                //Existe en memoire
                $notInMemory = false;
            }
            break;
    
        case 'getDefaultPrograms':
            //Definie le type de reponse
            $get = true;
            $typeReponse = new TypeReponse("X","X");

            //Definie la maniere pour recuperer l information
            if (true){
                //N existe pas en memoire
                $notInMemory = true;
                $sql = "SELECT id_PROGRAMME,nom FROM PROGRAMME WHERE id_UTILISATEUR IS NULL;"; 
            }
            else{
                //Existe en memoire
                $notInMemory = false;
            }
            break;
    
        case 'getPrivatesPrograms': 
            //Definie le type de reponse
            $get = true;
            $typeReponse = new TypeReponse("X","X");

            //Definie la maniere pour recuperer l information
            if (true){
                //N existe pas en memoire
                $notInMemory = true;
                $sql = "SELECT id_PROGRAMME,nom FROM PROGRAMME WHERE id_UTILISATEUR =".$_SESSION['userId'].";"; 
            }
            else{
                //Existe en memoire
                $notInMemory = false;
            }
            break;
    
        case 'getExersiseInformations': 
            if (!isset($_GET["idExersise"])){
                throw new Exception ('Un parametre de requete est attendu'); break;
            }
            //Definie le type de reponse
            $get = true;
            $typeReponse = new TypeReponse("1","X"); 
            
            //Definie la maniere pour recuperer l information
            if (true){
                //N existe pas en memoire
                $notInMemory = true;
                $sql = "SELECT E.titre, E.titre_affiche, E.auPoidsDeCorps, P.repetitions, P.kilos 
                        FROM EXERCICE E 
                        LEFT JOIN PERFORMANCE P ON P.id_EXERCICE = E.id_EXERCICE
                        INNER JOIN EXERCICE_PROGRAMME_UTILISATEUR EPU ON EPU.id_EXERCICE = E.id_EXERCICE
                        WHERE EPU.id_UTILISATEUR = ".$_SESSION['userId']." AND
                              E.id_EXERCICE = ".$_GET["idExersise"]."
                        GROUP BY E.titre;";
            }
            else{
                //Existe en memoire
                $notInMemory = false;
            }
            break;
    
        case 'getExersisesInformations':
            if (!isset($_SESSION["programId"])){
                throw new Exception("Un programme doit etre selectionne");
            }

            //Definie le type de reponse
            $get = true;
            $typeReponse = new TypeReponse("X","X");

            //Definie la maniere pour recuperer l information
            if (true){
                //N existe pas en memoire
                $notInMemory = true;
                $sql = "SELECT E.id_EXERCICE, E.titre, E.titre_affiche, E.auPoidsDeCorps, P.repetitions, P.kilos 
                        FROM EXERCICE E 
                        LEFT JOIN (
                            SELECT id_EXERCICE, MAX(date_perf), repetitions, kilos
                            FROM PERFORMANCE
                            GROUP BY id_EXERCICE
                        ) AS P ON P.id_EXERCICE = E.id_EXERCICE
                        INNER JOIN EXERCICE_PROGRAMME_UTILISATEUR EPU ON EPU.id_EXERCICE = E.id_EXERCICE 
                        WHERE EPU.id_UTILISATEUR = ".$_SESSION['userId']." AND 
                            EPU.id_PROGRAMME = ".$_SESSION["programId"]."
                        GROUP BY E.titre";
            }
            else{
                //Existe en memoire
                $notInMemory = false;
            }
            break;
        
        case 'getAllExersisesInformations':
            //Definie le type de reponse
            $get = true;
            $typeReponse = new TypeReponse("X","X");

            //Definie la maniere pour recuperer l information
            if (true){
                //N existe pas en memoire
                $notInMemory = true;
                $sql = "SELECT E.id_EXERCICE, E.titre, E.titre_affiche, E.auPoidsDeCorps, P.repetitions, P.kilos 
                        FROM EXERCICE E 
                        LEFT JOIN (
                            SELECT id_EXERCICE, MAX(date_perf), repetitions, kilos
                            FROM PERFORMANCE
                            GROUP BY id_EXERCICE
                        ) AS P ON P.id_EXERCICE = E.id_EXERCICE
                        WHERE E.id_UTILISATEUR = ".$_SESSION['userId']." OR
                        	  E.id_UTILISATEUR IS NULL
                        ORDER BY E.titre_affiche";
            }
            else{
                //Existe en memoire
                $notInMemory = false;
            }
            break;

        case 'addPerf':
            if (!isset($_GET["idExo"])){
                throw new Exception("Un exercice doit etre selectionne");
            }
            if (!isset($_GET["datePerf"])){
                throw new Exception("La performance doit etre datée");
            }
            if (!isset($_GET["repetitions"])){
                throw new Exception("La performance doit avoir un nombre de repetitions");
            }
            if (!isset($_GET["poids"])){
                throw new Exception("La performance doit avoir un poids");
            }

            $get = false;
            $typeReponse = null;
            $sql = "INSERT INTO `PERFORMANCE`( `date_perf`, `repetitions`, `kilos`, `id_EXERCICE`, `id_UTILISATEUR`) VALUES 
            ('".$_GET["datePerf"]."',".$_GET["repetitions"].",".$_GET["poids"].",".$_GET["idExo"].",".$_SESSION["userId"].")"; 
            break;

        case 'getPass':
            if (!isset($mail)){
                throw new Exception('La variable "mail" doit etre entree en php');
            }

            //Definie le type de reponse
            $get = true;
            $typeReponse = new TypeReponse("1","X");
            $notInMemory = true;
            $sql = "SELECT id_UTILISATEUR, mot_de_passe 
                    FROM UTILISATEUR
                    WHERE mail='$mail'";
            break;

        default:
            throw new Exception ('Requete "'.$_GET["query"].'" invalide'); break;
    }

    //Envoi de la requete
    if (!($get and !$notInMemory)){
        //Envoi sauf si on veut recevoir une donnee deja en memoire
        try{
            //Ouverture de la BDD avec droits adaptes
            if ($get){
                //Lecture
                $bdd = new PDO('mysql:host='.BDD_HOTE.';dbname='.BDD_NOM.';charset=utf8', DB_READER_USER_ID, DB_READER_USER_PASS);
            }
            else{
                //Ecriture
                $bdd = new PDO('mysql:host='.BDD_HOTE.';dbname='.BDD_NOM.';charset=utf8', DB_WRITER_USER_ID, DB_WRITER_USER_PASS);
            }

            //Envoi de la requete
            $requete = $bdd->prepare($sql);
            $requete->execute();
        }
        catch (Exception $e){
            throw new Exception ("Probleme avec la base de donnees");
        }
    }

    //Recuperation du resultat
    if ($get){
        if ($notInMemory){        
            //Depuis la requete           
            $tuples = $requete->fetchAll();

            //Mise en forme de la reponse           
            if ($typeReponse->nbTuples == "1"){
                if ($tuples == []){
                    //Aucune valeur trouvee
                    $reponse = NULL;
                }
                else if ($typeReponse->nbValeurs == "1"){
                    //Valeur unique
                    $reponse = $tuples[0][0];
                }
                else if ($typeReponse->nbValeurs == "X"){
                    //Valeur complexe
                    $reponse = $tuples[0];
                }
            }
            else if ($typeReponse->nbTuples == "X"){
                if ($tuples == []){
                    //Aucune valeur trouvee
                    $reponse = [];
                }
                else if ($typeReponse->nbValeurs == "1"){
                    //Liste simple
                    foreach ($tuples as &$tuple){
                        $tuple = $tuple[0]; //On recupere l element 0 de chaque tuple pour garder que l essentiel
                    }
                    $reponse = $tuples;
                }
                else if ($typeReponse->nbValeurs == "X"){
                    $reponse = $tuples;
                }
            }
            

        } 
        else{
            //Depuis la memoire
            switch ($_GET["query"]) {
                case 'getUserName': 
                    $reponse = $memoryContent["userNames"][$_SESSION["userId"]];
                    break;
                    
                case 'getDefaultPrograms':
                    break;
                    
                case 'getPrivatesPrograms': 
                    break;
            
                case 'getExersiseInformations': 
                    break;
            
                case 'getExersisesInformations':
                    break;
                }
        }
    }

    //Mise en memoire des valeurs de la reponse
    if ($get and $notInMemory){
        switch ($_GET["query"]){
            case 'getUserName': 
                
                break;
        
            case 'getDefaultPrograms':
                break;
        
            case 'getPrivatesPrograms': 
                break;
        
            case 'getExersiseInformations': 
                break;
        
            case 'getExersisesInformations':                 
                //Sauvegarde des exercices
                foreach($reponse as $exercice){
                    if (!in_array($exercice["id_EXERCICE"], $_SESSION["exersisesIdsInMemory"])){
                        //Si l exercice n est pas deja en memoire
                        array_push($_SESSION["exersisesIdsInMemory"], $exercice["id_EXERCICE"]);
                        array_push($_SESSION["exersisesInMemory"], $exercice);
                    }
                }
                
                //Liaison avec le programme les contenant
                array_push($_SESSION["programsInMemory"], $_SESSION["programId"]);
                $_SESSION["programsInMemory"] = [];
                break;
        }  
    }
}
catch(Exception $e){
    $erreur = true;
    $exception = $e;
}

//Retour du resultat
if ($_GET["for"] == "js"){
    if ($erreur){
        echo json_encode(["status"=>"fail","message"=>$exception->getMessage()]);
    }
    else if ($get){
        echo json_encode(["status"=>"success","datas"=>$reponse]);
    }
    else{
        echo json_encode(["status"=>"success"]);
    }
}
else{
    if ($erreur){
        throw new Exception($exception);
    }
    else if ($get){
        return $reponse;
    }
}
?>