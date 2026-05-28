<?php
session_start();
require_once("function.php");
require_once("General.php");
require_once("require/database_connection.php");

General::site_header();
General::site_navbar();
General::site_contact_us();
General::site_footer();
General::footer_scripts();
?>
