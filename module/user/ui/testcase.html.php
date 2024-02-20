<?php
declare(strict_types=1);
/**
 * The testcase view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
include './featurebar.html.php';


$that = zget($lang->user->thirdPerson, $user->gender);
$testcaseNavs['case2Him']  = array('text' => sprintf($lang->user->case2Him, $that),  'url' => inlink('testcase', "userID={$user->id}&type=case2Him&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$testcaseNavs['caseByHim'] = array('text' => sprintf($lang->user->caseByHim, $that), 'url' => inlink('testcase', "userID={$user->id}&type=caseByHim&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
if(isset($testcaseNavs[$type])) $testcaseNavs[$type]['active'] = true;

$cols = array();
foreach($config->user->defaultFields['testcase'] as $field) $cols[$field] = $config->testcase->dtable->fieldList[$field];
$cols['id']['checkbox']        = false;
$cols['title']['nestedToggle'] = false;
$cols['title']['data-toggle']  = 'modal';
$cols['title']['data-size']    = 'lg';
$cols['type']['flex']          = false;
$cols['pri']['title']          = $lang->priAB;
$cols['type']['title']         = $lang->typeAB;
$cols['status']['title']       = $lang->statusAB;
$cols['status']['statusMap']['changed'] = $lang->story->changed;

$cols = array_map(function($col)
{
    unset($col['fixed'], $col['group']);
    return $col;
}, $cols);

foreach($cases as $case)
{
    if($type == 'case2Him') $case->id = $case->case;
    $case->caseID = $case->id;

    if((isset($case->fromCaseVersion) && $case->fromCaseVersion > $case->version) || $case->needconfirm) $case->status = 'changed';
}

div
(
    setClass('shadow-sm rounded canvas'),
    nav
    (
        setClass('dtable-sub-nav'),
        set::items($testcaseNavs)
    ),
    dtable
    (
        set::_className('shadow-none'),
        set::extraHeight('+.dtable-sub-nav'),
        set::userMap($users),
        set::bordered(true),
        set::cols($cols),
        set::data(array_values($cases)),
        set::orderBy($orderBy),
        set::sortLink(inlink('testcase', "userID={$user->id}&type={$type}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
        set::onRenderCell(jsRaw('window.renderCell')),
        set::footPager(usePager())
    )
);

render();
