<?php
session_start();
session_destroy();
header("Location: ../../../index.php"); // Adjusted path to point to the login page
exit;
