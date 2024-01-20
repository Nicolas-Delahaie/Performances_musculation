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
import toast from 'react-hot-toast';

// Composants
import CarteExercice from './CarteExercice';
import PopupExercice from './PopupExercice';

export const ContexteExercice = createContext();

function Exercices() {
    const { apiAccess, showToasterValidation } = useContext(ContexteGlobal);
    // Donnees
    const [programmes, setProgrammes] = useState(null);
    const [exercices, setExercices] = useState(null);
    const [exercicesRecherches, setExercicesRecherches] = useState(null);
    const [indexExerciceAffiche, setIndexExerciceAffiche] = useState(null);     // Id de l'exerice DANS exerices
    const [erreur, setErreur] = useState(null);

    // Loaders
    var creatingProgram = false;

    // Filtres
    const [progSelectionne, setProgSelectionne] = useState(null);


    // Chargement de données
    const getProgrammes = async () => {
        const rep = await apiAccess({
            url: `http://localhost:8000/api/programmes`
        })
        if (rep.success) {
            setProgrammes(rep.datas);
        }
        else {
            setErreur(rep.erreur)
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
    const getExercicesRecherches = async (recherche) => {
        setExercicesRecherches(null);

        // On charge les exercices lies au nouveau programme selectionne
        const rep = await apiAccess({
            url: `http://localhost:8000/api/exercices`,
            params: {
                recherche: recherche,
                user_id: 1
            }
        })
        if (rep.success) {
            setExercicesRecherches(rep.datas);
        }
        else {
            console.log(rep.erreur);
        }
    }

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


    // Callbacks
    /**
     * Met a jour le filtre de recherche en fonction de la valeur du champ lorsqu'elle est validee
     * @param {event} e 
     */
    const rechercheValidee = (e) => {
        e.preventDefault();
        const saisie = e.target.recherche.value;

        if (saisie === "") {
            // Aucune recherche faite
            setExercicesRecherches(null);
        }
        else {
            // Recuperation des exercices correspondant a la recherche
            setProgSelectionne(null);
            getExercicesRecherches(saisie);
        }
    }
    /**
     * Met a jour le filtre de programme en fonction du clic
     * @param {int} id 
     */
    const clicFiltreProgramme = (id) => {
        // Le filtre est remis a null si on clique sur le programme deja selectionne
        const newProgId = id === progSelectionne ? null : id;
        localStorage.setItem('progSelectionne', newProgId);
        setExercicesRecherches(null);
        setProgSelectionne(newProgId);
    }

    const enregistrerNouveauProgramme = async (e) => {
        e.preventDefault();

        // Verification que le nom du programme n'est pas vide
        if (e.target.nomProgramme.value === "") {
            toast.error("Le nom du programme ne peut pas être vide !");
            return;
        }

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
        showToasterValidation(
            "Nom du programme",
            "Fabriquer",
            enregistrerNouveauProgramme,
            <input type="text" className="inputNomNouveauProgramme" name="nomProgramme" autoFocus autocomplete="off" />
        );
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
            <ContexteExercice.Provider value={{ exercices, setExercices, imagesExercices, indexExerciceAffiche, setIndexExerciceAffiche, performanceTexte, progSelectionne, programmes }}>
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
                                        className={programme.id === progSelectionne ? "selectionne" : undefined}
                                        key={programme.id}
                                    >{programme.nom}</button>
                                )
                            }
                            <button onClick={() => fabricationProgramme()} className="nouveauProgramme">+</button>
                        </div>
                    }
                </div>
                <div className="exercices">
                    {
                        exercicesRecherches ?   // Si on a fait une recherche
                            <>
                                {exercicesRecherches.length === 0 && <p>Aucun exercice correspond à cette recherche</p>}
                                {exercicesRecherches.map((exerciceRecherche, index) =>
                                    <CarteExercice exercice={exerciceRecherche} indexExo={index} addOrRemove={"add"} cliquable={false} key={exerciceRecherche.id} />
                                )}
                            </>
                            :
                            <>
                                {!progSelectionne && < p > Aucun programme séléctionné</p>}
                                {exercices && exercices.length === 0 && <p>Aucun exercice dans ce programme</p>}
                                {exercices && exercices.map((exercice, index) =>
                                    <CarteExercice exercice={exercice} indexExo={index} addOrRemove={"remove"} cliquable={true} key={exercice.id} />
                                )}
                            </>
                    }
                    {
                        erreur && <p>{erreur}</p>
                    }
                </div>
            </ContexteExercice.Provider >
        </div >
    );
}
export default Exercices;