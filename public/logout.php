<?php
session_start();
session_destroy();
header('Location: /scholasys/public/login.php');
exit;