<?php
declare(strict_types=1);
/**
 * The UI view file of story module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('twinsCount', $twinsCount);
jsVar('langTwins', $lang->story->twins . ': ');
if(!empty($errorTips)) js("zui.Modal.alert({message: '{$errorTips}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'});\n");

unset($lang->story->reasonList['subdivided']);
foreach($lang->story->reasonList as $key => $value)
{
    if($key == 'cancel') continue;
    $reasonList[] = array('text' => $value, 'value' => $key);
}
jsVar('reasonList', $reasonList);

/* Build form field value for batch edit. */
$data = array();
foreach($stories as $storyID => $story)
{
    $data[$storyID] = $story;
    $data[$storyID]->storyIdList = $story->id;
    $data[$storyID]->statusName  = zget($lang->story->statusList, $story->status);
}

formBatchPanel
(
    set::title($lang->story->batchClose),
    set::mode('edit'),
    set::data(array_values($data)),
    set::onRenderRow(jsRaw('renderRowData')),
    on::change('[data-name="closedReason"]', 'toggleDuplicateBox'),
    /* Field of id. */
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('hidden'),
        set::hidden(true)
    ),
    /* Field of storyIdList. */
    formBatchItem
    (
        set::name('storyIdList'),
        set::label($lang->idAB),
        set::control('hidden'),
        set::hidden(true)
    ),
    /* Field of id index. */
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('60px')
    ),
    /* Field of title. */
    formBatchItem
    (
        set::name('title'),
        set::label($lang->story->title),
        set::control('static'),
        set::width('300px')
    ),
    /* Field of status. */
    formBatchItem
    (
        set::name('statusName'),
        set::label($lang->story->status),
        set::control('static'),
        set::width('60px')
    ),
    /* Field of closeReason. */
    formBatchItem
    (
        set::label($lang->story->closedReason),
        set::width('220px'),
        set::name('closedReasonBox'),
        set::control('inputGroup'),
        inputGroup
        (
            picker
            (
                setClass('closedReason-select'),
                set::name('closedReason'),
                set::items($lang->story->reasonList),
                set::required(true)
            ),
            picker
            (
                setClass('duplicate-select hidden'),
                set::name('duplicateStory'),
                set::items(array()),
            )
        )
    ),
    /* Field of comment. */
    formBatchItem
    (
        set::name('comment'),
        set::label($lang->comment),
        set::control('text'),
        set::width('300px')
    )
);

render();
