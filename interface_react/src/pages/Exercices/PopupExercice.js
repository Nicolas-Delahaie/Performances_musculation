/**
 * @todo Gerer l ordre de deux performances fabriquees dans la meme minute
 */
// Libairies
import { useState } from 'react';
import { useContext } from 'react';
import Contexte from '../../Contexte';
import { ExerciceContexte } from './Exercices';
import toast, { Toaster } from 'react-hot-toast';

// Images
import croix from './../../img/assets/croix_quitter.png';

function PopupExercice({ exercice }) {
    const { apiAccess } = useContext(Contexte);
    const { indexExerciceAffiche,
        setIndexExerciceAffiche,
        imagesExercices,
        exercices,
        setExercices,
        performanceTexte
    } = useContext(ExerciceContexte);

    const [isPickingNewPerf, setIsPickingNewPerf] = useState();
    const [isSavingPerf, setIsSavingPerf] = useState(false);

    const image = imagesExercices[exercice.id] || imagesExercices.default;


    // Fabrication des dates
    const now = new Date();
    let dateActuelle = now.toLocaleDateString('fr-FR', { year: 'numeric', day: '2-digit', month: '2-digit' });
    let heureActuelle = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    // Formatage pour convenir au input defaultValue
    dateActuelle = dateActuelle.split('/').reverse().join('-'); //Transformation des "/" en "-"


    const enregistrerPerformance = async (e) => {
        e.preventDefault();

        // Verification que l'utilisateur n'essaye pas d'enregistrer une performance dans le futur
        const dateSaisie = new Date(e.target.date.value + " " + e.target.heure.value);
        if (dateSaisie > now) {
            toast.error(<b>Vous ne pouvez pas enregistrer une performance dans le futur !</b>);
            return;
        }

        if (!isSavingPerf) {
            // Enregistrement
            toast.loading("Enregistrement...");
            setIsSavingPerf(true);
            console.log(e.target.date.value + "   " + e.target.heure.value)
            const res = await apiAccess({
                url: 'http://localhost:8000/api/performance',
                method: "post",
                body: {
                    'date_perf': e.target.date.value + " " + e.target.heure.value,
                    'repetitions': parseInt(e.target.repetitions.value),
                    'charge': parseInt(e.target.charge.value) || undefined,
                    /**@todo Modifier ca pour que ce soit dynamique */
                    'user_id': 1,
                    'exercice_id': exercice.id,
                },
            })
            toast.dismiss();
            setIsSavingPerf(false);

            if (res.success) {
                //Enregistrement en front
                const newPerformances = [...exercice.performances, res.datas];
                newPerformances.sort((a, b) => new Date(b.date_perf) - new Date(a.date_perf));
                const newExercices = [...exercices];
                newExercices[indexExerciceAffiche].performances = newPerformances;
                setExercices(newExercices);

                setIsPickingNewPerf(false);
                toast.success(<b>Performance enregistrée !</b>);
            }
            else {
                toast.error(<b>{res.erreur}</b>);
            }
        }
    }

    const ecartDeTemps = (date) => {
        const now = new Date();
        const datePerf = new Date(date);
        const ecartEnMillisecondes = now - datePerf;

        if (ecartEnMillisecondes < 0) {
            // Date ultérieure
            console.error("Pas sensé avoir une performance dans le futur");
            return "dans le futur";
        }
        else {
            const ecartEnSecondesTronque = Math.floor(ecartEnMillisecondes / 1000);

            if (ecartEnSecondesTronque >= 60) {
                const ecartEnMinutesTronque = Math.floor(ecartEnSecondesTronque / (60));

                if (ecartEnMinutesTronque >= 60) {
                    const ecartEnHTronque = Math.floor(ecartEnMinutesTronque / 60);

                    if (ecartEnHTronque >= 24) {
                        const ecartEnJoursTronque = Math.floor(ecartEnHTronque / 24);

                        if (ecartEnJoursTronque >= 365) {
                            // Plus d'un an
                            const ecartEnAnneesTronque = Math.floor(ecartEnJoursTronque / 365);
                            return "il y a " + ecartEnAnneesTronque + " ans";
                        }
                        else {
                            // Entre 1 et 365 jours
                            return "il y a " + ecartEnJoursTronque + " jours";
                        }
                    }
                    else {
                        // Moins de 24h
                        return "il y a " + ecartEnHTronque + " heures";
                    }
                }
                else {
                    // Moins d'une heure
                    return "il y a " + ecartEnMinutesTronque + " minutes";
                }
            }
            else {
                // Moins d'une minute
                return "À l'instant";
            }
        }
    }


    return exercice && (
        <div className="popupExerciceContainer" onClick={() => setIndexExerciceAffiche(null)}>
            <Toaster />
            <div className="popupExercice" onClick={(e) => e.stopPropagation()}>
                <img className="croixQuitter" src={croix} onClick={() => setIndexExerciceAffiche(null)} />
                <h1>{exercice.nom}</h1>
                <div>
                    <img src={image} />
                    <div className="performances">
                        <h2>Progression</h2>
                        <div>
                            {exercice.performances.map(perf =>
                                <p>{performanceTexte(perf, exercice.poidsDeCorps) + " (" + ecartDeTemps(perf.date_perf) + ")"}</p>
                            )}
                        </div>
                        {
                            isPickingNewPerf ?
                                <form onSubmit={(e) => enregistrerPerformance(e)}>
                                    <label htmlFor="repetitions">Répétitions : </label>
                                    <input required type="number" name="repetitions" id="repetitions" autoFocus min="0" max="10000" />

                                    {exercice.poidsDeCorps ?
                                        <>
                                            <br />
                                            <label htmlFor="charge">Si lesté : </label>
                                        </>
                                        :
                                        <label htmlFor="charge"> à </label>
                                    }
                                    <input required={!exercice.poidsDeCorps} type="number" id="charge" name="charge" min="0" max="10000" />
                                    <label htmlFor="charge"> kilos</label>
                                    <br />

                                    <label htmlFor="date">Date de la performance : </label>
                                    <br />
                                    <input type="date" name="date" id="date" defaultValue={dateActuelle} max={dateActuelle} />
                                    <input type="time" name="heure" defaultValue={heureActuelle} />
                                    <br />

                                    <button type="submit">Valider</button>
                                    <button onClick={() => setIsPickingNewPerf(false)}>Annuler</button>
                                </form>
                                :
                                <button className="boutonNouvellePerf" onClick={() => setIsPickingNewPerf(true)}>Nouvelle performance</button>
                        }
                    </div>
                </div>
            </div>
        </div>
    );
}
export default PopupExercice;