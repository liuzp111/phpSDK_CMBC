phpSDK_CMBC说明：
==============

 1. 此SDK现在只针对JSAPI支付
 2. 招行支付和正常的微信支付对接流程是一样的，只是调整了一些参数，比如`appid`改成了传递`sub_appid`、`openid`改成了传递`sub_openid`。所以请大家在对接时，一定要先阅读文档中的参数要求。
 3. 文档地址：http://docs.uline.cc/#2-1

```
//SDK中涉及的参数说明
1、wxpay/UnifiedOrder_pub.class.php   
//设置微信下单支付链接
$this->url = "http://api.cmbxm.mbcloud.com/wechat/orders";

2、example/jsapi.php
    //获取支付参数
	$wx_info['AppId'] = '您公众号的appid';
	$wx_info['AppSecret'] = '您公众号的秘钥';
	$wx_info['PartnerId'] = '招行分配的支付id';
	$wx_info['PartnerKey'] = '招行分配的支付秘钥';


```