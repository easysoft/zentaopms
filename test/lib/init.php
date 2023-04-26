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
error_reporting(E_ALL);
define('RUN_MODE', 'test');

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

    echo "\n\n";
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
include $testPath . 'lib/yaml.class.php';
include $testPath . 'lib/rest.php';

if(!empty($config->test->account) and !empty($config->test->password) and !empty($config->test->base))
{
    $rest  = new rest($config->test->base);
    $token = $rest->post('/tokens', array('account' => $config->test->account, 'password' => $config->test->password));
    $token = $token->body;
}

global $isExtractAction;
$isExtractAction = $argc > 1 && $argv[1] == '-extract';

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
    global $_keys;
    global $_delimiter;
    global $isExtractAction;

    $_keys      = $keys;
    $_delimiter = $delimiter;

    if($isExtractAction) return true;

    if(empty($_result)) return print("0\n");

    if(is_array($_result) and isset($_result['code']) and $_result['code'] == 'fail') return print((string) $_result['message'] . "\n");

    /* Print $_result. */
    if(!$keys and is_array($_result)) return print((string)$_result[''] . "\n");
    if(!$keys or !is_array($_result) and !is_object($_result)) return print((string) $_result . "\n");

    $parts  = explode(';', $keys);
    foreach($parts as $part)
    {
        $values = getValues($_result, $part, $delimiter);
        foreach($values as $value) echo $value . "\n";
    }

    return true;
}

/**
 * extract ZTF note.
 *
 * @param  string $key
 * @access public
 * @return void
 */
function parseScript($expect)
{
    $debugInfo = debug_backtrace();
    if(!empty($debugInfo))
    {
        global $_keys;
        global $_delimiter;
        global $_current;

        $keys      = $_keys;
        $delimiter = $_delimiter;
        $file      = $debugInfo[count($debugInfo)-1]['file'];
        $contents  = file_get_contents($file);

        list($moduleName, $methodName, $methodParam) = genParamsByRPE($contents);

        $isGrup   = false;
        $stepDesc = '';
        $expects  = empty($expect) ? array() : explode($delimiter, $expect);

        $object    = '';
        $rowIndex  = -1;
        $pos       = strpos($keys, ':');
        if($pos)
        {
            $arrKey = substr($keys, 0, $pos);
            $keys   = substr($keys, $pos + 1);
            $pos    = strpos($arrKey, '[');
            if($pos)
            {
                $object   = substr($arrKey, 0, $pos);
                $rowIndex = trim(substr($arrKey, $pos + 1), ']');
            }
            else
            {
                $rowIndex = $arrKey;
            }
        }
        $keys = explode($delimiter, $keys);

        if(count($keys) > 1) $isGrup = true;

        if ($methodName === 0 && $methodParam === 0)
        {
            $stepDesc = "- 执行{$moduleName}" . ($isGrup ? "\n" : '');
        }
        else
        {
            $stepDesc = "- 执行{$moduleName}模块的{$methodName}方法，参数是{$methodParam}" . ($isGrup ? "\n" : '');
        }

        if(empty($keys)) $stepDesc .= " @{$expects[0]}\n";

        foreach($keys as $index => $row)
        {
            if(count($keys) < 2)
            {
                if($rowIndex == -1)
                {
                    $stepDesc .= $row ? ",属性{$row}" : '';
                }
                else
                {
                    $stepDesc .= ($rowIndex == -1 ? '' : ($isGrup ? ' - ' : '- ') . ",属性{$rowIndex} @{$expects[0]}\n");
                }
                $stepDesc .= " @$expect";
            }
            else
            {
                if($rowIndex == -1)
                {
                    $stepDesc .= ($isGrup ? ' - ' : '- ') . "属性{$row} @{$expects[$index]}\n";
                }
                else {
                    $stepDesc .= ($isGrup ? ' - ' : '- ') . "第{$rowIndex}条的{$row}属性 @{$expects[$index]}\n";
                }
            }
        }
        echo $stepDesc . ($isGrup ? "\n" : "\n");
    }
}

/**
 * Generate method,module,params from rpe.
 * 从RPE中获取模块，方法以及参数
 *
 * @param  string $rpe
 * @return array
 */
function genParamsByRPE($rpe)
{
    global $_current;
    preg_match_all("/r\((.*?)\)\s*&&\s*p\((.*?)\)\s*&&\s*e\((.*?)\);/", $rpe, $matches);
    $rParams = !empty($matches[1]) ? $matches[1] : array();
    $pParams = !empty($matches[2]) ? $matches[2] : array();
    $eParams = !empty($matches[3]) ? $matches[3] : array();
    $rParams = is_array($rParams) ? $rParams : array($rParams);
    $pParams = is_array($pParams) ? $pParams : array($pParams);
    $eParams = is_array($eParams) ? $eParams : array($eParams);

    $_current = intval($_current);
    $param    = $rParams[$_current];
    $_current++;
    $param                = trim($param, "'");
    $objArrowCount        = substr_count($param, '->');
    $rParamsStructureList = explode('->', $param);

    if($objArrowCount == 1)
    {
        $moduleName  = substr($rParamsStructureList[0], 1);
        $method      = $rParamsStructureList[1];
        $methodName  = substr(explode('(', $method)[0], 0, -4);
        $methodParam = substr(explode('(', $method)[1], 0, -1);
        $methodParam = trim($methodParam, "'");
    }
    elseif($objArrowCount == 2)
    {
        $moduleName  = $rParamsStructureList[1];
        $method      = $rParamsStructureList[2];
        $methodName  = explode('(', $method)[0];
        $methodParam = trim(substr(explode('(', $method)[1], 0, -1), ")");
        $methodParam = trim($methodParam, "'");
    }
    else
    {
        $moduleName  = $param;
        $methodName  = 0;
        $methodParam = 0;
    }

    $methodParam = preg_replace("/,\s*'/", ', ', $methodParam);

    return array($moduleName, $methodName, $methodParam);
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
            return print("Error: No object name '$object'.\n");
        }
    }

    if($index != -1)
    {
        if(is_array($value))
        {
            if(!isset($value[$index])) return print("Error: Cannot get index $index.\n");
            $value = $value[$index];
        }
        else if(is_object($value))
        {
            if(!isset($value->$index)) return print("Error: Cannot get index $index.\n");
            $value = $value->$index;
        }
        else
        {
            return print("Error: Not array, cannot get index $index.\n");
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
    global $isExtractAction;
    if($isExtractAction)
    {
        parseScript($expect);
        return;
    }

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
