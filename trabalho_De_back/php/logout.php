<?php
session_start();
session_destroy();
header("Location: /TRABALHO_DE_BACK/html/login.html");
exit;
?>
 