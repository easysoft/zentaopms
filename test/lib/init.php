<?php
/**
 * 本文件创建一个 app 实例，并且通过执行 $app->loadCommon() 方法创建名为 tester 的commonModel对象。
 * This file build a app instance and provide a instance of commonModel named as tester by $app->loadCommon().
 *
 * All request of entries should be routed by this router.
 *
 * @copyright   Copyright 2009-2017 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guanxing <guanxiying@easycorp.ltd>
 * @package     ZenTaoPMS
 * @version     $Id: $
 * @link        http://www.zentao.net
 */
/* Set the error reporting. */
error_reporting(E_ALL & E_STRICT);

$testPath      = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR;
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
    if(empty($_result) or empty($_result->status_code)) return false;

    $codeStr       = (string)$code;
    $statusCodeStr = (string)($_result->status_code);

    if($_result->status_code == $code or ($statusCodeStr[0] === '2' and $codeStr[0] === '2'))
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

/* Set configs. */
$config->zendataRoot = dirname(dirname(__FILE__)) . '/zendata';
$config->ztfPath     = dirname(dirname(__FILE__)) . '/tools/ztf';
$config->zdPath      = dirname(dirname(__FILE__)) . '/tools/zd';

/* init testDB. */
include $testPath . 'config/config.php';
include $testPath. 'lib/db.class.php';
include $testPath. 'lib/yaml.class.php';
include $testPath. 'lib/rest.php';
$db   = new db();

if(!empty($config->test->account) and !empty($config->test->password) and !empty($config->test->base))
{
    $rest  = new rest($config->test->base);
    $token = $rest->post('/tokens', array('account' => $config->test->account, 'password' => $config->test->password));
    $token = $token->body;
}
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

    if(empty($_result)) return print(">> 0\n");

    if(is_array($_result) and isset($_result['code']) and $_result['code'] == 'fail') return print(">> " . (string) $_result['message'] . "\n");

    /* Print $_result. */
    if(!$keys and is_array($_result)) return print(">> " . (string)$_result[''] . "\n");
    if(!$keys or !is_array($_result) and !is_object($_result)) return print(">> " . (string) $_result . "\n");

    $parts  = explode(';', $keys);
    $values = array();
    foreach($parts as $part)
    {
        $values[] = implode($delimiter, getValues($_result, $part, $delimiter));
    }

    echo ">> " . implode(';', $values) . "\n";

    return true;
}

/**
 * Get values
 *
 * @param mixed  $value
 * @param string $keys
 * @param string $delimiter
 * @access public
 * @return void
 */
function getValues($value, $keys, $delimiter)
{
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

    return $values;
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
 * Check order
 *
 * @param array  $objs
 * @param string $orderBy
 * @access public
 * @return bool
 */
function checkOrder($objs, $orderBy)
{
    if(empty($objs)) return true;

    list($field, $sort) = explode('_', $orderBy);
    $last = current($objs)->$field;
    foreach($objs as $obj)
    {
        if($sort == 'desc')
        {
            if($obj->$field > $last) return false;
        }
        else
        {
            if($obj->$field < $last) return false;
        }
    }

    return true;
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

/**
 * Switch user.
 *
 * @param  string $account
 * @access public
 * @return bool
 */
function su($account)
{
    $userModel = new userModel();
    $user = $userModel->identify($account, '123Qwe!@#');
    if($user) return $userModel->login($user);
    return false;
}
