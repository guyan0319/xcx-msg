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

//处理用户发送的信息
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
            "thumb_url" => "THUMB_URL" //图片url
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


