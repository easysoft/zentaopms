<?php
declare(strict_types=1);
/**
 * The debug helpers file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin\utils;

$logs = array();

function log($type, $msg = null, $file)
{
    global $config, $logs;

    if(!$config->debug) return;

    if($msg === null)
    {
        $msg = $type;
        $type = 'i';
    }

    if(is_array($msg))
    {
        $msgLines = array();
        foreach($msg as $m) $msgLines[] = strval($m);
        $msg = implode(' ', $msgLines);
    }
    else
    {
        $msg = strval($msg);
    }

    $logs[] = array(array('type' => strtolower($type), 'msg' => $msg));
}

function logInfo($msg, $file = null)  {log('i', $msg, $file);};
function logWarn($msg, $file = null)  {log('w', $msg, $file);};
function logError($msg, $file = null) {log('e', $msg, $file);};
