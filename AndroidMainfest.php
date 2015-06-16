<?php

include_once "config.php";



foreach ($package as $key => $value) {
	chdir($CURRENT_PATH);
	if ($config['package'][$value] == null) {
		continue;
	}

	$current_SDK = $config['package'][$value][0];
	$platform = $config['package'][$value][1];
	$packageName = $config['package'][$value][2];
	$path = "../SDK/$platform/$current_SDK/AndroidManifest.xml";
	$content = file_get_contents($path);

	if (!preg_match("/package[\s]*=[\s]*\"(.+?)\"/",$content,$matches)) {
		print_log("packageName替换错误 SDK=$current_SDK");
		die();
	}
	$content = preg_replace("/package[\s]*=[\s]*\".+?\"/", "package=\"$packageName\"", $content);

	//替换java文件中的R.java packageName
	`find ../SDK/$platform/$current_SDK/src -name "*.java"|xargs sed -i '' 's/$matches[1].R/$packageName.R/g'`;  //mac sed -i后面需要添加xxx,用于备份原来的文件, ''不备份
	
	$versionCodePattern = "/android:versionCode[\s]*=[\s]*\".+?\"/";
	if (!preg_match($versionCodePattern,$content)) {
		print_log("versionCode替换错误 SDK=$current_SDK");
		die();
	}
	$content = preg_replace($versionCodePattern, "android:versionCode=\"$versionCode\"", $content);

	$versionNamePattern = "/android:versionName[\s]*=[\s]*\".+?\"/";
	if (!preg_match($versionNamePattern,$content)) {
		print_log("versionName替换错误 SDK=$current_SDK");
		die();
	}
	$content = preg_replace($versionNamePattern, "android:versionName=\"$versionName\"", $content);

	

	if ($current_SDK == "qihoo") {
		$content = replace_qihoo_param($content,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "baidu") {
		replace_baidu_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "49you") {
		replace_49you_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "anzhi") {
		replace_anzhi_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "caohua") {
		replace_caohua_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "mumayi") {
		replace_mumayi_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "kugou") {
		replace_kugou_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "sogou") {
		replace_sogou_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "yuwan") {
		replace_yuwan_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "downjoy") {
		replace_downjoy_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "m4399") {
		replace_m4399_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "uc") {
		replace_uc_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "wdj") {
		replace_wdj_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "youku") {
		$content = replace_youku_param($content,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "ouwan") {
		replace_ouwan_param($platform,$current_SDK,$config['package'][$value][3]);
	}
	elseif ($current_SDK == "lengjing") {
		$content = replace_lengjing_param($platform,$current_SDK,$config['package'][$value][3]);
	}

	

	file_put_contents($path, $content);

}

function replace_qihoo_param($content,$config)
{
	if (!preg_match("/<meta-data[\s]*android:name=\"QHOPENSDK_APPID\"[\s]*android:value=(.+?)[\s]*>[\s]*<\/meta-data>/s",$content,$matches)) {
		print_log("QHOPENSDK_APPID替换错误 SDK=$current_SDK");
		die();
	}

	$content =  str_replace($matches[1], "\"${config['app_id']}\"", $content);

	if (!preg_match("/<meta-data[\s]*android:name=\"QHOPENSDK_APPKEY\"[\s]*android:value=(.+?)[\s]*>[\s]*<\/meta-data>/s",$content,$matches)) {
		print_log("QHOPENSDK_APPKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);

	if (!preg_match("/<meta-data[\s]*android:name=\"QHOPENSDK_PRIVATEKEY\"[\s]*android:value=(.+?)[\s]*>[\s]*<\/meta-data>/s",$content,$matches)) {
		print_log("QHOPENSDK_PRIVATEKEY替换错误 SDK=$current_SDK");
		die();
	}
	$value = md5($config['app_secret'] . "#" . $config['app_key']);
	$content =  str_replace($matches[1], "\"$value\"", $content);
	return $content;
}

function replace_baidu_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/complatform/gamesdk/Constant.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final int appID = (.+?);/",$content,$matches)) {
		print_log("APPID替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], $config['app_id'], $content);

	if (!preg_match("/public static final String appKEY = (.+?);/",$content,$matches)) {
		print_log("APPKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);

	file_put_contents($path, $content);
}

function replace_49you_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/complatform/gamesdk/Constant.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final int appID = (.+?);/",$content,$matches)) {
		print_log("APPID替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], $config['app_id'], $content);

	if (!preg_match("/public static final String appKEY = (.+?);/",$content,$matches)) {
		print_log("APPKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);

	file_put_contents($path, $content);
}

function replace_anzhi_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/complatform/gamesdk/Constant.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final String appSecret = (.+?);/",$content,$matches)) {
		print_log("appSecret替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_secret']}\"", $content);

	if (!preg_match("/public static final String appKEY = (.+?);/",$content,$matches)) {
		print_log("APPKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);

	file_put_contents($path, $content);
}


function replace_caohua_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/complatform/gamesdk/Constant.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final int appID = (.+?);/",$content,$matches)) {
		print_log("APPID替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], $config['app_id'], $content);

	if (!preg_match("/public static final String appKEY = (.+?);/",$content,$matches)) {
		print_log("APPKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);

	file_put_contents($path, $content);


	$path = "../SDK/$platform/$current_SDK/res/values/strings.xml";
	$content = file_get_contents($path);

	if (!preg_match("/<string name=\"app_ID\">(.+?)<\/string>/",$content,$matches)) {
		print_log("APPID替换错误 SDK=$current_SDK,path=$path");
		die();
	}
	$content =  str_replace($matches[1], $config['app_id'], $content);

	if (!preg_match("/<string name=\"app_KEY\">(.+?)<\/string>/",$content,$matches)) {
		print_log("APPKEY替换错误 SDK=$current_SDK,path=$path");
		die();
	}
	$content =  str_replace($matches[1], $config['app_key'], $content);

	file_put_contents($path, $content);

}

function replace_mumayi_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/complatform/gamesdk/Constant.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final String appKEY = (.+?);/",$content,$matches)) {
		print_log("APPKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);

	file_put_contents($path, $content);
}


function replace_kugou_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/complatform/gamesdk/Constant.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final int appMerchantId = (.+?);/",$content,$matches)) {
		print_log("appMerchantId替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], $config['app_merchant_id'], $content);

	if (!preg_match("/public static final int appId = (.+?);/",$content,$matches)) {
		print_log("appId替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], $config['app_id'], $content);

	if (!preg_match("/public static final String appKey = (.+?);/",$content,$matches)) {
		print_log("appKey替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);

	if (!preg_match("/public static final int appGameId = (.+?);/",$content,$matches)) {
		print_log("appId替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], $config['app_game_id'], $content);

	if (!preg_match("/public static final String appCode = (.+?);/",$content,$matches)) {
		print_log("appKey替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_code']}\"", $content);

	file_put_contents($path, $content);
}


function replace_sogou_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/complatform/gamesdk/Constant.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final int appID = (.+?);/",$content,$matches)) {
		print_log("APPID替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], $config['app_id'], $content);

	if (!preg_match("/public static final String appKEY = (.+?);/",$content,$matches)) {
		print_log("APPKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);

	file_put_contents($path, $content);
}


function replace_yuwan_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/complatform/gamesdk/Constant.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final String appID = (.+?);/",$content,$matches)) {
		print_log("appID替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_id']}\"", $content);

	if (!preg_match("/public static final String appKEY = (.+?);/",$content,$matches)) {
		print_log("APPKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);


	if (!preg_match("/public static final String appForumID = (.+?);/",$content,$matches)) {
		print_log("appForumID替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_forum_id']}\"", $content);

	
	file_put_contents($path, $content);
}

function replace_downjoy_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/downjoy/complatform/gamesdk/Constant.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final String merchantId = (.+?);/",$content,$matches)) {
		print_log("merchantId替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_merchant_id']}\"", $content);

	if (!preg_match("/public static final String appKey = (.+?);/",$content,$matches)) {
		print_log("APPKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);


	if (!preg_match("/public static final String appId = (.+?);/",$content,$matches)) {
		print_log("appId替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_id']}\"", $content);

	
	file_put_contents($path, $content);

}

function replace_m4399_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/complatform/gamesdk/Constant.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final String appKEY = (.+?);/",$content,$matches)) {
		print_log("app_id替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_id']}\"", $content);

	file_put_contents($path, $content);

}

function replace_uc_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/cn/uc/gamesdk/jni/Config.java";
	$content = file_get_contents($path);

	if (!preg_match("/public final static int cpId = (.+?);/",$content,$matches)) {
		print_log("cpId替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], $config['app_cp_id'], $content);

	if (!preg_match("/public final static int gameId = (.+?);/",$content,$matches)) {
		print_log("gameId替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], $config['app_game_id'], $content);


	file_put_contents($path, $content);

}


function replace_wdj_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/wdj/sdk/common/Constants.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final long APP_KEY = (.+?);/",$content,$matches)) {
		print_log("app_Key替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], $config['app_key'], $content);


	if (!preg_match("/public static final String SECURITY_KEY = (.+?);/",$content,$matches)) {
		print_log("SECURITY_KEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_security_key']}\"", $content);

	file_put_contents($path, $content);


	$path = "../SDK/$platform/$current_SDK/res/values/strings.xml";
	$content = file_get_contents($path);

	if (!preg_match("/<string name=\"wdj_key\">Wandoujia-PaySdk-(.+?)<\/string>/",$content,$matches)) {
		print_log("APPID替换错误 SDK=$current_SDK,path=$path");
		die();
	}
	$content =  str_replace($matches[1], $config['app_key'], $content);


	file_put_contents($path, $content);

}

function replace_youku_param($content,$config)
{
	if (!preg_match("/<meta-data[\s]*android:name=\"YKGAME_APPID\"[\s]*android:value=(.+?)[\s]*>[\s]*<\/meta-data>/s",$content,$matches)) {
		print_log("YKGAME_APPID替换错误 SDK=$current_SDK");
		die();
	}

	$content =  str_replace($matches[1], "\"${config['app_id']}\"", $content);

	if (!preg_match("/<meta-data[\s]*android:name=\"YKGAME_APPKEY\"[\s]*android:value=(.+?)[\s]*>[\s]*<\/meta-data>/s",$content,$matches)) {
		print_log("YKGAME_APPKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);

	if (!preg_match("/<meta-data[\s]*android:name=\"YKGAME_PRIVATEKEY\"[\s]*android:value=(.+?)[\s]*>[\s]*<\/meta-data>/s",$content,$matches)) {
		print_log("YKGAME_PRIVATEKEY替换错误 SDK=$current_SDK");
		die();
	}

	$content =  str_replace($matches[1], "\"${config['app_secret']}\"", $content);
	return $content;

}

function replace_ouwan_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/complatform/gamesdk/Constant.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final String appID = (.+?);/",$content,$matches)) {
		print_log("app_id替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_id']}\"", $content);

	if (!preg_match("/public static final String appKEY = (.+?);/",$content,$matches)) {
		print_log("appKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);

	file_put_contents($path, $content);

}

function replace_lengjing_param($platform,$current_SDK,$config)
{
	$path = "../SDK/$platform/$current_SDK/src/com/complatform/gamesdk/Constant.java";
	$content = file_get_contents($path);

	if (!preg_match("/public static final int appID = (.+?);/",$content,$matches)) {
		print_log("app_id替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], $config['app_id'], $content);

	if (!preg_match("/public static final String appKEY = (.+?);/",$content,$matches)) {
		print_log("appKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['app_key']}\"", $content);

	file_put_contents($path, $content);


	$path = "../SDK/$platform/$current_SDK/AndroidManifest.xml";
	$content = file_get_contents($path);

	if (!preg_match("/<meta-data[\s]*android:name=\"XMGAME_CHANNEL_CODE\"[\s]*android:value=(.+?)[\s]*\/>/s",$content,$matches)) {
		print_log("XMGAME_CHANNEL_CODE替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['channel_code']}\"", $content);

	if (!preg_match("/<meta-data[\s]*android:name=\"XMGAME_PRODUCT_CODE\"[\s]*android:value=(.+?)[\s]*\/>/s",$content,$matches)) {
		print_log("YKGAME_PRIVATEKEY替换错误 SDK=$current_SDK");
		die();
	}
	$content =  str_replace($matches[1], "\"${config['produce_code']}\"", $content);

	return $content;
}



print_log("AndroidManifest.xml替换完成");
?>
