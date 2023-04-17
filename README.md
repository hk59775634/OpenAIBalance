OpenAI 余额查询
这是一个使用 OpenAI API KEY 查询余额的简单示例。它使用 API 来获取 OpenAI 总量限制，已使用量。计算得来余额信息，并返回总余额、已使用金额和剩余金额等数据。

部署方法
将代码 clone 到Cloudflare的workers中发布：

调用方法
GET or POST
使用与访问https://api.openai.com一样的方法。即head中包含Authorization 和正确的key即可。

注意事项
该方法来自ChatGPT的回复。如果您人为存在bug或其他问题。可以自行去ChatGPT获得类似的代码。

作者
作者：TOM

GitHub：https://github.com/hk59775634/OpenAIBalance

协议
本项目使用 MIT 协议。您可以在 LICENSE 文件中查看更多详情。
