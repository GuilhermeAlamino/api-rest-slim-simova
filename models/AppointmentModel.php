<?php

namespace models;

use PDO;

class AppointmentModel
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getAppointments()
  {

    $stmt = $this->db->prepare("SELECT * FROM appointment");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    $countStmt = $this->db->prepare("SELECT COUNT(*) FROM appointment");
    $countStmt->execute();
    $count = $countStmt->fetchColumn();

    return ["data" => $results, "count" => $count];
  }

  public function getPreviousEndDate($id_employee, $start_date)
  {

    $stmt = $this->db->prepare("SELECT end_date FROM appointment WHERE id_employe = :id_employe AND start_date < :start_date ORDER BY start_date DESC LIMIT 1");
    $stmt->bindParam(':id_employe', $id_employee);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_OBJ);

    if ($result) {
      return $result->end_date;
    } else {
      return $start_date;
    }
  }

  public function createAppointment($start_date, $end_date, $id_employee, $description_work, $total_time)
  {

    $end_date = $this->getPreviousEndDate($id_employee, $start_date);
    $seq = $this->generateUniqueSeq($id_employee);

    $stmt = $this->db->prepare("INSERT INTO appointment (seq, start_date, end_date, id_employe, description_work, total_time) VALUES (:seq, :start_date, :end_date, :id_employe, :description_work, :total_time)");

    $stmt->bindParam(':seq', $seq);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->bindParam(':id_employe', $id_employee);
    $stmt->bindParam(':description_work', $description_work);
    $stmt->bindParam(':total_time', $total_time);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function generateUniqueSeq($id_employee)
  {
    $stmt = $this->db->prepare("SELECT MAX(seq) as max_seq FROM appointment WHERE id_employe = :id_employe");
    $stmt->bindParam(':id_employe', $id_employee);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_OBJ);

    if ($result && !empty($result->max_seq)) {
      return $result->max_seq + 1;
    } else {
      return 1;
    }
  }

  public function updateAppointment($id, $start_date, $enabled)
  {
    $stmt = $this->db->prepare("SELECT * FROM appointment WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $existingAppointment = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$existingAppointment) {
      return false;
    }

    $newEndDate = $this->calculateEndDate($start_date, $id);
    $totalTime = strtotime($start_date) - strtotime($newEndDate);

    $updateStmt = $this->db->prepare("UPDATE appointment SET start_date = :start_date, end_date = :end_date, enabled = :enabled, total_time = :total_time WHERE id = :id");
    $updateStmt->bindParam(':start_date', $start_date);
    $updateStmt->bindParam(':end_date', $newEndDate);
    $updateStmt->bindParam(':total_time', $totalTime);
    $updateStmt->bindParam(':enabled', $enabled);
    $updateStmt->bindParam(':id', $id);

    $updateStmt->execute();

    return $updateStmt->rowCount() > 0;
  }

  public function calculateEndDate($newStartDate, $id)
  {

    $stmt = $this->db->prepare("SELECT start_date FROM appointment WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_OBJ);

    if ($result) {
      $previousStartDate = $result->start_date;

      $newEndDate = $previousStartDate;
    } else {
      $newEndDate = $newStartDate;
    }

    return $newEndDate;
  }

  public function employeeExists($id_employee)
  {
    $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM employee WHERE id = :id_employee");
    $stmt->bindParam(':id_employee', $id_employee);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_OBJ);

    return ($result && $result->count > 0);
  }

  public function appointmentExists($id_appointment)
  {
    $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM appointment WHERE id = :id_appointment");
    $stmt->bindParam(':id_appointment', $id_appointment);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_OBJ);

    return ($result && $result->count > 0);
  }
}
