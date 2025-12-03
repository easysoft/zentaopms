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

$viewModule = $isLibCase ? 'caselib' : 'testcase';
$viewMethod = $isLibCase ? 'viewCase' : 'view';

/* 版本列表。Version list. */
$versions = array();
for($i = $case->version; $i >= 1; $i--)
{
    $versionItem = setting()
        ->text("#{$i}")
        ->set('data-app', $app->tab)
        ->url(createLink($viewModule, $viewMethod, "caseID={$case->id}&version={$i}&from={$from}&taskID={$taskID}&stepsType={$stepsType}"));

    if($isInModal)
    {
        $versionItem->set(array('data-load' => 'modal', 'data-target' => '.modal.show:not(.modal-hide)'));
    }

    $versionItem->selected($version == $i);
    $versions[] = $versionItem;
}
$versionBtn = count($versions) > 1 ? to::title(dropdown
(
    btn(set::type('ghost'), setClass('text-link font-normal text-base'), "#{$version}"),
    set::items($versions)
)) : null;

/* 初始化头部右上方工具栏。Init detail toolbar. */
$toolbar = array();
if(!$isInModal)
{
    if(!$isLibCase && hasPriv('testcase', 'create', $case)) $toolbar[] = array
    (
        'icon' => 'plus',
        'type' => 'primary',
        'text' => $lang->case->create,
        'url'  => createLink('testcase', 'create', "productID={$case->product}&branch={$case->branch}&moduleID={$case->module}")
    );

    if($isLibCase && hasPriv('caselib', 'createCase')) $toolbar[] = array
    (
        'icon' => 'plus',
        'type' => 'primary',
        'text' => $lang->case->create,
        'url'  => createLink('caselib', 'createCase', "libID={$case->lib}&module={$case->module}")
    );
}

/* 检查是否需要确认撤销/移除。*/
/* Build confirmeObject. */
if($this->config->edition == 'ipd')
{
    $testcase = $this->loadModel('story')->getAffectObject(array(), 'case', $case);

    if(!empty($testcase->confirmeActionType)) $config->testcase->actions->view['mainActions'] = array('confirmDemandRetract', 'confirmDemandUnlink');
    if(!empty($testcase->confirmeActionType)) $config->testcase->actions->view['suffixActions'] = array();
}

/* 初始化底部操作栏。Init bottom actions. */
$config->testcase->actionList['edit']['url'] = array('module' => 'testcase', 'method' => 'edit', 'params' => 'caseID={caseID}&comment=false&executionID=%executionID%&from={from}');
$actions = !$testcase->deleted ? $this->loadModel('common')->buildOperateMenu($case) : array();
if(!$testcase->deleted) $actions = array_merge($actions['mainActions'], !empty($actions['mainActions']) && !empty($actions['suffixActions']) ? array(array('type' => 'divider')) : array(), $actions['suffixActions']);
foreach($actions as $index => $action)
{
    if(!isset($action['url'])) continue;
    $actions[$index]['url'] = str_replace(array('%executionID%', '{runID}', '{from}'), array((string)$this->session->execution, (string)$runID, $from), $action['url']);

    if($isInModal && !isset($action['data-toggle']) && !isset($action['data-load']))
    {
        $actions[$index]['data-load'] = 'modal';
        $actions[$index]['data-size'] = 'lg';
    }
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
                setClass('text-left flex border-r step-id whitespace-pre-line'),
                width('1/2'),
                span
                (
                    setClass('nowrap pr-2 pl-' . (($step->grade - 1) * 2)),
                    $step->name
                ),
                text(html_entity_decode(str_replace(' ', '&nbsp;', $step->desc)))
            ),
            cell
            (
                setClass('text-left flex whitespace-pre-line'),
                width('1/2'),
                text(html_entity_decode(str_replace(' ', '&nbsp;', $step->expect)))
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
        setClass('relative'),
        mindmap
        (
            set::data($case->mindMapSteps),
            set::readonly(true)
        ),
        btn
        (
            setClass('ghost absolute z-1 top-1 right-1'),
            set::icon('fullscreen'),
            on::click()->call('zui.toggleFullscreen', '#stepsView')
        ),
        h::css('.is-in-fullscreen > .mindmap-iframe {height: 100%!important}')
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
$stepsMisc    = isInModal() ? array('data-load' => 'modal', 'data-target' => '.modal-content') : array();
$stepsActions['items'][] = $stepsMisc + array('icon' => 'table', 'data-app' => $app->tab, 'size' => 'xs', 'type' => $stepsType == 'table'   ? 'primary' : 'ghost', 'class' => 'mr-2', 'url' => createLink($viewModule, $viewMethod, "caseID={$case->id}&version={$case->version}&from={$from}&taskID={$taskID}&stepsType=table"));
$stepsActions['items'][] = $stepsMisc + array('icon' => 'tree',        'data-app' => $app->tab, 'size' => 'xs', 'type' => $stepsType == 'mindmap' ? 'primary' : 'ghost', 'url' => createLink($viewModule, $viewMethod, "caseID={$case->id}&version={$case->version}&from={$from}&taskID={$taskID}&stepsType=mindmap"));

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

$tabs['caseRelatedList'] = setting()
    ->group('relatives')
    ->title($lang->testcase->legendOther)
    ->control('caseRelatedList');

detail
(
    set::urlFormatter(array('{caseID}' => $case->caseID, '{version}' => $case->version, '{product}' => $case->product, '{branch}' => $case->branch, '{module}' => $case->module, '{id}' => $case->id, '{lib}' => $case->lib, '{confirmeObjectID}' => isset($case->confirmeObjectID) ? $case->confirmeObjectID : 0)),
    set::toolbar($toolbar),
    set::objectType('testcase'),
    set::object($case),
    set::sections($sections),
    set::tabs($tabs),
    set::actions($actions),
    $versionBtn
);
