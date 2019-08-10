# xcx-msg

### 项目说明：

本项目是一个简单微信小程序客服消息类，实现客服消息相关功能。官方给的php示例有误，这里就不再吐槽了。

本示例是采用开发者服务器，没有采用云调用的形式。

### 官方文档：

[客服消息指南](https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/customer-message/customer-message.html)

[客服消息服务端](https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/customer-message/customerServiceMessage.send.html)

### 适用场景

![](https://raw.githubusercontent.com/guyan0319/xcx-msg/master/images/xcx01.jpg)



### 客户消息流程图

![](https://raw.githubusercontent.com/guyan0319/xcx-msg/master/images/xcxkfxx.png)

### 使用步骤

### 1、开启客服消息

https://mp.weixin.qq.com/wxamp/index/index

登录-开发-开发设置-消息推送

[](https://raw.githubusercontent.com/guyan0319/xcx-msg/master/images/xcx02.png)

点击“启动”

[](https://raw.githubusercontent.com/guyan0319/xcx-msg/master/images/xcx03.png)

**URL(服务器地址)**：填开发者服务器对应的url，如 https://xxxxxx/demo.php

**Token(令牌)**：这个随便填，要求3-32位。

**EncodingAESKey(消息加密密钥)**：这个点击“随机生成”即可。

**消息加密方式**：可以根据自己需要选择，本例选择”兼容模式“。

**数据格式**：json相对于xml来说，从压缩效率及传输效率更具优势，这里我们选json。

注意：以上操作完后先不要提交，等配置好开发者服务端后再提交。

### 2、配置开发者服务端

检验signature的PHP示例代码：

```php
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
    $echostr=$_GET["echostr"];

    $token = TOKEN;//这里改成你第一步操作时填写的token
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );

    if ($tmpStr == $signature ) {
        return $echostr;
    } else {
        return false;
    }
```

官方示例没有返回 $echostr ，这个检验开发者服务端是否成功的关键，必须返回。

### 3、提交消息推送配置

如果没有报错，证明配置成功。

![](https://raw.githubusercontent.com/guyan0319/xcx-msg/master/images/xcx04.png)

### 4、开发者服务端demo

```
<?php



//验证signature
//$signature = $_GET["signature"];
//$timestamp = $_GET["timestamp"];
//$nonce = $_GET["nonce"];
//$echostr=$_GET["echostr"];
//
//$token = TOKEN;//这里改成你第一步操作时填写的token
//$tmpArr = array($token, $timestamp, $nonce);
//sort($tmpArr, SORT_STRING);
//$tmpStr = implode( $tmpArr );
//$tmpStr = sha1( $tmpStr );
//
//if ($tmpStr == $signature ) {
//    return $echostr;
//} else {
//    return false;
//}


include_once './Xcxmsg.php';
$xcxmsg = new Xcxmsg();

$postStr = file_get_contents('php://input');
if (!$postStr)
    return false;
$postArr = json_decode($postStr, true);
if (!isset($postArr['MsgType']) || !isset($postArr['FromUserName']))
    return false;
$data = ["touser" => $postArr['FromUserName']];

$accessToken = $xcxmsg->getAccessToken();
$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $accessToken;

switch ($postArr['MsgType']) {
    case "text":
        //如用户发送的是文字信息，这里处理
        //回复图文链接，也可以回复别的类型，根据需要
        $data['msgtype'] = "link";
        $data['link'] = [
            "title" => "hello",
            "description" => "Is Really A Happy Day",
            "url" => "LINK_URL",//连接url
            "thumb_url" =>"THUMB_URL" //图片url
        ];
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $xcxmsg->curl($json, $url);
        break;
    case "image": //如用户发送图片消息，进入这里

        //服务端回复 图片，也可以回复别的类型，根据需要
        $data['msgtype'] = "image";
        $data['image'] = ['media_id' => 'media_id值']; // 执行 $xcxmsg->upload($accessToken)返回的 media_id
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $xcxmsg->curl($json, $url);
    case "miniprogrampage":
        //如用户发送小程序卡片，进入这里
        //这里服务端回复小卡片，也可以回复别的类型，根据需要
        $data['msgtype'] = "miniprogrampage";
        $data['miniprogrampage'] = [
            "title" => "title",
            "pagepath" => "pages/index/index",
            "thumb_media_id" => "media_id值"];// 执行 $xcxmsg->upload($accessToken)返回的 media_id
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $xcxmsg->curl($json, $url);
        break;
    case "event":
        //如用户进入会话事件
        //这里可以回复文本
        $data['msgtype'] = "text";
        $data['text'] = [
            "content" => "Hello World",
            ];
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $xcxmsg->curl($json, $url);
        break;
    default:
}

```



### 5、小程序前端

在需要的地方添加以下代码：

```
<button open-type="contact" >客服消息</button>
```

用微信开发工具的预览，生成二维码，扫描测试是否成功。



项目地址：https://github.com/guyan0319/xcx-msg



在使用中如有任何问题，请回复指正，谢谢！