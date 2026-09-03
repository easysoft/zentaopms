<?php
declare(strict_types=1);
/**
* The UI file of product module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     product
* @link        https://www.zentao.net
*/

namespace zin;

$this->app->loadLang('program');
$this->app->loadLang('task');
jsVar('langManDay',   $lang->program->manDay);
jsVar('delayWarning', $lang->task->delayWarning);

dropmenu(set::text($product->name));

/* Set feature bar. */
featureBar
(
    set::current($status),
    set::linkParams("status={key}&productID={$product->id}&branch={$branchID}&involved={$involved}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    checkbox
    (
        set::id('involved'),
        set::name('involved'),
        set::checked($this->cookie->involved),
        set::text($lang->project->mine)
    ),
    item(set(array
    (
        'icon' => "help",
        'text' => '',
        'title' => $lang->product->projectInfo
    )))
);

/* Set right toolbar. */
$canBeChanged = common::canModify('product', $product);
if($branchStatus != 'closed' && $canBeChanged)
{
    toolbar
    (
        !hasPriv('project', 'manageProducts') ? null : item(set(array
        (
            'icon'        => 'link',
            'text'        => $lang->product->link2Project,
            'class'       => "secondary",
            'url'         => '#link2Project',
            'data-toggle' => 'modal'
        ))),
        !hasPriv('project', 'create') ? null : item(set(array
        (
            'icon'        => 'plus',
            'text'        => $lang->project->create,
            'class'       => "primary create-project-btn",
            'url'         => $this->createLink('project', 'createGuide', "programID=$product->program&from=project&productID={$product->id}&branchID=$branchID", '', true),
            'data-toggle' => 'modal',
            'data-type'   => 'ajax'
        )))
    );
}

/* Create link2Project modal. */
modal
(
    set::id('link2Project'),
    set::title($lang->product->link2Project),
    set::footerClass('form-actions'),
    setData('size', '500px'),
    on::click('#saveButton', 'link2Project(e)'),
    to::footer
    (
        btn(setClass('primary'), set::id('saveButton'), $lang->save),
        btn(setClass('default'), set('data-dismiss', 'modal'), $lang->cancel)
    ),
    picker(setClass('pt-2'), set::name('project'), set::items($projects)),
    input(set::type('hidden'), set::name('product'), set::value($product->id)),
    input(set::type('hidden'), set::name('branch'), set::value($branchID))
);

if($config->edition != 'open') $config->project->dtable->fieldList['workflowGroup']['map'] = $this->loadModel('workflowGroup')->getPairs('project', 'all', 1, 'all');
$settings = $this->loadModel('datatable')->getSetting('product', 'project');
$settings['id']['checkbox'] = false;

if(in_array($this->config->systemMode, array('ALM', 'PLM')))
{
    $programCol = array(
        'name'     => 'programName',
        'title'    => $lang->project->program,
        'type'     => 'shortTitle',
        'sortType' => false,
        'required' => true,
        'show'     => true,
        'group'    => 0,
    );
    $settings = array('program' => $programCol) + $settings;
}

if(!str_contains('all,undone', $status)) unset($settings['status']);

$tableData = initTableData($projectStats, $settings, $this->project);

/* Process data. */
$waitCount      = 0;
$doingCount     = 0;
$suspendedCount = 0;
$closedCount    = 0;
foreach($projectStats as $project)
{
    if($project->status == 'wait')      $waitCount ++;
    if($project->status == 'doing')     $doingCount ++;
    if($project->status == 'suspended') $suspendedCount ++;
    if($project->status == 'closed')    $closedCount ++;
}
$summary = sprintf($lang->project->summary, count($projectStats));
if($status == 'all') $summary = sprintf($lang->project->allSummary, count($projectStats), $waitCount, $doingCount, $suspendedCount, $closedCount);

$sortLink = createLink('product', 'project', "status={$status}&productID={$product->id}&branch={$branchID}&involved={$involved}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");

dtable
(
    set::id('table-product-project'),
    set::userMap($users),
    set::cols($settings),
    set::data(array_values($tableData)),
    set::orderBy($orderBy),
    set::sortLink($sortLink),
    set::customCols(true),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footer(array(array('html' => $summary, 'className' => "text-dark"), 'flex', 'pager')),
    set::footPager(usePager()),
    set::emptyTip($lang->project->empty),
    set::createTip($lang->project->create),
    set::createLink($branchStatus != 'closed' && hasPriv('project', 'create') && $canBeChanged ? createLink('project', 'createGuide', "programID=$product->program&from=project&productID={$product->id}&branchID=$branchID", '', true) : ''),
    set::createAttr("data-toggle='modal'")
);

render();
