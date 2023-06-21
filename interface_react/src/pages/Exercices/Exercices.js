// Images
import bench_press from '../../img/exercices/bench_press.png';
import deadlift from '../../img/exercices/deadlift.png';
import declined_bench_press from '../../img/exercices/declined_bench_press.png';
import overhead_press from '../../img/exercices/overhead_press.png';
import pronation_pull_ups from '../../img/exercices/pronation_pull-ups.png';
import pushups from '../../img/exercices/pushups.png';
import supine_pull_ups from '../../img/exercices/supine_pull-ups.png';
import defaut from '../../img/assets/logo.jpg';

// Librairies
import { useState, useEffect, useContext, createContext } from 'react';
import { ContexteGlobal } from '../../App';
import toast, { Toaster } from 'react-hot-toast';

// Composants
import CarteExercice from './CarteExercice';
import PopupExercice from './PopupExercice';
import ToucheEchap from './../../outils/ToucheEchap';

export const ContexteExercice = createContext();

function Exercices() {
    const { apiAccess } = useContext(ContexteGlobal);
    // Donnees
    const [programmes, setProgrammes] = useState(null);
    const [exercices, setExercices] = useState(null);
    const [exercicesRecherches, setExercicesRecherches] = useState(null);
    const [indexExerciceAffiche, setIndexExerciceAffiche] = useState(null);     // Id de l'exerice DANS exerices

    // Loaders
    var creatingProgram = false;

    // Filtres
    const [progSelectionne, setProgSelectionne] = useState(null);
    const [filtreRecherche, setFiltreRecherche] = useState("");

    // Chargement de données
    const getProgrammes = async () => {
        const rep = await apiAccess({
            url: `http://localhost:8000/api/programmes`
        })
        if (rep.success) {
            setProgrammes(rep.datas);
        }
        else {
            console.log(rep.erreur)
        }

    }
    const getExercices = async () => {
        setExercices(null);

        // On charge les exercices lies au nouveau programme selectionne
        const rep = await apiAccess({
            url: `http://localhost:8000/api/programmes/${progSelectionne}/exercices/performances?user_id=1`
        })
        if (rep.success) {
            setExercices(rep.datas);
        }
        else {
            console.log(rep.erreur)
        }
    }
    // const getAllExercices = async () => {
    //     setExercices(null);
    //     const rep = await apiAccess({
    //         url: `http://localhost:8000/api/exercices?user_id=1`   /**@todo rendre dynamique */
    //     })
    //     if (rep.success) {
    //         setExercices(rep.datas);
    //     }
    //     else {
    //         console.log(rep.erreur)
    //     }
    // }

    // Initialisation
    useEffect(() => {
        getProgrammes();
        setProgSelectionne(Number(localStorage.getItem('progSelectionne')));
    }, []);
    // Mises a jour
    useEffect(() => {
        if (!progSelectionne) {
            setExercices(null);
        }
        else {
            getExercices();
        }
    }, [progSelectionne]);

    // Fonctions de rendu
    const renderExercicesFiltres = (exercices) => {
        /**@todo faire en sorte qu'il aille voir dans toute la base de données */
        const trans_table = {
            'À': 'A', 'Â': 'A', 'Ä': 'A', 'Æ': 'AE',
            'Ç': 'C', 'È': 'E', 'É': 'E', 'Ê': 'E',
            'Ë': 'E', 'Î': 'I', 'Ï': 'I', 'Ô': 'O',
            'Œ': 'OE', 'Ù': 'U', 'Û': 'U', 'Ü': 'U',
            'à': 'a', 'â': 'a', 'ä': 'a', 'æ': 'ae',
            'ç': 'c', 'è': 'e', 'é': 'e', 'ê': 'e',
            'ë': 'e', 'î': 'i', 'ï': 'i', 'ô': 'o',
            'œ': 'oe', 'ù': 'u', 'û': 'u', 'ü': 'u',
            'ß': 'ss'
        };
        if (filtreRecherche) {
            // S'il y a une recherche d'effectuee
            return exercices.map((exercice, index) => {
                // Formatage des noms des exercices pour la recherche
                // En minuscule et sans accents
                let titre = exercice.nom.toLowerCase().replace(/[À-ÖØ-öø-ÿ]/g, function (match) {
                    return trans_table[match];
                });

                let recherche = filtreRecherche.toLowerCase().replace(/[À-ÖØ-öø-ÿ]/g, function (match) {
                    return trans_table[match];
                });

                if (titre.includes(recherche)) {
                    // Le titre de l'exercice contient la recherche
                    return <CarteExercice exercice={exercice} indexExo={index} isSearched="true" key={exercice.id} />
                }
            }).filter(Boolean); // Pour supprimer les null
        }
        else {
            // Si aucune recherche n'a ete effectuee
            return exercices.map((exercice, index) => {
                return <CarteExercice exercice={exercice} indexExo={index} key={exercice.id} />
            });
        }
    }

    // Callbacks
    /**
     * Met a jour le filtre de recherche en fonction de la valeur du champ lorsqu'elle est validee
     * @param {event} e 
     */
    const rechercheValidee = (e) => {
        e.preventDefault();
        setFiltreRecherche(e.target.recherche.value);
        // setProgSelectionne(null);
        // getAllExercices();
    }
    /**
     * Met a jour le filtre de programme en fonction du clic
     * @param {int} id 
     */
    const clicFiltreProgramme = (id) => {
        // Le filtre est remis a null si on clique sur le programme deja selectionne
        const newProgId = id === progSelectionne ? null : id;
        localStorage.setItem('progSelectionne', newProgId);
        setProgSelectionne(newProgId);
    }

    const enregistrerNouveauProgramme = async (e, t) => {
        e.preventDefault();

        toast.dismiss(t.id);
        toast.loading("Enregistrement...");
        const res = await apiAccess({
            url: 'http://localhost:8000/api/programmes',
            method: "post",
            body: {
                'nom': e.target.nomProgramme.value,
                'user_id': 1,
            }
        })
        toast.dismiss();
        creatingProgram = false;

        if (res.success) {
            setProgrammes([...programmes, res.datas]);
            toast.success("Programme fabriqué !")
        }
        else {
            toast.error("Impossible de fabriquer le programme !")
        }
    }
    const fabricationProgramme = () => {

        if (creatingProgram) return; // Si on est deja en train de creer un programme, on ne peut pas en creer un autre

        creatingProgram = true;
        toast((t) => (
            <div>
                <form onSubmit={(e) => enregistrerNouveauProgramme(e, t)}>
                    <label htmlFor="nomProgramme">Nom du programme : </label>
                    <input type="text" id="nomProgramme" name="nomProgramme" />
                    <div className="zoneBoutons">
                        <button type="button" onClick={() => { toast.dismiss(t.id); creatingProgram = false; }}>Annuler</button>
                        <input type="submit" value="Fabriquer" />
                    </div>
                </form>
            </div>
        ))
    }


    // Variables et fonction du contexte
    const imagesExercices = {
        1: overhead_press,
        2: bench_press,
        3: declined_bench_press,
        4: pushups,
        5: supine_pull_ups,
        6: pronation_pull_ups,
        7: deadlift,
        default: defaut,
    }
    const performanceTexte = (performance, poidsDeCorps) => {
        let texte = "";

        if (poidsDeCorps) {
            // POIDS DE CORPS
            const reps = performance.repetitions;
            if (performance.charge && performance.charge !== 0) {
                // LESTE
                texte = reps + " rep" + (reps > 1 ? "s" : "") + " lestée" + (reps > 1 ? "s" : "") + " à " + performance.charge + " kg";
            }
            else {
                texte = reps + " rep" + (reps > 1 ? "s" : "")
            }
        }
        else {
            // CHARGE LIBRE
            texte = performance.repetitions + " x " + performance.charge + "kg";
        }
        return texte;
    }

    // Render
    return (
        <div id="home">
            <Toaster />
            <ExerciceContexte.Provider value={{ exercices, setExercices, imagesExercices, indexExerciceAffiche, setIndexExerciceAffiche, performanceTexte, progSelectionne, programmes }}>
                {indexExerciceAffiche !== null && <PopupExercice exercice={exercices[indexExerciceAffiche]} />}
                <div className="filtres">
                    <form onSubmit={rechercheValidee}>
                        <input name="recherche" type="text" placeholder="Rechercher un exercice" autoComplete="off" />
                    </form>
                    {programmes &&
                        <div className="programmes">
                            {
                                programmes && programmes.map((programme) =>
                                    <button onClick={() => clicFiltreProgramme(programme.id)}
                                        className={programme.id === progSelectionne ? "selectionne" : ""}
                                        key={programme.id}
                                    >{programme.nom}</button>
                                )
                            }
                            <button onClick={() => fabricationProgramme()} className="nouveauProgramme">+</button>
                        </div>
                    }
                </div>
                <div className="exercices">
                    {exercices && renderExercicesFiltres(exercices)}
                </div>
            </ExerciceContexte.Provider>
        </div>
    );
}
export default Exercices;