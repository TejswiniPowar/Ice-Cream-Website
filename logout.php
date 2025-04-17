<?php
session_start();
session_unset();
session_destroy();
header("Location: scss/login.php");
exit;
