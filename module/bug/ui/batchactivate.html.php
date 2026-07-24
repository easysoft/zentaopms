<?php
declare(strict_types=1);
/**
 * The batchactivate view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zenggang <zenggang@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();
$items[] = array('name' => 'id',          'label' => $lang->idAB,               'control' => 'index',  'width' => '40px');
$items[] = array('name' => 'title',       'label' => $lang->bug->title,         'control' => 'input',  'width' => '120px', 'disabled' => true);
$items[] = array('name' => 'assignedTo',  'label' => $lang->bug->assignedTo,    'control' => 'picker', 'width' => '160px', 'items' => $users);
$items[] = array('name' => 'openedBuild', 'label' => $lang->bug->openedBuild,   'control' => 'picker', 'multiple' => true, 'width' => '200px', 'items' => $builds);
$items[] = array('name' => 'comment',     'label' => $lang->bug->legendComment, 'control' => 'input',  'width' => '1/3');
$items[] = array('name' => 'status',      'label' => $lang->bug->status,        'control' => 'hidden', 'hidden' => true);

foreach($bugs as $bug)
{
    if($bug->status != 'active') $bug->assignedTo = $bug->resolvedBy;
}

formBatchPanel
(
    set::title($lang->bug->common . $lang->hyphen . $lang->bug->batchActivate),
    set::mode('edit'),
    set::items($items),
    set::data(array_values($bugs)),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('hidden'),
        set::hidden(true)
    )
);

render();

