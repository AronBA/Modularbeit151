<?php
session_start();
session_regenerate_id();
//redirected den user automatisch
if (!isset($_SESSION['login']) && $_SESSION['login'] == false) {
    header("location: ../Backend/B_error.html");
}