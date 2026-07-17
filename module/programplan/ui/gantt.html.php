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

$isDiffMode = isset($ganttBaseline);
$isHistory  = (is_numeric($versionID) && $versionID > 0) || $versionID == 'nowait';
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
        $items    = array(array('text' => $lang->product->allProduct, 'url' => $this->createLink('programplan', 'browse', "projectID=$projectID&productID=0"), 'active' => $productID == 'all' || $productID == '0'));
        foreach($productList as $key => $productName) $items[] = array('text' => $productName, 'url' => $this->createLink('programplan', 'browse', "projectID=$projectID&productID=$key"), 'active' => ($productID == $key || ($key == 0 && $productID == 'all')));
        $productDropdown = dropdown
        (
            btn(set::type('link'), setClass('no-underline'), $viewName),
            set::items($items)
        );
    }

    $hasFrozenStage = false;
    foreach($plans['data'] as $plan)
    {
        if(!empty($plan->frozen)) $hasFrozenStage = true;
    }

    /* Build versions for dropdown. */
    $browseTemplate = createLink('programplan', 'browse', "projectID=$projectID&productID={$productID}&type={$type}&orderBy=$orderBy&baselineID=&browseType={$browseType}&queryID={$queryID}&from={$from}&blockID={$blockID}&versionID=%s");
    $versionItems = array();
    $versionItems['gantt']       = array('text' => $lang->programplan->ganttVersion,       'type' => 'heading', 'items' => array());
    $versionItems['deliverable'] = array('text' => $lang->programplan->deliverableVersion, 'type' => 'heading', 'items' => array());
    $versionItems['tmpGantt']    = array('text' => $lang->programplan->tmpGanttVersion,    'type' => 'heading', 'items' => array());
    $currentVersion = $lang->project->version;
    foreach($versions as $version)
    {
        $item = array('title' => $version->version, 'value' => $version->id, 'hint' => $version->version);
        if($version->reviewType == 'deliverable')
        {
            $item['content'] = array('html' => "<span class='label rounded-full size-sm outline m-1'>{$lang->project->deliverableAbbr}</span>");
            if(!empty($version->baselineList)) $item['content']['html'] .= "<span class='label rounded-full size-sm outline m-1' title='{$version->baselineList}'>{$lang->project->baseline}</span>";
        }

        if($version->reviewType == 'gantt' && $version->status != 'tmpGantt')
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

        if(hasPriv('programplan', 'rollbackGanttVersion'))
        {
            if(!isset($item['actions'])) $item['actions'] = array();
            $item['actions'][] = array('icon' => 'undo', 'hint' => $hasFrozenStage ? $lang->programplan->frozenCallback : $lang->programplan->rollbackGanttVersion, 'url' => createLink('programplan', 'rollbackGanttVersion', "projectID={$projectID}&versionID={$version->id}"), 'data-confirm' => $project->model == 'ipd' ? $lang->programplan->rollbackTip4IPD : $lang->programplan->rollbackTip, 'className' => 'ajax-submit', 'disabled' => $hasFrozenStage);
        }

        if($version->reviewType == 'deliverable') $versionItems['deliverable']['items'][$version->id] = $item;
        if($version->reviewType == 'gantt' && $version->status != 'tmpGantt') $versionItems['gantt']['items'][$version->id] = $item;
        if($version->reviewType == 'gantt' && $version->status == 'tmpGantt') $versionItems['tmpGantt']['items'][$version->id] = $item;
    }

    $settingsValue = $this->loadModel('setting')->getItem("owner=system&module=project&section=&key=ganttVersionSettings");

    $headingItem     = array();
    $versionItemList = array();
    foreach($versionItems as $key => $versionList)
    {
        $headingItem = array('type' => 'heading', 'text' => $versionList['text']);
        foreach($versionList['items'] as $id => $versionItem)
        {
            if(strpos(",$settingsValue,", ",$key,") === false) continue;

            if(!isset($versionItemList[$key])) $versionItemList[$key] = $headingItem;
            $versionItemList[$id] = array_merge($versionItem, array('type' => 'item'));
        }
    }

    $settingsItems   = array();
    $settingsItems[] = array('title' => $lang->programplan->ganttVersion,       'value' => 'gantt',       'visible' => strpos(",$settingsValue,", ',gantt,') !== false);
    $settingsItems[] = array('title' => $lang->programplan->deliverableVersion, 'value' => 'deliverable', 'visible' => strpos(",$settingsValue,", ',deliverable,') !== false);
    $settingsItems[] = array('title' => $lang->programplan->tmpGanttVersion,    'value' => 'tmpGantt',    'visible' => strpos(",$settingsValue,", ',tmpGantt,') !== false);

    $item = array('title' => $lang->project->latestVersion, 'value' => 0, 'class' =>  $versionID == '0' ? 'selected' : '', 'className' => 'sticky canvas', 'style' => array('bottom' => '-8px', 'height' => '32px'));
    if(hasPriv('programplan', 'createGanttVersion') && $versionID == '0') $item['actions'] = array(array('text' => $lang->project->saveVersion, 'class' => 'btn size-sm danger-outline rounded-full border border-gray', 'url' => createLink('programplan', 'createGanttVersion', "projectID={$projectID}&productID={$productID}&type={$type}"), 'data-toggle' => 'modal'));
    $versionItemList['nowait'] = array('title' => $lang->project->realProgress, 'value' => 'nowait', 'class' =>  $versionID == 'nowait' ? 'selected' : '', 'className' => 'sticky canvas border-t', 'style' => array('bottom' => '24px', 'height' => '32px'));
    $versionItemList['0']      = $item;
    if($versionID == 'nowait') $currentVersion = $lang->project->realProgress;
    if($versionID == '0' && $isDiffMode) $currentVersion = $lang->project->latestVersion;

    $langData = [];
    $langData['allVersions']    = $lang->project->allVersions;
    $langData['compare']        = $lang->project->diffVersion;
    $langData['confirm']        = $lang->confirm;
    $langData['cancel']         = $lang->cancel;
    $langData['settings']       = $lang->settings;
    $langData['versionDisplay'] = $lang->programplan->versionDisplay;

    $isLatestVersion = empty($versionID) && !$isDiffMode;
    $versionList     = null;
    if(empty($project->isTpl))
    {
        $versionList = li
        (
            setID('versionList'),
            setClass('ml-2'),
            setStyle(array('order' => '10010')),
            empty($productID) && $type == 'gantt' && $browseType != 'bysearch' ? versiondiff
            (
                setClass('inline-block'),
                set::appendClass('fixed-item'),
                set::versionID($versionID),
                set::currentVersion($currentVersion),
                set::canDiffVersion(hasPriv('programplan', 'diffGanttVersion')),
                set::diffMode($isDiffMode),
                set::versionItems($versionItemList),
                set::settingsItems($settingsItems),
                set::diffLang($langData),
                set::browseTemplate($browseTemplate),
                set::baseline($isDiffMode ? $ganttBaseline : null)
            ) : null,
            icon
            (
                'help',
                setID('diffNotice'),
                setClass($isDiffMode ? '' : 'hidden'),
                set::title($lang->programplan->noticeDiffVersion)
            )
        );
    }

    featureBar
    (
        btn(setClass('ghost mr-2', ($browseType != 'bysearch' ? 'active' : '')), $lang->project->featureBar['browse']['all'], set::url($this->createLink('programplan', 'browse', "projectID=$projectID&productID=$productID"))),
        $productDropdown,
        $hasSearch && $isLatestVersion ? li(searchToggle(set::module('projectTask'), set::open($browseType == 'bysearch'))) : null,
        $versionList
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
    set::toolbar($isFromDoc ? array() : array('criticalPath', 'fullscreen', 'setting')),
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
