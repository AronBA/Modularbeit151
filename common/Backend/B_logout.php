<?php
//Start session
session_start();

//Destroy session
if (session_destroy()){
    header("location: ../index.html");
    exit;
}


