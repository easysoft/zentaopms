<?php
declare(strict_types=1);
/**
 * The todo view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
include './featurebar.html.php';

$todoNavs = array();
foreach($lang->user->featureBar['todo'] as $navKey => $navName) $todoNavs[$navKey] = array('text' => $navName, 'url' => inlink('todo', "userID={$user->id}&type={$navKey}&status=all&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"));
$todoNavs['before']['url'] = inlink('todo', "userID={$user->id}&type=before&status=undone&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}");
if(isset($todoNavs[$type])) $todoNavs[$type]['active'] = true;

$this->loadModel('my');
$cols = array();
foreach($config->user->defaultFields['todo'] as $field) $cols[$field] = $config->my->todo->dtable->fieldList[$field];
$cols['id']['checkbox'] = false;
$cols['name']['link']   = array('module' => 'todo', 'method' => 'view', 'params' => "id={id}&from=company");
$cols['type']['type']   = 'html';
$cols['type']['align']  = 'center';

$cols = array_map(function($col)
{
    unset($col['fixed'], $col['group']);
    return $col;
}, $cols);

$waitCount  = 0;
$doingCount = 0;
foreach($todos as $todo)
{
    if($todo->status == 'wait')  $waitCount ++;
    if($todo->status == 'doing') $doingCount ++;

    if($todo->date == FUTURE_TIME) $todo->date = $lang->todo->periods['future'];
}
$summary = sprintf($lang->todo->summary, count($todos), $waitCount, $doingCount);

panel
(
    setClass('list'),
    set::title(null),
    set::headingActions(array(nav(set::items($todoNavs)))),
    dtable
    (
        set::bordered(true),
        set::cols($cols),
        set::data(array_values($todos)),
        set::orderBy($orderBy),
        set::sortLink(inlink('todo', "userID={$user->id}&type={$type}&status={$status}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
        set::footer(array(array('html' => $summary, 'className' => "text-dark"), 'flex', 'pager')),
        set::footPager(usePager()),
    )
);

render();
