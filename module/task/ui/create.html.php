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

jsVar('window.executionID', $execution->id);
jsVar('vision', $config->vision);
jsVar('window.lifetime', $execution->lifetime);
jsVar('window.attribute', $execution->attribute);
jsVar('window.lifetimeList', $lifetimeList);
jsVar('window.attributeList', $attributeList);
jsVar('teamMemberError', $lang->task->error->teamMember);
jsVar('requiredFields', $config->task->create->requiredFields);
jsVar('estimateNotEmpty', sprintf($lang->error->gt, $lang->task->estimate, '0'));
jsVar('taskID', $taskID ?? 0);

$fields = useFields('task.create');
$fields->autoLoad('execution', 'execution,type,name,assignedToBox,region,lane,module,storyBox,datePlan,pri,estimate,desc,files,mailto,keywords,after,testStoryBox');

$fields->orders('name,assignedToBox', 'module,testStoryBox', 'desc,module,storyBox');
$fields->fullModeOrders('type,module,storyBox', 'desc,files,mailto,keywords');
if($execution->type == 'kanban')
{
    $fields->orders('desc,module,storyBox', 'type,assignedToBox,region,lane');
    $fields->fullModeOrders('name,assignedToBox', 'type,module,storyBox', 'desc,files,mailto,keywords');
    if(empty($features['story'])) $fields->fullModeOrders('type,module,storyBox', 'name,assignedToBox', 'desc,files,mailto,keywords');
}

if(empty($features['story']) && $execution->type != 'kanban')
{
    $fields->fullModeOrders('type,module,storyBox,assignedToBox', 'desc,files,mailto,keywords');
}

$teamForm = array();
if(empty($task->team))
{
    for($i = 1; $i <= 3; $i ++)
    {
        $teamForm[] = h::tr
            (
                h::td
                (
                    setClass('team-index'),
                    span
                    (
                        setClass("team-number"),
                        $i
                    ),
                    icon('angle-down')
                ),
                h::td
                (
                    set::width('240px'),
                    setClass('team-member'),
                    picker
                    (
                        set::name("team[]"),
                        set::items($members)
                    )
                ),
                h::td
                (
                    set::width('135px'),
                    inputControl
                    (
                        input
                        (
                            set::name("teamEstimate[]"),
                            set::placeholder($lang->task->estimateAB)
                        ),
                        to::suffix($lang->task->suffixHour),
                        set::suffixWidth(20)
                    )
                ),
                h::td
                (
                    set::width('100px'),
                    setClass('center'),
                    btnGroup
                    (
                        set::items(array(
                            array('icon' => 'plus',  'class' => 'btn ghost btn-add'),
                            array('icon' => 'trash', 'class' => 'btn ghost btn-delete')
                        ))
                    )
                )
            );
    }
}
else
{
    $i = 0;
    foreach($task->team as $member)
    {
        $i ++;
        $teamForm[] = h::tr
            (
                h::td
                (
                    setClass('team-index'),
                    span
                    (
                        setClass("team-number"),
                        $i
                    ),
                    icon('angle-down')
                ),
                h::td
                (
                    set::width('240px'),
                    picker
                    (
                        set::name("team[]"),
                        set::items($members),
                        set::value($member->account)
                    )
                ),
                h::td
                (
                    set::width('135px'),
                    inputControl
                    (
                        input
                        (
                            set::name("teamEstimate[]"),
                            set::placeholder($lang->task->estimateAB),
                            set::value($member->estimate)
                        ),
                        to::suffix($lang->task->suffixHour),
                        set::suffixWidth(20)
                    )
                ),
                h::td
                (
                    set::width('100px'),
                    setClass('center'),
                    btnGroup
                    (
                        set::items(array(
                            array('icon' => 'plus',  'class' => 'btn ghost btn-add'),
                            array('icon' => 'trash', 'class' => 'btn ghost btn-delete')
                        ))
                    )
                )
            );
    }
}

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
    on::change('[name=selectTestStory]', 'toggleSelectTestStory'),
    on::change('.team-member [name^=team]', 'changeTeamMember'),
    on::change('[name=execution]', 'loadAll'),
    on::click('[name=isShowAllModule]', 'showAllModule'),
    on::click('[name=copyButton]', 'copyStoryTitle'),
    on::click('.assignedToList .picker-multi-selection', 'removeTeamMember'),
    on::keyup('[name=name]', 'saveTaskName'),
    on::keyup('[name=estimate]', 'saveTaskEstimate'),
    modal
    (
        setID('modalTeam'),
        set::title($lang->task->teamMember),
        h::table
        (
            setID('teamTable'),
            h::tr
            (
                h::td
                (
                    width('90px'),
                    $lang->task->mode
                ),
                h::td
                (
                    picker
                    (
                        set::name("mode"),
                        !empty($task->mode) ? set::value($task->mode) : set::value("linear"),
                        set::items($lang->task->modeList),
                        set::required(true)
                    )
                )
            ),
            setClass('table table-form'),
            $teamForm,
            h::tr
            (
                h::td
                (
                    setClass('team-saveBtn'),
                    set(array('colspan' => 4)),
                    btn
                    (
                        setClass('toolbar-item btn primary'),
                        $lang->save
                    )
                )
            )
        )
    )
);
