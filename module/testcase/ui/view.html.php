<?php
declare(strict_types=1);
/**
 * The view view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/promptmenu.html.php');

jsVar('viewParams', "caseID={$case->id}&version={$version}&from={$from}&taskID={$taskID}&stepsType=");

$isInModal = isInModal();

/* 初始化头部右上方工具栏。Init detail toolbar. */
$toolbar = array();
if(!$isInModal && hasPriv('testcase', 'create', $case))
{
    $toolbar[] = array
    (
        'icon' => 'plus',
        'type' => 'primary',
        'text' => $lang->case->create,
        'url'  => createLink('testcase', 'create', "productID={$case->product}&branch={$case->branch}&moduleID={$case->module}")
    );
}

/* 初始化底部操作栏。Init bottom actions. */
$actions = $this->loadModel('common')->buildOperateMenu($case);
$actions = $actions = array_merge($actions['mainActions'], array(array('type' => 'divider')), $actions['suffixActions']);
foreach($actions as $index => $action)
{
    if(!isset($action['url'])) continue;
    $actions[$index]['url'] = str_replace('%executionID%', (string)$this->session->execution, $action['url']);
}

$steps = array();
if(!empty($case->steps) && $stepsType == 'table')
{
    foreach($case->steps as $step)
    {
        $stepClass = $step->type == 'step' ? 'step-group' : "step-{$step->type}";
        $stepClass .= count($steps) > 0 && $step->grade == 1 ? ' mt-2' : ' border-t-0';

        $steps[] = cell
        (
            setClass("step {$stepClass} border align-top flex"),
            cell
            (
                setClass('text-left flex border-r step-id'),
                width('1/2'),
                span
                (
                    setClass('pr-2 pl-' . (($step->grade - 1) * 2)),
                    $step->name
                ),
                html(nl2br(str_replace(' ', '&nbsp;', $step->desc)))
            ),
            cell
            (
                setClass('text-left flex'),
                width('1/2'),
                html(nl2br(str_replace(' ', '&nbsp;', $step->expect)))
            )
        );
    }
}
$stepsTable = !empty($case->steps) ? div
(
    $stepsType == 'table' ? div
    (
        setID('stepsTable'),
        div
        (
            setClass('steps-header'),
            div
            (
                setClass('text-left inline-block steps border'),
                width('1/2'),
                $lang->testcase->stepDesc
            ),
            div
            (
                setClass('text-left inline-block border border-l-0'),
                width('1/2'),
                $lang->testcase->stepExpect
            )
        ),
        div
        (
            setClass('steps-body'),
            $steps
        )
    ) : div
    (
        setID('stepsView'),
        mindmap
        (
            set::data($case->mindMapSteps),
            set::readonly(true)
        )
    )
) : div
(
    setClass('canvas text-center py-2'),
    p
    (
        setClass('py-2 my-2'),
        span
        (
            setClass('text-gray'),
            $lang->noData
        )
    )
);

$stepsActions = array();
$stepsActions['items'][] = array('icon' => 'table-large', 'size' => 'xs', 'type' => $stepsType == 'table' ? 'primary' : 'ghost', 'class' => 'mr-2', 'url' => createLink('testcase', 'view', "caseID={$case->id}&version={$case->version}&from={$from}&taskID={$taskID}&stepsType=table"));
$stepsActions['items'][] = array('icon' => 'tree', 'size' => 'xs', 'type' => $stepsType == 'mindmap' ? 'primary' : 'ghost', 'url' => createLink('testcase', 'view', "caseID={$case->id}&version={$case->version}&from={$from}&taskID={$taskID}&stepsType=mindmap"));

/* 初始化主栏内容。Init sections in main column. */
$sections = array();
$sections[] = setting()
    ->title($lang->testcase->precondition)
    ->control('html')
    ->content($case->precondition);

$sections[] = setting()
    ->title($lang->testcase->steps)
    ->titleActions($stepsActions)
    ->children(wg($stepsTable));

$sections[] = setting()
    ->control('fileList')
    ->files($case->files)
    ->showDelete(false)
    ->padding(false)
    ->object($case);

/* 初始化侧边栏标签页。Init sidebar tabs. */
$tabs = array();

$tabs[] = setting()
    ->group('basic')
    ->title($lang->testcase->legendBasicInfo)
    ->control('caseBasicInfo');

$tabs[] = setting()
    ->group('basic')
    ->title($lang->testcase->legendOpenAndEdit)
    ->control('caseTimeInfo');

$tabs[] = setting()
    ->group('relatives')
    ->title($lang->testcase->legendOther)
    ->control('caseRelatedList');

detail
(
    set::urlFormatter(array('{caseID}' => $case->caseID, '{version}' => $case->version, '{product}' => $case->product, '{branch}' => $case->branch, '{module}' => $case->module, '{id}' => $case->id, '{lib}' => $case->lib)),
    set::toolbar($toolbar),
    set::sections($sections),
    set::tabs($tabs),
    set::actions($actions)
);
