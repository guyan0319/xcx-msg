<?php
//demo

include_once './Xcxmsg.php';

$xcxmsg = new Xcxmsg();



//验证
$request = $this->request();
$postStr = $request->getBody()->__toString();
if (!$postStr)
    return false;
$postArr = json_decode($postStr, true);
if (!isset($postArr['MsgType']) || !isset($postArr['FromUserName']))
    return false;
$data = ["touser" => $postArr['FromUserName']];
$host = \EasySwoole\EasySwoole\Config::getInstance()->getConf('HOST');
$accessToken = $this->getAccessToken();
$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $accessToken;

switch ($postArr['MsgType']) {
    case "text":
        //发送图文链接
        $data['msgtype'] = "link";
        $data['link'] = [
            "title" => "陌单",
            "description" => "Is Really A Happy Day",
            "url" => "http://j.mp/2NO7XdT",
            "thumb_url" => $host . "/web/images/modan.png"
        ];
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->curl($json, $url);
        break;
    case "image":
        //发送图片消息
        $data['msgtype'] = "image";
        $data['image'] = ['media_id' => $this->upload($accessToken)];
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->curl($json, $url);
    case "miniprogrampage":
        //发送小程序卡片
        $data['msgtype'] = "miniprogrampage";
        $data['miniprogrampage'] = [
            "title" => "title",
            "pagepath" => "pages/index/index",
            "thumb_media_id" => "jiFsp-C6MtbBy78p9zxZI_wN39HqIhr12KqhCbtRv_nDDpz4Me0GZIeGkq1mIUu7"];
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $this->curl($json, $url);
        break;
    case "event":
        //进入会话事件

        break;

    default:
}


