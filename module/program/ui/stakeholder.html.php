<?php
declare(strict_types=1);
/**
 * The stakeholder view file of program module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     program
 * @link        https://www.zentao.net
 */

namespace zin;

dropmenu();

/* Feature bar. */
featureBar
(
    li
    (
        set::className('nav-item'),
        a
        (
            set::href(createLink('program', 'stakeholder', "proram={$programID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
            setClass('active'),
            setData('app', $app->tab),
            $lang->program->stakeholder
        )
    )
);

/* Toolbar. */
$createLink = $this->createLink('program', 'createstakeholder', "programID=$programID");
if(hasPriv('program', 'createstakeholder')) toolbar(btn(setClass('btn primary'), set::icon('plus'), set::url($createLink), $lang->program->createStakeholder));

/* Create datatable with reusing stakeholder module. */
$this->loadModel('stakeholder');

$role = array();
$role['id']    = 'role';
$role['title'] = $lang->user->role;
$role['name']  = 'role';
$role['type']  = 'text';
$role['show']  = true;

$fieldListKeys = array_keys($this->config->stakeholder->dtable->fieldList);
array_splice($fieldListKeys, array_search('phone', array_keys($this->config->stakeholder->dtable->fieldList), true), 0, 'role');
$fieldListExtend = array();
foreach($fieldListKeys as $key)
{
    if($key == 'role') $fieldListExtend[$key] = $role;
    else $fieldListExtend[$key] = $this->config->stakeholder->dtable->fieldList[$key];
}

$fieldListExtend['id']['type'] = 'checkID';

$this->config->stakeholder->dtable->fieldList = $fieldListExtend;
$cols = $this->loadModel('datatable')->getSetting('stakeholder');

/* Set list and menu of actions to customize the actions of stakeholder in program module. */
$cols['actions']['list'] = array('unlinkStakeholder' => array
(
    'icon'         => 'unlink',
    'className'    => 'ajax-submit',
    'text'         => $lang->program->unlinkStakeholder,
    'hint'         => $lang->program->unlinkStakeholder,
    'url'          => $this->createLink('program', 'unlinkStakeholder', "programID={$programID}&id={id}"),
    'data-confirm' => $lang->program->confirmUnlink
));
$cols['actions']['menu'] = array('unlinkStakeholder');

$data = initTableData($stakeholders, $cols, $this->stakeholder);
foreach($data as $rowData)
{
    $rowData->name = $rowData->realname;
    $rowData->from = $lang->stakeholder->fromList[$rowData->from];
    $rowData->role = $lang->user->roleList[$rowData->role];
}

jsVar('summeryTpl', $lang->program->checkedProjects);
jsVar('confirmBatchUnlinkTip', $lang->program->confirmBatchUnlink);

dtable
(
    set::customCols(false),
    set::cols($cols),
    set::data($data),
    set::footPager(usePager()),
    set::checkable(true),
    set::orderBy($orderBy),
    set::sortLink(createLink('program', 'stakeholder', "programID={$programID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar(array
    (
        'type'  => 'btn-group',
        'items' => array(array
        (
            'text'     => $lang->unlink,
            'class'    => 'btn primary batch-unlink',
            'data-url' => createLink('program', 'batchUnlinkStakeholders', "programID={$programID}")
        ))
    ))
);

render();
