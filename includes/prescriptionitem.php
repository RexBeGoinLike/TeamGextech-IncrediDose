<?php
include("db.php");

class PrescriptionItem implements JsonSerializable {
    private $prescriptionitemid;
    private $prescriptionid;
    private $name;
    private $brand;
    private $quantity;
    private $dosage;
    private $frequency;
    private $description;
    private $substitutions;

    public function __construct($prescriptionitemid, $prescriptionid, $name, $brand, $quantity, $dosage, $frequency, $description, $substitutions) {
        $this->prescriptionitemid = $prescriptionitemid;
        $this->prescriptionid = $prescriptionid;
        $this->name = $name;
        $this->brand = $brand;
        $this->quantity = $quantity;
        $this->dosage = $dosage;
        $this->frequency = $frequency;
        $this->description = $description;
        $this->substitutions = $substitutions;
    }

    public function get_prescriptionitemid() {
        return $this->prescriptionitemid;
    }

    public function get_prescriptionid() {
        return $this->prescriptionid;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_brand() {
        return $this->brand;
    }

    public function get_quantity() {
        return $this->quantity;
    }

    public function get_dosage() {
        return $this->dosage;
    }

    public function get_frequency() {
        return $this->frequency;
    }

    public function get_description() {
        return $this->description;
    }

    public function get_substitutions() {
        return $this->substitutions;
    }

    public function jsonSerialize(): array {
        return [
            'prescriptionitemid' => $this->prescriptionitemid,
            'prescriptionid' => $this->prescriptionid,
            'name' => $this->name,
            'brand' => $this->brand,
            'quantity' => $this->quantity,
            'dosage' => $this->dosage,
            'frequency' => $this->frequency,
            'description' => $this->description,
            'substitutions' => $this->substitutions
        ];
    }
}

function addPrescriptionItem($prescriptionid, $name, $brand, $quantity, $dosage, $frequency, $description, $substitutions) {
    global $db;
    
    try {
        $query = "INSERT INTO PRESCRIPTIONITEM (prescriptionid, name, brand, quantity, dosage, frequency, description, substitutions) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("issisisi", $prescriptionid, $name, $brand, $quantity, $dosage, $frequency, $description, $substitutions);
        
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Prescription item added successfully',
                'prescriptionitem_id' => $stmt->insert_id
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to add prescription item: ' . $stmt->error
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
    
    $prescriptionid = $input['prescriptionid'] ?? '';
    $name = $input['name'] ?? '';
    $brand = $input['brand'] ?? '';
    $quantity = $input['quantity'] ?? '';
    $dosage = $input['dosage'] ?? '';
    $frequency = $input['frequency'] ?? '';
    $description = $input['description'] ?? '';
    $substitutions = $input['substitutions'] ?? '';
    
    $result = addPrescriptionItem($prescriptionid, $name, $brand, $quantity, $dosage, $frequency, $description, $substitutions);
    echo json_encode($result);

} else {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        $prescriptionid = isset($_GET['prescriptionid']) && $_GET['prescriptionid'] !== '' ? (int)$_GET['prescriptionid'] : null;
        $name = isset($_GET['name']) && $_GET['name'] !== '' ? $_GET['name'] : null;
        $brand = isset($_GET['brand']) && $_GET['brand'] !== '' ? $_GET['brand'] : null;
        $substitutions = isset($_GET['substitutions']) && $_GET['substitutions'] !== '' ? (int)$_GET['substitutions'] : null;

        $allowed_sort = ['prescriptionitemid', 'prescriptionid', 'name', 'brand', 'quantity', 'dosage', 'frequency', 'substitutions'];
        $sort_by = isset($_GET['sort_by']) && in_array($_GET['sort_by'], $allowed_sort) ? $_GET['sort_by'] : 'prescriptionitemid';
        $sort_dir = (isset($_GET['sort_dir']) && strtolower($_GET['sort_dir']) === 'asc') ? 'ASC' : 'DESC';

        $conditions = [];
        $params = [];     
        $types = '';      

        if ($search !== null && $search !== '') {
            $conditions[] = "(CAST(prescriptionitemid AS CHAR) LIKE ? OR name LIKE ? OR brand LIKE ? OR description LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
            $types .= 'ssss';
        }

        if ($prescriptionid !== null) {
            $conditions[] = "prescriptionid = ?";
            $params[] = $prescriptionid;
            $types .= 'i';
        }

        if ($name !== null) {
            $conditions[] = "name LIKE ?";
            $params[] = '%' . $name . '%';
            $types .= 's';
        }

        if ($brand !== null) {
            $conditions[] = "brand LIKE ?";
            $params[] = '%' . $brand . '%';
            $types .= 's';
        }

        if ($substitutions !== null) {
            $conditions[] = "substitutions = ?";
            $params[] = $substitutions;
            $types .= 'i';
        }

        $where = '';
        if (!empty($conditions)) {
            $where = ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql = "SELECT prescriptionitemid, prescriptionid, name, brand, quantity, dosage, frequency, description, substitutions
                FROM PRESCRIPTIONITEM
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

        $stmt->execute();
        $stmt->bind_result($prescriptionitemid, $prescriptionidRes, $nameRes, $brandRes, $quantityRes, $dosageRes, $frequencyRes, $descriptionRes, $substitutionsRes);

        $prescriptionitems = [];
        while ($stmt->fetch()) {
            $prescriptionitems[] = [
                'prescriptionitemid' => $prescriptionitemid,
                'prescriptionid' => $prescriptionidRes,
                'name' => $nameRes,
                'brand' => $brandRes,
                'quantity' => $quantityRes,
                'dosage' => $dosageRes,
                'frequency' => $frequencyRes,
                'description' => $descriptionRes,
                'substitutions' => $substitutionsRes
            ];
        }
        $stmt->close();

       
        header('Content-Type: application/json');
        echo json_encode([
            'data' => $prescriptionitems,
            'count' => count($prescriptionitems)
        ]);
        exit;
    }
}
?>