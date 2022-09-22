<?php
namespace app\common\components;

use Yii;
use yii\base\Component;
class Ucpaas extends Component
{
    //API�����ַ
    const BaseUrl = "https://open.ucpaas.com/ol/sms/";
    ///�������˺�ID����д�ڿ����߿���̨��ҳ�ϵ�Account Sid
	const accountsid = "c0fc74c847e2cd325493b3afb6536b0f";

	//�������˺�TOKEN����д�ڿ����߿���̨��ҳ�ϵ�Auth Token
	const token = "9f73b37554fa1eb729f05dcdf812cff4";
		
    public function init()
    {
        parent::init();
        print('init;');
    }

    private function getResult($url, $body = null, $method)
    {
        $data = $this->connection($url,$body,$method);
        if (isset($data) && !empty($data)) {
            $result = $data;
        } else {
            $result = 'û�з�������';
        }
        return $result;
    }

    /**
     * @param $url    ��������
     * @param $body   post����
     * @param $method post��get
     * @return mixed|string
     */
	 
    private function connection($url, $body,$method)
    {
        if (function_exists("curl_init")) {
            $header = array(
                'Accept:application/json',
                'Content-Type:application/json;charset=utf-8',
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            if($method == 'post'){
                curl_setopt($ch,CURLOPT_POST,1);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$body);
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $opts = array();
            $opts['http'] = array();
            $headers = array(
                "method" => strtoupper($method),
            );
            $headers[]= 'Accept:application/json';
            $headers['header'] = array();
            $headers['header'][]= 'Content-Type:application/json;charset=utf-8';

            if(!empty($body)) {
                $headers['header'][]= 'Content-Length:'.strlen($body);
                $headers['content']= $body;
            }

            $opts['http'] = $headers;
            $result = file_get_contents($url, false, stream_context_create($opts));
        }
        return $result;
    }

    /**
	�������Ͷ��ŵ�function��������ע��/�һ�����/��֤/�������ѵȵ����û��������ŵķ��ͳ���
     * @param $appid        Ӧ��ID
     * @param $mobile       ���ն��ŵ��ֻ�����
     * @param $templateid   ����ģ�壬���ں�̨���Ų�Ʒ��ѡ������Ӧ�á�����ģ��-ģ��ID���鿴��ģ��ID
     * @param null $param   �����������������ʹ��Ӣ�Ķ��Ÿ������磺param=��a,b,c����
     * @param $uid			���ڹ�˾��ʶ���ŵĲ���������ѡ�
     * @return mixed|string 
     * @throws Exception
     */
    public function SendSms($appid,$templateid,$param=null,$mobile,$uid){
        $url = self::BaseUrl . 'sendsms';
        $body_json = array(
            'sid'=>self::accountsid,
            'token'=>self::token,
            'appid'=>$appid,
            'templateid'=>$templateid,
            'param'=>$param,
            'mobile'=>$mobile,
            'uid'=>$uid,
        );
        print(self::accountsid);
        print(self::token);
        print($appid);
        $body = json_encode($body_json);
        $data = $this->getResult($url, $body,'post');
        return $data;
    }
	
	 /**
	 Ⱥ���Ͷ��ŵ�function����������Ӫ/�澯/����֪ͨ�ȶ��û��ķ��ͳ���
     * @param $appid        Ӧ��ID
     * @param $mobileList   ���ն��ŵ��ֻ����룬������뽫��Ӣ�Ķ��Ÿ������硰18088888888,15055555555,13100000000��
     * @param $templateid   ����ģ�壬���ں�̨���Ų�Ʒ��ѡ������Ӧ�á�����ģ��-ģ��ID���鿴��ģ��ID
     * @param null $param   �����������������ʹ��Ӣ�Ķ��Ÿ������磺param=��a,b,c����
     * @param $uid			���ڹ�˾��ʶ���ŵĲ���������ѡ�
     * @return mixed|string 
     * @throws Exception
     */
	public function SendSms_Batch($appid,$templateid,$param=null,$mobileList,$uid){
        $url = self::BaseUrl . 'sendsms_batch';
        $body_json = array(
            'sid'=>self::accountsid,
            'token'=>self::token,
            'appid'=>$appid,
            'templateid'=>$templateid,
            'param'=>$param,
            'mobile'=>$mobileList,
            'uid'=>$uid,
        );
        $body = json_encode($body_json);
        $data = $this->getResult($url, $body,'post');
        return $data;
    }
}