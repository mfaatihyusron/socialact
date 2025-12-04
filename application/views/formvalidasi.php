<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
    <?php
    // Membuat variabel kosong
    $Nama = "";
    $Username = "";
    $pass = "";
    $NamaErr = "";
    $UsernameErr = "";
    $passErr = "";

    // Cek form sudah di klik submit/belum
    if(isset($_POST['submit'])){
        $Nama = trim($_POST['nama']);
        $Username = trim($_POST['username']);
        $pass = trim($_POST['pass']);

        // Cek input kosong
        if(empty($Nama)){
            $NamaErr = "Nama masih kosong.<br>";
        }
        if(empty($Username)){
            $UsernameErr = "Username masih kosong.<br>";
        }
        if(empty($pass)){
            $passErr = "Password masih kosong.<br>";
        }

        // Cek semua input sudah diisi apa belum
        if( !empty($Nama) and !empty($Username) and !empty($pass) ){
            echo "Selamat semua input sudah diisi.<br>";
        }
    }
    ?>

    <h3>Form Register</h3>
    <form action="validasi_kosong.php" method="post" style="padding: 15px;">
        Nama : <input type="text" name="nama" value="<?php echo $Nama; ?>" style="margin: 10px;padding: 5px;"> <br>
        <?php echo $NamaErr; ?>

        Username : <input type="text" name="username" value="<?php echo $Username; ?>" style="margin: 10px;padding: 5px;"> <br>
        <?php echo $UsernameErr; ?>

        Password : <input type="password" name="pass" value="<?php echo $pass; ?>" style="margin: 10px;padding: 5px;"> <br>
        <?php echo $passErr; ?>

        <input type="submit" name="submit" value="Register" style="padding: 9px; margin-top: 10px;">
    </form>
    
</body>
</html>