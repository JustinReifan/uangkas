<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.html");
    exit;
}
require 'functions.php';
$siswa = query("SELECT * FROM siswa");
$config = query("SELECT * FROM config");

// tombol cari ditekan
if (isset($_POST["cari"])) {
    $siswa = cari($_POST["keyword"]);
}
if (isset($_POST["isLunas"])) {
    $siswa = isLunas();
}
if (isset($_POST["notLunas"])) {
    $siswa = notLunas();
}

if (isset($_POST["defaultLunas"])) {
    $siswa = defaultLunas();
}

if (isset($_POST["btnupdatewave"])) {

    // cek data berhasil diubah
    if (ubahWave($_POST) > 0) {
        echo "
                <script> 
                    alert('data berhasil diubah!');
                    document.location.href = 'index.php';
                </script>
            ";
    } else {
        echo "
                <script> 
                        alert('Gagal mengubah data!');
                        document.location.href = 'index.php';
                </script>
            ";
        // echo mysqli_error($conn);
    }
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
    <script src="js/jquery.js"></script>
    <script src="js/script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
    <link rel="stylesheet" href="assets/css/admin.css?v=<?php echo time(); ?>">

    <style>
        .loader {
            width: 23px;
            position: absolute;
            top: 141px;
            left: 195px;
            z-index: -1;
            display: none;
        }
    </style>
</head>

<body>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow fixed-top" style="background-color: #c5aa6a">
        <div class="container-fluid">
            <a href="index.html"><img src="assets/img/logo.jpeg" alt="" class="rounded float-start" style="width: 60px; height: 60px" /></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-start ms-4" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item tombol-ilang2">
                        <a class="nav-link" aria-current="page" href="index.html#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html#gallery">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html#footer">Contacts</a>
                    </li>
                    <li class="nav-item tombol-ilang">
                        <a class="nav-link" href="logout.php">Log Out</a>
                    </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse justify-content-end navbar-nav me-3">
                <li class="nav-item me-4">
                    <a href="logout.php" class="nav-link active"><i class="fa-solid fa-arrow-right-from-bracket fa-lg text-warning"></i></a>
                </li>
            </div>
        </div>
    </nav>
    <!-- navbar end-->
    <br> <br><br><br>



    <!-- ALERT BOX -->
    <h1 class="display-4 d-flex mx-auto justify-content-center">TABEL UANGKAS KELAS</h1>
    <div class="container">
        <div class="alert-bos">
            <div class="alert alert-success w-25 d-flex justify-content-center mx-auto" role="alert" style="padding: 10px 0px;">
                <div class="d-flex justify-content-center align-content-center">

                    <h3 class="wave-info"><i class=" fa-solid fa-circle-info fa-sm"></i> Wave Now: <?= $config[0]['wave']; ?></h3>
                    <div id="myNav" class="overlay ">
                        <div class="card h-100 ">
                            <div class="card-header fw-bold">
                                Edit Global Wave
                            </div>
                            <div class="card-body ">
                                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                                <div class="overlay-content">
                                    <form action="" method="post">
                                        <label>
                                            Ubah Wave: <br>
                                            <input type="text" class="input-wave" name="wave">
                                            <button type="submit" onclick="closeNav()" class="btn btn-danger tombol-back">Back</button>
                                            <button type="submit" class="btn btn-primary tombol-submit" name="btnupdatewave">Submit</button>
                                        </label>
                                    </form>
                                </div>

                                <hr>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="wave-button">
                <span class="edit-pen" onclick="openNav()">&nbsp; &nbsp; <i class="fa-solid fa-square-pen fa-2xl"></i></span>
                <a href="addwavemain.php" class="edit-plus" id="addwaveglobal" onclick="return confirm('Tambah 1 Wave Global?');"><i class="fa-solid fa-circle-plus fa-2xl"></i></a>
            </div>

        </div>

    </div>
    <!-- end alert box -->
    <!-- <hr class="mx-auto" style="width: 90%;"> -->


    <!-- filter start-->
    <br>
    <form action="" method="post">
        <div class="filter">

            <div class="filter-box d-flex justify-content-start">
                <button type="submit" name="defaultLunas" class="btn mx-1 mb-1 text-uppercase bg-secondary text-light">All</button>
                <button type="submit" name="isLunas" class="btn mx-1 mb-1 text-uppercase bg-secondary text-light">Lunas</button>
                <button type="submit" name="notLunas" class="btn mx-1 mb-1 text-uppercase bg-secondary text-light">Belum Lunas</button>
            </div>

            <div class="search-box col d-flex justify-content-end">
                <label for="keyword" class="fw-lighter text-searchbox">Search: &nbsp;</label>
                <input type="text" name="keyword" size="20" autofocus placeholder="Cari Data" autocomplete="off" id="keyword">
                <button type="submit" name="cari" id="tombol-cari" class="search-box">Cari</button>
                <a class="tombol-plus btn btn-secondary" style="margin-left: 2px;" href="tambah.php"><i class="fa-solid fa-plus fa-lg"></i></a>
            </div>


            <img src="assets/img/loader.gif" class="loader">
        </div>
    </form>
    <!-- filter end -->



    <!-- table start -->
    <div id="container" class="table-wrapper mb-5 mt-0">


        <table class="fl-table">
            <thead>
                <tr class="table-header">
                    <th>Absen</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Wave Dibayar</th>
                    <th>Sisa Wave</th>
                    <th>Uang Dibayar</th>
                    <th>Sisa Uang</th>
                    <th>Lunas</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <?php foreach ($siswa as $row) : ?>
                <tr>
                    <td><?= $row["absen"]; ?></td>
                    <td class="nama"><?= $row["nama"]; ?></td>
                    <td class="kelas"><?= $row["kelas"]; ?></td>
                    <td><?= $row["wavedibayar"]; ?></td>
                    <td><?= $row["wavesisa"]; ?></td>
                    <td><?= $row["dibayar"]; ?></td>
                    <td><?= $row["sisa"]; ?></td>
                    <td class="emoji"><?= $row["lunas"]; ?></td>
                    <td class="edit">
                        <a href="ubah.php?absen=<?= $row["absen"]; ?>"><i class="fa-regular fa-pen-to-square"></i></a> |
                        <a href="hapus.php?absen=<?= $row["absen"]; ?>" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus?');"><i class="fa-solid fa-trash"></i></a> |
                        <a href="addwaveuser.php?absen=<?= $row["absen"]; ?>" onclick="return confirm('Tambah 1 wave ke siswa <?= $row["nama"]; ?> ?');"><i class="fa-solid fa-circle-plus"></i></a>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </div> <br>

    <!-- footer -->
    <footer class="mt-5">
        <div class="footer" id="footer" style="background-color: #f8dcc4">
            <div class="row">
                <ul>
                    <li>
                        <a href="https://instagram.com/"><i class="fa fa-instagram"></i></a>
                    </li>
                    <li>
                        <a href="https://wa.me/6281932888380"><i class="fa fa-whatsapp"></i></a>
                    </li>
                    <li>
                        <a href="https://github.com/JustinReifan"><i class="fa fa-github"></i></a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <ul>
                    <li><a href="logout.php">Logout</a></li>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="https://jasa.tinped.com">Website Collections</a></li>
                    <li><a href="https://instagram.com/justin_reifan">About Dev</a></li>
                </ul>
            </div>

            <div class="text-center credit">XI MM 3 © 2023 - All Rights Reserved <br />Made with ❤️ by Justine R.</div>
        </div>
    </footer>
    <!-- footer end -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/70d30dbaf2.js" crossorigin="anonymous"></script>
    <script>
        function openNav() {
            document.getElementById("myNav").style.scale = "1";
        }

        /* Close */
        function closeNav() {
            document.getElementById("myNav").style.scale = "0";
        }
    </script>
</body>

</html>