<?php
header("Content-type: text; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
fncConnectDB();

function fncConnectDB(){
		include("config.php");
		$conn = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
   		$data = json_decode( trim(file_get_contents('php://input')), TRUE);
		if(isset($data))
		{

			switch ($data["mode"]) {
				case "GETINFO":
				   fncGetInfo($conn,$data);
				break;
				case "UPDATEPROFILEINFO":
				fncUpdateProfileInfo($conn,$data);
				break;
				case "DELETEATTINFO":
				fncDeleteAttInfo($conn,$data);
				break;
				case "UPDATESERIAL":
				fncUpdateSerial($conn,$data);
				break;
				case "UPLOADPROFILETODEVICE":
				fncUploadProfileToDevice($conn,$data);
				break;
				case "UPDATEPROFILEDEVICESTATUS":
				fncUpdateProfileDeviceStatus($conn,$data);
				break;
				default:
				echo "Your favorite color is neither red, blue, nor green!";
				}
		}
		
	}

function fncGetInfo($conn,$data){
		$date=date("Y/m/d");
		//$arrTimer=array();
		$timer = 1;
		$arrIp =array();
		$arrPortNo =array();
		$arrPostApi=array();
		$arrAttMaxID=array();
		$arrAttDeleteEveryDate=array();
		$arrAttDeletedDate=array();
		$arrAttLinePerPage=array();
		$arrProfileDownload=array();
		$arrProfileMaxID=array();
		$arrProfileNumPerPage=array();
		$arrProfileUploadToDevice = array();

		if($conn->connect_error){
		die("Connection Failed: " . $conn->connect_error);
		}
		$computerName = $data["pcname"];
		//echo $computerName;
		$sql="SELECT * FROM fingerprintdevice WHERE fig_PCName=?" ;
		$stmt= $conn->prepare($sql);
		$stmt->bind_param("s",$computerName);
		$stmt->execute();
		$stmt->store_result();
        $row=bindAll($stmt);
		//$result=$stmt->get_result();
		if($stmt->affected_rows > 0){
			while($stmt->fetch()){
				//array_push($arrTimer,$row["fig_TimerMinutes"]);
				$timer = $row["fig_TimerMinutes"];
				array_push($arrIp,$row["fig_IP"]);
				array_push($arrPortNo,$row["fig_PortNo"]);
				array_push($arrAttMaxID,$row["fig_AttMaxID"]);
				array_push($arrAttDeleteEveryDate,$row["fig_AttDeleteDayBefore"]);
				array_push($arrAttDeletedDate,$row["fig_AttDeletedDate"]);
				array_push($arrAttLinePerPage,$row["fig_AttLinePerPage"]);
				array_push($arrProfileDownload,$row["fig_ProfileDownload"]);
				array_push($arrProfileMaxID,$row["fig_ProfileMaxID"]);
				array_push($arrProfileNumPerPage,$row["fig_ProfileLinePerPage"]);
				array_push($arrProfileUploadToDevice ,$row["fig_ProfileUploadDevice"]);
			}
			//echo "timer=" . implode(',',$arrTimer) . "|";
			echo "timer=" . $timer . "|";
			echo "ip=" . implode(',',$arrIp). "|";
			echo "attlogdeleteeverydate=" . implode(',',$arrAttDeleteEveryDate). "|";
			echo "attlogdeleteddate=" . implode(',',$arrAttDeletedDate). "|";
			echo "attlogmaxid=" . implode(',',$arrAttMaxID). "|";
			echo "attlogline=" . implode(',',$arrAttLinePerPage). "|";
			echo "port=" . implode(',',$arrPortNo). "|";
			echo "profiledownload=" . implode(',',$arrProfileDownload). "|";
			echo "profileuploaddevice=" . implode(',',$arrProfileUploadToDevice). "|";
			echo "profilemaxid=" . implode(',',$arrProfileMaxID). "|";
			echo "profileline=" . implode(',',$arrProfileNumPerPage). "|";
			echo "attapiurl=http://127.0.0.1/example-app/public/attupload.php" . "|";
			echo "profileapiurl=http://127.0.0.1/example-app/public/profileupload.php";
		}else{
			echo "NO DATA";
		}
}

function fncUpdateProfileInfo($conn,$data){
 $date=date("Y-m-d H:i:s");
 $status = 0;
 $stmt = $conn->prepare("UPDATE fingerprintdevice SET fig_ProfileDownload=?,fig_ProfileDownloadDate=? WHERE 	fig_IP=? AND fig_SerialNo=?");
 $stmt->bind_param("ssss", $status,$date,$data["ip"],$data["serial"]);
 $stmt->execute();
 echo("ok");
}

function fncDeleteAttInfo($conn,$data){
 $date=date("Y-m-d H:i:s");
 $status = 0;
 $stmt = $conn->prepare("UPDATE fingerprintdevice SET fig_AttDeletedDate=?,fig_AttMaxID=0 WHERE	fig_IP=? AND fig_SerialNo=?");
 $stmt->bind_param("sss",$date,$data["ip"],$data["serial"]);
 $stmt->execute();
 echo("ok");
}

function fncUpdateSerial($conn,$data){
		$sql="SELECT * FROM fingerprintdevice WHERE fig_IP=?" ;
		$stmt= $conn->prepare($sql);
		$stmt->bind_param("s",$data["ip"]);
		$stmt->execute();
		$stmt->store_result();
        $row=bindAll($stmt);
        if($stmt->affected_rows > 0){
			while($stmt->fetch()){
				if($row["fig_SerialNo"] == ""){
					$stmt = $conn->prepare("UPDATE fingerprintdevice SET fig_SerialNo=? WHERE fig_IP=? ");
					$stmt->bind_param("ss", $data["serial"],$data["ip"]);
					$stmt->execute();
				}
			}

        }
        echo("ok");
}

function fncUploadProfileToDevice($conn,$data){
	$xml = new SimpleXMLElement('<xml/>');
	$ip = $data["ip"];
	$sql="SELECT * FROM employee LEFT JOIN (SELECT * FROM raw_profile WHERE pro_Ip=?)prof ON userid= pro_id WHERE pro_id IS NULL" ;
	$stmt= $conn->prepare($sql);
	$stmt->bind_param("s",$ip);
	$stmt->execute();
	$stmt->store_result();
	$row=bindAll($stmt);
	if($stmt->affected_rows > 0){
		$xml = new SimpleXMLElement('<xml/>');
		$xml->addChild('ip',$ip);
		while($stmt->fetch()){
			$profile = $xml->addChild('profile');
			$profile->addChild('userid', $row["UserID"]);
			$profile->addChild('name',  $row["EmployeeName"]);
		}
	Header('Content-type: text/xml');
	echo($xml->asXML());
	}
	else{
		echo("");
	}
}

function fncUpdateProfileDeviceStatus($conn,$data){
	$date=date("Y-m-d H:i:s");
	$status = 0;
	$stmt = $conn->prepare("UPDATE fingerprintdevice SET fig_ProfileUploadDevice=?,fig_ProfileUploadDate=? WHERE 	fig_IP=? AND fig_SerialNo=?");
	$stmt->bind_param("ssss", $status,$date,$data["ip"],$data["serial"]);
	$stmt->execute();
	echo("ok");
   }

function bindAll($stmt) {
  $meta = $stmt->result_metadata();
  $fields = array();
  $fieldRefs = array();
  while ($field = $meta->fetch_field())
  {
      $fields[$field->name] = "";
      $fieldRefs[] = &$fields[$field->name];
  }

  call_user_func_array(array($stmt, 'bind_result'), $fieldRefs);
  $stmt->store_result();
  //var_dump($fields);
  return $fields;
}


?>
