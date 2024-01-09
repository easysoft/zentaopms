<?php
declare(strict_types=1);
/**
* The UI file of product module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     product
* @link        https://www.zentao.net
*/

namespace zin;

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
if($branchStatus != 'closed')
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

/* Get column settings of the data table. */
foreach($config->productProject->showFields as $showField)
{
    if(isset($config->project->dtable->fieldList[$showField])) $cols[$showField] = $config->project->dtable->fieldList[$showField];
    if($showField == 'program')
    {
        $cols[$showField]['name']     = 'programName';
        $cols[$showField]['fixed']    = 'left';
        $cols[$showField]['type']     = 'shortTitle';
        $cols[$showField]['checkbox'] = false;
        $cols[$showField]['title']    = $lang->project->program;
    }
    $cols[$showField]['sortType'] = false;
    $cols[$showField]['group']    = 0;
}
$cols['id']['checkbox'] = false;

$cols['PM']['type']        = 'link';
$cols['PM']['link']        = helper::createLink('user', 'profile', 'userID={PMUserID}', '', true);
$cols['PM']['data-toggle'] = 'modal';

if(!in_array($this->config->systemMode, array('ALM', 'PLM'))) unset($cols['program']);
if(!str_contains('all,undone', $status)) unset($cols['status']);

/* Set extend fields for workflow. */
$extendFieldList = $this->product->getFlowExtendFields();
foreach($extendFieldList as $field => $name)
{
    $extCol = $config->product->dtable->extendField;
    $extCol['name']  = $field;
    $extCol['title'] = $name;
    $cols[$field]    = $extCol;
}

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

    $project = $this->project->formatDataForList($project, $PMList);
}
$summary = sprintf($lang->project->summary, count($projectStats));
if($status == 'all') $summary = sprintf($lang->project->allSummary, count($projectStats), $waitCount, $doingCount, $suspendedCount, $closedCount);

dtable
(
    set::cols($cols),
    set::data(array_values($projectStats)),
    set::onRenderCell(jsRaw('function(result, data){return window.renderCustomCell(result, data);}')),
    set::footer(array(array('html' => $summary, 'className' => "text-dark"), 'flex', 'pager')),
    set::footPager(usePager()),
    set::emptyTip($lang->project->empty),
    set::createTip($lang->project->create),
    set::createLink($branchStatus != 'closed' && hasPriv('project', 'create') ? createLink('project', 'createGuide', "programID=$product->program&from=project&productID={$product->id}&branchID=$branchID", '', true) : ''),
    set::createAttr("data-toggle='modal'")
);

render();
