<?php
session_start();
if ($_SESSION["userId"]){
    session_destroy();
}

header("Location:".$_SERVER["HTTP_REFERER"]);
?>