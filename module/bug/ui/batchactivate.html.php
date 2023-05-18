<?php
declare(strict_types=1);
/**
 * The batchactivate view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zenggang <zenggang@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

$bugData = array();
foreach($bugs as $bug)
{
    $bugData[] = array('bugIdList' => $bug->id, 'id' => $bug->id, 'title' => $bug->title, 'statusList' => $bug->status, 'assignedToList' => $bug->resolvedBy, 'openedBuildList' => $bug->openedBuild);
}

$items = array();
$items[] = array('name' => 'statusList', 'label' => '');
$items[] = array('name' => 'bugIdList', 'label' => '');
$items[] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '40px');
$items[] = array('name' => 'title', 'label' => $lang->bug->title, 'control' => 'static', 'width' => '120px');
$items[] = array('name' => 'assignedToList', 'label' => $lang->bug->assignedTo, 'control' => 'select', 'width' => '160px', 'items' => $users);
$items[] = array('name' => 'openedBuildList', 'label' => $lang->bug->openedBuild, 'control' => 'select', 'multiple' => true, 'width' => '200px', 'items' => $builds);
$items[] = array('name' => 'commentList', 'label' => $lang->bug->legendComment, 'control' => 'editor', 'width' => '1/3');

$extendFields = $this->bug->getFlowExtendFields();
foreach($extendFields as $extendField)
{
    $items[] = array('name' => $extendField->field, 'label' => $extendField->name,  'required' => strpos(",$extendField->rules,", ',1,') !== false);
}

formBatchPanel
(
    set::title($lang->bug->common . $lang->colon . $lang->bug->batchActivate),
    set::mode('edit'),
    set::items($items),
    set::data($bugData),
);

render();

