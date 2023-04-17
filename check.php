<?php
header('Content-Type: application/json');

function checkBilling($apiKey, $apiUrl) {
    // 计算起始日期和结束日期
    $now = new DateTime();
    $startDate = (new DateTime())->sub(new DateInterval('P90D'));
    $endDate = (new DateTime())->add(new DateInterval('P1D'));
    $subDate = (new DateTime())->setDate($now->format('Y'), $now->format('m'), 1);

    // 设置API请求URL和请求头
    $urlSubscription = "{$apiUrl}/v1/dashboard/billing/subscription"; // 查是否订阅
    $urlBalance = "{$apiUrl}/dashboard/billing/credit_grants"; // 查普通账单
    $urlUsage = "{$apiUrl}/v1/dashboard/billing/usage?start_date={$startDate->format('Y-m-d')}&end_date={$endDate->format('Y-m-d')}"; // 查使用量
    $headers = array(
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json'
    );

    try {
        // 获取API限额
        $subscriptionData = json_decode(file_get_contents($urlSubscription, false, stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => implode("\r\n", $headers)
            )
        ))), true);
        $totalAmount = $subscriptionData['hard_limit_usd'];

        // 判断总用量是否大于20，若大于则更新startDate为subDate
        if ($totalAmount > 20) {
            $startDate = $subDate;
        }

        // 重新生成urlUsage
        $urlUsage = "{$apiUrl}/v1/dashboard/billing/usage?start_date={$startDate->format('Y-m-d')}&end_date={$endDate->format('Y-m-d')}";

        // 获取已使用量
        $usageData = json_decode(file_get_contents($urlUsage, false, stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => implode("\r\n", $headers)
            )
        ))), true);
        $totalUsage = $usageData['total_usage'] / 100;

        // 计算剩余额度
        $remaining = $totalAmount - $totalUsage;

        // 输出总用量、总额及余额信息
        return array($totalAmount, $totalUsage, $remaining);
    } catch (Exception $e) {
        return array(null, null, null);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = json_decode(file_get_contents('php://input'), true);
    $apiKey = isset($postData['apikey']) ? trim($postData['apikey']) : '';

    if (empty($apiKey)) {
        http_response_code(400);
        echo json_encode(array('error' => 'API Key is missing'));
        exit();
    }

    // 设置默认API链接
    $apiUrl = 'https://vercel.askopenai.tech';
    if (isset($postData['apiurl']) && !empty($postData['apiurl'])) {
        $apiUrl = trim($postData['apiurl']);
        if (!preg_match('/^https?:\/\//', $apiUrl)) {
            $apiUrl = 'https://' . $apiUrl;
        }
    }

    $data = checkBilling($apiKey, $apiUrl);
    if (empty($data[0])
