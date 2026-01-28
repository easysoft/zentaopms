<?php
declare(strict_types=1);
/**
 * The feedback view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

jsVar('errorNoProject',   $lang->feedback->noProject);
jsVar('errorNoExecution', $lang->feedback->noExecution);

featureBar
(
    set::current($type),
    set::linkParams("mode={$mode}&type={key}&param=&orderBy={$orderBy}"),
    li(searchToggle(set::module($this->app->rawMethod . 'Feedback'), set::open($type == 'bysearch')))
);

foreach($feedbacks as $feedback)
{
    $feedback->solution = zget($lang->feedback->solutionList, $feedback->solution, '');
}

$cols = $this->loadModel('datatable')->getSetting($this->moduleName);
$cols['actions']['list']['edit']['data-toggle'] = 'modal';
$feedbacks = initTableData($feedbacks, $cols, $this->feedback);

if(!empty($cols['product'])) $cols['product']['map'] = $allProducts;
if(!empty($cols['module']))  $cols['module']['map']  = $modules;
if(!empty($cols['dept']))    $cols['dept']['map']    = $depts;

$canBatchEdit     = common::hasPriv('feedback', 'batchEdit');
$canBatchClose    = common::hasPriv('feedback', 'batchClose');
$canBatchAssignTo = common::hasPriv('feedback', 'batchAssignTo');
$canBatchAction   = $canBatchEdit || $canBatchClose || $canBatchAssignTo;

$footToolbar = array();
if($canBatchAction)
{
    $footToolbar['items'] = array();
    if($canBatchEdit)
    {
        $footToolbar['items'][] = array('text' => $lang->edit, 'className' => 'primary batch-btn not-open-url', 'data-url' => createLink('feedback', 'batchEdit', "browseType=$type&from={$app->rawMethod}"));
    }
    if($canBatchClose)
    {
        $footToolbar['items'][] = array('text' => $lang->close, 'className' => 'primary batch-btn not-open-url', 'data-url' => createLink('feedback', 'batchClose', "from={$app->rawMethod}"));
    }
    if($canBatchAssignTo)
    {
        $pinyinItems     = common::convert2Pinyin($users);
        $assignedToItems = array();
        foreach($users as $key => $value)
        {
            if($value) $assignedToItems[] = array('text' => $value, 'keys' => zget($pinyinItems, $value, ''), 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('feedback', 'batchAssignTo', "assignedTo=$key"));
        }
        $footToolbar['items'][] = array('caret' => 'up', 'text' => $lang->feedback->assignedTo, 'type' => 'dropdown', 'data-placement' => 'top-start', 'items' => $assignedToItems, 'data-menu' => array('searchBox' => true));
    }
    $footToolbar['btnProps'] = array('size' => 'sm', 'btnType' => 'secondary');
}

dtable
(
    set::cols(array_values($cols)),
    set::data(array_values($feedbacks)),
    set::checkable($canBatchAction),
    set::userMap($users),
    set::orderBy($orderBy),
    set::customCols(true),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$type}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager()),
    set::footToolbar($footToolbar)
);

modal
(
    setID('toTask'),
    set::modalProps(array('title' => $lang->feedback->selectProjects)),
    to::footer
    (
        div
        (
            setClass('toolbar gap-4 w-full justify-center'),
            btn($lang->feedback->nextStep, setID('toTaskButton'), setClass('primary'), set('data-on', 'click'), set('data-call', 'toTask')),
            btn($lang->cancel, setID('cancelButton'), setData(array('dismiss' => 'modal')))
        )
    ),
    formPanel
    (
        on::change('#taskProjects', 'changeTaskProjects'),
        set::actions(''),
        formRow
        (
            formGroup
            (
                set::label($lang->feedback->project),
                set::required(true),
                set::control('picker'),
                set::name('taskProjects'),
                set::items($projects),
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->feedback->execution),
                set::required(true),
                inputGroup
                (
                    setID('executionBox'),
                    picker
                    (
                        set::name('executions'),
                        set::items(array())
                    ),
                    input
                    (
                        setClass('hidden'),
                        set::name('feedbackID')
                    )
                )
            )
        )
    )
);

render();
