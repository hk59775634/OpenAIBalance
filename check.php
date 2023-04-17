<?php
  $request_body = file_get_contents('php://input');
  $apikey = json_decode($request_body)->apikey;

  if (!$apikey) {
    http_response_code(400);
    echo "请提供 API KEY";
    exit();
  }

  $apiUrl = "https://api.openai.com";
  $urlSubscription = "{$apiUrl}/v1/dashboard/billing/subscription";
  $now = new DateTime();
  $startDate = new DateTime("-90 days");
  $endDate = (new DateTime())->add(new DateInterval("P1D"));
  $subDate = new DateTime();
  $subDate->setDate($now->format("Y"), $now->format("m"), 1);

  $headers = [
    "Authorization" => "Bearer {$apikey}",
    "Content-Type" => "application/json",
  ];

  $response = file_get_contents($urlSubscription, false, stream_context_create([
    "http" => [
      "method" => "GET",
      "header" => implode("\r\n", array_map(function($key, $value) {
        return "{$key}: {$value}";
      }, array_keys($headers), $headers))
    ]
  ]));

  if (!$response) {
    http_response_code(400);
    echo "您的账户已被封禁，请登录OpenAI进行查看。";
    exit();
  }

  $subscriptionData = json_decode($response);
  $totalAmount = $subscriptionData->hard_limit_usd;

  if ($totalAmount > 20) {
    $startDate = $subDate;
  }

  $urlUsage = "{$apiUrl}/v1/dashboard/billing/usage?start_date={$startDate->format("Y-m-d")}&end_date={$endDate->format("Y-m-d")}";

  $response = file_get_contents($urlUsage, false, stream_context_create([
    "http" => [
      "method" => "GET",
      "header" => implode("\r\n", array_map(function($key, $value) {
        return "{$key}: {$value}";
      }, array_keys($headers), $headers))
    ]
  ]));

  $usageData = json_decode($response);
  $totalUsage = $usageData->total_usage / 100;
  $remaining = $totalAmount - $totalUsage;

  $response_body = json_encode([
    "total_amount" => number_format($totalAmount, 2),
    "total_used" => number_format($totalUsage, 2),
    "remaining" => number_format($remaining, 2)
  ]);

  header("Content-Type: application/json");
  echo $response_body;
