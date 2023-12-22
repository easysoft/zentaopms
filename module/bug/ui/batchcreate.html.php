<?php
declare(strict_types=1);
/**
 * The batchCreate view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

$defaultBug = array('module' => $moduleID, 'project' => $projectID, 'execution' => $executionID, 'openedBuild' => 'trunk', 'pri' => 3, 'severity' => 3);
if($product->type != 'normal') $defaultBug['branch'] = $branch;
if(isset($executionType) && $executionType == 'kanban')
{
    $defaultBug['region'] = $regionID;
    $defaultBug['laneID'] = $laneID;
}

$bugs = array();
if(!empty($titles))
{
    foreach($titles as $title => $fileName)
    {
        $bug = $defaultBug;
        $bug['title']       = $title;
        $bug['uploadImage'] = $fileName;
        $bugs[] = $bug;
    }
}

foreach(array_filter(explode(',', $config->bug->create->requiredFields)) as $field) $requiredFields[$field] = '';

$items = array();

/* Field of id. */
$items[] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '32px');
if($bugs) $items[] = array('name' => 'uploadImage', 'label' => '', 'control' => 'hidden', 'hidden' => true);

/* Field of branch. */
if($product->type != 'normal') $items[] = array('name' => 'branch', 'label' => $lang->product->branchName[$product->type], 'control' => 'picker', 'items' => $branches, 'value' => $branch, 'width' => '200px', 'ditto' => true);

/* Field of module. */
$items[] = array('name' => 'module', 'label' => $lang->bug->module, 'control' => 'picker', 'items' => $moduleOptionMenu, 'value' => $moduleID, 'width' => '200px', 'required' => isset($requiredFields['module']), 'ditto' => true);

/* Field of project. */
$items[] = array('name' => 'project', 'label' => $lang->bug->project, 'control' => 'picker', 'items' => $projects, 'value' => $projectID, 'width' => '200px', 'required' => isset($requiredFields['project']), 'ditto' => true);

/* Field of execution. */
$items[] = array
(
    'name'     => 'execution',
    'label'    => isset($project->model) && $project->model == 'kanban' ? $lang->bug->kanban : $lang->bug->execution,
    'control'  => 'picker',
    'items'    => $executions,
    'value'    => $executionID,
    'width'    => '200px',
    'required' => isset($requiredFields['execution']),
    'ditto'    => true
);

/* Field of openedBuild. */
$items[] = array('name' => 'openedBuild', 'label' => $lang->bug->openedBuild, 'control' => 'picker', 'items' => $builds, 'value' => 'trunk', 'multiple' => true, 'width' => '200px', 'required' => true, 'ditto' => true);

/* Field of title. */
$items[] = array( 'name' => 'title', 'label' => $lang->bug->title, 'width' => '240px', 'required' => true, 'control' => 'colorInput');

/* Field of region and lane. */
if(isset($executionType) && $executionType == 'kanban')
{
    $items[] = array('name' => 'region', 'label' => $lang->kanbancard->region, 'control' => 'picker', 'value' => $regionID, 'items' => $regionPairs, 'width' => '200px', 'required' => true);
    $items[] = array('name' => 'laneID', 'label' => $lang->kanbancard->lane, 'control' => 'picker', 'value' => $laneID, 'items' => $lanePairs, 'width' => '200px', 'required' => true);
}

/* Field of deadline. */
$items[] = array('name' => 'deadline', 'label' => $lang->bug->deadline, 'control' => 'date', 'width' => '136px', 'required' => isset($requiredFields['deadline']), 'ditto' => true);

/* Field of steps. */
$items[] = array('name' => 'steps', 'label' => $lang->bug->steps, 'width' => '240px', 'required' => isset($requiredFields['steps']));

/* Field of type. */
$items[] = array('name' => 'type', 'label' => $lang->typeAB, 'control' => 'picker', 'items' => $lang->bug->typeList, 'value' => '', 'width' => '160px', 'required' => isset($requiredFields['type']), 'ditto' => true);

/* Field of pri. */
$items[] = array('name' => 'pri', 'label' => $lang->bug->pri, 'control' => array('type' => 'priPicker', 'required' => true), 'items' => $lang->bug->priList, 'value' => 3, 'width' => '100px', 'required' => isset($requiredFields['pri']), 'ditto' => true);

/* Field of severity. */
$items[] = array('name' => 'severity', 'label' => $lang->bug->severity, 'control' => array('type' => 'severityPicker', 'required' => true), 'items' => $lang->bug->severityList, 'value' => 3, 'width' => '80px', 'required' => isset($requiredFields['severity']));

/* Field of os. */
$items[] = array('name' => 'os', 'label' => $lang->bug->os, 'control' => 'picker', 'items' => $lang->bug->osList, 'width' => '200px', 'multiple' => true, 'required' => isset($requiredFields['os']));

/* Field of browser. */
$items[] = array('name' => 'browser', 'label' => $lang->bug->browser, 'control'  => 'picker', 'items' => $lang->bug->browserList, 'width' => '200px', 'multiple' => true, 'required' => isset($requiredFields['browser']));

/* Field of keywords. */
$items[] = array('name' => 'keywords', 'label' => $lang->bug->keywords, 'width' => '200px', 'required' => isset($requiredFields['keywords']));

formBatchPanel
(
    set::title($lang->bug->batchCreate),
    set::uploadParams('module=bug&params=' . helper::safe64Encode("productID=$product->id&branch=$branch&executionID=$executionID&moduleID=$moduleID")),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'batchCreateFields')),
    set::pasteField('title'),
    $bugs ? set::data($bugs) : null,
    set::items($items),
    on::change('[data-name="branch"]', 'setBranchRelated'),
    on::change('[data-name="project"]', 'loadProductExecutionsByProject'),
    on::change('[data-name="execution"]', 'loadExecutionBuilds'),
    on::change('[data-name="region"]', 'setLane'),
    formHidden('product', $product->id)
);

render();
