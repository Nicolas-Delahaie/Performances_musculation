exoAffiche = false;
//nomExoAffiche

/**
 * @brief Affiche l interface de l exercice clique
 */
function clicOnExersise(nomExo){
    if (exoAffiche){
        if (nomExoAffiche == nomExo){
            //Clic sur un exo deja affiche 
            hideExersiseInterface();
        }
        else{
            //Clic sur un exo non affiche
            hideExersiseInterface();
            showExersiseInterface(nomExo);
        }
    }
    else{
        //Aucun exo affiche
        showExersiseInterface(nomExo);
    }
}



/** @brief Retire l interface de l exercice */
function hideExersiseInterface(){
    document.body.removeChild(document.getElementById("oc_container_exersise"))
    exoAffiche = false;
}
/** @brief Ajotue l interface de l exercice clique */
function showExersiseInterface(nomExo){     
    //Creation de l element
    var container_exo = document.createElement("section");
    container_exo.setAttribute("id", "oc_container_exersise");
    document.body.insertBefore(container_exo, document.querySelector("header"));
    
    //Recuperation du code html a afficher
    var requete = new XMLHttpRequest();
    requete.onload = function() {
        //La variable à passer est alors contenue dans l'objet response et l'attribut responseText.
        html = this.responseText;
    };
    requete.open("GET", "src/scripts/htmlExoClique.php?nomExo="+nomExo, false); //True pour que l'exécution du script continue pendant le chargement, false pour attendre.
    requete.send();

    container_exo.innerHTML = html;
    
    exoAffiche = true;  
    nomExoAffiche = nomExo;
}