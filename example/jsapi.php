<?php 
    require '../common/common.php';
    ini_set('date.timezone','Asia/Shanghai');
    //测试openid，openid即微信公众号的openid，具体获取方式参考微信开发文档
    $openid = 'om9zdwWUZQ-8GhSJifQywH0TGLto';
    $jsApiParameters = callPay($openid);
    /**
     * 生成微信支付参数
     * 参数说明文档地址：http://docs.uline.cc/#2-1
     * @param type $order_id
     * @param type $uid
     * @author liuzp111
     * @return type
     */
     function callPay($openid)
    { 
         
        //获取支付参数
    	$wx_info['AppId'] = '您公众号的appid';
    	$wx_info['AppSecret'] = '您公众号的秘钥';
    	$wx_info['PartnerId'] = '招行分配的支付id';
    	$wx_info['PartnerKey'] = '招行分配的支付秘钥';
    	//生成并获取支付信息
    	require "../wxpay/JsApi_pub.class.php";
    	require"../wxpay/UnifiedOrder_pub.class.php";
    	$JsApi = new JsApi_pub($wx_info['AppId'],$wx_info['AppSecret'],$wx_info['PartnerKey']);
    	
    	$unifiedOrder = new UnifiedOrder_pub($wx_info['AppId'],$wx_info['PartnerId'],$wx_info['PartnerKey']);
        //================================
        //POST XML 内容体进行请求.
        //http://docs.uline.cc/#uline-weixin  
        //请不要传递文档中未说明的参数。
        //可接受的参数，详细参考文档
        //================================
    	$unifiedOrder->setParameter("sub_openid",$openid);//注意参数名sub_openid
    	
    	$unifiedOrder->setParameter("body",'测试');//商品名称过于长，所以用微店名称代替
    	$unifiedOrder->setParameter("out_trade_no",date('YmdHis',  time()));//商户订单号
    	$unifiedOrder->setParameter("total_fee",1);//总金额
    	$unifiedOrder->setParameter("notify_url",'http://'.$_SERVER["HTTP_HOST"].'/Notify.php');//通知地址
    	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
    	$getPara = $unifiedOrder->getPara();
        $pay_info = $getPara['js_prepay_info'];
        $js_prepay_info = json_decode($pay_info,TRUE);
        $jsApiParameters =   getParameters($js_prepay_info);

        write2file(date('Y-m-d H:i:s').'|callPay|支付参数生成|data='.$jsApiParameters,'log','callPay');  
        return $jsApiParameters;
    }
    /**
     * 支付参数
     * @return type
     */
     function getParameters($js_prepay_info)
    {
        $jsApiObj["appId"] = $js_prepay_info['appId'];//WxPayConf_pub::APPID;
        $timeStamp = $js_prepay_info['timeStamp'];
        $jsApiObj["timeStamp"] = "$timeStamp";

        $jsApiObj["nonceStr"] = $js_prepay_info['nonceStr'];
        $jsApiObj["package"] = $js_prepay_info['package'];
        $jsApiObj["signType"] = $js_prepay_info['signType'];
        $jsApiObj["paySign"] = $js_prepay_info['paySign'];
        $parameters = json_encode($jsApiObj);
        return $parameters;
    }

?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信支付样例-支付</title>
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				alert(res.err_code+res.err_desc+res.err_msg);
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	</script>

</head>
<body>
    <br/>
    <font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px">1分</span>钱</b></font><br/><br/>
	<div align="center">
		<button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>
	</div>
</body>
</html>