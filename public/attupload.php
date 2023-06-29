<?php
include("config.php");
header("Content-type: text/xml; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$arrid = array();
$ip="";
$serial="";
$attmaxid="";
try{
  $data = trim(file_get_contents('php://input'));
  $xml = simplexml_load_string($data);
if ($xml === false) {
    echo "Failed loading XML: ";
    foreach(libxml_get_errors() as $error) {
        echo "<br>", $error->message;
    }
}
 else {
   $attmaxid = trim($xml->attmaxid);
   $ip = trim($xml->ip);
   $serial = trim($xml->serialno);
   $conn = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
   $createdate = date("Y-m-d H:i:s");
   // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
   $stmt = $conn->prepare("INSERT INTO raw_att(att_UserID, user_id, att_ip, att_serial, att_Date, branch,reason,att_UpdateTime,att_CreateTime)  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $att_stmt = $conn->prepare("INSERT INTO attendances(device, device_ip, device_serial, user_id,profile_id,date,start_time,type,type_id, branch_id,corrected_start_time,corrected_end_time,created_by,created_at)  VALUES (?, ?, ?, ?,?,?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $update_stmt = $conn->prepare("UPDATE attendances SET user_id=?,end_time=?,corrected_start_time=?,corrected_end_time=?,updated_at=? WHERE profile_id=? AND date=? AND branch_id=?");
    foreach($xml->children() as $child) {
      if($child->getName() == "attinfo"){
        $b = 0;
        if($serial=="BIMW221260023")
          $b = "1";
        else
          $b = "2";
        $r = "";
        $id = $child->attributes();

        //get profile id
        $userid = $child->userid;
        $profilesql = mysqli_query($conn, "SELECT * FROM raw_profile WHERE pro_UserId = '".$userid."' AND pro_Serial = '".$serial."'");
        
        $profilerow = mysqli_num_rows($profilesql);
        if($profilerow>0){
          while($profilerow = mysqli_fetch_array($profilesql)) {
              $profile_id = $profilerow['pro_id'];
            }
        }
        else{
          $profile_id = 0;
        }

        //get user id
        $usersql = mysqli_query($conn, "SELECT * FROM users WHERE profile_id = '".$profile_id."'");
        
        $userrow = mysqli_num_rows($usersql);
        if($userrow>0){
          while($userrow = mysqli_fetch_array($usersql)) {
              $user_id = $userrow['id'];
              $corrected_start_time = $userrow['working_start_time'];
              $corrected_end_time = $userrow['working_end_time'];
            }
        }
        else{
          $user_id = 0;
          $corrected_start_time = $time;
          $corrected_end_time = $time;
        }

        $stmt->bind_param("sssssssss", $profile_id,$user_id, $ip,$serial,$child->time,$b,$r,$createdate,$createdate);
        $stmt->execute();
        array_push($arrid,$id["id"]);

        //The following section is developed by tth for attendances table and late table
        //explode date and time
        $datetime = explode(" ", $child->time);
        $date = $datetime[0];
        $time = $datetime[1];


        //check attendance data have
        $attendancesql = mysqli_query($conn, "SELECT * FROM attendances WHERE profile_id = '".$profile_id."' AND date = '".$date."' AND branch_id = '".$b."'");        
        $attendancerow = mysqli_num_rows($attendancesql);

        if($attendancerow>0){
      
          while($attendancerow = mysqli_fetch_array($attendancesql)) {
              $update_stmt->bind_param("ssssssss", $user_id,$time, $corrected_start_time, $corrected_end_time,$createdate, $profile_id, $date, $b);
              $update_stmt->execute();
          }
      
        }
        else{
          $device = "Finger Print";
          $type = "Working Day";
          $type_id = "0";
          $att_stmt->bind_param("ssssssssssssss", $device, $ip,$serial,$user_id,$profile_id,$date,$time,$type,$type_id,$b,$corrected_start_time,$corrected_end_time,$user_id,$createdate);
          $att_stmt->execute();

          //Save late table
          if (strtotime($time) > strtotime($corrected_start_time)){

            $year = date('Y', strtotime($date));
            $month = date('F', strtotime($date));
            $month = strtolower($month);
            if($month=='january' || $month=="february" ||$month=="march")
              $year = $year-1;

            $late_stmt = $conn->prepare("INSERT INTO lates(user_id, $month, year,created_at)  VALUES (?, ?, ?, ?)");
            $update_late_stmt = $conn->prepare("UPDATE lates SET $month=?,updated_at=? WHERE user_id=? AND year=?");
        
            $latesql = mysqli_query($conn, "SELECT * FROM lates WHERE user_id = '".$user_id."' AND year = '".$year."'");
            $laterow = mysqli_num_rows($latesql);
            if($laterow>0){
              while($laterow = mysqli_fetch_array($latesql)) {
                $count_times = $laterow[$month]+ 1;
                $update_late_stmt->bind_param("ssss", $count_times,$createdate, $user_id, $year);
                $update_late_stmt->execute();
              }
            }
            else{
              $count_times = 1;
              $late_stmt->bind_param("ssss", $user_id, $count_times,$year,$createdate);
              $late_stmt->execute();
            }
          }
        }
        //End tth section
    }
}
 //echo($attmaxid . "," . $ip . "," . $serial);
 $stmt = $conn->prepare("UPDATE fingerprintdevice SET fig_AttMaxID=?,fig_AttDownloadDate=? WHERE  fig_IP=? AND fig_SerialNo=?");
 $stmt->bind_param("ssss", $attmaxid,$createdate,$ip,$serial);
 $stmt->execute();
 //echo "UPDATE fingerprintdevice SET fig_AttMaxID='$attmaxid',fig_AttDownloadDate='$createdate' WHERE fig_IP='$ip' AND fig_SerialNo='$serial'";

}
 echo(implode('|',$arrid));
 } catch (Exception $e) {
  echo $e->getMessage();
 }
?>
