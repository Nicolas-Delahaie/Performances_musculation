// Contextes
import { ContexteExercice } from './Exercices';
import { ContexteGlobal } from '../../App';

// Librairies
import { useContext } from 'react';
import toast from 'react-hot-toast';

// Images
import imgAjouter from './../../img/assets/ajouter.png';
import imgSupprimer from './../../img/assets/poubelle_supprimer.png';

function CarteExercice({ exercice, indexExo, cliquable, addOrRemove }) {
    const { apiAccess, showToasterValidation } = useContext(ContexteGlobal);
    const { setIndexExerciceAffiche, imagesExercices, performanceTexte, programmes, progSelectionne, setExercices, exercices } = useContext(ContexteExercice);
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

        console.log(exercice.id, progSelectionne);
        const res = await apiAccess({
            url: `http://localhost:8000/api/exercices_programmes`,
            method: "delete",
            params: {
                'exercice_id': exercice.id,
                'programme_id': progSelectionne,
            },
        })

        if (res.success) {
            // Suppression en front
            const newExercices = [...exercices].filter(exo => exo.id !== exercice.id);
            setExercices(newExercices);
        }
        else {
            toast.error("Impossible de le supprimer du programme : " + res.erreur);
        }
    }


    // Association d un exercice a un programme
    const clicAssociationProg = (e) => {
        e.stopPropagation();    // Pour pas que ça ouvre la popup (clic sur la carte)

        // Recuperation des programmes disponibles pour l exercice

        // Affichage du toaster
        showToasterValidation(
            "Ajouter à quel programme ?",
            "Valider",
            associationProg,
            <select name="programmeSelectionne" className="inputProgrammeSelectionne" autoFocus>
                {programmes.map((programme) =>
                    <option value={programme.id}>{programme.nom}</option>
                )}
            </select>
        );
    }
    const associationProg = async (e) => {
        e.preventDefault();
        const idProgramme = parseInt(e.target.programmeSelectionne.value);

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
                peformances &&
                <p className="performances">Dernière perfomance :<br />
                    {peformances.length === 0 ?
                        "Aucune performance enregistrée"
                        :
                        performanceTexte(exercice.performances[0], exercice.poidsDeCorps)
                    }
                </p>
            }
            {addOrRemove === "add" && <img onClick={(e) => clicAssociationProg(e)} src={imgAjouter} className="boutonDAffectation" />}
            {addOrRemove === "remove" && <img onClick={(e) => clicDissociationProg(e)} src={imgSupprimer} className="boutonDAffectation" />}
        </div>
    );
}
export default CarteExercice;