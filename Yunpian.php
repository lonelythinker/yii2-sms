<?php

namespace lonelythinker\yii2\sms;

/**
 * 云片网
 */
class Yunpian extends Sms
{
    /**
     * @var string
     */
    public $apikey;
    
    /**
     * @var string
     */
    public $api_secret;
    
    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Object::init()
     */
    public function init()
    {
    	parent::init();
    	// 1. require the file
    	require_once (__DIR__ . '/yunpian/YunpianAutoload.php');
    	// 2. 首先在 conf/config.php   中配置自己的相关信息
    	$GLOBALS['YUNPIAN_CONFIG']['APIKEY'] = $this->apikey;
    	$GLOBALS['YUNPIAN_CONFIG']['API_SECRET'] = $this->api_secret;
    }
    
	/**
	 * 添加模板
	 * @param string $tpl_content 模板内容
	 * @return mixed
	 */
    public function tpl_add($tpl_content){
    	if(isset($tpl_content) && !empty($tpl_content)){
	    	$tplOperator = new \TplOperator();
	    	$result = $tplOperator->add(['tpl_id' => time(),'tpl_content' => $tpl_content]);
	    	if($result && is_object($result)){
	    		$this->state = isset($result->responseData['code']) ? $result->responseData['code'] : (isset($result->success) && $result->success ? 0 : '');
	    		$this->message = isset($result->responseData['msg']) ? $result->responseData['msg'] : '添加模板出现未知错误';
	    		$this->extendArr = ['tpl_id' => isset($result->responseData['tpl_id']) ? (string)$result->responseData['tpl_id'] : ''];
	    	}
    	}
    	return $this->state === 0;
    }
    
	/**
	 * 修改模板
	 * @param string $tpl_id 模板ID
	 * @param string $tpl_content 模板内容
	 * @return mixed
	 */
    public function tpl_upd($tpl_id, $tpl_content){
    	if(isset($tpl_id) && isset($tpl_content) && !empty($tpl_content)){
	    	$tplOperator = new \TplOperator();
	    	$result = $tplOperator->upd(['tpl_id' => $tpl_id,'tpl_content' => $tpl_content]);
	    	if($result && is_object($result)){
	    		$this->state = isset($result->responseData['code']) ? $result->responseData['code'] : (isset($result->success) && $result->success ? 0 : '');
	    		$this->message = isset($result->responseData['msg']) ? $result->responseData['msg'] : '修改模板出现未知错误';
	    	}
    	}
    	return $this->state === 0;
    }
    
	/**
	 * 删除模板
	 * @param string $tpl_id 模板ID
	 * @return mixed
	 */
    public function tpl_del($tpl_id){
    	if(isset($tpl_id)){
	    	$tplOperator = new \TplOperator();
	    	$result = $tplOperator->del(['tpl_id' => $tpl_id]);
	    	if($result && is_object($result)){
	    		$this->state = isset($result->responseData['code']) ? $result->responseData['code'] : (isset($result->success) && $result->success ? 0 : '');
	    		$this->message = isset($result->responseData['msg']) ? $result->responseData['msg'] : '删除模板出现未知错误';
	    	}
    	}
    	return $this->state === 0;
    }

    /**
     * 发送短信
     *
     * @param string|array $mobile  手机或手机数组
     * @param string $content 短信内容
     * @return boolean        短信是否发送成功
     */
    public function send($mobile, $content)
    {
    	if(isset($mobile) && isset($content)){
	    	$smsOperator = new \SmsOperator();
	    	$data['mobile'] = is_array($mobile)? implode(',', $mobile) : $mobile;
	    	$data['text'] = $content;
	    	$result = $smsOperator->batch_send($data);
	    	if($result && is_object($result)){
	    		$this->state = isset($result->responseData['total_count']) && $result->responseData['total_count'] > 0 && isset($result->success) && $result->success ? 0 : '';
	    		$msgs = '';
	    		if(isset($result->responseData['data'])){
	    			$errorMobiles = [];
	    			foreach ($result->responseData['data'] as $item){
	    				if(isset($item['code']) && $item['code'] !== 0){
	    					$this->extendArr['errorMobiles'][] = $item['mobile'];
	    				}
	    				if(isset($item['msg']) && !empty($item['msg'])){
	    					$msgs .= $item['msg'].' ';
	    				}
	    			}
	    			$this->message = $this->state === 0 ? '发送成功' : (empty($msgs) ? '发送失败' : $msgs);
	    		}
	    	}
    	}
    	return $this->state === 0;
    }
}
