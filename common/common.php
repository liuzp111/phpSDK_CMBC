<?php

/*
 * 功能:写文件日志
*/
function write2file($data,$dir,$prefile)
{
	$filename='../data/'.$dir.'/'.$prefile.'_'.date('Ymd').'.log';

	if(!file_exists($filename))
	{
		//如果不存在，则创建该文件
		touch($filename); //touch在linux也有此命令,是快速的建立一个文件
	}

	$fp = fopen($filename,'ab');
	fwrite($fp,$data.PHP_EOL);
	fclose($fp);
}
