OpenAIBalance
This API allows you to check your OpenAI key balance. It returns the total amount, total used, and remaining balance of your OpenAI key.

API Endpoint
Deploy to workers in Cloudflare and use the POST method.

https://your-worker-name.your-subdomain.workers.dev/openaibalance

Request Format
The request should be in JSON format and should contain your OpenAI API key.

```json
{
"apikey": "sk-xxxxxxxxxxxxxxxxxxxxx"
}

Response Format
The response will be in JSON format and will contain the total amount, total used, and remaining balance of your OpenAI key.

```json
{
"total_amount": "120.00",
"total_used": "0.01",
"remaining": "119.99"
}

Feel free to use this API in your projects or integrate it into your applications.
