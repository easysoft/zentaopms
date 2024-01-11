<?php
declare(strict_types=1);
/**
 * The browse view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    set::current($browseType),
    set::linkParams("browseType={key}"),
    li(searchToggle(set::open($type == 'bysearch'), set::module('user')))
);

toolbar
(
    btn
    (
        set::icon('cog-outline'),
        setClass('btn ghost'),
        set::url(createLink('custom', 'set', 'module=user&field=roleList')),
        setData('app', 'admin'),
        $lang->company->manageRole
    ),
    btnGroup
    (
        btn
        (
            setClass('btn primary create-user-btn'),
            set::icon('plus'),
            set::url(createLink('user', 'create', "deptID={$deptID}&type={$browseType}")),
            $lang->user->create
        ),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items
            (
                array
                (
                    array('text' => $lang->user->create,      'url' => createLink('user', 'create', "deptID={$deptID}&type={$browseType}"), 'className' => '.create-user-btn'),
                    array('text' => $lang->user->batchCreate, 'url' => createLink('user', 'batchCreate', "deptID={$deptID}&type={$browseType}")),
                )
            ),
            set::placement('bottom-end')
        )
    )
);

$settingLink = createLink('dept', 'browse');
$closeLink   = createLink('company', 'browse', "browseType={$browseType}&param=0&type={$type}");
sidebar
(
    moduleMenu(set(array
    (
        'modules'     => $deptTree,
        'activeKey'   => $type == 'bydept' ? $param : 0,
        'settingLink' => $settingLink,
        'closeLink'   => $closeLink,
        'showDisplay' => false,
        'settingText' => $lang->dept->manage
    )))
);
$footToolbar = common::hasPriv('user', 'batchEdit') ? array(
    'items' => array(array('text' => $lang->edit, 'className' => 'secondary open-url', 'data-load' => 'post', 'data-url' => createLink('user', 'batchEdit', "deptID={$deptID}&type={$browseType}"), 'data-data-map' => 'userIdList[]: #userList~checkedIDList')),
    'btnProps' => array('size' => 'sm', 'btnType' => 'secondary')
) : null;

if(common::hasPriv('user', 'batchEdit')) $this->config->company->user->dtable->fieldList['id']['type'] = 'checkID';

foreach($users as $user)
{
    if(!$user->last) $user->last = '';
}

$tableData = initTableData($users, $this->config->company->user->dtable->fieldList, $this->loadModel('user'));
dtable
(
    setID('userList'),
    set::orderBy($orderBy),
    set::sortLink(createLink('company', 'browse', "browseType={$browseType}&param={$param}&type={$type}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::cols($this->config->company->user->dtable->fieldList),
    set::data($tableData),
    set::checkable(common::hasPriv('user', 'batchEdit')),
    set::fixedLeftWidth('0.2'),
    set::footToolbar($footToolbar),
    set::footPager(usePager())
);

render();
