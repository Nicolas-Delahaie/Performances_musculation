<?php
$nomExo = $_GET["nomExo"];
$exersisesBdTemp = ["pushups"=>["nomAffiche"=>"Pompes" ,"favorite"=>true, "bodyWeight"=>true, "weight"=>0, "repetitions"=>40], 
                    "deadlift_halteres"=> ["nomAffiche"=>"Soulevé de terre", "favorite"=>true, "bodyWeight"=>false, "weight"=>100, "repetitions"=>3],
                    "overhead_press"=> ["nomAffiche"=>"Soulevé militaire à la barre", "favorite"=>false, "bodyWeight"=>false, "weight"=>60, "repetitions"=>2]];

echo '
<section id="oc_container_exersise">
    <section id="oc_exersise">
        <svg id="quitBtn" onclick="hideExersiseInterface()" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
        </svg>
        <img src="src/datas/img/exercices/'.$nomExo.'.png" onclick="hideExersiseInterface()">
        <section id="oc_exersise_description">
            <section>
                <section id="oc_exersise_description_title">
                    <h3>'.$exersisesBdTemp[$nomExo]["nomAffiche"].'</h3>
                    <img id="oc_edit_button" src="src/datas/img/assets/logo_modifier.png">
                </section>
                <img id="oc_favorite_button" src="src/datas/img/assets/etoilePleine.svg">
            </section>
            <button id="oc_new_record_button">New reccord</button>
        </section>
    </section>
</section>
';
?>