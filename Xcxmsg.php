<?php
/**
 * 客服消息
 * Author: Zhiqiang Guo
 * Date: 2019/8/9
 * Time: 18:03
 */

class Xcxmsg
{
    /*
     * curl
     *
     * @author Zhiqiang Guo
     * @param int params
     * @access public
     */
    public function curl ($data,$url)
    {
        if (empty($json) || empty($url)) return false;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl);//捕抓异常
        }
        curl_close($curl);
        return $output;
    }
    /*
     * 上传媒体文件
     *
     * @author Zhiqiang Guo
     * @param int params
     * @access public
     */
    public function upload ($data)
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=" . $accessToken;
        $res = $this->curl($data, $url);
        if ($res) {
            $res = json_decode($res, true);
        } else {
            return false;
        }
        return $res['media_id'] ?? false;
    }
    /*
     * access_token
     *
     * @author Zhiqiang Guo
     * @param int params
     * @access public
     */
    public function getAccessToken($data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $data['AppID'] . '&secret=' . $data['AppSecret'];
        $weixin = file_get_contents($url);
        $jsondecode = json_decode($weixin);
        $array = get_object_vars($jsondecode);
        return $array['access_token']??'';
    }
}