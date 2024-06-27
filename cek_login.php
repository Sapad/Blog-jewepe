<?php
// $pass = password_hash('jewepe123', PASSWORD_DEFAULT);
// var_dump($pass);
// die;

include("admin/config_query.php");

$db = new database();

// Initialize session
session_start();

// Check if a session is active
if (isset($_SESSION['username']) || isset($_SESSION['id_users'])) {
    header('location: admin/index.php');
} else {
    // Check if the form is submitted
    if (isset($_POST['submit'])) {
        // Remove backslashes
        $username = stripslashes($_POST['username']);
        $password = stripslashes($_POST['password']);

        // Check if username and password are not empty
        if (!empty(trim($username)) && !empty(trim($password))) {
            // Select data from tb_users based on username
            $query = $db->get_data_users($username);

           


            if($query){
                $rows = mysqli_num_rows($query);
            }else{
                $rows = 0;
            }


            // cek ketersediaan data username 
            if($rows !=0){
                $getData = $query->fetch_assoc();
                // var_dump($getPassword);die;

                if(password_verify($password, $getData['password'])){
                    $_SESSION['username'] = $username;
                    $_SESSION['id_users'] = $getData['id_users'];

                    header('location: admin/index.php');
                }else{
                    header('location:login.php?pesan=gagal');
                }
            }else{
                header('location:login.php?pesan=notfound');
            }   
        }else{
            header('location:login.php?pesan=empty');
        }
    }else{
        header('location:login.php?pesan=empty');
    }
}

?>
