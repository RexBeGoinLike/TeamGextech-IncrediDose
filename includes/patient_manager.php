<?php
include("db.php");

function getPatients($doctorid) {
	global $db;
    $stmt = $db->prepare("SELECT * from User INNER JOIN Prescription ON User.userid=Prescription.patientid 
    	WHERE Prescription.doctorid = ?");
    $stmt->execute([$doctorid]);
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
    	$data[] = $row;
	}
	return $data;
}

function getPatientById($doctorid, $patientname) {
	global $db;
	$stmt = $db->prepare("SELECT * from User INNER JOIN Prescription ON User.userid=Prescription.patientid WHERE Prescription.doctorid = ? AND (firstname = ? OR lastname = ?)");
    $stmt->execute([$doctorid, $patientname, $patientname]);
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
    	$data[] = $row;
	}
	return $data;
}


$action = $_GET['action'];

switch ($action) {
	case "getPatients":
		$doctorid = $_GET['doctorid'];
		header('Content-Type: application/json');
		echo json_encode(getPatients($doctorid));
		break;

	case "getPatientByName":
		$doctorid = $_GET['doctorid'];
		$patientname = $_GET['patientname'];
		header('Content-Type: application/json');
		echo json_encode(getPatientById($doctorid, $patientname));
		break;
}
?>