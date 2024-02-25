<?php
session_start();
require 'dbcon.php';
$fileList = array();
$fileList = scandir("files");
$counter = 0;
foreach($fileList as $file) {
    if($file=="." or $file==".." or $file=="copy_file.bat")
        continue;
    if(str_contains($file, "History")) {
        $fileName = "files/".$file;
        if (($fileOpen = fopen ("$fileName", "r")) !== FALSE ) {
            while (($data = fgetcsv($fileOpen, 1000, ",")) !== FALSE) {
                $accNo = $data[0];
                $accName = $data[1];
                $date = $data[2];
                $time = $data[3];
                $symbol = $data[4];
                if (strcmp($symbol,"EQUITY")==0) {
                    $equity = round($data[5],2);
                    $sql = "SELECT * FROM balance WHERE acc_no=$accNo and date='$date' and time='$time' and equity='$equity'";
                    $result = mysqli_query($con,$sql);
                    if (mysqli_num_rows($result)==0) {
                        $sql = "INSERT INTO balance (id, acc_no, date, time, equity) VALUES(NULL, $accNo, '$date', '$time', $equity )";
                        $result = mysqli_query($con, $sql);
                    }
                    else {
                        echo "Already there : $sql<br>";
                        $counter = 1;
                    }
                }
                elseif (strcmp($symbol,"BALANCE")==0) {
                    $balance = round($data[5],2);
                    $sql = "SELECT * FROM balance WHERE acc_no=$accNo and date='$date' and time='$time' and balance='$balance'";
                    $result = mysqli_query($con,$sql);
                    if (mysqli_num_rows($result)==0) {
                        $sql = "UPDATE balance SET balance=$balance WHERE acc_no=$accNo and date='$date' and time='$time'";
                        $result = mysqli_query($con, $sql);
                    }
                    else {
                        echo "Already there : $sql<br>";
                        $counter = 1;
                    }
                }
                elseif (strcmp($symbol,"FLOATING")==0) {
                    $float = round($data[5],2);
                    $sql = "SELECT * FROM balance WHERE acc_no=$accNo and date='$date' and time='$time' and floating='$float'";
                    $result = mysqli_query($con,$sql);
                    if (mysqli_num_rows($result)==0) {
                        $sql = "UPDATE balance SET floating=$float WHERE acc_no=$accNo and date='$date' and time='$time'";
                        $result = mysqli_query($con, $sql);
                    }
                    else {
                        echo "Already there : $sql<br>";
                        $counter = 1;
                    }
                }
                else {
                    $lot = round($data[5],2);
                    $sql = "SELECT * FROM data WHERE acc_no='$accNo' and date='$date' and time='$time' and pair='$symbol' and lot='$lot'";
                    $result = mysqli_query($con,$sql);
                    if ($result) {
                        if (mysqli_num_rows($result)==0) {
                            $sql2 = "SELECT * FROM data WHERE acc_no='$accNo' and date='$date' and time='$time'";
                            $result2 = mysqli_query($con,$sql2);
                            if(mysqli_num_rows($result2)==0){
                                $sql1 = "INSERT INTO data VALUES(NULL,$accNo,'$date','$time','$symbol','$lot',current_timestamp())";
                                $result1 = mysqli_query($con,$sql1);
                                if (!$result1) {
                                    echo "Cannot Insert : $sql1<br>Error : ",mysqli_error($con),"<br>";
                                    $counter = 1;
                                }
                            }
                            else{
                                $sql1 = "UPDATE data SET acc_no='$accNo' and date='$date' and time='$time' and pair='$symbol' and lot='$lot' and timestamp=current_timestamp()";
                                $result1 = mysqli_query($con,$sql1);
                                if (!$result1) {
                                    echo "Cannot Update : $sql1<br>Error : ",mysqli_error($con),"<br>";
                                    $counter = 1;
                                }
                            }
                        }
                        else {
                            echo "Already there : $sql  <br>";
                            $counter = 1;
                        }
                    }
                }
            }
            fclose($fileOpen);
        }
    }
}
if ($counter==0) {
    echo "All Data Entered Successfully";
}
elseif ($counter==1) {
    echo "Error in inserting some or all data";
    echo "<br>Or may be all data is already inserted.";
}
?>