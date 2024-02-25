<?php
session_start();
if (isset($_SESSION['name'])) {
    header("Location: user-dashboard.php?type=active");
}
elseif (isset($_SESSION['adminName'])) {
    header("Location: admin-dashboard.php?id=1");
}
require 'dbcon.php';
?>

<!DOCTYPE html>
<html lang="en" class="html">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books - Login</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Link CSS -->
    <link rel="stylesheet" href="css/custom-style.css">

    <!-- Favicon Link -->
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <script src="js/custom-script.js"></script>
</head>
<body class="d-flex flex-column body" style="background-color: #cfc0f3;">
    <div class="container-fluid full-h d-flex justify-content-center align-items-center">
        <div class="p-3 p-md-5 col-12 col-sm-8 col-lg-4 shadow-lg bg-light rounded">
            <p class="display-4 text-center pb-3 mb-4 border-bottom border-3">
                LOGIN
            </p>
            <form action="<?php $_PHP_SELF; ?>" method="post">
                <div class="d-flex mb-3">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="user" checked>
                        <label class="form-check-label" for="flexRadioDefault2">
                            User
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="admin" <?php
                        if (isset($_POST['flexRadioDefault']) and $_POST['flexRadioDefault'] == 'admin') {
                            echo 'checked';
                        }
                        ?>>
                        <label class="form-check-label" for="flexRadioDefault1">
                            Admin
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Username :</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="username" required autocomplete="off" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ""; ?>">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password :</label>
                    <div class="form-control d-flex">
                        <input type="password" style="border: none; outline: none;" id="exampleInputPassword1" class="w-100" name="password" required>
                        <span class="ps-2 border-start d-flex align-items-center pass-view">
                            <img src="assets/eye-solid.svg" alt="" class="img-fluid" width="20" onclick="passView();">
                            <img src="assets/eye-slash-solid.svg" alt="" class="img-fluid d-none" width="20" onclick="passView();">
                        </span>
                    </div>
                </div>
                <div class="d-flex">
                    <button name="login" type="submit" class="btn btn-primary rounded-pill ms-auto px-3 fs-6">Login</button>
                </div>
            </form>
        </div>
        <?php
        if (isset($_POST['login'])) {
            $category = $_POST['flexRadioDefault'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $sql = "SELECT name FROM `login` WHERE category='$category' and username='$username' and password='$password'";
            $result = mysqli_query($con,$sql);
            if (mysqli_num_rows($result) > 0) {
                $name = mysqli_fetch_assoc($result)['name'];
                if ($category=="user") {
                    $_SESSION['name'] = $name;
                    header("Location: user-dashboard.php?type=active");
                }
                else {
                    $_SESSION['adminName'] = $name;
                    header("Location: admin-dashboard.php?id=1");
                }
            }
            else {
                ?>
                <div id="snackbar">Invalid Login or Password</div>
                <script>
                    showSnackBar();
                </script>
                <?php
            }
        }
        ?>
    </div>
</body>
</html>