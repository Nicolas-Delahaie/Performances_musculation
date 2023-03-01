<?php
// echo json_encode(["res"=>"Coucou"]);
if (isset($_GET["for"]))
    if ($_GET["for"] == "js"){
        echo json_encode(["res"=>"js"]);
    }
    else{
        echo json_encode(["res"=>"php"]);

    }
?>

