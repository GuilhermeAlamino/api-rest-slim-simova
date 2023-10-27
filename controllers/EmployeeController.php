<?php

namespace controllers;

use DB;
use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use helpers\Helper;
use models\EmployeeModel;

class EmployeeController
{

  public function index(Request $request, Response $response)
  {
    $db = new DB();
    $conn = $db->connect();
    $employeeModel = new EmployeeModel($conn);

    $results = $employeeModel->getEmployees();

    return Helper::jsonResponse($response, "sucess", ["result" => $results], 200);
  }

  public function store(Request $request, Response $response)
  {
    $employee = $request->getParsedBody();

    $name = $employee['name'] ?? null;
    $code = $employee['code'] ?? null;

    if (empty($name) || empty($code)) {
      return Helper::jsonResponse($response, "error", "Campos obrigatórios", 400);
    }

    $db = new DB();
    $conn = $db->connect();
    $employeeModel = new EmployeeModel($conn);

    if ($employeeModel->isCodeExists($code)) {
      return Helper::jsonResponse($response, "error", "Código já existe", 400);
    }

    if ($employeeModel->createEmployee($name, $code)) {
      return Helper::jsonResponse($response, "success", "Criação realizada com sucesso", 200);
    } else {
      return Helper::jsonResponse($response, "error", "Falha na inserção", 500);
    }
  }

  public function update(Request $request, Response $response, $args)
  {
    $id = $args['id'];

    $data = $request->getParsedBody();

    if (empty($data['name'])) {
      return Helper::jsonResponse($response, "error", "Campos obrigatórios", 400);
    }

    $name = $data['name'];

    $db = new DB();
    $conn = $db->connect();
    $employeeModel = new EmployeeModel($conn);
    
    if (!$employeeModel->employeeExists($id)) {
      return Helper::jsonResponse($response, "error", "Funcionário não encontrado", 404);
    }

    $success = $employeeModel->updateEmployee($id, $name);

    if ($success) {
      return Helper::jsonResponse($response, "success", "Registro atualizado com sucesso", 200);
    } else {
      return Helper::jsonResponse($response, "error", "Falha na atualização do registro", 500);
    }
  }
}
