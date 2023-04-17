import json
from datetime import datetime, timedelta
import urllib.request
import urllib.parse

def check_key(key):
    apiUrl = "https://api.openai.com"
    urlSubscription = f"{apiUrl}/v1/dashboard/billing/subscription"
    headers = {
        "Authorization": f"Bearer {key}",
        "Content-Type": "application/json",
    }

    req_subscription = urllib.request.Request(urlSubscription, headers=headers, method='GET')
    with urllib.request.urlopen(req_subscription) as response:
        if response.status != 200:
            return {"error": "您的账户已被封禁，请登录OpenAI进行查看。"}
        subscriptionData = json.loads(response.read().decode())
        totalAmount = subscriptionData['hard_limit_usd']

    now = datetime.now()
    startDate = (now - timedelta(days=90)).strftime('%Y-%m-%d')
    endDate = (now + timedelta(days=1)).strftime('%Y-%m-%d')
    subDate = now.replace(day=1).strftime('%Y-%m-%d')
    if totalAmount > 20:
        startDate = subDate

    urlUsage = f"{apiUrl}/v1/dashboard/billing/usage?start_date={startDate}&end_date={endDate}"
    req_usage = urllib.request.Request(urlUsage, headers=headers, method='GET')
    with urllib.request.urlopen(req_usage) as response:
        usageData = json.loads(response.read().decode())
        totalUsage = usageData['total_usage'] / 100
        remaining = totalAmount - totalUsage

    response_body = json.dumps({
        "total_amount": format(totalAmount, '.2f'),
        "total_used": format(totalUsage, '.2f'),
        "remaining": format(remaining, '.2f')
    })

    return json.loads(response_body)

result = check_key("sk-xxxxxxxx......")
print(result)
