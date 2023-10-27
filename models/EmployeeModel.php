<?php

namespace models;

use PDO;

class EmployeeModel
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getEmployees()
  {
    $stmt = $this->db->prepare("SELECT * FROM employee");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function isCodeExists($code)
  {
    $checkStmt = $this->db->prepare("SELECT COUNT(*) FROM employee WHERE code = :code");
    $checkStmt->bindParam(':code', $code);
    $checkStmt->execute();
    return $checkStmt->fetchColumn() > 0;
  }

  public function createEmployee($name, $code)
  {
    $stmt = $this->db->prepare("INSERT INTO employee (name, code) VALUES (:name, :code)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':code', $code);
    $stmt->execute();
    return $stmt->rowCount() > 0;
  }

  public function updateEmployee($id, $name)
  {
    $stmt = $this->db->prepare("SELECT * FROM employee WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $existingEmployee = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$existingEmployee) {
      return false;
    }

    $updateStmt = $this->db->prepare("UPDATE employee SET name = :name WHERE id = :id");
    $updateStmt->bindParam(':name', $name);
    $updateStmt->bindParam(':id', $id);

    $updateStmt->execute();

    return $updateStmt->rowCount() > 0;
  }

  public function employeeExists($id_employee)
  {
    $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM employee WHERE id = :id_employee");
    $stmt->bindParam(':id_employee', $id_employee);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_OBJ);

    return ($result && $result->count > 0);
  }
}
