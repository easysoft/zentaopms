<?php
declare(strict_types=1);
/**
 * The header view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

$badgesOptions = array();
$badgesOptions['mode']            = $mode;
$badgesOptions['todoCount']       = $todoCount;
$badgesOptions['isIPD']           = $isIPD;
$badgesOptions['isMax']           = $isMax;
$badgesOptions['isBiz']           = $isBiz;
$badgesOptions['isOpenedURAndSR'] = $isOpenedURAndSR;
$badgesOptions['enableER']        = $this->config->enableER;
$badgesOptions['rawMethod']       = $app->rawMethod;

if($app->rawMethod == 'work')
{
    $badgeMap = array();
    $nameMap = array('task' =>'task', 'story' =>'story', 'bug' =>'bug', 'testcase' =>'case', 'testtask' =>'testtask');

    if($isOpenedURAndSR !== 0)                       $nameMap = array_merge($nameMap, array('requirement' => 'requirement'));
    if($isBiz !== 0 || $isMax !== 0 || $isIPD !== 0) $nameMap = array_merge($nameMap, array('feedback' => 'feedback', 'ticket' => 'ticket'));
    if($isMax !== 0 || $isIPD !== 0)                 $nameMap = array_merge($nameMap, array('issue' => 'issue', 'risk' => 'risk', 'nc' => 'qa', 'myMeeting' => 'meeting'));
    if($isIPD !== 0)                                 $nameMap = array_merge($nameMap, array('demand' => 'demand'));

    foreach($nameMap as $name => $countKey)
    {
        $badgeMap[$name] = isset($todoCount[$countKey]) ? $todoCount[$countKey] : 0;
    }

    mainNavbar
    (
        set::badgeMap($badgeMap)
    );
}
