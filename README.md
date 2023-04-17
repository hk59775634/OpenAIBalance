OpenAIBalance
This API allows you to check your OpenAI key balance. It returns the total amount, total used, and remaining balance of your OpenAI key.

API Endpoint
Deploy to workers in Cloudflare and use the POST method.

https://your-worker-name.your-subdomain.workers.dev/

Request Format
The request should be in JSON format and should contain your OpenAI API key.


```json
    {
        "apikey": "sk-xxxxxxxxxxxxxxxxxxxxx"
    }
```


Response Format
The response will be in JSON format and will contain the total amount, total used, and remaining balance of your OpenAI key.


```json
    {
        "total_amount": "120.00",
        "total_used": "0.01",
        "remaining": "119.99"
    }
```


Feel free to use this API in your projects or integrate it into your applications.



OpenAI余额查询

此API允许您检查OpenAI密钥余额。它返回OpenAI密钥的总金额、总使用量和剩余余额。

部署API

部署到Cloudflare中的workers，并使用POST方法。
https://your-worker-name.your-subdomain.workers.dev/


请求格式

请求应该是JSON格式，并且应该包含您的OpenAI API密钥。



```json

{

“apikey”：“sk-xxxxxxxxxxxxxxxxx”

}

```


响应格式

响应将采用JSON格式，并包含OpenAI密钥的总量、使用总量和剩余余额。


```json
    {
        "total_amount": "120.00",
        "total_used": "0.01",
        "remaining": "119.99"
    }
```

请随意在您的项目中使用此API或将其集成到您的应用程序中。
