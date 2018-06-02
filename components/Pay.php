<?php
namespace app\components;

use function GuzzleHttp\Psr7\str;
use yii\base\BaseObject;
use yii\base\Component;

class Pay extends Component
{
    public $login;
    public $secretKey;
    public $transKey;

    const SAVE_SANDBOX = 'https://sandbox.astropaycard.com/api_curl/cc/save';
        public function init()
        {
            parent::init(); // TODO: Change the autogenerated stub
        }

    public function Save($params){
        $params = array_merge(['x_login' => $this->login,
          'x_trans_key' => $this->transKey], $params);
        $message = $params['x_email'].$params['cc_number'].$params['cc_exp_month'].$params['cc_cvv'].$params['cc_exp_year'].$params['x_cpf'].$params['x_country'];
        $params['control'] = $this->setMessage($message);
        $result = $this->query($params, self::SAVE_SANDBOX);
        return $result['cc_token'];
    }

    public function setMessage($message){
        return strtoupper(hash_hmac('sha256', pack('A*', $message), pack('A*',$this->secretKey)));
    }

    protected function query($params, $url){
        $ch = curl_init($url);
        $p = [];
        foreach ($params as $key => $value){
            $p[] = "{$key}={$value}";
        }
        $p = join('&', $p);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $response = curl_exec($ch);
        return $this->parseResponse($response);
    }

    public function parseResponse($response){
        $xml = new \SimpleXMLElement($response);
        if ((string) $xml->status === 'OK'){
           return (array) $xml;
        }

    }
}