<?php
ini_set('date.timezone','Asia/Shanghai');

//需要打包的标志 $package ->config.php定义

$IS_DEBUG = false;


include_once "config.php";
include_once "AndroidMainfest.php";

function parsedir($baesDir,&$files,&$path=null)
{
	if (is_file($baesDir)) {
		$files[] = $baseDir;
		return;
	}
    $items = scandir($baesDir);
    $path[] = $baesDir;
    foreach ($items as $value) {
        if ($value[0] != ".") {
            if ($value!="." && $value!="..") {
                parsedir($baesDir."/".$value,$files,$path);
            }
        }
    }
}


function createBuildXML(&$config,$name)
{
	$config['package']['projectName'] = $config['package']['prefix'] . $name;
	$out_string = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$out_string .= "<project name=\"{$config['package']['projectName']}\" default=\"help\">\n";
	$out_string .= "\t<property file=\"../../../package_tool/apk/ant.properties\" />\n";
    $out_string .= "\t<import file=\"../../../package_tool/apk/sdk_ant_build.xml\" />\n";
    $out_string .= '</project>' . "\n";

    return $out_string;
}

function copyAPK($config,$name)
{
	`mkdir -p ${config['out_package_path']}/$name`;

	$showtime=date("m-d@H-i");
	$out_fileName = $config['out_package_path'] . $name . '/' . $config['package']['prefix'] . $name . '@' . $showtime . '.apk';
	$in_fileName = $config['package']['prefix'] . $name . '-';
	if ($IS_DEBUG) {
		$in_fileName .= 'debug.apk';
	}
	else {
		$in_fileName .= 'release.apk';
	}

	system("cp bin/$in_fileName $out_fileName");
	echo "------------------------------------------------------------------------------------\n";
}


$time = time();
foreach ($package as $key => $value) {
	chdir($CURRENT_PATH);
	if ($config['package'][$value] == null) {
		continue;
	}
	$current_SDK = $config['package'][$value][0];
	$platform = $config['package'][$value][1];
	// 编译C++
	$last_SDK = "";
	if (file_exists('save/save.txt')) {
		$last_SDK = file_get_contents('save/save.txt');
	}
	if ($last_SDK != $current_SDK) {
		if (!is_file("jni/Android_{$platform}_{$current_SDK}.mk")) {
			print_log("android.mk文件不存在 SDK=$current_SDK");
			die();
		}
		system("cp -rf jni/Android_{$platform}_{$current_SDK}.mk jni/Android.mk");
		file_put_contents('save/save.txt', $current_SDK);
	}

	system("rm -rf libs");
	system("rm -rf ../SDK/$platform/$current_SDK/libs/armeabi");
	system("echo $current_SDK|./build_native.sh",$ret_back);  


	// 拷贝生成的so文件到SDK目录下
	`mkdir -p ../SDK/$platform/$current_SDK/libs/armeabi`;
	system("cp -f libs/armeabi/*.so ../SDK/$platform/$current_SDK/libs/armeabi");

	if (!file_exists("../SDK/$platform/$current_SDK/libs/armeabi/libddtank.so") || !file_exists("../SDK/$platform/$current_SDK/libs/armeabi/libgame.so") || $ret_back!=0) {
		print_log("C++编译错误 SDK=$current_SDK");
		die();
	}
	echo "------------------------------------------C++编译结束,开始生成APK--------------------------------------\n";
	// 生成apk
	$android_dir = $CURRENT_PATH . "/../SDK/$platform/" . $current_SDK;


	$out_string = createBuildXML($config,$current_SDK);
	chdir($android_dir);
	file_put_contents('build.xml', $out_string);

	//资源从package_tool/assets链接过来
	`rm -rf assets`;
	`ln -s ../../../package_tool/assets assets`;
	
	$ret_back = -1;
	if ($IS_DEBUG) {
		system("$ANT_DIR clean debug -f build.xml",$ret_back);
	}
	else
	{
		system("$ANT_DIR clean release -f build.xml",$ret_back);
	}

	if ($ret_back!=0) {
		print_log("apk生成错误 SDK=$current_SDK");
		die();
	}
	`rm -rf build.xml`;


	copyAPK($config,$current_SDK);

}

$time = time() - $time;

echo "time=" . $time ,"\n";


?>
