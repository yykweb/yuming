<?php
namespace yykweb\yuming\build;
use houdunwang\log\Log;

class Base
{
    protected $config = [ ];

    //初始配置
    public function config( $config ) {
        $this->config = $config;
    }

    //开始请求
    /**
     * @param $api_type  //类型   比如注册，修改密码，查看资料
     * @param $api_data  //要提交的数组，以键值对方式提交
     * @param $order_id  //订单ID
     * @return mixed|string
     */
    public function get( $api_type,$api_data,$order_id) {
        //构造要请求的参数数组，无需改动//获取API接口请求配置项
        $data = [
            "api_url"       => $this->config['api_url'],
            "api_user"      => $this->config['api_user'],
            "api_pass"      => $this->config['api_pass']
        ];
        $api = '';
        switch($api_type)
        {
            case 'add':
                $api = "domainname\r\nadd\r\n";
                break;
            case 'mod':
                $api = "domainname\r\nmod\r\n";
                break;
            case 'renew':
                $api = "domainname\r\nrenew\r\n";
                break;
            case 'open':
                $api = "domainname\r\nopen\r\n";
                break;
            case 'check':
                $api = "domainname\r\ncheck\r\n";
                break;
            case 'transfer':
                $api = "domainname\r\ntransfer\r\n";
                break;
            case 'info':
                $api = "domainname\r\ninfo\r\n";
                break;
            case 'url':
                $api = "domainname\r\nurl\r\n";
                break;
        }
        //数组拼接字符串
        $api .= $this->create_string($api_data)."."."\r\n";
        //建立请求
        $md5 = md5($data['api_user'].$data['api_pass'].substr($api,0,10));
        $post_url = $data['api_url']."?userid=".$data['api_user']."&versig=".$md5."&strCmd=".urlencode($api);
        $return = file_get_contents($post_url);
        $xml = simplexml_load_string($return);
        if($xml->returncode != '200')
        {
            Log::write("Error:".iconv( "UTF-8", "gb2312//IGNORE" , $xml->returnmsg)."\t".iconv("UTF-8","gb2312//IGNORE","订单号：").$order_id."\r\n",Log::ERROR);
            return "提交失败！请联系管理员！";
        }
        return json_decode(json_encode(simplexml_load_string($return)),TRUE);

    }

    /**其它类型提交
     * @param $api_type
     * @param $api_data
     * @param $order_id
     */
    public function other($api_type,$api_data,$order_id)
    {
        $data = [
            "api_url"       => $this->config['api_url'],
            "api_user"      => $this->config['api_user'],
            "api_pass"      => $this->config['api_pass']
        ];
        $api = '';
        switch($api_type)
        {
            case 'get':
                $api = "other\r\nget\r\n";
                break;
            case 'sync':
                $api = "other\r\nsync\r\n";
                break;
            case 'upload':
                $api = "other\r\nupload\r\n";
                break;
            case 'whois':
                $api = "other\r\nwhois\r\n";
                break;
        }

        //数组拼接字符串
        $api .= $this->create_string($api_data)."."."\r\n";
        //建立请求
        $md5 = md5($data['api_user'].$data['api_pass'].substr($api,0,10));
        $post_url = $data['api_url']."?userid=".$data['api_user']."&versig=".$md5."&strCmd=".urlencode($api);
        $return = file_get_contents($post_url);
        $xml = simplexml_load_string($return);
        if($xml->returncode != '200')
        {
            Log::write("Error:".iconv( "UTF-8", "gb2312//IGNORE" , $xml->returnmsg)."\t".iconv("UTF-8","gb2312//IGNORE","订单号：").$order_id."\r\n",Log::ERROR);
            return "提交失败！请联系管理员！";
        }
        return json_decode(json_encode(simplexml_load_string($return)),TRUE);
    }

    public function dns($api_type,$api_data,$order_id)
    {
        $data = [
            "api_url"       => $this->config['api_url'],
            "api_user"      => $this->config['api_user'],
            "api_pass"      => $this->config['api_pass']
        ];
        $api = '';
        switch($api_type)
        {
            case 'add':
                $api = "dnsresolve\r\nadd\r\n";
                break;
            case 'mod':
                $api = "dnsresolve\r\nmod\r\n";
                break;
            case 'del':
                $api = "dnsresolve\r\ndel\r\n";
                break;
            case 'list':
                $api = "dnsresolve\r\nlist\r\n";
                break;
        }

        //数组拼接字符串
        $api .= $this->create_string($api_data)."."."\r\n";
        //建立请求
        $md5 = md5($data['api_user'].$data['api_pass'].substr($api,0,10));
        $post_url = $data['api_url']."?userid=".$data['api_user']."&versig=".$md5."&strCmd=".urlencode($api);
        $return = file_get_contents($post_url);
        $xml = simplexml_load_string($return);
        if($xml->returncode != '200')
        {
            Log::write("Error:".iconv( "UTF-8", "gb2312//IGNORE" , $xml->returnmsg)."\t".iconv("UTF-8","gb2312//IGNORE","订单号：").$order_id."\r\n",Log::ERROR);
            return "提交解析失败！请联系管理员！";
        }
        return json_decode(json_encode(simplexml_load_string($return)),TRUE);
    }

    //数组转字符串
    private function create_string($para)
    {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=trim($key).":".trim($val)."\r\n";
        }
        return $arg;
    }
}