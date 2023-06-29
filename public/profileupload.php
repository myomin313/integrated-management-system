<?php
include("config.php");
header("Content-type: text/xml; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set("Asia/Yangon");
$arrid = array();
$ip="";
$serial="";
$profilemaxid="";
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
   $profilemaxid = trim($xml->usermaxid);
   $ip = trim($xml->ip);
   $serial = trim($xml->serialno);
   $conn = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
   $createdate = date("Y-m-d H:i:s");
   // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
   $stmt = $conn->prepare("INSERT INTO raw_profile ( pro_UserID, pro_UserName, pro_Ip, pro_Serial, pro_UploadedTime) VALUES (?, ?, ?, ?, ?)");
   foreach($xml->children() as $child) {
      if($child->getName() == "userprofile"){
    $id = $child->attributes();
        $stmt->bind_param("sssss", $child->userid,$child->name, $ip,$serial,$createdate);
        $stmt->execute();
        array_push($arrid,$id["id"]);
    }
}
 //echo($profilemaxid . "," . $ip . "," . $serial);
 $stmt = $conn->prepare("UPDATE fingerprintdevice SET fig_ProfileMaxID=?,fig_ProfileDownloadDate=? WHERE  fig_IP=? AND fig_SerialNo=?");
 $stmt->bind_param("ssss", $profilemaxid,$createdate, $ip, $serial);
 $stmt->execute();
}
  echo(implode('|',$arrid));

 } catch (Exception $e) {
  echo $e->getMessage();
 }
?>
