<?php

namespace controllers;

use DB;
use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use helpers\Helper;
use models\AppointmentModel;

class AppointmentController
{

  public function index(Request $request, Response $response)
  {
    $db = new DB();
    $conn = $db->connect();
    $appointmentModel = new AppointmentModel($conn);

    $results = $appointmentModel->getAppointments();
    $data = $results["data"];
    $count = $results["count"];

    $responseData = [
      "result" => $data,
      "total" => $count,
    ];

    return Helper::jsonResponse($response, "success", $responseData, 200);
  }

  public function store(Request $request, Response $response)
  {
    $appointment = $request->getParsedBody();

    $start_date = $appointment['start_date'] ?? null;
    $id_employe = $appointment['id_employe'] ?? null;
    $description_work = $appointment['description_work'] ?? null;

    if (empty($start_date) || empty($id_employe) || empty($description_work)) {
      return Helper::jsonResponse($response, "error", "Campos obrigatórios", 400);
    }

    $db = new DB();
    $conn = $db->connect();
    $appointmentModel = new AppointmentModel($conn);

    if (!$appointmentModel->employeeExists($id_employe)) {
      return Helper::jsonResponse($response, "error", "Funcionário não encontrado", 404);
    }

    $previousEndDate = $appointmentModel->getPreviousEndDate($id_employe, $start_date);

    $totalTime = strtotime($start_date) - strtotime($previousEndDate);

    $success = $appointmentModel->createAppointment($start_date, $previousEndDate, $id_employe, $description_work, $totalTime);

    if ($success) {
      return Helper::jsonResponse($response, "success", "Criação realizada com sucesso", 200);
    } else {
      return Helper::jsonResponse($response, "error", "Falha na inserção", 500);
    }
  }

  public function update(Request $request, Response $response, $args)
  {
    $id = $args['id'];

    $data = $request->getParsedBody();

    if (empty($data['start_date']) || empty($data['enabled'])) {
      return Helper::jsonResponse($response, "error", "Campos obrigatórios", 400);
    }

    $start_date = $data['start_date'];
    $enabled = $data['enabled'];

    $db = new DB();
    $conn = $db->connect();
    $appointmentModel = new AppointmentModel($conn);

    if (!$appointmentModel->appointmentExists($id)) {
      return Helper::jsonResponse($response, "error", "Apontamento não encontrado", 404);
    }

    $success = $appointmentModel->updateAppointment($id, $start_date, $enabled);

    if ($success) {
      return Helper::jsonResponse($response, "success", "Registro atualizado com sucesso", 200);
    } else {
      return Helper::jsonResponse($response, "error", "Falha na atualização do registro", 500);
    }
  }
}
