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

$visibleFields  = array();
$requiredFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field) $visibleFields[$field] = '';
}

foreach(explode(',', $config->bug->create->requiredFields) as $field)
{
    if($field)
    {
        $requiredFields[$field] = '';
        if(strpos(",{$config->bug->list->customBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
    }
}

$items = array();

/* Field of id. */
$items[] = array
(
    'name'    => 'id',
    'label'   => $lang->idAB,
    'control' => 'index',
    'width'   => '32px',
);

/* Field of branch. */
$items[] = array
(
    'name'    => 'branches',
    'label'   => $lang->product->branchName[$product->type],
    'hidden'   => zget($visibleFields, $product->type, true, false),
    'control' => 'select',
    'items'   => $branches,
    'value'   => $branch,
    'width'   => '200px',
    'ditto'   => true,
);

/* Field of module. */
$items[] = array
(
    'name'     => 'modules',
    'label'    => $lang->bug->module,
    'hidden'   => zget($visibleFields, 'module', true, false),
    'control'  => 'select',
    'items'    => $moduleOptionMenu,
    'value'    => $moduleID,
    'width'    => '200px',
    'required' => zget($requiredFields, 'module', false, true),
    'ditto'    => true,
);

/* Field of project. */
$items[] = array
(
    'name'     => 'projects',
    'label'    => $lang->bug->project,
    'hidden'   => zget($visibleFields, 'project', true, false),
    'control'  => 'select',
    'items'    => $projects,
    'value'    => $projectID,
    'width'    => '200px',
    'required' => zget($requiredFields, 'project', false, true),
    'ditto'    => true,
);

/* Field of execution. */
$items[] = array
(
    'name'     => 'executions',
    'label'    => isset($project->model) && $project->model == 'kanban' ? $lang->bug->kanban : $lang->bug->execution,
    'hidden'   => zget($visibleFields, 'execution', true, false),
    'control'  => 'select',
    'items'    => $executions,
    'value'    => $executionID,
    'width'    => '200px',
    'required' => zget($requiredFields, 'execution', false, true),
    'ditto'    => true,
);

/* Field of openedBuild. */
$items[] = array
(
    'name'     => 'openedBuilds',
    'label'    => $lang->bug->openedBuild,
    'control'  => array(
        'type'     => 'select',
        'items'    => $builds,
        'value'    => 'trunk',
        'multiple' => true,
    ),
    'width'    => '200px',
    'required' => true,
    'ditto'    => true,
);

/* Field of title. */
$items[] = array
(
    'name'     => 'title',
    'label'    => $lang->bug->title,
    'width'    => '240px',
    'required' => true,
);

/* Field of region and lane. */
if(isset($executionType) && $executionType == 'kanban')
{
    $items[] = array
    (
        'name'    => 'regions',
        'label'   => $lang->kanbancard->region,
        'control' => 'select',
        'value'   => $regionID,
        'items'   => $regionPairs,
        'width'   => '200px',
    );

    $items[] = array
    (
        'name'    => 'lanes',
        'label'   => $lang->kanbancard->lane,
        'control' => 'select',
        'value'   => $laneID,
        'items'   => $lanePairs,
        'width'   => '200px',
    );
}

/* Field of deadline. */
$items[] = array
(
    'name'     => 'deadlines',
    'label'    => $lang->bug->deadline,
    'hidden'   => zget($visibleFields, 'deadline', true, false),
    'control'  => 'date',
    'width'    => '128px',
    'required' => zget($requiredFields, 'deadline', false, true),
    'ditto'    => true,
);

/* Field of steps. */
$items[] = array
(
    'name'     => 'stepses',
    'label'    => $lang->bug->steps,
    'hidden'   => zget($visibleFields, 'steps', true, false),
    'width'    => '240px',
    'required' => zget($requiredFields, 'steps', false, true),
);

/* Field of type. */
$items[] = array
(
    'name'     => 'types',
    'label'    => $lang->typeAB,
    'hidden'   => zget($visibleFields, 'type', true, false),
    'control'  => 'select',
    'items'    => $lang->bug->typeList,
    'value'    => $type,
    'width'    => '160px',
    'required' => zget($requiredFields, 'type', false, true),
    'ditto'    => true,
);

/* Field of pri. */
$items[] = array
(
    'name'     => 'pris',
    'label'    => $lang->bug->pri,
    'hidden'   => zget($visibleFields, 'pri', true, false),
    'control'  => 'select',
    'items'    => $lang->bug->priList,
    'value'    => $pri,
    'width'    => '80px',
    'required' => zget($requiredFields, 'pri', false, true),
);

/* Field of severity. */
$items[] = array
(
    'name'     => 'severities',
    'label'    => $lang->bug->severity,
    'hidden'   => zget($visibleFields, 'severity', true, false),
    'control'  => 'select',
    'items'    => $lang->bug->severityList,
    'value'    => 3,
    'width'    => '80px',
    'required' => zget($requiredFields, 'severity', false, true),
);

/* Field of os. */
$items[] = array
(
    'name'     => 'oses',
    'label'    => $lang->bug->os,
    'hidden'   => zget($visibleFields, 'os', true, false),
    'control'  => 'select',
    'items'    => $lang->bug->osList,
    'width'    => '200px',
    'required' => zget($requiredFields, 'os', false, true),
);

/* Field of browser. */
$items[] = array
(
    'name'     => 'browsers',
    'label'    => $lang->bug->browser,
    'hidden'   => zget($visibleFields, 'browser', true, false),
    'control'  => 'select',
    'items'    => $lang->bug->browserList,
    'width'    => '200px',
    'required' => zget($requiredFields, 'browser', false, true),
);

/* Field of keywords. */
$items[] = array
(
    'name'     => 'keywords',
    'label'    => $lang->bug->keywords,
    'hidden'   => zget($visibleFields, 'keywords', true, false),
    'width'    => '200px',
    'required' => zget($requiredFields, 'keywords', false, true),
);


formBatchPanel
(
    set::items($items),
    on::change('[data-name="branches"]', 'setBranchRelated'),
    on::change('[data-name="projects"]', 'loadProductExecutionsByProject'),
    on::change('[data-name="executions"]', 'loadExecutionBuilds'),
    on::change('[data-name="regions"]', 'setLane'),
    formHidden('product', $productID),
);

render();

