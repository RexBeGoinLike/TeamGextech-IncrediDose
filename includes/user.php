<?php
include("db.php");

class User implements JsonSerializable {
    private $userid;
    private $firstname;
    private $lastname;
    private $contactnum;
    private $email;
    private $password;
    private $birthdate;
    private $createdat;
    private $gender;
    private $role;

    public function __construct($userid, $firstname, $lastname, $contactnum, $email, $password, $birthdate, $createdat, $gender, $role) {
        $this->userid = $userid;       
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->contactnum = $contactnum;
        $this->email = $email;
        $this->password = $password;
        $this->birthdate = $birthdate;
        $this->createdat = $createdat;
        $this->gender = $gender;
        $this->role = $role;
    }

    public function getUserid()
    {
        return $this->userid;
    }

    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getContactnum()
    {
        return $this->contactnum;
    }

    public function setContactnum($contactnum)
    {
        $this->contactnum = $contactnum;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getCreatedat()
    {
        return $this->createdat;
    }

    public function setCreatedat($createdat)
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getUserid()
    {
        return $this->userid;
    }

    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }



    public function jsonSerialize(): array {
        return [
            'prescriptionid' => $this->prescriptionid,

        ];
    }
}


function addPrescription($dateprescribed, $validperiod, $patientid, $doctorid) {
    global $db;
    
    try {
        $query = "INSERT INTO PRESCRIPTION (dateprescribed, validperiod, patientid, doctorid) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ssii", $dateprescribed, $validperiod, $patientid, $doctorid);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Prescription added successfully',
                'prescription_id' => $stmt->insert_id
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to add prescription: ' . $stmt->error
            ];
        }
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $dateprescribed = $input['dateprescribed'] ?? '';
    $validperiod = $input['validperiod'] ?? '';
    $patientid = $input['patientid'] ?? '';
    $doctorid = $input['doctorid'] ?? '';
    
    $result = addPrescription($dateprescribed, $validperiod, $patientid, $doctorid);
    echo json_encode($result);
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    $search = isset($_GET['search']) ? trim($_GET['search']) : null;
    $patientid = isset($_GET['patientid']) && $_GET['patientid'] !== '' ? (int)$_GET['patientid'] : null;
    $doctorid  = isset($_GET['doctorid'])  && $_GET['doctorid'] !== ''  ? (int)$_GET['doctorid']  : null;
    $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : null;
    $date_to   = isset($_GET['date_to'])   ? $_GET['date_to']   : null;

    // Sorting 
    $allowed_sort = ['prescriptionid','dateprescribed','validperiod','patientid','doctorid'];
    $sort_by = isset($_GET['sort_by']) && in_array($_GET['sort_by'], $allowed_sort) ? $_GET['sort_by'] : 'dateprescribed';
    $sort_dir = (isset($_GET['sort_dir']) && strtolower($_GET['sort_dir']) === 'asc') ? 'ASC' : 'DESC';

    $conditions = [];
    $params = [];     // values
    $types = '';      // mysqli bind types

    if ($search !== null && $search !== '') {
        $conditions[] = "(CAST(prescriptionid AS CHAR) LIKE ? OR dateprescribed LIKE ?)";
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
        $types .= 'ss';
    }

    if ($patientid !== null) {
        $conditions[] = "patientid = ?";
        $params[] = $patientid;
        $types .= 'i';
    }

    if ($doctorid !== null) {
        $conditions[] = "doctorid = ?";
        $params[] = $doctorid;
        $types .= 'i';
    }

    if ($date_from !== null && $date_to !== null) {
        $conditions[] = "dateprescribed BETWEEN ? AND ?";
        $params[] = $date_from;
        $params[] = $date_to;
        $types .= 'ss';
    } else if ($date_from !== null) {
        $conditions[] = "dateprescribed >= ?";
        $params[] = $date_from;
        $types .= 's';
    } else if ($date_to !== null) {
        $conditions[] = "dateprescribed <= ?";
        $params[] = $date_to;
        $types .= 's';
    }

    $where = '';
    if (!empty($conditions)) {
        $where = ' WHERE ' . implode(' AND ', $conditions);
    }

    // Final SELECT with ORDER BY 
    $sql = "SELECT prescriptionid, dateprescribed, validperiod, patientid, doctorid
            FROM PRESCRIPTION
            $where
            ORDER BY $sort_by $sort_dir";

    $stmt = $db->prepare($sql);

    
    if ($types !== '') {
        $bind_names = [];
        $bind_names[] = &$types;
        for ($i = 0; $i < count($params); $i++) {
            $bind_names[] = &$params[$i];
        }
        call_user_func_array([$stmt, 'bind_param'], $bind_names);
    }

    // Fetch
    $stmt->execute();
    $stmt->bind_result($prescriptionid, $dateprescribed, $validperiod, $patientidRes, $doctoridRes);

    $prescriptions = [];
    while ($stmt->fetch()) {
        $prescriptions[] = [
            'prescriptionid' => $prescriptionid,
            'dateprescribed' => $dateprescribed,
            'validperiod' => $validperiod,
            'patientid' => $patientidRes,
            'doctorid' => $doctoridRes
        ];
    }
    $stmt->close();

   
    header('Content-Type: application/json');
    echo json_encode([
        'data' => $prescriptions,
        'count' => count($prescriptions)
    ]);
    exit;
}

?>