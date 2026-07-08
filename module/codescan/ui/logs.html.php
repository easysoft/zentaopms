<?php
declare(strict_types=1);
/**
 * The logs view file of ci module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     ci
 * @link        https://www.zentao.net
 */
namespace zin;

global $app, $lang;

$moduleName = empty($moduleName) ? $app->getModuleName() : $moduleName;
$methodName = empty($methodName) ? 'ajaxGetLogs'         : $methodName;
$logParams  =  empty($logParams) ? 'logID=%logID%'       : $logParams;
$logID      =  empty($logID)     ? 0                     : $logID;

$codeCss = file_get_contents($app->getWwwRoot() . 'js/misc/highlight/styles/code.css');
$logsCss = file_get_contents(dirname(dirname(__FILE__)) . '/css/logs.ui.css');
$logsJs  = file_get_contents(dirname(dirname(__FILE__)) . '/js/logs.ui.js');

$logBlock = div
(
    h::css(<<<CSS
$codeCss
$logsCss
CSS
    ),
    h::js(<<<JS
const logParams  = '$logParams';
const moduleName = '$moduleName';
const methodName = '$methodName';
const logID      = $logID;

$logsJs
JS
    ),
    $logID ? null : h::css('#pipelineLog {scrollbar-color: #64758b #fcfdfe;}'),
    $logID ? h::js("openPipelineLog($logID)") : null,
    setID('pipelineLogBlock'),
    setClass('canvas border overflow-hidden rounded-md z-20', $logID ? 'open default' : ''),
    empty($logID) ? div
    (
        setClass('flex justify-between p-2 log-header canvas'),
        h5($lang->pipeline->logs),
        btn
        (
            setClass('ghost size-sm'),
            set::icon('close')
        )
    ) : null,
    h::pre
    (
        setID('pipelineLog'),
        setClass('overflow-scroll whitespace-normal hljs mx-1 h-full')
    )
);
