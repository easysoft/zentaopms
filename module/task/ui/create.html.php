<?php
declare(strict_types=1);
/**
 * The create view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tian Shujie<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

jsVar('window.executionID', $execution->id);
jsVar('vision', $config->vision);
jsVar('window.lifetime', $execution->lifetime);
jsVar('window.attribute', $execution->attribute);
jsVar('window.lifetimeList', $lifetimeList);
jsVar('window.attributeList', $attributeList);
jsVar('teamMemberError', $lang->task->error->teamMember);
jsVar('requiredFields', $config->task->create->requiredFields);
jsVar('estimateNotEmpty', sprintf($lang->error->gt, $lang->task->estimateAB, '0'));
jsVar('taskID', $taskID ?? 0);
jsVar('toTaskList', !empty($task->id));
jsVar('showFields', $showFields);
jsVar('canViewStory', common::hasPriv('execution', 'storyView'));
jsVar('ignoreLang', $lang->project->ignore);

if(!empty($task->team))
{
    foreach($task->team as $member)
    {
        $member->team         = $member->account;
        $member->teamEstimate = (float)$member->estimate;
    }
}

$fields = useFields('task.create');
$fields->autoLoad('execution', 'execution,type,name,assignedToBox,region,lane,module,storyBox,datePlan,pri,estimate,desc,files,mailto,keywords,after,testStoryBox');

$fields->orders('type,testStoryBox', 'type,testStoryBox,parent,assignedToBox', 'desc,module,storyBox');
$fields->fullModeOrders('type,module,storyBox,testStoryBox', 'desc,files,mailto,keywords');
if($execution->type == 'kanban' || empty(data('execution.multiple')))
{
    $fields->orders('desc,module,storyBox', 'type,parent,assignedToBox,testStoryBox,region,lane');
    $fields->fullModeOrders('name,assignedToBox', 'type,module,storyBox,testStoryBox', 'desc,files,mailto,keywords');
    if(empty($features['story'])) $fields->fullModeOrders('type,module,storyBox', 'name,assignedToBox', 'desc,files,mailto,keywords');
}

if(empty($features['story']) && $execution->type != 'kanban' && !empty(data('execution.multiple')))
{
    $fields->fullModeOrders('type,module,storyBox,testStoryBox,parent,assignedToBox', 'desc,files,mailto,keywords');
}
if($execution->lifetime == 'ops') $fields->fullModeOrders('type,module,storyBox,testStoryBox,parent,name,assignedToBox', 'desc,files,mailto,keywords');

formGridPanel
(
    set::title($lang->task->create),
    set::fields($fields),
    set::loadUrl($loadUrl),
    on::change('[name=module]', 'loadExecutionStories'),
    on::change('[name=story]', 'setStoryRelated'),
    on::change('[name=type]', 'typeChange'),
    on::change('[name=region]', 'loadLanes'),
    on::change('[name=multiple]', 'toggleTeam'),
    on::change('[name=parent]', 'getParentEstStartedAndDeadline'),
    on::change('[name=selectTestStory]', 'toggleSelectTestStory'),
    on::change('.team-member [name^=team]', 'changeTeamMember'),
    on::change('[name=execution]', 'loadAll'),
    on::change('[name=estStarted]', 'checkEstStartedAndDeadline'),
    on::change('[name=deadline]', 'checkEstStartedAndDeadline'),
    on::click('[name=isShowAllModule]', 'showAllModule'),
    on::click('[name=copyButton]', 'copyStoryTitle'),
    on::click('.assignedToList .picker-multi-selection', 'removeTeamMember'),
    on::click('#teamTable .team-saveBtn', 'checkTeamMember'),
    on::keyup('[name=name]', 'saveTaskName'),
    on::keyup('[name=estimate]', 'saveTaskEstimate'),
    modal
    (
        setID('modalTeam'),
        set::title($lang->task->addMember),
        div
        (
            setClass('flex items-center w-96 mb-4'),
            span(setClass('mr-4 w-16'), $lang->task->mode),
            picker
            (
                set::name("mode"),
                !empty($task->mode) ? set::value($task->mode) : set::value("linear"),
                set::items($lang->task->modeList),
                set::required(true)
            )
        ),
        formBatch
        (
            set::tagName('div'),
            setID('teamTable'),
            set::mode('add'),
            !empty($task->team) ? set::data(array_values($task->team)) : null,
            set::sortable(true),
            set::size('sm'),
            set::minRows(3),
            set::onRenderRow(jsRaw('renderRowData')),
            formBatchItem
            (
                set::name('id'),
                set::width('32px'),
                set::control('index')
            ),
            formBatchItem
            (
                set::name('team'),
                set::label($lang->task->teamMember),
                set::width('240px'),
                set::control('picker'),
                set::items($members)
            ),
            formBatchItem
            (
                set::name('estimateBox'),
                set::label($lang->task->estimateAB),
                set::width('135px'),
                set::control('inputGroup'),
                set::required(true),
                inputControl
                (
                    input(set::name("teamEstimate")),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20)
                )
            ),
            set::actions(array(array('text' => $lang->save, 'class' => 'primary team-saveBtn')))
        )
    )
);
