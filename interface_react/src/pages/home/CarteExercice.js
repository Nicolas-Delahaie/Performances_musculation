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
import { useState } from 'react';
import { useEffect } from 'react';


function CarteExercice({ exercice }) {
    const [performance, setPerformance] = useState(null);
    const liensImages = {
        1: overhead_press,
        2: bench_press,
        3: declined_bench_press,
        4: pushups,
        5: supine_pull_ups,
        6: pronation_pull_ups,
        7: deadlift,
    }

    useEffect(() => {
        const nbPerfs = exercice.performances.length;
        if (nbPerfs !== 0) {
            const dernierePerf = exercice.performances[0];
            var texte = "";
            if (exercice.poidsDeCorps) {
                // POIDS DE CORPS
                const reps = dernierePerf.repetitions;
                var texte = reps + " rep" + (reps > 1 ? "s" : "");
                if (dernierePerf.charge && dernierePerf.charge !== 0) {
                    // LESTE
                    texte += " léstée" + (reps > 1 ? "s" : "") + " à " + dernierePerf.charge + " kg";
                }
                setPerformance(texte);
            }
            else {
                // CHARGE LIBRE
                setPerformance(dernierePerf.repetitions + " x " + dernierePerf.charge + "kg");
            }
        }
    }, []);

    return exercice && (
        <div className="carteExercice">
            <h2>{exercice.nom}</h2>
            <img src={liensImages[exercice.id] || defaut} />
            {performance && <p className="performances">Dernière performance<br />{performance}</p>}
        </div>
    );
}
export default CarteExercice;