<?php
declare(strict_types=1);
/**
 * The ajaxGetTestStories view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

$requiredFields = array();
foreach(explode(',', $config->task->create->requiredFields) as $field)
{
    if($field) $requiredFields[$field] = '';
}

$taskTR = array();
$i      = 0;
foreach($testStories as $storyID => $storyTitle)
{
    $taskTR[] = h::tr
        (
            h::td
            (
                setClass('border-b border-l'),
                div($storyID, setClass('ml-2 flex items-center h-8'))
            ),
            h::td
            (
                setClass('c-testStory'),
                picker
                (
                    setID("testStory{$i}"),
                    set::name("testStory[$i]"),
                    set::value($storyID),
                    set::items($testStories),
                    set::required(true)
                )
            ),
            h::td
            (
                setClass('c-testPri'),
                priPicker
                (
                    setID("testPri{$i}"),
                    set::name("testPri[$i]"),
                    set::required(true),
                    set::value(empty($task->pri) ? 3 : $task->pri),
                    set::items(array_filter($lang->task->priList))
                )
            ),
            h::td
            (
                setClass('c-testEstStarted'),
                datepicker
                (
                    setID("testEstStarted{$i}"),
                    set::name("testEstStarted[$i]"),
                    set::placeholder($lang->task->estStarted),
                    set::value(empty($task->estStarted) ? '' : $task->estStarted)
                )
            ),
            h::td
            (
                setClass('text-center text-gray border-b'),
                span('—')
            ),
            h::td
            (
                setClass('c-testDeadline'),
                datepicker
                (
                    setID("testDeadline{$i}"),
                    set::name("testDeadline[$i]"),
                    set::placeholder($lang->task->deadline),
                    set::value(empty($task->deadline) ? '' : $task->deadline)
                )
            ),
            h::td
            (
                setClass('c-testAssignedTo'),
                picker
                (
                    setID("testAssignedTo{$i}"),
                    set::name("testAssignedTo[$i]"),
                    set::value(empty($task->assignedTo) ? '' : $task->assignedTo),
                    set::items($members)
                )
            ),
            h::td
            (
                inputControl
                (
                    setClass('c-estimate'),
                    input
                    (
                        setID("testEstimate{$i}"),
                        set::name("testEstimate[$i]")
                    ),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20)
                )
            ),
            h::td
            (
                setClass('c-actions'),
                btnGroup
                (
                    set::items(array(
                        array('class' => 'btn ghost text-gray', 'icon' => 'plus', 'onclick' => 'addItem(this)'),
                        array('class' => 'btn ghost text-gray', 'icon' => 'trash', 'onclick' => 'removeItem(this)')
                    ))
                )
            )
        );
    $i ++;
}

h::table
(
    setClass('table table-form'),
    setID('testTaskTable'),
    h::thead
    (
        h::tr
        (
            setClass('text-gray text-left'),
            h::th('ID'),
            h::th($lang->task->storyAB),
            h::th
            (
                $lang->task->pri,
                setClass(isset($requiredFields['pri']) ? 'required' : '')
            ),
            h::th
            (
                $lang->task->datePlan,
                set('colspan', 3),
                setClass((isset($requiredFields['estStarted']) || isset($requiredFields['deadline'])) ? 'required' : '')
            ),
            h::th
            (
                $lang->task->assignedTo,
            ),
            h::th
            (
                $lang->task->estimateAB,
                setClass(isset($requiredFields['estimate']) ? 'required' : '')
            ),
            h::th
            (
                setClass('c-actions'),
            )
        ),
        h::col(setStyle('width', '80px')),
        h::col(setStyle('width', 'auto')),
        h::col(setStyle('width', '80px')),
        h::col(setStyle('width', '140px')),
        h::col(setStyle('width', '30px')),
        h::col(setStyle('width', '140px')),
        h::col(setStyle('width', '120px')),
        h::col(setStyle('width', '100px')),
        h::col(setStyle('width', '70px')),
    ),
    h::tbody
    (
        $taskTR
    )
);

/* ====== Render page ====== */
render();
