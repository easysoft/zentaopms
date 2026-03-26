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

include './ganttfields.html.php';

$showFields = str_replace('PM', 'owner_id', $showFields);
$isHistory  = is_numeric($versionID) && $versionID > 0;
$isFromDoc  = $from === 'doc';
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
    if($project->stageBy == 'product' && empty($project->isTpl))
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

    /* Build versions for dropdown. */
    $versionItems   = array();
    $currentVersion = $lang->project->version;
    foreach($versions as $version)
    {
        $item = array('title' => $version->version, 'value' => $version->id, 'hint' => $version->version);
        if($version->reviewType == 'deliverable') $item['text'] = "[{$lang->project->deliverableAbbr}]";
        if($version->reviewType == 'baseline')    $item['text'] = "[{$lang->project->baseline}]";
        if($version->reviewType == 'gantt')
        {
            $item['hint']    = $version->items;
            $item['actions'] = array();
            if(hasPriv('programplan', 'editGanttVersion'))   $item['actions'][] = array('icon' => 'edit',  'hint' => $lang->edit,   'url' => createLink('programplan', 'editGanttVersion', "versionID={$version->id}"), 'data-toggle' => 'modal');
            if(hasPriv('programplan', 'deleteGanttVersion')) $item['actions'][] = array('icon' => 'trash', 'hint' => $lang->delete, 'url' => createLink('programplan', 'deleteGanttVersion', "versionID={$version->id}"), 'class' => 'ajax-submit', 'data-confirm' => $lang->confirmDelete);
        }

        if($version->id == $versionID)
        {
            $currentVersion = $version->version;
            $item['class']  = 'selected';
        }
        $versionItems[$version->id] = $item;
    }

    $item = array('title' => $lang->project->latestVersion, 'value' => 0);
    if(hasPriv('programplan', 'createGanttVersion')) $item['actions'] = array(array('text' => $lang->project->saveVersion, 'class' => 'btn size-sm danger-outline rounded-full', 'url' => createLink('programplan', 'createGanttVersion', "projectID=$projectID"), 'data-toggle' => 'modal'));
    $versionItems['nowait'] = array('title' => $lang->project->realProgress, 'value' => 'nowait');
    $versionItems['0']      = $item;
    if($versionID == 'nowait') $currentVersion = $lang->project->realProgress;
    if($versionID == '0' && isset($ganttBaseline)) $currentVersion = $lang->project->latestVersion;

    $langData = [];
    $langData['allVersions'] = $lang->project->allVersions;
    $langData['compare']     = $lang->project->diffVersion;
    $langData['confirm']     = $lang->confirm;
    $langData['cancel']      = $lang->cancel;

    featureBar
    (
        btn(setClass('ghost mr-2', ($browseType != 'bysearch' ? 'active' : '')), $lang->project->featureBar['browse']['all'], set::url($this->createLink('programplan', 'browse', "projectID=$projectID&productID=$productID&type=gantt"))),
        $productDropdown,
        $hasSearch ? li(searchToggle(set::module('projectTask'), set::open($browseType == 'bysearch'))) : null,
        li
        (
            setID('versionList'),
            setClass('ml-2'),
            setStyle('order', '20000'),
            dropdown
            (
                h::css
                (
                    '.menu-item.selected {background: var(--menu-selected-bg); color: var(--menu-selected-color);}',
                    '.menu-item .item-content {overflow:hidden; text-overflow:clip;}',
                    '.menu-item .item-title {flex:none;}'
                ),
                jsVar('versionLangData', $langData),
                jsVar('versionID', $versionID),
                jsVar('currentVersion', $currentVersion),
                jsVar('+diffMode', isset($ganttBaseline)),
                jsVar('browseTemplate', createLink('programplan', 'browse', "projectID=$projectID&productID={$productID}&type={$type}&orderBy=$orderBy&baselineID=&browseType={$browseType}&queryID={$queryID}&from={$from}&blockID={$blockID}&versionID=%s")),
                div
                (
                    btn
                    (
                        setID('versionBox'),
                        setClass('ghost gray-300-outline rounded-full'),
                        $currentVersion,
                        isset($ganttBaseline) ? setData(array('value' => $versionID)) : null,
                        span(setClass('caret'))
                    ),
                    span
                    (
                        setID('compareBox'),
                        setClass(isset($ganttBaseline) ? '' : 'hidden'),
                        btn
                        (
                            setClass('ghost size-sm'),
                            icon('exchange'),
                            on::click()->call('exchangeVersion', jsRaw('event'))
                        ),
                        btn
                        (
                            setID('nextBox'),
                            setClass('ghost gray-300-outline rounded-full'),
                            isset($ganttBaseline) ? zget(zget($versionItems, $ganttBaseline, array()), 'title') : null,
                            isset($ganttBaseline) ? setData(array('value' => $ganttBaseline)) : null,
                            span(setClass('caret'))
                        )
                    )
                ),
                set::menu([
                   'checkOnClick' => '.has-checkbox .item',
                   'items' => array_values($versionItems),
                   'width' => 200,
                   'header' => jsRaw('setVersionDropdownHeader'),
                   'footer' => jsRaw('setVersionDropdownFooter'),
                   'getItem' => jsRaw('getVersionItem'),
                   'onClickItem' => jsRaw('setClickVersionItem')
                ]),
                set::triggerProps([
                    'onShown' => jsRaw('showMenu'),
                    'onHide' => jsRaw('function(){return !this.menu.state.showCheckbox}')
                ]),
            )
        ),
    );
    toolbar
    (
        btnGroup
        (
            btn(setClass('square switchBtn text-primary'), set::title($lang->programplan->gantt), icon('gantt-alt')),
            btn(setClass('square switchBtn'), set::title($lang->project->bylist), set::url($this->createLink('project', 'execution', "status=all&projectID=$projectID")), icon('list'))
        ),
        dropdown
        (
            btn(set::type('link'), setClass('no-underline'), set::icon('export'), $lang->export),
            set::items(array
            (
                array('text' => $lang->execution->gantt->exportImg, 'url' => 'javascript:exportGantt()'),
                array('text' => $lang->execution->gantt->exportPDF, 'url' => 'javascript:exportGantt("pdf")')
            ))
        ),
        common::hasPriv('programplan', 'relation') ? btn(set::url($this->createLink('programplan', 'relation', "projectID={$projectID}")), set::icon('list-alt'), $lang->programplan->setTaskRelation, setClass('no-underline'), set::type('link')) : null,
        (common::canModify('project', $project) && common::hasPriv('programplan', 'create') && empty($product->deleted)) ? btn(set::url($this->createLink('programplan', 'create', "projectID=$projectID&productID=$productID")), set::icon('plus'), $lang->programplan->create, setClass('primary programplan-create-btn')) : null
    );
}

gantt
(
    set('ganttLang', $ganttLang),
    set('ganttFields', $ganttFields),
    set('canEdit', $isFromDoc || $isHistory ? false : hasPriv('programplan', 'ganttEdit')),
    set('canEditDeadline', $isFromDoc || $isHistory ? false : hasPriv('review', 'edit')),
    set('zooming', isset($zooming) ? $zooming : 'day'),
    set('showChart', !$dateDetails),
    set('users', $users),
    set('showFields', $showFields),
    set::root($projectID),
    set::settingLink(createLink('programplan', 'ajaxcustom')),
    set::toolbar(array('criticalPath', 'fullscreen', 'setting')),
    set::exportFileName('gantt-export-' . $projectID),
    set::weekend(array('weekend' => zget($config->execution, 'weekend', 2), 'restDay' => zget($config->execution, 'restDay', 0))),
    set::holidays($holidays),
    set::workingDays($workingDays),
    set('options', $plans)
);

$isFromDoc ? btn
(
    setClass('mt-4'),
    set::type('primary'),
    on::click("insertToDoc($blockID, '$insertLink')"),
    $lang->doc->insertText
) : null;
