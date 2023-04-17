<?php
// 从请求中获取 API KEY
$key = $_POST['apikey'];
if (!$key) {
    http_response_code(400);
    echo '请提供 API KEY';
    return;
}

// 计算起始日期和结束日期
$now = new DateTime();
$startDate = (new DateTime())->sub(new DateInterval('P90D'));
$endDate = (new DateTime())->add(new DateInterval('P1D'));
$subDate = new DateTime();
$subDate->setDate($now->format('Y'), $now->format('m'), 1);

// 设置 API 请求 URL 和请求头
$urlSubscription = 'https://api.openai.com/v1/dashboard/billing/subscription';
$urlUsage = 'https://api.openai.com/v1/dashboard/billing/usage?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d');
$headers = [
    'Authorization' => 'Bearer ' . $key,
    'Content-Type' => 'application/json'
];

// 获取 API 限额
$subscriptionData = json_decode(file_get_contents($urlSubscription, false, stream_context_create(['http' => ['header' => $headers]])));
if (!$subscriptionData) {
    http_response_code(401);
    echo 'API KEY 无效';
    return;
}
$totalAmount = $subscriptionData->hard_limit_usd;

// 判断总用量是否大于20，若大于则更新 startDate 为 subDate
if ($totalAmount > 20) {
    $startDate = $subDate;
    $urlUsage = 'https://api.openai.com/v1/dashboard/billing/usage?start_date=' . $startDate->format('Y-m-d') . '&end_date=' . $endDate->format('Y-m-d');
}

// 获取已使用量
$usageData = json_decode(file_get_contents($urlUsage, false, stream_context_create(['http' => ['header' => $headers]])));
$totalUsage = $usageData->total_usage / 100;

// 计算剩余额度
$remaining = $totalAmount - $totalUsage;

// 输出总用量、总额及余额信息
echo "Total Amount: " . number_format($totalAmount, 2) . "<br>";
echo "Used: " . number_format($totalUsage, 2) . "<br>";
echo "Remaining: " . number_format($remaining, 2) . "<br>";
