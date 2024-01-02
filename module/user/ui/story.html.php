<?php
declare(strict_types=1);
/**
 * The story view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
include './featurebar.html.php';

$that = zget($lang->user->thirdPerson, $user->gender);
$storyNavs['assignedTo'] = array('text' => sprintf($lang->user->assignedTo, $that), 'url' => inlink('story', "userID={$user->id}&storyType={$storyType}&type=assignedTo&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$storyNavs['openedBy']   = array('text' => sprintf($lang->user->openedBy,   $that), 'url' => inlink('story', "userID={$user->id}&storyType={$storyType}&type=openedBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$storyNavs['reviewedBy'] = array('text' => sprintf($lang->user->reviewedBy, $that), 'url' => inlink('story', "userID={$user->id}&storyType={$storyType}&type=reviewedBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
$storyNavs['closedBy']   = array('text' => sprintf($lang->user->closedBy,   $that), 'url' => inlink('story', "userID={$user->id}&storyType={$storyType}&type=closedBy&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), 'load' => 'table');
if(isset($storyNavs[$type])) $storyNavs[$type]['active'] = true;

$this->loadModel('my');
$cols = array();
foreach($config->user->defaultFields['story'] as $field) $cols[$field] = $config->my->story->dtable->fieldList[$field];
$cols['id']['checkbox']        = false;
$cols['title']['nestedToggle'] = false;
$cols['title']['data-toggle']  = 'modal';
$cols['title']['data-size']    = 'lg';
$cols['plan']['name']          = 'planTitle';
$cols['product']['name']       = 'productTitle';
if($storyType == 'requirement' || $this->config->vision == 'lite') unset($cols['plan']);
if($this->config->vision == 'lite') unset($cols['stage']);
if($this->config->vision == 'lite') $cols['product']['title'] = $lang->story->project;

$cols = array_map(function($col)
{
    unset($col['fixed'], $col['group']);
    return $col;
}, $cols);

foreach($stories as $story) $story->estimate .= $config->hourUnit;

div
(
    setClass('shadow-sm rounded canvas'),
    nav
    (
        setClass('dtable-sub-nav py-1'),
        set::items($storyNavs)
    ),
    dtable
    (
        set::_className('shadow-none'),
        set::extraHeight('+.dtable-sub-nav'),
        set::userMap($users),
        set::bordered(true),
        set::cols($cols),
        set::data(array_values($stories)),
        set::orderBy($orderBy),
        set::sortLink(inlink('story', "userID={$user->id}&storyType={$storyType}&type={$type}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
        set::footPager(usePager())
    )
);

render();
