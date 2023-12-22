<?php
declare(strict_types=1);
/**
 * The batchCreate view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('productID', $productID);

$visibleFields  = array();
$requiredFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field) $visibleFields[$field] = '';
}
foreach(explode(',', $config->testcase->create->requiredFields) as $field)
{
    if($field)
    {
        $requiredFields[$field] = '';
        if(strpos(",{$config->testcase->list->customBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
    }
}
$hiddenStory = isAjaxRequest('modal') && $story;

$items = array();

/* Field of id. */
$items[] = array
(
    'name'    => 'id',
    'label'   => $lang->idAB,
    'control' => 'index',
    'width'   => '32px'
);

/* Field of branch. */
if($product->type != 'normal')
{
    $items[] = array
    (
        'name'    => 'branch',
        'label'   => $lang->product->branchName[$product->type],
        'hidden'  => zget($visibleFields, $product->type, true, false),
        'control' => 'picker',
        'items'   => $branches,
        'value'   => $branch,
        'width'   => '200px',
        'ditto'   => true
    );
}

/* Field of module. */
$items[] = array
(
    'name'     => 'module',
    'label'    => $lang->testcase->module,
    'hidden'   => zget($visibleFields, 'module', true, false),
    'control'  => array('type' => 'picker', 'required' => true),
    'items'    => $moduleOptionMenu,
    'value'    => $currentModuleID,
    'width'    => '200px',
    'required' => isset($requiredFields['module']),
    'ditto'    => true
);

/* Field of scene. */
$items[] = array
(
    'name'     => 'scene',
    'label'    => $lang->testcase->scene,
    'hidden'   => zget($visibleFields, 'scene', true, false),
    'control'  => array('type' => 'picker', 'required' => true),
    'items'    => $sceneOptionMenu,
    'value'    => $currentSceneID,
    'width'    => '200px',
    'required' => isset($requiredFields['scene']),
    'ditto'    => true
);

unset($lang->testcase->typeList['unit']);

/* Field of type. */
$items[] = array
(
    'name'     => 'type',
    'label'    => $lang->testcase->type,
    'control'  => 'picker',
    'items'    => $lang->testcase->typeList,
    'value'    => 'feature',
    'width'    => '160px',
    'required' => true,
    'ditto'    => true
);

/* Field of stage. */
$items[] = array
(
    'name'     => 'stage',
    'label'    => $lang->testcase->stage,
    'hidden'   => zget($visibleFields, 'stage', true, false),
    'control'  => 'picker',
    'items'    => $lang->testcase->stageList,
    'value'    => '',
    'multiple' => true,
    'width'    => '160px',
    'required' => isset($requiredFields['stage'])
);

/* Field of story. */
$items[] = array
(
    'name'     => 'story',
    'label'    => $lang->testcase->story,
    'hidden'   => zget($visibleFields, 'story', true, false) || $hiddenStory,
    'control'  => 'picker',
    'items'    => $storyPairs,
    'value'    => $story ? $story->id : '',
    'width'    => '200px',
    'required' => isset($requiredFields['story'])
);

/* Field of title. */
$items[] = array
(
    'name'     => 'title',
    'label'    => $lang->testcase->title,
    'width'    => '240px',
    'required' => true
);

/* Field of pri. */
$items[] = array
(
    'name'     => 'pri',
    'label'    => $lang->testcase->pri,
    'hidden'   => zget($visibleFields, 'pri', true, false),
    'control'  => 'priPicker',
    'items'    => $lang->testcase->priList,
    'value'    => 3,
    'width'    => '100px',
    'required' => isset($requiredFields['pri']),
    'ditto'   => true
);

/* Field of review. */
$items[] = array
(
    'name'     => 'review',
    'label'    => $lang->testcase->review,
    'hidden'   => zget($visibleFields, 'review', true, false),
    'control'  => 'picker',
    'items'    => $lang->testcase->reviewList,
    'value'    => $needReview,
    'width'    => '160px',
    'ditto'    => true
);

/* Field of precondition. */
$items[] = array
(
    'name'     => 'precondition',
    'label'    => $lang->testcase->precondition,
    'hidden'   => zget($visibleFields, 'precondition', true, false),
    'width'    => '200px',
    'required' => isset($requiredFields['precondition'])
);

/* Field of keywords. */
$items[] = array
(
    'name'     => 'keywords',
    'label'    => $lang->testcase->keywords,
    'hidden'   => zget($visibleFields, 'keywords', true, false),
    'width'    => '200px',
    'required' => isset($requiredFields['keywords'])
);

formBatchPanel
(
    set::title($lang->testcase->batchCreate),
    set::pasteField('title'),
    set::items($items),
    on::change('[data-name="branch"]', 'onBranchChangedForBatch'),
    on::change('[data-name="module"]', 'onModuleChangedForBatch')
);

render();
