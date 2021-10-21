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

$frameworkRoot = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR;

/**
 * Assert status code and set body as $_result.
 *
 * @param  int $code
 * @access public
 * @return bool
 */
function c($code)
{
    global $_result;
    if($_result and isset($_result->status_code) and $_result->status_code == $code)
    {
        $_result = $_result->body;
        return true;
    }

    echo ">> \n\n";
    return false;
}

/* Load the framework. */
include $frameworkRoot . 'router.class.php';
include $frameworkRoot . 'control.class.php';
include $frameworkRoot . 'model.class.php';
include $frameworkRoot . 'helper.class.php';

$app    = router::createApp('pms', dirname(dirname(__FILE__)), 'router');
$tester = $app->loadCommon();

/* Load rest for api. */
if(!isset($config->webSite)) die("Error: \$config->webSite is not set.\n");

$app->loadClass('requests', true);
include 'rest.php';
$rest = new Rest($config->webSite . '/api.php/v1');

/* Global token for api. */
$token = $rest->post('/tokens', array('account' => 'admin', 'password' => '123qwe!@#'));
if(isset($token->body)) $token = $token->body;

/* Set configs. */
$config->zendataRoot = dirname(dirname(__FILE__)) . '/zendata';
$config->ztfPath     = dirname(dirname(__FILE__)) . '/tools/ztf';
$config->zdPath      = dirname(dirname(__FILE__)) . '/tools/zd';

/**
 * Save variable to $_result.
 *
 * @param  mix    $result
 * @access public
 * @return bool true
 */
function r($result)
{
    global $_result;
    $_result = $result;
    return true;
}

/**
 * Print value or properties.
 *
 * @param  string    $key
 * @param  string    $delimiter
 * @access public
 * @return void
 */
function p($keys = '', $delimiter = ',')
{
    global $_result;

    /* Print $_result. */
    if(!$keys or !is_array($_result) and !is_object($_result)) return print(">> " . (string) $_result . "\n");

    $object = '';
    $index  = -1;
    $pos    = strpos($keys, ':');
    if($pos)
    {
        $arrKey = substr($keys, 0, $pos);
        $keys   = substr($keys, $pos + 1);

        $pos = strpos($arrKey, '[');
        if($pos)
        {
            $object = substr($arrKey, 0, $pos);
            $index  = trim(substr($arrKey, $pos + 1), ']');
        }
        else
        {
            $index = $arrKey;
        }
    }
    $keys = explode($delimiter, $keys);

    $value = $_result;
    if($object)
    {
        if(is_array($value))
        {
            $value = $value[$object];
        }
        else if(is_object($value))
        {
            $value = $value->$object;
        }
        else
        {
            return print(">> Error: No object name '$object'.\n");
        }
    }

    if($index != -1)
    {
        if(is_array($value))
        {
            if(!isset($value[$index])) return print(">> Error: Cannot get index $index.\n");
            $value = $value[$index];
        }
        else if(is_object($value))
        {
            if(!isset($value->$index)) return print(">> Error: Cannot get index $index.\n");
            $value = $value->$index;
        }
        else
        {
            return print(">> Error: Not array, cannot get index $index.\n");
        }
    }

    $values = array();
    foreach($keys as $key) $values[] = zget($value, $key, '');

    echo ">> " . implode($delimiter, $values) . "\n";

    return true;
}

/**
 * Expect values, ztf will put params to step.
 *
 * @param  string    $exepect
 * @access public
 * @return void
 */
function e($expect)
{
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
