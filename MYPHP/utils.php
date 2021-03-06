<?php
/**
 * 核心类
 */
final class Utils {
    public static function run() {
        self::_set_const(); //设置常量
        defined('DEBUG') || define('DEBUG', False);
        if(DEBUG) {
            self::_create_dir(); //创建文件夹
            self::_import_file(); //载入必须文件
        } else {
            error_reporting(0);
            require TEMP_PATH.'/~boot.php';
        }
        Application::run(); //执行应用类
    }
    private static function _set_const() {
        $path = str_replace('\\','/', __FILE__);
        define('MYPHP_PATH', dirname($path));
        define('CONFIG_PATH', MYPHP_PATH.'/Config');
        define('DATA_PATH', MYPHP_PATH.'/Data');
        define('LIB_PATH', MYPHP_PATH.'/Lib');
        define('CORE_PATH', LIB_PATH.'/Core');
        define('FUNCTION_PATH', LIB_PATH.'/Function');
        //定义根目录
        define('ROOT_PATH', dirname(MYPHP_PATH));
        //临时目录
        define('TEMP_PATH', ROOT_PATH.'/Temp');
        //日志目录
        define('LOG_PATH', TEMP_PATH.'/Log');
        //应用目录
        define('APP_PATH', ROOT_PATH.'/'.APP_NAME);
        define('APP_CONFIG_PATH', APP_PATH.'/Config');
        define('APP_CONTROLLER_PATH', APP_PATH.'/Controller');
        define('APP_TPL_PATH', APP_PATH.'/Tpl');
        define('APP_PUBLIC_PATH', APP_TPL_PATH.'/Public');

        //创建公共
        define('COMMON_PATH',ROOT_PATH.'/Common');
        //公共配置项文件夹
        define('COMMON_CONFIG_PATH',COMMON_PATH.'/Config');
        //公共模型文件夹
        define('COMMON_MODEL_PATH',COMMON_PATH.'/Model');
        //公共库文件夹
        define('COMMON_LIB_PATH',COMMON_PATH.'/Lib');

        define('KUAIXUEPHP_VERSION','1.0');
        //定义post方式常量
        define('IS_POST', $_SERVER['REQUEST_METHOD'] === 'POST' ? true : false);
        //定义ajax常量
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            define('IS_AJAX',true);
        } else {
            define('IS_AJAX',false);
        }
    }

    /**
     *创建应用目录
     *
     * @return [type] [description]
     */
    private static function _create_dir() {
        $arr = array(
            COMMON_CONFIG_PATH,
            COMMON_MODEL_PATH,
            COMMON_LIB_PATH,
            APP_PATH,
            APP_CONFIG_PATH,
            APP_CONTROLLER_PATH,
            APP_TPL_PATH,
            APP_PUBLIC_PATH,
            TEMP_PATH,
            LOG_PATH
        );
        foreach($arr as $v) {
            is_dir($v) || mkdir($v,0777,true);
        }

        is_file(APP_TPL_PATH.'/success.html') || copy(DATA_PATH.'/Tpl/success.html', APP_TPL_PATH.'/success.html');
        is_file(APP_TPL_PATH.'/error.html') || copy(DATA_PATH.'/Tpl/error.html', APP_TPL_PATH.'/error.html');
    }

    private static function _import_file() {
        $fileArr = array(
            CORE_PATH.'/Log.class.php',
            FUNCTION_PATH.'/function.php',
            CORE_PATH.'/Application.class.php',
            CORE_PATH.'/Controller.class.php'
        );
        $str = '';
        foreach($fileArr as $v) {
            $str .= trim(substr(file_get_contents($v), 5, -2))."\r\n";
            require_once $v;
        }
        $str = "<?php\r\n".$str;
        file_put_contents(TEMP_PATH.'/~boot.php',$str) || die('access not allow');
    }
}

Utils::run();