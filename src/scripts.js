

function showExersise(){ 
    // //Creation des elements
    var container_exo = document.createElement("section");
//     container_exo.outerhtml = `
// <section id="oc_container_exersise">
//     <section id="oc_exersise">
//         <img src="src/datas/img/exercices/deadlift_halteres.png" alt="">
        
//         <section id="oc_exersise_description">
//             <section>
//                 <section id="oc_exersise_description_title">
//                     <h3>Soulevé de terre haltère</h3>
//                     <img id="oc_edit_button" src="src/datas/img/assets/logo_modifier.png" alt="">
//                 </section>
                
//                 <img id="oc_favorite_button" src="src/datas/img/assets/etoilePleine.svg" alt="">
//             </section>

//             <button id="oc_new_record_button">New record</button>
//         </section>
//     </section>
// </section>`;
// document.body.appendChild(container_exo);
document.body.insertBefore(container_exo, document.querySelector("header"));

    
    
    
    const exo = document.createElement("section");
    container_exo.appendChild(exo);

    const imgExo = document.createElement("img");  
    exo.appendChild(imgExo);
    const description = document.createElement("section");
    exo.appendChild(description);

    const description_header = document.createElement("section");
    description.appendChild(description_header);
    const new_reccord = document.createElement("button");
    description.appendChild(new_reccord);
    
    const container_title = document.createElement("section");
    description_header.appendChild(container_title);
    const star = document.createElement("img");
    description_header.appendChild(star);

    const title_exo = document.createElement("h3");
    container_title.appendChild(title_exo);
    const edit_img = document.createElement("img");
    container_title.appendChild(edit_img);

    //Ajout des identifiants
    container_exo.setAttribute("id", 'oc_container_exersise');
    exo.setAttribute("id", 'oc_exersise');
    // imgExo.setAttribute("id", );
    description.setAttribute("id", 'oc_exersise_description');
    // description_header.setAttribute("id", );
    new_reccord.setAttribute("id", 'oc_new_record_button');
    container_title.setAttribute("id", 'oc_exersise_description_title');
    star.setAttribute("id", 'oc_favorite_button');
    // title_exo.setAttribute("id", );
    edit_img.setAttribute("id", 'oc_edit_button');


    //Ajout du contenu des elements
    new_reccord.textContent = "New reccord";
    title_exo.textContent = "Soulevé de terre haltère";
    imgExo.setAttribute("src", "src/datas/img/exercices/deadlift_halteres.png");
    star.setAttribute("src", "src/datas/img/assets/etoilePleine.svg");
    edit_img.setAttribute("src", "src/datas/img/assets/logo_modifier.png");



    console.log(container_exo.outerhtml);  
}

