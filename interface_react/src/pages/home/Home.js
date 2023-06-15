// Librairies
import { useState, useEffect } from 'react';

// Composants
import CarteExercice from './CarteExercice';

function Home() {
    // Donnees
    const [programmes, setProgrammes] = useState(null);
    const [exercices, setExercices] = useState(null);
    // Filtres
    const [progSelectionne, setProgSelectionne] = useState(null);
    const [filtreRecherche, setFiltreRecherche] = useState(null);


    // Initialisation
    const getProgrammes = async () => {
        const rep = await (await fetch("http://localhost:8000/api/programmes")).json();
        setProgrammes(rep);
    }

    useEffect(() => {
        getProgrammes();
    }, []);


    // Callbacks
    /**
     * Met a jour le filtre de recherche en fonction de la valeur du champ lorsqu'elle est validee
     * @param {event} e 
     */
    const rechercheValidee = (e) => {
        e.preventDefault();
        setFiltreRecherche(e.target.recherche.value);
    }
    /**
     * Met a jour le filtre de programme en fonction du clic
     * @param {int} id 
     */
    const clicFiltreProgramme = async (id) => {
        // Le filtre est remis a null si on clique sur le programme deja selectionne
        const newProgId = id === progSelectionne ? null : id;
        setProgSelectionne(newProgId);

        if (newProgId) {
            // On charge les exercices lies au nouveau programme selectionne
            setExercices(null);
            const rep = await (await fetch(`http://localhost:8000/api/programmes/${newProgId}/exercices/performances?user_id=1`)).json();
            setExercices(rep);
            console.log(rep);
        }
    }

    // Render
    return (
        <div id="home">
            <div className="filtres">
                {/* <p>programme selectionne : {progSelectionne}</p> */}
                {/* <>Recherche : {filtreRecherche}</> */}
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
                    </div>
                }
            </div>
            <div className="exercices">
                {exercices && exercices.map(exercice =>
                    <CarteExercice exercice={exercice} key={exercice.id} />
                )}
            </div>
        </div>
    );
}
export default Home;