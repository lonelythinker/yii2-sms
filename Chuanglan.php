<?php

namespace lonelythinker\yii2\sms;

use yii\base\NotSupportedException;

/**
 * 创蓝
 * 
 * @author lonelythinker <710366112@qq.com>
 * @property string $password write-only password
 * @property string $state read-only state
 * @property string $message read-only message
 */
class Chuanglan extends Sms
{
    /**
     * @inheritdoc
     */
    public $url = 'http://222.73.117.158/msg/HttpBatchSendSM';
    
    /**
     * @inheritdoc
     */
    public function send($mobile, $content)
    {
        if (parent::send($mobile, $content)) {
            return true;
        }
        
        $data = [
            'account' => $this->username,
            'pswd' => $this->password,
            'mobile' => $mobile,
            'msg' => $content,
            'needstatus' => 'true',
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        $result=preg_split("/[,\r\n]/",$result);
        
        $this->state = isset($result[1]) ? (string) $result[1] : null;
        	
        $success = false;
        switch ($this->state) {
            case '0' :
                $this->message = '提交成功';
                break;
            case '101' :
                $this->message = '无此用户';
                break;
            case '102' :
                $this->message = '密码错';
                break;
            case '103' :
                $this->message = '提交过快';
                break;
            case '104' :
                $this->message = '系统忙';
                break;
            case '105' :
                $this->message = '敏感短信';
                break;
            case '106' :
                $this->message = '消息长度错';
                break;
            case '107' :
                $this->message = '包含错误的手机号码';
                break;
            case '108' :
                $this->message = '手机号码个数错';
                break;
            case '109' :
                $this->message = '无发送额度';
                break;
            case '110' :
                $this->message = '不在发送时间内';
                break;
            case '111' :
                $this->message = '超出该账户当月发送额度限制';
                break;
            case '112' :
                $this->message = '无此产品，用户没有订购该产品';
                break;
            case '113' :
                $this->message = 'extno格式错';
                break;
            case '115' :
                $this->message = '自动审核驳回';
                break;
            case '116' :
                $this->message = '签名不合法，未带签名';
                break;
            case '117' :
                $this->message = 'IP地址认证错,请求调用的IP地址不是系统登记的IP地址';
                break;
            case '118' :
                $this->message = '用户没有相应的发送权限';
                break;
            case '119' :
                $this->message = '用户已过期';
                break;
            case '120' :
                $this->message = '短信内容不在白名单中';
                break;
            default :
                $this->message = '提交成功';
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
        throw new NotSupportedException('创蓝不支持发送模板短信！');
    }
}