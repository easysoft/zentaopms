<?php
declare(strict_types=1);
/**
* The dashboard view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$blocks = array();
foreach($longBlocks as $index => $block)
{
    $blocks[] = array
    (
        'id'        => $block->id,
        'code'      => $block->code,
        'color'     => isset($block->params->color) ? $block->params->color : null,
        'fetch'     => $block->blockLink,
        'size'      => 'smWide',
        'left'      => 0,
        'top'       => count($blocks) * 4
    );
}

foreach($shortBlocks as $index => $block)
{
    $blocks[] = array
    (
        'id'        => $block->id,
        'code'      => $block->code,
        'color'     => isset($block->params->color) ? $block->params->color : null,
        'fetch'     => $block->blockLink,
        'size'      => 'sm',
        'left'      => 2,
        'top'       => (count($blocks) - count($longBlocks)) * 4
    );
}

$blockMenuItems = array();
$blockMenuItems[] = array('text' => $lang->block->refresh, 'data-type' => 'refresh');
$blockMenuItems[] = array('text' => $lang->edit, 'data-url' => createLink('block', 'edit', "blockID={id}"));
$blockMenuItems[] = array('text' => $lang->block->hidden, 'data-url' => createLink('block', 'delete', "blockID={id}&type=hidden"));
$blockMenuItems[] = array('text' => $lang->block->createBlock, 'data-url' => createLink('block', 'create', "dashboard=$dashboard"));
$blockMenuItems[] = array('text' => $lang->block->reset, 'data-url' => createLink('block', 'reset', "dashboard=$dashboard"));

dashboard
(
    set::blocks($blocks),
    set::blockMenu(array('items' => $blockMenuItems))
);

render();
