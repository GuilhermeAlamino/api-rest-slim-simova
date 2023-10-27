<?php

namespace helpers;

class Helper
{

  public static function jsonResponse($response, $status, $message, $statusCode)
  {
    $response->getBody()->write(json_encode(["status" => $status, "message" => $message]));
    return $response->withHeader('content-type', 'application/json')->withStatus($statusCode);
  }
}
