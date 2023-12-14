<?php
declare(strict_types=1);
/**
 * The batchcreatecard view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanban->batchCreateCard), set::titleClass('text-lg font-bold'));

$items   = array();
$items[] = array('name' => 'name',       'label' => $lang->kanbancard->name,       'control' => 'input',      'width' => '180px', 'required' => true);
$items[] = array('name' => 'lane',       'label' => $lang->kanbancard->lane,       'control' => 'picker',     'width' => '160px', 'items' => $lanePairs, 'ditto' => true, 'required' => true);
$items[] = array('name' => 'assignedTo', 'label' => $lang->kanbancard->assignedTo, 'control' => 'picker',     'width' => 'auto', 'multiple' => true, 'items' => $users, 'value' => $app->user->account);
$items[] = array('name' => 'estimate',   'label' => $lang->kanbancard->estimate,   'control' => 'input',      'width' => '60px');
$items[] = array('name' => 'begin',      'label' => $lang->kanbancard->begin,      'control' => 'datePicker', 'width' => '160px', 'ditto' => true);
$items[] = array('name' => 'end',        'label' => $lang->kanbancard->end,        'control' => 'datePicker', 'width' => '160px', 'ditto' => true);
$items[] = array('name' => 'pri',        'label' => $lang->kanbancard->pri,        'control' => 'priPicker',  'width' => '60px', 'items' => $lang->kanbancard->priList, 'value' => '3');

formBatchPanel
(
    set::items($items)
);

render();
