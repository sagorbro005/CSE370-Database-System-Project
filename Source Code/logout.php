<?php
session_start();

// Destroy all sessions and cookies
session_unset();
session_destroy();

// Redirect to login page
header("Location: login.html");
exit();
?>
