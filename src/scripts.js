exoAffiche = false;

/**
 * @brief Affiche l interface de l exercice clique
 */
function clicOnExersise(nomExo){
    if (exoAffiche){
        //On retire l ancien exo
        hideExersiseInterface();
        
    }
    showExersiseInterface(nomExo);
}


/**
 * @brief Retire l interface de l exercice
 */
function hideExersiseInterface(){
    document.body.removeChild(document.getElementById("oc_container_exersise"))
    exoAffiche = false;
}

/**
 * @brief Ajotue l interface de l exercice clique
 */
function showExersiseInterface(nomExo){     
    //Creation de l element
    var container_exo = document.createElement("section");
    document.body.insertBefore(container_exo, document.querySelector("header"));
    
    container_exo.setAttribute("id", "oc_container_exersise");
    container_exo.innerHTML = `    
    <section id="oc_container_exersise">
    <section id="oc_exersise">
    <svg id="quitBtn" onclick="hideExersiseInterface()" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
    </svg>
    <img src="src/datas/img/exercices/deadlift_halteres.png">
    <section id="oc_exersise_description">
    <section>
    <section id="oc_exersise_description_title">
    <h3>Soulevé de terre haltère</h3><img id="oc_edit_button"
    src="src/datas/img/assets/logo_modifier.png">
    </section><img id="oc_favorite_button" src="src/datas/img/assets/etoilePleine.svg">
    </section><button id="oc_new_record_button">New reccord</button>
    </section>
    </section>
    </section>
    `;
    exoAffiche = true;  
}