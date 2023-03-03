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
 * @return mixed
 */


$get;           //Indique si la requete doit renvoyer un resultat
$notInMemory;   //Indique si la reponse demandee est deja en memoire

$memoryIds = [];        //Contient les id des reponses deja renvoyees
$memoryContent = [];    //Contient le contenu des reponses deja renvoyees
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

//Verif-Execution
try{
    //Verification des parametres
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["userId"])){
        //Aucun utilisateur connecte
        throw new Exception ("Impossible d'accceder aux donnees sans etre connecte");
    }       
    if (!isset($_GET["for"])){
        //Doit preciser le type de retour
        throw new Exception ('Le script doit savoir quel type de donnee il doit renvoyer dans $_GET["for"] (php par defaut)');
    }
    if (!isset($_GET["query"])){
        //Aucune requete mise en parametre
        throw new Exception ("Necessite une requete en parametre GET");
    }

    //Analyse des parametres (requetage ou recuperation)
    switch ($_GET["query"]) {
        case 'getUserName': 
            //Definie le type de reponse
            $get = true;
            $typeReponse = new TypeReponse("1","1");

            //Definie la maniere pour recuperer l information
            if (!isset($memoryContent["userNames"][$_SESSION["userId"]])){
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
            if (!isset($memoryContent["defaultPrograms"])){
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
            if (!isset($memoryContent["pivatePrograms"])){
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
            if (!isset($memoryContent["exersises"])){
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
            //Definie le type de reponse
            $get = true;
            $typeReponse = new TypeReponse("X","X");

            //Definie la maniere pour recuperer l information
            if (!isset($memoryContent["exersises"])){
                //N existe pas en memoire
                $notInMemory = true;
                $sql = "SELECT E.id_EXERCICE, E.titre, E.titre_affiche, E.auPoidsDeCorps, P.repetitions, P.kilos 
                    FROM EXERCICE E 
                    LEFT JOIN PERFORMANCE P ON P.id_EXERCICE = E.id_EXERCICE
                    INNER JOIN EXERCICE_PROGRAMME_UTILISATEUR EPU ON EPU.id_EXERCICE = E.id_EXERCICE
                    WHERE EPU.id_UTILISATEUR = ".$_SESSION['userId']." AND 
                            EPU.id_PROGRAMME = ".$_SESSION["programId"]."
                    GROUP BY E.titre;"; 
            }
            else{
                //Existe en memoire
                $notInMemory = false;
            }
            break;
            
        default:
            throw new Exception ('Requete "'.$_GET["query"].'" invalide'); break;
    }

    //Envoi de la requete
    if (!$get or $notInMemory){
        //Si on veut (executer une simple requete) OU si on veut (chercher des donnees depuis la BDD)
        //Ouverture (avec droits coherents)
        try{
            //Mise des droits
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

    //Recuperation des resultats
    if ($get){
        if ($notInMemory){        
            //Depuis la requete           
            $tuples = $requete->fetchAll();
    
            //Mise au propre de la reponse
            if ($typeReponse->nbTuples == "1"){
                if ($typeReponse->nbValeurs == "1"){
                    //Valeur unique
                    $reponse = $tuples[0][0];
                }
                else if ($typeReponse->nbValeurs == "X"){
                    //Valeur complexe
                    $reponse = $tuples[0];
                }
            }
            else if ($typeReponse->nbTuples == "X"){
                if ($typeReponse->nbValeurs == "1"){
                    //Liste simple
                    foreach ($tuples as &$tuple){
                        $tuple = $tuple[0]; //On recupere l element 0 de chaque tuple pour garder que l essentiel
                    }
                    $reponse = $tuples;
                }
                elseif ($typeReponse->nbValeurs == "X"){
                    $reponse = $tuples;
                }
            }

            //Mise en memoire des valeurs de la reponse
            switch ($_GET["query"]){
                case 'getUserName': 
                    $memoryContent["userNames"][$_SESSION["userId"]] = $reponse;
                    break;
        
                // case 'getDefaultPrograms':
                //     $memoryContent["defaultPrograms"] = $reponse;
                //     break;
        
                // case 'getPrivatesPrograms': 
                //     $memoryContent["privatesPrograms"] = $reponse;
                //     break;
            
                // case 'getExersiseInformations': 
                //     //Pre condition : ne pas deja l avoir en memoire 
                //     //Garde en memoire l id de l exercice
                //     array_push($memoryContent["exersisesIdInMemory"], $reponse);
                //     array_push($memoryContent["exersises"], $reponse);
                //     break;
            
                // case 'getExersisesInformations': 
                //     foreach($reponse as $exercice){
                //         if ($exercice["id_EXERCICE"]){
                //             //L exercice n est pas encore en memoire
                //             //Garde en memoire l id de l exercice
                //             array_push($memoryContent["exersisesIdInMemory"], $reponse);
                //             array_push($memoryContent["exersises"], $reponse);
                //         }
                //     }                
                //     break;
            }
        }
        else{
            //Depuis la memoire
            switch ($_GET["query"]) {
                case 'getUserName': 
                    $reponse = $memoryContent["userNames"][$_SESSION["userId"]];
                    break;
            
                case 'getDefaultPrograms':
                    $reponse = "oui";
                    break;
            
                case 'getPrivatesPrograms': 
                    $reponse = "oui";
                    break;
            
                case 'getExersiseInformations': 
                    $reponse = "oui";
                    break;
            
                case 'getExersisesInformations':
                    $reponse = "oui";
                    break;
            }
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
    else{
        echo json_encode(["status"=>"success","datas"=>$reponse]);
    }
}
else{
    if ($erreur){
        throw new Exception($reponse);
    }
    else if ($get){
        return $reponse;
    }
}
?>