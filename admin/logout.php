<?php 

session_start();

//unset semua session variable
unset($_SESSION['username']); 
unset($_SESSION['id_users']); 

//inset all
session_unset();

//Destroy_Session;
session_destroy();

//Arahkan kearah  halaman login
header('location: ../login.php?pesan=logout');
exit;


?>