<?php
declare(strict_types=1);
/**
 * The batchEdit view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('tab', $app->tab);
jsVar('modules', $modules);
jsVar('products', $products);
jsVar('branchTagOption', $branchTagOption);
jsVar('executionMembers', isset($executionMembers) ? $executionMembers : array());
jsVar('projectMembers', isset($projectMembers) ? $projectMembers : array());
jsVar('productMembers', isset($productMembers) ? $productMembers : array());
jsVar('isBranchProduct', $branchProduct ? 1 : 0);
jsVar('noProductProjects', $noProductProjects);
jsVar('noSprintProjects', $noSprintProjects);
jsVar('deletedProjects', $deletedProjects);
jsVar('deletedExecutions', $deletedExecutions);
jsVar('projectExecutions', $projectExecutions);
jsVar('productProjects', $productProjects);
jsVar('productExecutions', $productExecutions);
jsVar('productOpenedBuilds', $productOpenedBuilds);
jsVar('projectOpenedBuilds', $projectOpenedBuilds);
jsVar('executionOpenedBuilds', $executionOpenedBuilds);

$visibleFields  = array();
$requiredFields = array();
foreach(explode(',', $config->bug->custom->batchEditFields) as $field)
{
    if($field) $visibleFields[$field] = '';
}
foreach(explode(',', $config->bug->edit->requiredFields) as $field)
{
    if($field)
    {
        $requiredFields[$field] = '';
        if(strpos(",{$config->bug->list->customBatchEditFields},", ",{$field},") !== false) $visibleFields[$field] = '';
    }
}

$assignedToList = array_merge($users, array('closed' => 'Closed'));

$items   = array();
$items[] = array('name' => 'id',          'label' => $lang->idAB,             'control' => 'index',  'width' => '50px');
$items[] = array('name' => 'product',     'label' => $lang->bug->product,     'control' => 'input',  'width' => '220px', 'hidden' => true);
$items[] = array('name' => 'project',     'label' => $lang->bug->project,     'control' => 'picker', 'width' => '200px', 'items' => array());
$items[] = array('name' => 'execution',   'label' => $lang->bug->execution,   'control' => 'picker', 'width' => '200px', 'items' => array());
$items[] = array('name' => 'type',        'label' => $lang->typeAB,           'control' => 'picker', 'width' => '200px', 'items' => $lang->bug->typeList, 'required' => isset($requiredFields['type']), 'hidden' => zget($visibleFields, 'type', true, false), 'ditto' => true, 'defaultDitto' => 'off');
$items[] = array('name' => 'severity',    'label' => $lang->bug->severity,    'control' => array('control' => 'severityPicker', 'required' => true), 'width' => '80px', 'items' => $lang->bug->severityList, 'required' => isset($requiredFields['severity']), 'hidden' => zget($visibleFields, 'severity', true, false));
$items[] = array('name' => 'pri',         'label' => $lang->bug->pri,         'control' => array('control' => 'priPicker', 'required' => true),      'width' => '80px', 'items' => $lang->bug->priList,      'required' => isset($requiredFields['pri']),      'hidden' => zget($visibleFields, 'pri', true, false));
$items[] = array('name' => 'title',       'label' => $lang->bug->title,       'control' => 'colorInput', 'width' => '240px', 'required' => true);
$items[] = array('name' => 'branch',      'label' => $lang->bug->branch,      'control' => 'picker',     'width' => '200px', 'required' => isset($requiredFields['branch']), 'items' => array(), 'hidden' => !$branchProduct);
$items[] = array('name' => 'module',      'label' => $lang->bug->module,      'control' => 'picker',     'width' => '200px', 'required' => isset($requiredFields['module']), 'items' => array(), 'ditto' => true, 'defaultDitto' => 'off');
$items[] = array('name' => 'openedBuild', 'label' => $lang->bug->openedBuild, 'control' => 'picker', 'width' => '200px', 'items' => array(), 'multiple' => true, 'required' => true);
$items[] = array('name' => 'plan',        'label' => $lang->bug->plan,        'control' => array('control' => 'picker', 'required' => false), 'width' => '200px', 'required' => isset($requiredFields['plan']), 'items' => array(), 'ditto' => true, 'defaultDitto' => 'off');
$items[] = array('name' => 'case',        'label' => $lang->bug->case,        'control' => array('control' => 'picker', 'required' => false), 'width' => '200px', 'required' => isset($requiredFields['case']), 'items' => $fromCases);
$items[] = array('name' => 'assignedTo',  'label' => $lang->bug->assignedTo,  'control' => 'picker', 'width' => '200px', 'required' => isset($requiredFields['assignedTo']), 'items' => $assignedToList, 'ditto' => true, 'defaultDitto' => 'off');
$items[] = array('name' => 'deadline',    'label' => $lang->bug->deadline,    'control' => 'date',   'width' => '128px', 'required' => isset($requiredFields['deadline']),   'hidden' => zget($visibleFields, 'deadline', true, false));
$items[] = array('name' => 'os',          'label' => $lang->bug->os,          'control' => 'picker', 'width' => '200px', 'required' => isset($requiredFields['os']),         'items' => $lang->bug->osList,      'hidden' => zget($visibleFields, 'os',      true, false), 'multiple' => true);
$items[] = array('name' => 'browser',     'label' => $lang->bug->browser,     'control' => 'picker', 'width' => '200px', 'required' => isset($requiredFields['browser']),    'items' => $lang->bug->browserList, 'hidden' => zget($visibleFields, 'browser', true, false), 'multiple' => true);
$items[] = array('name' => 'keywords',    'label' => $lang->bug->keywords,    'control' => 'input',  'width' => '200px', 'required' => isset($requiredFields['keywords']),   'hidden' => zget($visibleFields, 'keywords', true, false));
$items[] = array('name' => 'resolvedBy',  'label' => $lang->bug->resolvedBy,  'control' => 'picker', 'width' => '200px', 'required' => isset($requiredFields['resolvedBy']), 'items' => $users, 'ditto' => true, 'defaultDitto' => 'off');
$items[] = array('name' => 'resolution',  'label' => $lang->bug->resolution,  'width' => '220px', 'control' => array('control' => 'inputGroup', 'items' => array(array('control' => 'picker', 'name' => 'resolution', 'items' => $lang->bug->resolutionList, 'required' => isset($requiredFields['resolution'])), array('control' => 'picker', 'name' => 'duplicateBug', 'items' => array(), 'placeholder' => $lang->bug->placeholder->duplicate, 'required' => true, 'class' => 'duplicate-select'))));

formBatchPanel
(
    set::title($lang->bug->batchEdit),
    set::mode('edit'),
    set::data(array_values($bugs)),
    set::onRenderRow(jsRaw('renderRowData')),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'batchEditFields')),
    on::change('[data-name="project"]', 'projectChange'),
    on::change('[data-name="execution"]', 'executionChange'),
    on::change('[data-name="branch"]', 'branchChange'),
    on::change('.picker-box[data-name="resolution"]', 'setDuplicate'),
    set::items($items),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('hidden'),
        set::hidden(true)
    )
);

render();

