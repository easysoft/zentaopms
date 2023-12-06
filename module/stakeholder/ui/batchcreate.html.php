<?php
declare(strict_types=1);
/**
 * The batch create stakeholder view of stakeholder module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
namespace zin;

$data = array();
foreach($stakeholders as $stakeholder)
{
    if(!isset($users[$stakeholder->user])) continue;
    $data[$stakeholder->user]['accounts'] = $stakeholder->user;
}
foreach($parentStakeholders as $stakeholder)
{
    if(!isset($users[$stakeholder->account])) continue;
    $data[$stakeholder->account]['accounts'] = $stakeholder->account;
}
foreach($deptUsers as $deptAccount => $userName)
{
    if(!isset($users[$deptAccount])) continue;
    $data[$deptAccount]['accounts'] = $deptAccount;
}

jsVar('projectID', $projectID);

formBatchPanel
(
    set::width('1/2'),
    to::heading
    (
        div(setClass('panel-title text-lg'), $lang->program->createStakeholder),
        inputGroup
        (
            setClass('selectDeptBox'),
            $lang->execution->selectDept,
            picker(set::name('dept'), set::items($depts), set::value($dept), setID('dept'), set('data-placeholder', $lang->execution->selectDeptTitle), on::change("setDeptUsers")),
        ),
        $project->parent ? btn(set::url($this->createLink('stakeholder', 'batchcreate', "projectID={$projectID}&dept=&parent=$project->parent")), setClass('primary'), $lang->program->importStakeholder) : null
    ),
    set::minRows(count($data) + 5),
    set::bodyClass('w-1/2'),
    set::data(array_values($data)),
    formBatchItem
    (
        set::name('accounts'),
        set::label($lang->team->account),
        set::control('picker'),
        set::items($users)
    )
);
