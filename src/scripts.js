var exoAffiche = false;
var nomExoAffiche = "";
var exercice;

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
/**
 * @brief Affiche la zone de saisie d'une nouvelle PR
 */
function clickAddRecord(){
    //Creation de l element
    var zoneDeSaisie = document.createElement("null");    
    document.getElementById('oc_exersise_description').appendChild(zoneDeSaisie);
    document.getElementById('oc_exersise_description').removeChild(document.getElementById('oc_new_record_button'));
    zoneDeSaisie.outerHTML =`<section id="oc_exersise_new_record">
                                 <section>
                                     <input type="number" name="weight" id="lineEd-weight">
                                     <p class='rightLabel'>kg</p>
                                     <input type="number" name="reps" id="lineEd-reps" value="1">
                                     <p class='rightLabel'>reps</p>
                                     <p id='dateLabel'>Fait le</p> 
                                     <input type="date" name="date" id="lineEd-date" value="2023-23-02">
                                 </section>
                                 <button id='bValidate' onclick='clickValidateRecord()'>Valider</button>
                             </section>`;
}
/**
 * @brief Regarde si les informations sont correctes puis ajoute a la base de donnees
 */
function clickValidateRecord(){
    alert('Validation de la conformite des informations puis ajout')
}
/**
 * @brief Met a jour les elements de l exercice clique avec les infos de la bdd
 * @param[in] nomExoAffiche 
 * @param[out] exercice
 */
function initialiseExerciceBDD(){
    bddTemp = {"pushups":{"nomAffiche":"Pompes" ,
                         "favorite":true, 
                         "bodyWeight":true, 
                         "weight":0, 
                         "repetitions":40}, 
               "deadlift_halteres": {"nomAffiche":"Soulevé de terre", 
                                      "favorite":true, 
                                      "bodyWeight":false, 
                                      "weight":100, 
                                      "repetitions":3},
               "overhead_press": {"nomAffiche":"Soulevé militaire à la barre", 
                                   "favorite":false, 
                                   "bodyWeight":false, 
                                   "weight":60, 
                                   "repetitions":2}};
    exercice = bddTemp[nomExoAffiche];
}


/** @brief Retire l interface de l exercice */
function hideExersiseInterface(){
    document.body.removeChild(document.getElementById("oc_container_exersise"))
    exoAffiche = false;
    nomExoAffiche = "";
}
/** @brief Ajotue l interface de l exercice clique */
function showExersiseInterface(nomExo){     
    //Reinitiailistion des donnees du nouvel exercice
    exoAffiche = true;  
    nomExoAffiche = nomExo;
    initialiseExerciceBDD();

    //Creation de l element
    var container_exo = document.createElement("null");
    document.body.insertBefore(container_exo, document.querySelector("header"));
    container_exo.outerHTML = `
    <section id="oc_container_exersise">
        <section id="oc_exersise">
            <svg id="quitBtn" onclick="hideExersiseInterface()" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
            </svg>
            <img src="src/datas/img/exercices/`+nomExo+`.png" onclick="hideExersiseInterface()">
            <section id="oc_exersise_description">
                <section id="oc_exersise_description_header">
                    <section id="oc_exersise_description_title">
                        <h3>`+exercice["nomAffiche"]+`</h3>
                        <img id="oc_edit_button" src="src/datas/img/assets/logo_modifier.png">
                    </section>
                    <img id="oc_favorite_button" src="src/datas/img/assets/etoilePleine.svg">
                </section>
                <button id="oc_new_record_button" onclick="clickAddRecord()">Nouveau record</button>
            </section>
        </section>
    </section>
    `;    
}