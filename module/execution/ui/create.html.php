<?php
declare(strict_types=1);
/**
 * The create view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

$fields    = useFields('execution.create');
$typeField = $isStage ? 'attribute' : 'lifetime';
$fields->fullModeOrders('productsBox,PO,QD,PM,RD,team,teams,teamMembers[],desc');
if(!$isStage || empty($config->setPercent))
{
    $fields->remove('percent');
    $fields->field('days')->width('1/2');
}
if(empty($config->setCode))  $fields->remove('code');
if(!empty($config->setCode)) $fields->moveBefore($typeField, 'name');
if(!empty($project->model) && $project->model == 'kanban')
{
    $fields->remove($typeField);
    $fields->field('name')->width('full');
}
if(!empty($project->model) && $project->model == 'agileplus' && !empty($config->setCode))
{
    $fields->field('code')->width('1/4');
    $fields->field($typeField)->width('1/4');
    $fields->moveAfter($typeField, 'code');
}

jsVar('+projectID', $projectID);
jsVar('copyProjectID', $copyProjectID);
jsVar('weekend', $config->execution->weekend);
jsVar('isStage', $isStage);
jsVar('copyExecutionID', $copyExecutionID);
jsVar('executionID', isset($executionID) ? $executionID : 0);

$showExecutionExec = !empty($from) and ($from == 'execution' || $from == 'doc');
formGridPanel
(
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $showExecutionExec ? $lang->execution->createExec : $lang->execution->create,
    )),
    to::headingActions
    (
        a
        (
            icon('copy', setClass('mr-1')),
            setClass('primary-ghost'),
            setData(array('destoryOnHide' => true, 'toggle' => 'modal', 'target' => '#copyExecutionModal')),
            $lang->execution->copy
        ),
        divider(setClass('py-2 my-0.5 mx-4 self-center'))
    ),
    on::change('[name=project]', 'refreshPage'),
    on::change('[name=type]', 'setType'),
    on::change('[name=begin]', 'computeWorkDays(NaN)'),
    on::change('[name=end]', 'computeWorkDays(NaN)'),
    on::change('[name=teams]', 'loadMembers'),
    on::change('#copyTeam', 'toggleCopyTeam'),
    set::fields($fields)
);

modalTrigger
(
    modal
    (
        set::id('copyExecutionModal'),
        set::footerClass('justify-center'),
        to::header
        (
            span
            (
                h4
                (
                    set::className('copy-title'),
                    $lang->execution->copyTitle
                )
            ),
            picker
            (
                set::className('pickerProject'),
                set::name('project'),
                set::items($copyProjects),
                set::value($projectID),
                set::required(true),
                on::change('loadProjectExecutions')
            )
        ),
        to::footer
        (
            btn
            (
                setClass('primary btn-wide hidden confirmBtn'),
                set::text($lang->confirm),
                on::click('setCopyExecution')
            )
        ),
        div
        (
            set::id('copyExecutions'),
            setClass('flex items-center flex-wrap')
        )
    )
);

/* ====== Render page ====== */
render();
