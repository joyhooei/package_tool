<?php

// local urlMap = {
// vn = "http://49.213.119.163", 
// cn = "http://137.116.153.22",
// en = "http://ddten001.cloudapp.net",
// fcb = "http://api.zykong.com"
//  };

// 修改config.plist

//需要打包的标志
for ($i=10; $i < 999; $i++) { 
	$package[] = $i;
}

$package = [25];
$versionCode = '1';
$versionName = '1.0.0.0';

$ANT_DIR = '/Users/user1/Documents/DevelopmentTools/apache-ant-1.9.4/bin/ant';
$OUT_PACKAGE_PATH = dirname(__FILE__) . '/../package_out/';

if (!is_dir($OUT_PACKAGE_PATH)) {
	`mkdir $OUT_PACKAGE_PATH`;
}

$CURRENT_PATH = dirname(__FILE__);
$config['out_package_path'] = $OUT_PACKAGE_PATH;

function print_log($string)
{
	echo "========================================================================================\n";
	echo "--------------------------------$string\n";
	echo "========================================================================================\n\n";
}

$config['package'] = [
	'prefix' => 'ddtank-',
	10  =>  ['qihoo','yunrongtianshang','com.ddtankfcb.yyts.qihoo',['app_id'=>'202567596','app_key'=>'350f925a619f3acf69283ac5e62c7d48','app_secret'=>'61334dc1feb6e227307a7b2a2f37b1e3']],    		//奇虎360
	11  =>  ['baidu','yunrongtianshang','com.ddtankfcb.yyts.baidu',['app_id'=>'5806204','app_key'=>'ExDNaHq7RXZkDs63OXKe8PD7']],    		//百度
	12  =>  ['49you','yunrongtianshang','com.ddtankfcb.yyts.sj49you',['app_id'=>'287','app_key'=>'D93F74A06A9A5343F29FE6C944FEAF5D']],			//49you
	13  =>  ['anzhi','yunrongtianshang','com.ddtankfcb.yyts.anzhi',['app_secret'=>'iVbRv5WryYFGe0pp0en3E4Wi','app_key'=>'1430796972733l0HnypI694Ux2ag3o']], 			//安智
	14  =>  ['caohua','yunrongtianshang','com.tencent.tmgp.chhd_ddtankfcb',['app_id'=>'53','app_key'=>'8B61F74DCA692CE1A0F9E97CD76556B6']], 	//草花
	15  =>  ['mumayi','yunrongtianshang','com.ddtankfcb.yyts.mumayi',['app_key'=>'0cfaf8f2a827ac702ho1o7VLcCi421FJyZaFKZXXXGEerleqO13LxdDtk5n4p5pBihNA7A']],			//木蚂蚁							
	// 16  =>  ['pps','yunrongtianshang','com.angame.ddtank.pps'],				//pps
	// 17  =>  ['youlong','yunrongtianshang','com.angame.ddtank.youlong'],		//游龙
	// 18  =>  ['muzhiwan','yunrongtianshang','com.angame.ddtank.muzhiwan'],	//拇指玩
	19  =>  ['yingyongbao','huaqingfeiyang','com.tencent.tmgp.ddtankfcb'],		//应用宝
	20  =>  ['kugou','yunrongtianshang','com.ddtankfcb.yyts.kugou',['app_id'=>'1328','app_key'=>'aQoutrpYO89qDNC5Dque3H5e9jgEkANG','app_merchant_id'=>'175','app_game_id'=>'10555','app_code'=>'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDPa5TwgGXsmm6pVY0IeHVsoO1dYmDaq8AIg+S3urQbjtyQx+wsRSCPAVvnFWLDQlx+0lVAuksEC3X3vZJYjS8FmRwnAT4e6vPyoWFLl3M6WikBqraZR7nKWCrhgd3WBMntiwe05qa5RHL9t4k1ygTdDcHr0uL6llG3GdukURrKawIDAQAB']],			//酷狗
	21  =>  ['sogou','yunrongtianshang','com.ddtankfcb.yyts.sogou',['app_id'=>'870','app_key'=>'cd5a4020b338cce89544a9fbeb8ea3f4c030b760d2fc655b7f1415dd3577a92a']],			//搜狗
	22  =>  ['yuwan','yunrongtianshang','com.ddtankfcb.yyts.yuwan',['app_id'=>'10000065','app_key'=>'80805f3521f6055a023a90ce5a03d447','app_forum_id'=>'999']],			//鱼丸
	// 23  =>  ['haima','yunrongtianshang','com.angame.ddtank.haima'],			//海马
	24  =>  ['downjoy','yunrongtianshang','com.ddtankfcb.yyts.dangle',['app_id'=>'3432','app_key'=>'sJ7x3Obv','app_merchant_id'=>'1350']],			//当乐
	25  =>  ['m4399','yunrongtianshang','com.ddtankfcb.yyts.m4399',['app_id'=>'104187']],			//m4399
	26  =>  ['uc','yunrongtianshang','com.ddtankfcb.yyts.uc',['app_cp_id'=>'51611','app_game_id'=>'556118']],					//uc
	27  =>  ['wdj','yunrongtianshang','com.ddtankfcb.yyts.wdj',['app_key'=>'100027945','app_security_key'=>'b913fdfbea69370e47bcbba30e0cdcd4']],				//豌豆荚
	// 28  =>  ['yy','yunrongtianshang','com.ddtank.angame.yayawan'],			//丫丫玩
	29  =>  ['youku','yunrongtianshang','com.ddtankfcb.yyts.youku',['app_id'=>'1502','app_key'=>'7c77c81007e16817','app_secret'=>'1261d54f0be8e320b74c50a28c639978']],			//优酷
	30  =>  ['ouwan','yunrongtianshang','com.ddtankfcb.yyts.ouwan',['app_id'=>'396b88cbc681cc7e','app_key'=>'1c0562cc0f99310d']],			//偶玩
	31  =>  ['lengjing','yunrongtianshang','com.ljapps.p1878',['app_id'=>'1878','app_key'=>'0ab7e0c81dfa4e069aa3406257090dc3','produce_code'=>'p1878','channel_code'=>'34a5b5323af343198fb208bad99f0776']]					//棱镜

];

?>
