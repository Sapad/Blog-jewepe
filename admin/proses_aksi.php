<?php 
include('config_query.php');
$db = new database();
session_start();
$id_users = $_SESSION['id_users'];
$aksi = $_GET['aksi'];


if($aksi == "add"){
    //tambah artikel

    //debugging untuk menampilkan $files

    // echo "<pre>";
    // print_r($_FILES);
    // echo "</pre>";
    // die;

    //cek file yang sudah dipilih
    if($_FILES['header']['name']!=''){

        $tmp = explode('.', $_FILES['header']['name']); //memecah nama file dan extension 
        $ext = end($tmp); //mengambil extention
        $filename = $tmp[0]; // mengambil nilai nama file tanpa extention
        $allowed_ext = array("jpg","jpeg", "png"); // extentio file yang diizinkan 

        if (in_array($ext, $allowed_ext)) { //cek validasi extention x
            if($_FILES["header"]["size"] <= 5120000) { //cek ukuran gambar maks 5mb
                $name = $filename.'_'.rand().'.'.$ext; //rename nama file gambar
                $path = "../files/".$name; //lokasi upload file 
                $uploaded = move_uploaded_file($_FILES['header']['tmp_name'], $path); //memindahkan file 

                if($uploaded){
                    $insertData =  $db->tambah_data($name, $_POST['judul_artikel'], $_POST['isi_artikel'], 
                    $_POST['status_publish'], $id_users); // query insert data

                    if($insertData){
                        echo "<script>alert('Data Berhasil ditambahkan');document.location.href='index.php';</script>";
                    }else{
                        echo "<script>alert('Mohon Maaf data tidak berhasil ditambahkan');document.location.href='index.php';</script>";
                    }
                }else{
                    echo "<script>alert('Mohon Maaf Upload file gagal');document.location.href='tambah_data.php';</script>";
                }
            }else{
                echo "<script>alert('Mohon maaf Ukuran Gambar lebih dari 5MB');document.location.href='tambah_data.php';</script>";
            }
        }else{
            echo "<script>alert('File yang di upload bukan diizinkan');document.location.href='tambah_data.php';</script>";
        }
    }else{
        echo "<script>alert('Silakan Pilih File Gambar');document.location.href='tambah_data.php';</script>";
    }
}elseif ($aksi == "update") {
    // edit artikel
    $id_artikel = $_POST['id_artikel'];
    if(!empty($id_artikel)){ //cek id_user tersedia atau tidak
        if($_FILES['header']['name']!=''){ //cek melakukan upload file
            //eksekusi upload file
            $data = $db->get_by_id($id_artikel);

            //operasi hapus file gambar
            if(file_exists('../files/'.$data['header']) && $data['header'])
                unlink('../files/'.$data['header']);

                $tmp = explode('.', $_FILES['header']['name']); //memecah nama file dan extension 
                $ext = end($tmp); //mengambil extention
                $filename = $tmp[0]; // mengambil nilai nama file tanpa extention
                $allowed_ext = array("jpg","jpeg", "png"); // extentio file yang diizinkan 

                if (in_array($ext, $allowed_ext)) { //cek validasi extention x
                    if($_FILES["header"]["size"] <= 5120000) { //cek ukuran gambar maks 5mb
                        $name = $filename.'_'.rand().'.'.$ext; //rename nama file gambar
                        $path = "../files/".$name; //lokasi upload file 
                        $uploaded = move_uploaded_file($_FILES['header']['tmp_name'], $path); //memindahkan file 

                        if($uploaded){
                            $updateData =  $db->update_data($name, $_POST['judul_artikel'], $_POST['isi_artikel'], 
                            $_POST['status_publish'], $_POST['id_artikel'], $id_users); // query update data

                            if($updateData){
                                echo "<script>alert('Data Berhasil di Update');document.location.href='index.php';</script>";
                            }else{
                                echo "<script>alert('Mohon Maaf data tidak berhasil ditambahkan');document.location.href='index.php';</script>";
                            }
                        }else{
                            echo "<script>alert('Mohon Maaf Upload file gagal');document.location.href='edit.php?id=".$id_artikel."';</script>";
                        }
                    }else{
                        echo "<script>alert('Mohon maaf Ukuran Gambar lebih dari 5MB');document.location.href='edit.php?id=".$id_artikel."';</script>";
                    }
                }else{
                    echo "<script>alert('File yang di upload bukan diizinkan');document.location.href='edit.php?id=".$id_artikel."';</script>";
                }
        }else{
            $updateData = $db->update_data('not_set',$_POST['judul_artikel'], $_POST['isi_artikel'],
            $_POST['status_publish'], $_POST['id_artikel'], $id_users);
            if($updateData){
                echo "<script>alert('Data berhasil di update');document.location.href='index.php';</script>";
            }else{
                echo "<script>alert('Data gagal diubah');document.location.href='tambah_data.php';</script>";
            }
        }
    }else{
        echo "<script>alert('Anda belum memilih artikel');document.location.href='index.php';</script>";
    }
}elseif($aksi == "delete"){
    // delete artikel
    $id_artikel = $_GET['id'];
    if(!empty($id_artikel)){
        //eksekusi hapus data
        $data = $db->get_by_id($data_artikel);

        //delete file
        if(file_exists('../files/'.$data['header']) && $data['header'])
                unlink('../files/'.$data['header']);

            $deleteData = $db->delete_data($id_artikel);
            if($deleteData){
                echo "<script>alert('Data berhasil diHapus');document.location.href='index.php';</script>";
            }else{
                echo "<script>alert('Data gagal diubah');document.location.href='tambah_data.php';</script>";
            }
    }else{
        echo "<script>alert('Anda belum memilih artikel');document.location.href='index.php';</script>";

    }
}else{
    echo "<script>alert('Anda tidak mendapatkan akses untuk operasi ini');document.location.href='index.php';</script>";
}

?>

