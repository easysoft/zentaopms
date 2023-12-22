<?php
declare(strict_types=1);
/**
 * The batch edit view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('branchProduct', $branchProduct);
jsVar('branchOption',  zget($branchTagOption, $productID, array()));
jsVar('modulePairs',   $modulePairs);
jsVar('scenePairs',    $scenePairs);
jsVar('productID',     $productID);
jsVar('products',      $products);
jsVar('cases',         $cases);

if(isset($suhosinInfo))
{
    div
    (
        set::className('alert warning'),
        $suhosinInfo
    );
}
else
{
    $visibleFields  = array();
    $requiredFields = array();
    foreach(explode(',', $showFields) as $field)
    {
        if(!$field) continue;
        $visibleFields[$field] = $field;
    }

    foreach(explode(',', $config->testcase->edit->requiredFields) as $field)
    {
        if(!$field) continue;

        $requiredFields[$field] = $field;

        if(strpos(",{$config->testcase->list->customBatchEditFields},", ",{$field},") !== false) $visibleFields[$field] = $field;
    }

    $items = array();
    $items[] = array
    (
        'name'    => 'id',
        'label'   => $lang->idAB,
        'control' => 'hidden',
        'hidden'  => true
    );
    $items[] = array
    (
        'name'    => 'id',
        'label'   => $lang->idAB,
        'control' => 'index',
        'width'   => '50px'
    );
    $items[] = array
    (
        'name'         => 'pri',
        'label'        => $lang->priAB,
        'width'        => '100px',
        'control'      => array('type' => 'priPicker', 'required' => true),
        'items'        => $lang->testcase->priList,
        'ditto'        => true,
        'hidden'       => !isset($visibleFields['pri']),
        'required'     => isset($requiredFields['pri']),
        'defaultDitto' => 'off'
    );
    $items[] = array
    (
        'name'     => 'status',
        'label'    => $lang->statusAB,
        'width'    => '120px',
        'control'  => array('type' => 'picker', 'required' => true),
        'items'    => $lang->testcase->statusList,
        'hidden'   => !isset($visibleFields['status']),
        'required' => isset($requiredFields['status'])
    );
    $items[] = array
    (
        'name'    => 'branch',
        'label'   => $lang->testcase->branch,
        'width'   => '180px',
        'control' => 'picker',
        'items'   => zget($branchTagOption, $productID, array()),
        'hidden'  => !$branchProduct
    );
    $items[] = array
    (
        'name'     => 'module',
        'label'    => $lang->testcase->module,
        'width'    => '180px',
        'control'  => array('type' => 'picker', 'required' => true),
        'items'    => array(),
        'hidden'   => !isset($visibleFields['module']),
        'required' => isset($requiredFields['module'])
    );
    $items[] = array
    (
        'name'     => 'scene',
        'label'    => $lang->testcase->scene,
        'width'    => '180px',
        'control'  => array('type' => 'picker', 'required' => true),
        'items'    => array(),
        'hidden'   => ($isLibCase || !isset($visibleFields['scene'])),
        'required' => isset($requiredFields['scene'])
    );
    $items[] = array
    (
        'name'     => 'story',
        'label'    => $lang->testcase->story,
        'control'  => 'picker',
        'width'    => '180px',
        'items'    => $stories,
        'hidden'   => !isset($visibleFields['story']),
        'required' => isset($requiredFields['story'])
    );
    $items[] = array
    (
        'name'     => 'title',
        'label'    => $lang->testcase->title,
        'control'  => 'input',
        'width'    => '180px',
        'required' => true
    );

    unset($lang->testcase->typeList['unit']);
    $items[] = array
    (
        'name'         => 'type',
        'label'        => $lang->testcase->type,
        'width'        => '140px',
        'control'      => array('type' => 'picker', 'required' => true),
        'items'        => $lang->testcase->typeList,
        'ditto'        => true,
        'required'     => isset($requiredFields['type']),
        'defaultDitto' => 'off'
    );
    $items[] = array
    (
        'name'     => 'precondition',
        'label'    => $lang->testcase->precondition,
        'width'    => '180px',
        'control'  => 'textarea',
        'hidden'   => !isset($visibleFields['precondition']),
        'required' => isset($requiredFields['precondition'])
    );
    $items[] = array
    (
        'name'     => 'keywords',
        'label'    => $lang->testcase->keywords,
        'control'  => 'input',
        'width'    => '180px',
        'hidden'   => !isset($visibleFields['keywords']),
        'required' => isset($requiredFields['keywords'])
    );
    $items[] = array
    (
        'name'     => 'stage',
        'label'    => $lang->testcase->stage,
        'width'    => '120px',
        'control'  => 'picker',
        'multiple' => true,
        'items'    => $lang->testcase->stageList,
        'hidden'   => !isset($visibleFields['stage']),
        'required' => isset($requiredFields['stage'])
    );

    formBatchPanel
    (
        on::change('[data-name="branch"]', 'onBranchChangedForBatch'),
        on::change('[data-name="module"]', 'onModuleChangedForBatch'),
        set::mode('edit'),
        set::data(array_values($cases)),
        set::items($items),
        set::onRenderRow(jsRaw('handleRenderRow'))
    );
}

render();
