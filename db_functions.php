<?php
// Database configuration
$DB_HOST = 'localhost';
$DB_NAME = 'employee_db';
$DB_USER = 'root';
$DB_PASS = '';

function getDatabaseConnection() {
    try {
        global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS;
        $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    } catch (Exception $e) {
        throw new Exception("Database connection error: " . $e->getMessage());
    }
}

function getDepartments() {
    try {
        $conn = getDatabaseConnection();
        $query = "SELECT id, name FROM departments ORDER BY name";
        $result = $conn->query($query);
        
        $departments = [];
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
        
        $conn->close();
        return $departments;
    } catch (Exception $e) {
        throw new Exception("Error fetching departments: " . $e->getMessage());
    }
}

function addEmployee($name, $email, $department_id, $phone_number) {
    try {
        $conn = getDatabaseConnection();
        
        // Start transaction
        $conn->begin_transaction();
        
        // Insert into employees table with correct columns
        $stmt = $conn->prepare("INSERT INTO employees (name, email, department_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $email, $department_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error adding employee: " . $stmt->error);
        }
        
        $employee_id = $conn->insert_id;
        
        // Insert into contacts table with correct columns
        $stmt = $conn->prepare("INSERT INTO contacts (employee_id, phone_number) VALUES (?, ?)");
        $stmt->bind_param("is", $employee_id, $phone_number);
        
        if (!$stmt->execute()) {
            throw new Exception("Error adding contact details: " . $stmt->error);
        }
        
        // Commit transaction
        $conn->commit();
        
        $conn->close();
        return true;
        
    } catch (Exception $e) {
        if (isset($conn)) {
            $conn->rollback();
            $conn->close();
        }
        throw new Exception($e->getMessage());
    }
}

function getEmployeeDetails($employee_id) {
    try {
        $conn = getDatabaseConnection();
        
        // Corrected JOIN conditions
        $query = "SELECT e.*, d.name as department_name, c.phone_number 
                 FROM employees e 
                 JOIN departments d ON e.department_id = d.id 
                 JOIN contacts c ON e.id = c.employee_id 
                 WHERE e.id = ?";
                 
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();
        
        $conn->close();
        return $employee;
        
    } catch (Exception $e) {
        throw new Exception("Error fetching employee details: " . $e->getMessage());
    }
}