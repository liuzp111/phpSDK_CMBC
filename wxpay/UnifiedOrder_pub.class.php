<?php
/**
 * 统一支付接口类
 */
//require 'WxPayConf_pub.class.php';
require 'Wxpay_client_pub.class.php';
require 'SDKRuntimeException.class.php';
 



class UnifiedOrder_pub extends Wxpay_client_pub
{	
	var $appid;
	var $mch_id;
	var $key;
	var $sub_appid;
	function __construct($appid,$mchid,$key) 
	{
          
		//设置接口链接

		$this->url = "http://api.cmbxm.mbcloud.com/wechat/orders ";
		//设置curl超时时间
		$this->curl_timeout = WxPayConf_pub::CURL_TIMEOUT;
		$this->appid=$appid;
		$this->sub_appid=$appid;
		$this->mch_id=$mchid;
//                $this->sub_mch_id = $mchid;
		$this->key=$key;
		
	}
	
	/**
	 * 生成接口参数xml
	 */
	public function createXml($appid='',$mchid='',$key='')
	{
		try
		{
			//检测必填参数
			if($this->parameters["out_trade_no"] == null) 
			{
				throw new SDKRuntimeException("缺少统一支付接口必填参数out_trade_no！"."<br>");
			}
			elseif($this->parameters["body"] == null)
			{
				throw new SDKRuntimeException("缺少统一支付接口必填参数body！"."<br>");
			}
			elseif ($this->parameters["total_fee"] == null ) 
			{
				throw new SDKRuntimeException("缺少统一支付接口必填参数total_fee！"."<br>");
			}
			elseif ($this->parameters["notify_url"] == null) 
			{
				throw new SDKRuntimeException("缺少统一支付接口必填参数notify_url！"."<br>");
			}
			elseif ($this->parameters["trade_type"] == null) 
			{
				throw new SDKRuntimeException("缺少统一支付接口必填参数trade_type！"."<br>");
			}
//			elseif ($this->parameters["trade_type"] == "JSAPI" &&$this->parameters["openid"] == NULL)
//			elseif ($this->parameters["trade_type"] == "JSAPI" &&$this->parameters["openid"] == NULL)
//			{
//				throw new SDKRuntimeException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>");
//			}
//			
		   	//$this->parameters["appid"] = $this->appid;//WxPayConf_pub::APPID;//公众账号ID
		   	$this->parameters["sub_appid"] = $this->appid;//WxPayConf_pub::APPID;//公众账号ID
		   	$this->parameters["mch_id"] = $this->mch_id;//WxPayConf_pub::MCHID;//商户号
		   	//$this->parameters["sub_mch_id"] = $this->mch_id;//WxPayConf_pub::MCHID;//商户号
		   	$this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];//终端ip	    
		    $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
		    $this->parameters["sign"] = $this->getSign($this->parameters,$this->key);//签名
		    
		    return  $this->arrayToXml($this->parameters);
		}
		catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
	}
	
	/**
	 * 获取prepay_id
	 */
	public function getPrepayId()
	{
            $this->postXml();

            $this->result = $this->xmlToArray($this->response);
            $prepay_id = $this->result["prepay_id"];

            //$prepay_id=1;//测试
            return $prepay_id;
	}
	/**
	 * 获取prepay_id
	 */
	public function getPara()
	{
            $this->postXml();

            $this->result = $this->xmlToArray($this->response);
            return $this->result;
	}
	
}


?>