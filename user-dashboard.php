<?php
session_start();
include 'dbcon.php';
date_default_timezone_set('Asia/Kolkata');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>User Dashboard</title>

        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />

        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/custom-style.css" rel="stylesheet" />

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
    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            <div class="border-end" id="sidebar-wrapper">
                <div class="sidebar-heading h4 mt-2">Members</div>
                <div class="list-group list-group-flush mt-4">
                    <a class="list-group-item list-group-item-action list-group-item-light px-3" href="user-dashboard.php?type=active">Active</a>
                    <a class="list-group-item list-group-item-action list-group-item-light px-3" href="user-dashboard.php?type=inactive">In-Active</a>
                    <a class="list-group-item list-group-item-action list-group-item-light px-3" href="user-dashboard.php?type=summary">Summary</a>
                </div>
            </div>
            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->
                <nav class="navbar navbar-light">
                    <div class="container-fluid">
                        <button class="btn px-0" id="sidebarToggle"><span class="navbar-toggler-icon"></span></button>
                        <form action="<?php $_PHP_SELF ?>" method="POST">
                            <div class="dropdown ms-auto">
                                <a class="text-dark dropdown-toggle text-decoration-none" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                    <?php
                                    if (isset($_POST['logout'])) {
                                        $_SESSION['name'] = NULL;
                                    }
                                    if (isset($_SESSION['name']))
                                    echo "Welcome <span class='fw-bold'>".$_SESSION['name']."</span>";
                                    else
                                    header("Location: ./");
                                    ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right mt-2" aria-labelledby="dropdownMenuLink">
                                    <li><a class="dropdown-item" href="#">Profile</a></li>
                                    <div class="dropdown-divider"></div>
                                    <li>
                                        <button class="dropdown-item" name="logout" type="submit">Logout</button>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </nav>
                <!-- Page content-->
                <form action="<?php $_PHP_SELF ?>" method="post" class="border-bottom mb-3">
                    <div class="container-fluid d-flex justify-content-center justify-content-md-around gap-3 my-3 flex-wrap">
                        <div class="date-picker d-flex flex-column justify-content-around p-2 pt-0">
                            <label for="startDate" style="font-size: 0.8rem;" class="p-0 pb-2 m-0">Start Date</label>
                            <input type="date" name="startDate" id="startDate" value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['startDate'], ENT_QUOTES) : date("Y-m-01"); ?>">
                        </div>
                        <div class="date-picker d-flex flex-column justify-content-around p-2 pt-0">
                            <label for="endDate" style="font-size: 0.8rem;" class="p-0 pb-2 m-0">End Date</label>
                            <input type="date" name="endDate" id="endDate" value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['endDate'], ENT_QUOTES) : date("Y-m-d"); ?>">
                        </div>
                        <div class="date-picker d-flex flex-column p-2 pb-0">
                            <label for="endDate" style="font-size: 0.8rem;" class="p-0 pb-2 m-0">Select Accounts</label>
                            <select class="selectpicker" name="accs[]" multiple data-live-search="true" title="Choose any one" data-actions-box="true" id="accs">
                                <?php
                                $category = $_GET['type'];
                                $sql = "SELECT acc_no FROM users WHERE category='$category'";
                                $result = mysqli_query($con,$sql);
                                while($data = mysqli_fetch_row($result)) {
                                    ?>
                                    <option value="<?php echo $data[0]; ?>" <?php
                                    if(isset($_POST['search'])) {
                                        if (in_array($data[0],$_POST['accs']))
                                        echo 'selected';
                                    }
                                    else
                                        echo 'selected';
                                        ?> > <?php echo $data[0]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                       
                        <div class="date-picker d-flex flex-column p-2 pb-0">
                            <label for="endDate" style="font-size: 0.8rem;" class="p-0 pb-2 m-0">Column Filter</label>
                            <select class="selectpicker" name="filters[]" multiple data-live-search="true" title="Column Filter" data-actions-box="true">
                                <?php
                                $sql = "SELECT DISTINCT pair FROM data,users WHERE users.category='$category' and users.acc_no=data.acc_no;";
                                $result = mysqli_query($con,$sql);
                                if(mysqli_num_rows($result)>0) {
                                    while ($row = mysqli_fetch_row($result)) {
                                        ?>
                                        <option value="<?php echo $row[0]; ?>" <?php
                                        if(isset($_POST['search'])) {
                                            if (in_array($row[0],$_POST['filters']))
                                            echo 'selected';
                                        }
                                        else
                                            echo 'selected';
                                        ?>><?php echo $row[0]; ?></option>
                                        <?php
                                    }
                                    ?>
                                <option value="BALANCE" <?php
                                    if(isset($_POST['search'])) {
                                        if (in_array("BALANCE",$_POST['filters']))
                                        echo 'selected';
                                    }
                                    else
                                        echo 'selected';
                                    ?>>BALANCE</option>
                                <option value="EQUITY" <?php
                                    if(isset($_POST['search'])) {
                                        if (in_array("EQUITY",$_POST['filters']))
                                        echo 'selected';
                                    }
                                    else
                                        echo 'selected';
                                    ?>>EQUITY</option>
                                <option value="FLOATING" <?php
                                    if(isset($_POST['search'])) {
                                        if (in_array("FLOATING",$_POST['filters']))
                                        echo 'selected';
                                    }
                                    else
                                        echo 'selected';
                                    ?>>FLOATING</option>
                                    <?php
                                }
                                ?>
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
                </form>
                            
                <?php
                if(isset($_POST['search'])) {

                    $startDate = $_POST['startDate'];
                    $endDate = $_POST['endDate'];
                    $accounts = array();
                    $accounts = $_POST['accs'];
                    $columns = array();
                    $columns = $_POST['filters'];
                    ?>
                    <div class="container-fluid p-0">
                        <div class="h2 text-center pb-4 pt-2 border-bottom">Table of Content</div>
                        <?php
                        foreach ($accounts as $accNo) {
                            $sql = "SELECT name FROM users WHERE acc_no='$accNo'";
                            $result = mysqli_query($con,$sql);
                            $name = mysqli_fetch_assoc($result)['name'];
                            $i = 1;
                            ?>
                            <div class="table-responsive p-3">    
                            <div class="h4 my-3"><?php echo $accNo; ?> - <?php echo $name; ?></div>
                            <table class="table table-striped table-borderless border-bottom">
                                <thead>
                                    <?php
                                    $datesArr = array();
                                    ?>
                                    <tr>
                                        <th scope="col">No.</th>
                                        <th scope="col">Pair</th>
                                        <?php
                                        $sql = "SELECT DISTINCT date, time FROM data WHERE acc_no='$accNo' and (date>='$startDate' and date<='$endDate')";
                                        $result = mysqli_query($con,$sql);
                                        if (mysqli_num_rows($result)>0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                array_push($datesArr,$row['date']);
                                                $temp = str_replace("-","/",$row['date']);
                                                $newDate = date("d-m-Y",strtotime($temp));
                                                ?>
                                                    <th scope="col" class="text-nowrap"><?php echo $newDate . "<br>" . $row['time']; ?></th>
                                                <?php
                                            }
                                        }
                                        else {
                                            ?>
                                                <th scope="col" class="text-nowrap">No data available</th>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = "SELECT DISTINCT pair FROM data WHERE acc_no='$accNo'";
                                        $result = mysqli_query($con,$sql);
                                        if (mysqli_num_rows($result)>0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                if (in_array($row['pair'],$columns)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $i; $i++; ?></td>
                                                    <td><?php echo $row['pair']; ?></td>
                                                    <?php
                                                        $temp = $row['pair'];
                                                        if(count($datesArr)>0)
                                                        foreach ($datesArr as $value) {
                                                            $sql = "SELECT lot FROM data WHERE pair='$temp' and acc_no='$accNo' and date='$value'";
                                                            $result1 = mysqli_query($con,$sql);
                                                            if (mysqli_num_rows($result1)>0) {
                                                                while ($lot = mysqli_fetch_assoc($result1)) {
                                                                    ?>
                                                                    <td><?php echo $lot['lot']; ?></td>
                                                                    <?php
                                                                }
                                                            }
                                                            else {
                                                                ?>
                                                                <td>NA.</td>
                                                                <?php
                                                            }
                                                        }
                                                        else {
                                                            ?>
                                                            <td>NA.</td>
                                                            <?php
                                                        }
                                                    ?>
                                                </tr>
                                                <?php
                                                }
                                            }
                                        }
                                        if (in_array("EQUITY",$columns)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i; $i++; ?></td>
                                        <td>EQUITY</td>
                                        <?php
                                            if(count($datesArr)>0)
                                            foreach ($datesArr as $value) {
                                                $sql = "SELECT * FROM balance WHERE acc_no=$accNo and date='$value'";
                                                $result = mysqli_query($con,$sql);
                                                if (mysqli_num_rows($result)>0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        ?>
                                                        <td><?php echo $row['equity']; ?></td>
                                                        <?php
                                                    }
                                                }
                                                else {
                                                    ?>
                                                    <td>NA.</td>
                                                    <?php
                                                }
                                            }
                                            else {
                                                ?>
                                                <td>NA.</td>
                                                <?php
                                            }
                                        ?>
                                    </tr>
                                    <?php
                                    }
                                    if (in_array("BALANCE",$columns)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i; $i++; ?></td>
                                        <td>BALANCE</td>
                                        <?php
                                            if(count($datesArr)>0)
                                            foreach ($datesArr as $value) {
                                                $sql = "SELECT * FROM balance WHERE acc_no=$accNo and date='$value'";
                                                $result = mysqli_query($con,$sql);
                                                if (mysqli_num_rows($result)>0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        ?>
                                                        <td><?php echo $row['balance']; ?></td>
                                                        <?php
                                                    }
                                                }
                                                else {
                                                    ?>
                                                    <td>NA.</td>
                                                    <?php
                                                }
                                            }
                                            else {
                                                ?>
                                                <td>NA.</td>
                                                <?php
                                            }
                                        ?>
                                    </tr>
                                    <?php
                                    }
                                    if (in_array("FLOATING",$columns)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $i; $i++; ?></td>
                                            <td>FLOATING</td>
                                            <?php
                                                if(count($datesArr)>0)
                                                foreach ($datesArr as $value) {
                                                    $sql = "SELECT * FROM balance WHERE acc_no=$accNo and date='$value'";
                                                    $result = mysqli_query($con,$sql);
                                                    if (mysqli_num_rows($result)>0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            ?>
                                                            <td><?php
                                                            if ($row['floating']!=0) {
                                                                echo $row['floating'];
                                                            }
                                                            else {
                                                                echo "NA.";
                                                            }
                                                            ?></td>
                                                            <?php
                                                        }
                                                    }
                                                    else {
                                                        ?>
                                                        <td>NA.</td>
                                                        <?php
                                                    }
                                                }
                                                else {
                                                    ?>
                                                    <td>NA.</td>
                                                    <?php
                                                }
                                            ?>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                            <?php
                        }
                    }
                ?>                            
            </div>
        </div>

        <script src="js/scripts.js"></script>
        <script src="js/custom-script.js"></script>
    </body>
</html>
