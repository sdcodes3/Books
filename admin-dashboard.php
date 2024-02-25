<?php
session_start();
require 'dbcon.php';
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />

    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />

    <!-- BootStrap Select for Multiple Select -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <!-- Google Fonts CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet">
    <style>
        #sidebarToggle:active,
        #sidebarToggle:focus {
            box-shadow: none !important;
        }
        .close {
            font-size: initial !important;
            font-weight: initial !important;
            line-height: initial !important;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end" id="sidebar-wrapper">
            <div class="sidebar-heading h4 mt-2">Admin</div>
            <div class="list-group list-group-flush mt-4">
                <a class="list-group-item list-group-item-action list-group-item-light px-3" href="admin-dashboard.php?id=1">Add User</a>
                <a class="list-group-item list-group-item-action list-group-item-light px-3" href="admin-dashboard.php?id=2">Add Data</a>
                <a class="list-group-item list-group-item-action list-group-item-light px-3" href="admin-dashboard.php?id=3">Add Balances</a>
                <a class="list-group-item list-group-item-action list-group-item-light px-3" href="admin-dashboard.php?id=4">Account Sheet</a>
                <a class="list-group-item list-group-item-action list-group-item-light px-3" href="admin-dashboard.php?id=5">View Data</a>
            </div>
        </div>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-light">
                <div class="container-fluid">
                    <button class="btn px-2" id="sidebarToggle">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <form action="<?php $_PHP_SELF ?>" method="POST">
                        <div class="dropdown ms-auto">
                            <a class="text-dark dropdown-toggle text-decoration-none" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                <?php
                                if (isset($_POST['logoutAdmin'])) {
                                    $_SESSION['adminName'] = NULL;
                                }
                                if (isset($_SESSION['adminName'])) {
                                    echo "Welcome <strong>" . $_SESSION['adminName'] . "</strong>";
                                }
                                else {
                                    header("Location: ./");
                                }
                                ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right mt-2" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <div class="dropdown-divider"></div>
                                <li>
                                    <button class="dropdown-item" name="logoutAdmin" type="submit">Logout</button>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </nav>
            <?php
            function userAcknowlegde($msg) {
                ?>
                <div class="container">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><?php echo $msg; ?></strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <img src="assets/close.svg" alt="">
                        </button>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php
            if (isset($_POST['add'])) {
                $addName = $_POST['name'];
                $addAccNo = $_POST['accNo'];
                $addCateg = $_POST['cat'];
                $addDepo = $_POST['depo'];
                $addDate = $_POST['depoDate'];
                $sql = "SELECT * FROM `users` WHERE acc_no='$addAccNo'";
                $result = mysqli_query($con,$sql);
                if ($result) {
                    if (mysqli_num_rows($result)==0) {
                        $sql = "INSERT INTO users VALUES (NULL, '$addName', $addAccNo, '$addCateg', '$addDepo', '$addDate')";
                        $result = mysqli_query($con,$sql);
                        if ($result) {
                            userAcknowlegde("Data Inserted Successfully.");
                        }
                        else {
                            userAcknowlegde("Data Inserted Failed.");
                        }
                    }
                    else {
                        userAcknowlegde("Data already exist.");
                    }
                }
            }
            ?>
            <?php
            if (isset($_POST['addData'])) {
                $dataAccNo = $_POST['accs'];
                $dataDate = $_POST['date'];
                $dataTime = $_POST['time'];
                $dataPair = $_POST['pair'];
                if (is_numeric($_POST['lot'])) {
                    $dataLot = number_format($_POST['lot'], 2);
                }
                else {
                    echo "Enter lot in numeric value only";
                }
                $sql = "SELECT * FROM data WHERE acc_no=$dataAccNo and date='$dataDate' and pair='$dataPair' and lot=$dataLot";
                $result = mysqli_query($con,$sql);
                if (mysqli_num_rows($result)==0) {
                    $sql = "INSERT INTO data (`id`, `acc_no`, `date`, `time`, `pair`, `lot`) VALUES (NULL, $dataAccNo, '$dataDate', '$dataTime', '$dataPair', $dataLot)";
                    $result = mysqli_query($con,$sql);
                    if ($result) {
                        userAcknowlegde("Data Inserted Successfully");
                    }
                }
            }
            ?>
            <!-- Page content-->
            <?php
            if (isset($_GET['id']))
            $contentId = $_GET['id'];
            else
            $contentId = 1;
            if ($contentId==4) {
                ?>
                <div class="container">
                    <div class="border-bottom">
                        <form>
                            <select name="cName">
                                <option value="Kirti">Kirtiben Patel</option>
                                <option value="HS">j K Shah</option>
                                <option value="HS">H S Goswami</option>
                                <option value="HS">H S Goswami</option>
                            </select>
                        </form>
                    </div>
                </div>
                <?php
            }
            elseif ($contentId==3) {
            ?>
            <div class="container mt-3 py-3 rounded bg-light">
                <div class="h2">Add Balance</div>
                <form action="" class="row">
                    <div class="d-flex flex-column col-12 col-md-6 p-3 form-group mb-0">
                        <label for="accNo">Account Number</label>
                        <select name="accs[]" id="accNo" class="form-control">
                            <option value="6218366" selected>6218366</option>
                            <option value="6223019">6223019</option>
                        </select>
                    </div>
                    <div class="d-flex flex-column col-12 col-md-6 p-3 form-group mb-0">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="2022-04-21">
                    </div>
                    <div class="d-flex flex-column col-12 col-md-6 p-3 form-group mb-0">
                        <label for="time">Time</label>
                        <input type="time" name="time" id="time" step="2" class="form-control" value="16:11:12">
                    </div>
                    <div class="d-flex flex-column col-12 col-md-6 p-3 form-group mb-0">
                        <label for="equity">Equity</label>
                        <input type="text" name="equity" id="equity" class="form-control" placeholder="Enter Equity">
                    </div>
                    <div class="d-flex flex-column col-12 col-md-6 p-3 form-group mb-0">
                        <label for="balance">Balance</label>
                        <input type="text" name="balance" id="balance" class="form-control" placeholder="Enter Balance">
                    </div>
                    <div class="d-flex col-12 col-md-6 p-3 form-group align-items-end mb-0 justify-content-end">
                        <button class="btn btn-primary rounded-pill px-3" type="submit">Add Balance</button>
                    </div>
                </form>
            </div>
            <?php
            }
            elseif ($contentId==2) {
            ?>
            <!-- Add Data -->
            <div class="container mt-3 py-3 rounded bg-light">
                <div class="h2">Add Data</div>
                <form action="<?php $_PHP_SELF ?>" class="row" method="POST">
                    <div class="d-flex flex-column col-12 col-md-6 p-3 form-group mb-0">
                        <label for="accNo">Account Number</label>
                        <select name="accs" id="accNo" class="form-control">
                            <?php
                            $sql = "SELECT * FROM users";
                            $result = mysqli_query($con,$sql);
                            if (mysqli_num_rows($result)>0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <option value="<?php echo $row['acc_no']; ?>" <?php if (isset($_POST['accs'])) {
                                        if ($_POST['accs']==$row['acc_no']) {
                                            echo "selected";
                                        }
                                    } ?>><?php echo $row['acc_no']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="d-flex flex-column col-12 col-md-6 p-3 form-group mb-0">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="<?php echo(date('Y-m-d')); ?>">
                    </div>
                    <div class="d-flex flex-column col-12 col-md-6 p-3 form-group mb-0">
                        <label for="time">Time</label>
                        <input type="time" name="time" id="time" step="2" class="form-control" value="<?php echo(date("H:i:s")); ?>">
                    </div>
                    <div class="d-flex flex-column col-12 col-md-6 p-3 form-group mb-0">
                        <label for="pair">Pair</label>
                        <input list="pairs" name="pair" id="pair" class="form-control" placeholder="Enter or Select Pair" required>
                        <datalist id="pairs">
                            <?php
                            $sql = "SELECT DISTINCT pair FROM data";
                            $result = mysqli_query($con,$sql);
                            if (mysqli_num_rows($result)>0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <option value="<?php echo $row['pair']; ?>"><?php echo $row['pair']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </datalist>
                    </div>
                    <div class="d-flex flex-column col-12 col-md-6 p-3 form-group mb-0">
                        <label for="lot">Lot</label>
                        <input type="text" name="lot" id="lot" class="form-control" placeholder="Enter Lot" required>
                    </div>
                    <div class="d-flex col-12 col-md-6 p-3 form-group align-items-end mb-0 justify-content-end">
                        <button class="btn btn-primary rounded-pill px-3" type="submit" name="addData">Add Data</button>
                    </div>
                </form>
            </div>
            <?php
            }
            elseif ($contentId==1) {
            ?>
            <!-- Add User -->
            <div class="container mt-3 py-3 rounded bg-light">
                <div class="h2 mb-3" style="padding-left: 15px;">Add User</div>
                <form action="<?php $_PHP_SELF ?>" class="d-flex flex-wrap" method="POST">
                    <div class="d-flex flex-column form-group col-12">
                        <label for="name" class="mb-2">Name :</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required>
                    </div>
                    <div class="d-flex flex-column form-group col-12">
                        <label for="accNo" class="mb-2">Account Number :</label>
                        <input type="number" name="accNo" id="accNo" class="form-control" placeholder="Enter Account Number" required>
                    </div>
                    <div class="d-flex flex-column form-group col-12">
                        <label for="cat" class="mb-2">Category :</label>
                        <select name="cat" id="cat" class="form-control">
                            <option value="self">Self</option>
                            <option value="active" selected>Active</option>
                            <option value="inactive">In - Active</option>
                        </select>
                    </div>
                    <div class="d-flex flex-column form-group col-12">
                        <label for="depo" class="mb-2">Inital Deposit :</label>
                        <input type="text" name="depo" id="depo" class="form-control" placeholder="Enter Initial Deposit Amount" required>
                    </div>
                    <div class="d-flex col-12 flex-wrap">
                        <div class="d-flex flex-column form-group mb-0 me-2">
                            <label for="depoDate" class="mb-2">Date of Deposit :</label>
                            <input type="date" name="depoDate" id="depoDate" class="form-control" required style="width: max-content;" value="<?php echo(date("Y-m-d")); ?>">
                        </div>
                        <div class="d-flex align-items-end ms-auto">
                            <button class="btn btn-primary rounded-pill px-3 ms-auto" type="submit" name="add">Add</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php
            }
            else {
            ?>
            <!-- <form action="" method="get" class="border-bottom mb-3">
                <div class="container-fluid d-flex justify-content-center justify-content-md-around gap-3 my-3 flex-wrap">
                    <div class="date-picker d-flex flex-column justify-content-around p-2 pt-0">
                        <label for="startDate" style="font-size: 0.8rem;" class="p-0 pb-2 m-0">Start Date</label>
                        <input type="date" name="startDate" id="startDate">
                    </div>
                    <div class="date-picker d-flex flex-column justify-content-around p-2 pt-0">
                        <label for="endDate" style="font-size: 0.8rem;" class="p-0 pb-2 m-0">End Date</label>
                        <input type="date" name="endDate" id="endDate">
                    </div>
                    <div class="date-picker d-flex flex-column p-2 pb-0">
                        <label for="endDate" style="font-size: 0.8rem;" class="p-0 pb-2 m-0">Select Accounts</label>
                        <select class="selectpicker" name="accs[]" multiple data-live-search="true" title="Choose any one" data-actions-box="true" id="accs">
                        </select>
                    </div>
                   
                    <div class="date-picker d-flex flex-column p-2 pb-0">
                        <label for="endDate" style="font-size: 0.8rem;" class="p-0 pb-2 m-0">Column Filter</label>
                        <select class="selectpicker" name="filters[]" multiple data-live-search="true" title="Column Filter" data-actions-box="true">
                        </select>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="learn-more" type="submit" name="search">
                            <span class="circle" aria-hidden="true">
                                <span class="icon arrow"></span>
                            </span>
                            <span class="button-text">Search</span>
                        </button>
                    </div>
                </div>
            </form>        -->

            <?php
            }
            ?>
        </div>
    </div>

    <script src="js/scripts.js"></script>
    <script src="js/custom-script.js"></script>
</body>
</html>