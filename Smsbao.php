<?php

namespace lonelythinker\yii2\sms;

use yii\base\NotSupportedException;

/**
 * 短信宝短信验证码
 * 
 * @author lonelythinker <710366112@qq.com>
 * @property string $password write-only password
 * @property string $state read-only state
 * @property string $message read-only message
 */
class Smsbao extends Sms
{
    /**
     * @inheritdoc
     */
    public $url = 'http://api.smsbao.com/sms';
    
    /**
     * @inheritdoc
     */
    public function send($mobile, $content)
    {
        if (parent::send($mobile, $content)) {
            return true;
        }
        
        $data = [
            'u' => $this->username,
            'p' => md5($this->password),
            'm' => $mobile,
            'c' => $content
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        
        $this->state = (string) curl_exec($ch);
        curl_close($ch);
        
        $success = false;
        switch ($this->state) {
            case '0' :
                $this->message = '短信发送成功';
                break;
            case '-1' :
                $this->message = '参数不全';
                break;
            case '-2' :
                $this->message = '服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！';
                break;
            case '30' :
                $this->message = '密码错误';
                break;
            case '40' :
                $this->message = '账号不存在';
                break;
            case '41' :
                $this->message = '余额不足';
                break;
            case '42' :
                $this->message = '帐户已过期';
                break;
            case '43' :
                $this->message = 'IP地址限制';
                break;
            case '50' :
                $this->message = '内容含有敏感词';
                break;
            case '51' :
                $this->message = '手机号码不正确';
                break;
            default :
                $this->message = '短信发送成功';
                $success = true;
                break;
        }
        
        return $success;
    }
    
    /**
     * @inheritdoc
     */
    public function sendByTemplate($mobile, $data, $id)
    {
        throw new NotSupportedException('短信宝不支持发送模板短信！');
    }
}
