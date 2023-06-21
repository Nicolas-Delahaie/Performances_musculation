// Contextes
import { ContexteExercice } from './Exercices';
import { ContexteGlobal } from '../../App';

// Librairies
import { useContext } from 'react';
import toast, { Toaster } from 'react-hot-toast';

function CarteExercice({ exercice, indexExo, isSearched = false }) {
    const { apiAccess } = useContext(ContexteGlobal);
    const { setIndexExerciceAffiche, imagesExercices, performanceTexte, programmes, setExercices } = useContext(ContexteExercice);
    const image = imagesExercices[exercice.id] || imagesExercices.default;
    var dialogOpened = false;
    const peformances = exercice.performances || null;

    const dialogChoixProgramme = (e) => {
        e.stopPropagation();

        /**@todo Recupérer les programmes disponibles pour l exercice */

        dialogOpened = true;
        toast((t) => (
            <div>
                <form onSubmit={(e) => { ajoutAuProgramme(e); toast.dismiss(t.id) }}>
                    <label htmlFor="nomProgramme">Ajouter à quel programme ?</label>
                    <select name="programmeSelectionne">
                        {programmes.map((programme) =>
                            <option value={programme.id}>{programme.nom}</option>
                        )}
                    </select>
                    <button type="submit">Ajouter</button>
                </form>
            </div>
        ))
    }

    const ajoutAuProgramme = async (e) => {
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
        dialogOpened = false;

        if (res.success) {
            toast.success("Ajouté au programme !")
        }
        else {
            toast.error("Impossible d'ajouter au programme : " + res.erreur);
        }
    }

    return (
        <div className="carteExercice"
            onClick={isSearched ? undefined : () => setIndexExerciceAffiche(indexExo)}    // Si on a le droit de cliquer dessus (pas sur une recherche), on affiche la popup
        >
            <Toaster />
            {/** @todo Ajouter un bouton pour supprimer l exo du programme */}
            <h2>{exercice.nom}</h2>
            <img src={image} />
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
            {isSearched && <button onClick={(e) => dialogChoixProgramme(e)}>Ajouter à un programme</button>}
        </div>
    );
}
export default CarteExercice;