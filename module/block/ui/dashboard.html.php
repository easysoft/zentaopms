<?php
declare(strict_types=1);
/**
* The dashboard view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

foreach($blocks as $block)
{
    $block->color = isset($block->params->color) ? $block->params->color : null;
    $block->fetch = isset($block->blockLink) ? $block->blockLink : null;
}

$blocks = json_decode(json_encode($blocks), true);

$blockMenuItems = array();
$blockMenuItems[] = array('text' => $lang->block->refresh, 'className' => 'not-open-url', 'data' => array('type' => 'refresh'));
if($this->app->user->account != 'guest')
{
    $blockMenuItems[] = array('text' => $lang->edit, 'className' => 'not-open-url', 'data' => array('type' => 'edit', 'url' => createLink('block', 'edit', "blockID={id}"), 'size' => 'sm'));
    if(count($blocks) > 1) $blockMenuItems[] = array('text' => $lang->block->hidden, 'className' => 'not-open-url', 'data' => array('type' => 'delete', 'url' => createLink('block', 'delete', "blockID={id}"), 'confirm' => $lang->block->confirmRemoveBlock));
    if($this->app->user->admin && count($blocks) > 1) $blockMenuItems[] = array('text' => $lang->block->closeForever, 'className' => 'not-open-url', 'data' => array('type' => 'delete', 'url' => createLink('block', 'close', "blockID={id}"), 'confirm' => $lang->block->confirmClose));
}
$blockMenuItems[] = array('text' => $lang->block->createBlock, 'className' => 'not-open-url', 'data' => array('type' => 'create', 'url' => createLink('block', 'create', "dashboard=$dashboard"), 'toggle' => 'modal', 'size' => 'sm'));
$blockMenuItems[] = array('text' => $lang->block->reset, 'className' => 'not-open-url', 'data' => array('type' => 'reset', 'url' => createLink('block', 'reset', "dashboard=$dashboard"), 'confirm' => $lang->block->confirmReset));

dashboard
(
    set::key("dashboard-{$dashboard}" . (!empty($objectID) ? "-$objectID" : '')),
    set::onlyLoadVisible(false),
    set::forceUpdateID(time()),
    set::blocks(array_values($blocks)),
    set::blockMenu(array('items' => $blockMenuItems)),
    set::emptyBlockContent(array('html' => '<div class="panel rounded bg-canvas panel-block shadow"><div class="panel-heading border-b h-12"></div></div>')),
    set::onClickMenu(jsRaw('handleClickBlockMenu')),
    set::onLayoutChange(jsRaw('handleLayoutChange'))
);

$featurePages = $this->loadModel('misc')->getFeatureNotices();
if($featurePages)
{
    $pageCount   = count($featurePages);
    $pageBlocks = array();
    foreach($featurePages as $index => $page)
    {
        $isFirst = $index === 0;
        $isLast  = $index === $pageCount - 1;
        $buttons = array();

        if(!$isFirst)
        {
            $buttons[] = btn
            (
                setClass('mr-4'),
                on::click('togglePage("page-' . ($index - 1) . '")'),
                $lang->block->prevPage
            );
        }

        if($isLast)
        {
            $buttons[] = btn
            (
                setClass('primary'),
                setData('dismiss', 'modal'),
                $lang->block->experience
            );
        }
        else
        {
            $buttons[] = btn
            (
                setClass('primary'),
                on::click('togglePage("page-' . ($index + 1) . '")'),
                $lang->block->nextPage
            );
        }

        $pageBlocks[] = div
        (
            setClass('page-block page-' . $index . ($isFirst ? '' : ' hidden')),
            img(set::src($page['src'])),
            !empty($page['moreLink']) ? div(setClass('learn-more-link flex justify-end text-root text-primary-600'), a(set::href($page['moreLink']), set::target('_blank'), $lang->block->learnMore . ' >')) : null,
            div
            (
                setClass('my-6 text-center'),
                $buttons
            )
        );
    }

    modal
    (
        setID('featureNoticeModal'),
        $pageBlocks
    );
}
else
{
    $pluginRemind = $this->misc->getPluginRemind();
    if($pluginRemind)
    {
        modal
        (
            setID('expiredModal'),
            set::title($lang->misc->expiredTipsTitle),
            html($pluginRemind)
        );
    }
    else
    {
        $metriclibRemind = $this->misc->getMetriclibRemind();
        if($metriclibRemind)
        {
            modal
            (
                setID('metriclibModal'),
                html($metriclibRemind)
            );
        }
    }
}

render();
