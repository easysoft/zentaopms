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

foreach($blocks as $block)
{
    $block->color  = isset($block->params->color) ? $block->params->color : null;
    $block->fetch  = $block->blockLink;
    $block->height = $block->height ? $block->height : 3;
    $block->size   = array($block->width, $block->height);
    unset($block->title);
}

$blocks = json_decode(json_encode($blocks), true);

$blockMenuItems = array();
$blockMenuItems[] = array('text' => $lang->block->refresh, 'className' => 'not-open-url', 'data' => array('type' => 'refresh'));
$blockMenuItems[] = array('text' => $lang->edit, 'className' => 'not-open-url', 'data' => array('type' => 'edit', 'url' => createLink('block', 'edit', "blockID={id}"), 'size' => 'sm'));
$blockMenuItems[] = array('text' => $lang->block->hidden, 'className' => 'not-open-url', 'data' => array('type' => 'delete', 'url' => createLink('block', 'delete', "blockID={id}&type=hidden"), 'confirm' => $lang->block->confirmRemoveBlock));
$blockMenuItems[] = array('text' => $lang->block->createBlock, 'className' => 'not-open-url', 'data' => array('type' => 'create', 'url' => createLink('block', 'create', "dashboard=$dashboard"), 'toggle' => 'modal', 'size' => 'sm'));
$blockMenuItems[] = array('text' => $lang->block->reset, 'className' => 'not-open-url', 'data' => array('type' => 'reset', 'url' => createLink('block', 'reset', "dashboard=$dashboard"), 'confirm' => $lang->block->confirmReset));

dashboard
(
    set::blocks(array_values($blocks)),
    set::blockMenu(array('items' => $blockMenuItems)),
    set::onClickMenu(jsRaw('handleClickBlockMenu'))
);

render();
