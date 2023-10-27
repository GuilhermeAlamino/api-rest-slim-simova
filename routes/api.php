<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use controllers\AppointmentController;
use controllers\EmployeeController;

$app = AppFactory::create();

$app->get('/api/employee', [EmployeeController::class, 'index']);

$app->post('/api/employee/store', [EmployeeController::class, 'store']);

$app->put('/api/employee/edit/{id}', [EmployeeController::class, 'update']);

$app->get('/api/appointment', [AppointmentController::class, 'index']);

$app->post('/api/appointment/store', [AppointmentController::class, 'store']);

$app->put('/api/appointment/edit/{id}', [AppointmentController::class, 'update']);
