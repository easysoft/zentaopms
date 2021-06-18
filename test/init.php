<?php
/**
 * 本文件创建一个 app 实例，并且通过执行 $app->loadCommon() 方法创建名为 tester 的commonModel对象。
 * This file build a app instance and provide a instance of commonModel named as tester by $app->loadCommon().
 *
 * All request of entries should be routed by this router.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Guanxing <guanxiying@easycorp.ltd>
 * @package     ZenTaoPMS
 * @version     $Id: $
 * @link        http://www.zentao.net
 */
/* Set the error reporting. */
error_reporting(E_ALL & E_STRICT);

$frameworkRoot = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR;

/* Load the framework. */
include $frameworkRoot . 'router.class.php';
include $frameworkRoot . 'control.class.php';
include $frameworkRoot . 'model.class.php';
include $frameworkRoot . 'helper.class.php';

$app    = router::createApp('pms', dirname(dirname(__FILE__)), 'router');
$tester = $app->loadCommon();

$config->zendataRoot = dirname(__FILE__) . DS . 'zendata';
$config->zdPath      = __DIR__ . "/runtime/zd/zd";

/**
 * Save variable to $_result.
 *
 * @param  mix    $result
 * @access public
 * @return bool true
 */
function run($result)
{
    global $_result;
    $_result = $result;
    return true;
}

/**
 * Print expect data.
 *
 * @param  string    $key
 * @param  string    $delimiter
 * @access public
 * @return void
 */
function expect($key, $delimiter = ',')
{
    global $_result;
    echo ">> ";
    $result = "";

    if(!is_array($_result) and !is_object($_result))
    {
        $result = (string) $_result;
    }
    else
    {
        $keyList =  explode(',', $key);
        $dimension = 1;
        foreach($_result as $value)
        {
            if(is_array($value) or is_object($value)) $dimension = 2;
        }

        if($dimension == 1) $_result = array($_result);
        foreach($_result as $object)
        {
            foreach($keyList as $key) $result .= zget($object, $key, '') . $delimiter;
        }
        $result = trim($result, $delimiter);
    }
    echo $result . "\n";
    echo "\n";
}

/**
 * Import data create by zendata to one table.
 *
 * @param  string    $table
 * @param  string    $yaml
 * @param  int       $count
 * @access public
 * @return void
 */
function zdImport($table, $yaml, $count = 10)
{
    chdir(dirname(__FILE__));
    global $app, $config;
    $dns   = "mysql://{$config->db->user}:{$config->db->password}@{$config->db->host}:{$config->db->port}/{$config->db->name}#utf8";
    $table = trim($table, '`');
    $command = "$config->zdPath -c $yaml -t $table -T -dns $dns --clear -n $count";
    system($command);
}
