<?php
declare(strict_types=1);
/**
 * The bug view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
include './featurebar.html.php';

$that = zget($lang->user->thirdPerson, $user->gender);
$bugNavs['assignedTo'] = array('text' => sprintf($lang->user->assignedTo, $that), 'url' => inlink('bug', "userID={$user->id}&type=assignedTo&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$bugNavs['openedBy']   = array('text' => sprintf($lang->user->openedBy,   $that), 'url' => inlink('bug', "userID={$user->id}&type=openedBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$bugNavs['resolvedBy'] = array('text' => sprintf($lang->user->resolvedBy, $that), 'url' => inlink('bug', "userID={$user->id}&type=resolvedBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$bugNavs['closedBy']   = array('text' => sprintf($lang->user->closedBy,   $that), 'url' => inlink('bug', "userID={$user->id}&type=closedBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
if(isset($bugNavs[$type])) $bugNavs[$type]['active'] = true;

$cols = array();
foreach($config->user->defaultFields['bug'] as $field) $cols[$field] = $config->bug->dtable->fieldList[$field];
$cols['id']['checkbox'] = false;
$cols['title']['data-toggle'] = 'modal';
$cols['title']['data-size']   = 'lg';
$cols['resolution']['flex']   = false;

$cols = array_map(function($col)
{
    unset($col['fixed'], $col['group']);
    return $col;
}, $cols);

div
(
    setClass('shadow-sm rounded canvas'),
    nav
    (
        setClass('dtable-sub-nav py-1'),
        set::items($bugNavs)
    ),
    dtable
    (
        set::_className('shadow-none'),
        set::extraHeight('+.dtable-sub-nav'),
        set::userMap($users),
        set::bordered(true),
        set::cols($cols),
        set::data(array_values($bugs)),
        set::priList($lang->bug->priList),
        set::severityList($lang->bug->severityList),
        set::orderBy($orderBy),
        set::sortLink(inlink('bug', "userID={$user->id}&type={$type}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
        set::footPager(usePager())
    )
);

render();
