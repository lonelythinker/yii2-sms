<?php

namespace lonelythinker\yii2\sms;

use yii\base\NotSupportedException;

/**
 * 云片网
 * 
 * @author lonelythinker <710366112@qq.com>
 * @property string $state read-only state
 * @property string $message read-only message
 */
class Yunpian extends Sms
{
    /**
     * @var string
     */
    public $apikey;
    
    /**
     * @inheritdoc
     */
    public $url = 'http://yunpian.com/v1/sms/send.json';
    
    /**
     * @inheritdoc
     */
    public function send($mobile, $content)
    {
        if (parent::send($mobile, $content)) {
            return true;
        }
        
        $data = [
            'apikey' => $this->apikey,
            'mobile' => $mobile,
            'text' => $content
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        $json = json_decode($result);
        if ($json && is_object($json)) {
            $this->state = isset($json->code) ? (string) $json->code : null;
            $this->message = isset($json->msg) ? (string) $json->msg : null;
        }
        
        return $this->state === '0';
    }
    
    /**
     * @inheritdoc
     */
    public function sendByTemplate($mobile, $data, $id)
    {
        throw new NotSupportedException('云片网不支持发送模板短信！');
    }
}
