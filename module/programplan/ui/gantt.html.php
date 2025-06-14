<?php
declare(strict_types=1);
/**
 * The gantt view file of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     programplan
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

data('fileName', 'gantt-export-' . $projectID);
include './ganttfields.html.php';

$isFromDoc = $from === 'doc';
if($isFromDoc)
{
    jsVar('ganttOptions', $plans);
    jsVar('ganttFields', $ganttFields);
    jsVar('showFields', $showFields);

    $this->app->loadLang('doc');
    $projectChangeLink = createLink('programPlan', 'browse', "projectID={projectID}&productID={$productID}&type={$type}&orderBy=$orderBy&baselineID=&browseType={$browseType}&queryID={$queryID}&from=$from&blockID=$blockID");
    $insertLink        = createLink('programPlan', 'browse', "projectID=$projectID&productID={$productID}&type={$type}&orderBy=$orderBy&baselineID=&browseType={$browseType}&queryID={$queryID}&from=$from&blockID={blockID}");

    formPanel
    (
        setID('zentaolist'),
        setClass('mb-4-important'),
        set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['gantt'])),
        set::actions(array()),
        set::showExtra(false),
        to::titleSuffix
        (
            span
            (
                setClass('text-muted text-sm text-gray-600 font-light'),
                span
                (
                    setClass('text-warning mr-1'),
                    icon('help'),
                ),
                $lang->doc->previewTip
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('project'),
                set::label($lang->doc->project),
                set::control(array('required' => false)),
                set::items($projects),
                set::value($projectID),
                set::required(),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                ),
                on::change('[name="project"]')->do("loadModal('$projectChangeLink'.replace('{projectID}', $(this).val()))")
            )
        )
    );
}

if($app->rawModule == 'programplan' && !$isFromDoc)
{
    $productDropdown = null;
    if($project->stageBy == 'product')
    {
        $viewName = $productID != 0 ? zget($productList, $productID) : $lang->product->allProduct;
        $items    = array(array('text' => $lang->product->allProduct, 'url' => $this->createLink('programplan', 'browse', "projectID=$projectID&productID=0&type=gantt"), 'active' => $productID == 'all' || $productID == '0'));
        foreach($productList as $key => $productName) $items[] = array('text' => $productName, 'url' => $this->createLink('programplan', 'browse', "projectID=$projectID&productID=$key&type=gantt"), 'active' => ($productID == $key || ($key == 0 && $productID == 'all')));
        $productDropdown = dropdown
        (
            btn(set::type('link'), setClass('no-underline'), $viewName),
            set::items($items)
        );
    }
    featureBar
    (
        btn(setClass('ghost mr-2', ($browseType != 'bysearch' ? 'active' : '')), $lang->programplan->gantt, set::url($this->createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=gantt"))),
        $productDropdown,
        $hasSearch ? li(searchToggle(set::module('projectTask'), set::open($browseType == 'bysearch'))) : null
    );
    toolbar
    (
        btnGroup
        (
            btn(setClass('square switchBtn text-primary'), set::title($lang->programplan->gantt), icon('gantt-alt')),
            btn(setClass('square switchBtn'), set::title($lang->project->bylist), set::url($this->createLink('project', 'execution', "status=all&projectID=$projectID")), icon('list'))
        ),
        btn(setClass('no-underline text-primary'), set::type('link'), setID('criticalPath'), $lang->execution->gantt->showCriticalPath, set::url('javascript:updateCriticalPath()')),
        btn(setClass('no-underline'), set::type('link'), setID('fullScreenBtn'), set::icon('fullscreen'), $lang->programplan->full),
        dropdown
        (
            btn(set::type('link'), setClass('no-underline'), set::icon('export'), $lang->export),
            set::items(array
            (
                array('text' => $lang->execution->gantt->exportImg, 'url' => 'javascript:exportGantt()'),
                array('text' => $lang->execution->gantt->exportPDF, 'url' => 'javascript:exportGantt("pdf")')
            ))
        ),
        btn(set::url($this->createLink('programplan', 'ajaxcustom')), set::icon('cog-outline'), $lang->settings, setClass('no-underline'), set::type('link'), set('data-toggle', 'modal'), set('data-size', 'sm')),
        common::hasPriv('programplan', 'relation') ? btn(set::url($this->createLink('programplan', 'relation', "projectID={$projectID}")), set::icon('list-alt'), $lang->programplan->setTaskRelation, setClass('no-underline'), set::type('link')) : null,
        (common::canModify('project', $project) && common::hasPriv('programplan', 'create') && empty($product->deleted)) ? btn(set::url($this->createLink('programplan', 'create', "projectID=$projectID")), set::icon('plus'), $lang->programplan->create, setClass('primary programplan-create-btn')) : null
    );
}

gantt
(
    set('ganttLang', $ganttLang),
    set('ganttFields', $ganttFields),
    set('canEdit', $isFromDoc ? false : hasPriv('programplan', 'ganttEdit')),
    set('canEditDeadline', $isFromDoc ? false : hasPriv('review', 'edit')),
    set('zooming', isset($zooming) ? $zooming : 'day'),
    set('showChart', !$dateDetails),
    set('options', $plans)
);

$isFromDoc ? btn
(
    setClass('mt-4'),
    set::type('primary'),
    on::click("insertToDoc($blockID, '$insertLink')"),
    $lang->doc->insertText
) : null;
