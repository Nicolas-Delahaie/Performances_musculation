import { ContexteExercice } from './Exercices';
import { ContexteGlobal } from '../../App';
import { useContext } from 'react';

function CarteExercice({ exercice, indexExo, isSearched = false }) {
    const { apiAccess } = useContext(ContexteGlobal);
    const { setIndexExerciceAffiche, imagesExercices, performanceTexte, progSelectionne, programmes } = useContext(ContexteExercice);
    const image = imagesExercices[exercice.id] || imagesExercices.default;

    const peformances = exercice.performances || null;

    const liaisonExo_Programme = async (e) => {
        e.stopPropagation();

        const res = await apiAccess({
            url: `http://localhost:8000/api/exercices_programmes`,
            method: "post",
            body: {
                'exercice_id': exercice.id,
                'programme_id': progSelectionne,
            },
        })
        if (res.success) {
            //Enregistrement en front
            // const newPerformances = [...exercice.performances, res.datas];
            // setExercices(newExercices);

            alert("OK");
        }
        else {
            alert(res.erreur)
        }
    }

    return (
        <div className="carteExercice"
            onClick={isSearched ? undefined : () => setIndexExerciceAffiche(indexExo)}    // Si on a le droit de cliquer dessus (pas sur une recherche), on affiche la popup
        >
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
            {/* {isSearched && <button onClick={(e) => liaisonExo_Programme(e)}>Ajouter au programme {programmes.filter((prog) => prog.id == progSelectionne)[0].nom}</button>} */}
        </div>
    );
}
export default CarteExercice;