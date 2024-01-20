// Contextes
import { ContexteExercice } from './Exercices';
import { ContexteGlobal } from '../../App';

// Librairies
import { useContext, useState } from 'react';
import toast from 'react-hot-toast';

// Images
import imgAjouter from './../../img/assets/ajouter.png';
import imgSupprimer from './../../img/assets/poubelle_supprimer.png';

function CarteExercice({ exercice, indexExo, cliquable, addOrRemove }) {
    // Fonction et variable de contexte
    const { apiAccess, showToasterValidation } = useContext(ContexteGlobal);
    const { setIndexExerciceAffiche, imagesExercices, performanceTexte, programmes, progSelectionne, setExercices, exercices } = useContext(ContexteExercice);

    // Donnees de l exercice
    const image = imagesExercices[exercice.id] || imagesExercices.default;
    const peformances = exercice.performances || null;


    // Dissociation d un exercice d un programme
    const clicDissociationProg = (e) => {
        e.stopPropagation();    // Pour pas que ça ouvre la popup (clic sur la carte)

        // Affichage du toaster
        showToasterValidation(
            "Voulez-vous vraiment supprimer cet exercice du programme ?",
            "Supprimer",
            dissociationProg
        );
    }
    const dissociationProg = async (e) => {
        e.preventDefault();

        // Suppression en front
        const newExercices = [...exercices].filter(exo => exo.id !== exercice.id);
        setExercices(newExercices);

        // Suppression en back
        console.log(exercice.id, progSelectionne);
        await apiAccess({
            url: `http://localhost:8000/api/exercices_programmes`,
            method: "delete",
            params: {
                'exercice_id': exercice.id,
                'programme_id': progSelectionne,
            },
        })
    }


    // Association d un exercice a un programme
    const clicAssociationProg = async (e) => {
        e.stopPropagation();    // Pour pas que ça ouvre la popup (clic sur la carte)

        // Recuperation des programmes disponibles pour l exercice
        var programmesDisponibles;
        toast.loading("Chargement des programmes disponibles..")
        const rep = await apiAccess({
            url: `http://localhost:8000/api/programmes/disponibles`,
            params: {
                user_id: 1,
                exercice_id: exercice.id
            }
        })
        toast.dismiss();

        if (rep.success) {
            programmesDisponibles = rep.datas;

            // Affichage du toaster
            showToasterValidation(
                "Ajouter à quel programme ?",
                "Valider",
                associationProg,
                <select name="programmeSelectionne" className="inputProgrammeSelectionne" autoFocus>
                    <option>Selectionner un programme</option>
                    {programmesDisponibles.map((programme) =>
                        <option value={programme.id}>{programme.nom}</option>
                    )}
                </select>
            );
        }
        else {
            toast.error("Impossible de charger les programmes disponibles : " + rep.erreur);
        }
    }
    const associationProg = async (e) => {
        e.preventDefault();

        const valeur = e.target.programmeSelectionne.value;
        if (valeur === "Selectionner un programme") {
            toast.error("Vous devez selectionner un programme");
            return;    // On ne fait rien si on a pas selectionne de programme
        }

        const idProgramme = parseInt(valeur);

        toast.loading("Ajout en cours...");
        const res = await apiAccess({
            url: `http://localhost:8000/api/exercices_programmes`,
            method: "post",
            body: {
                'exercice_id': exercice.id,
                'programme_id': idProgramme,
            },
        })
        toast.dismiss();

        if (res.success) {
            toast.success("Ajouté au programme !")
        }
        else {
            toast.error("Impossible d'ajouter au programme : " + res.erreur);
        }
    }

    return (
        <div className="carteExercice"
            onClick={cliquable ? () => setIndexExerciceAffiche(indexExo) : undefined}    // Si on a le droit de cliquer dessus (pas sur une recherche), on affiche la popup
        >
            {/** @todo Ajouter un bouton pour supprimer l exo du programme */}
            <h2>{exercice.nom}</h2>

            <img className="imgExercice" src={image} />
            {
                peformances && peformances.length !== 0 ?
                    <p className="performances">Dernière perfomance :<br />
                        {performanceTexte(exercice.performances[0], exercice.poidsDeCorps)}
                    </p>
                    :
                    "Aucune performance enregistrée"
            }
            {addOrRemove === "add" && <img onClick={(e) => clicAssociationProg(e)} src={imgAjouter} className="boutonDAffectation" />}
            {addOrRemove === "remove" && <img onClick={(e) => clicDissociationProg(e)} src={imgSupprimer} className="boutonDAffectation" />}
        </div>
    );
}
export default CarteExercice;