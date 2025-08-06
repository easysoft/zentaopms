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

$upgradeRemind = $this->loadModel('misc')->getUpgradeRemind();
if($upgradeRemind)
{
    $clientLang = common::checkNotCN() ? 'en' : 'cn';
    $guideInfo  = $this->config->upgradeGuide[$config->edition];
    $version    = $guideInfo['version'];
    $imagePath  = $guideInfo['imagePath'];
    $moreLink   = 'https://api.zentao.net/goto.php?item=' . $guideInfo['linkItem'];

    modal
    (
        setID('upgradeModal'),
        div
        (
            setClass('page-block pageOne'),
            img(set::src("{$imagePath}{$clientLang}_upgrade_guide1_{$version}.svg")),
            div(setClass('learn-more-link flex justify-end text-root text-primary-600'), a(set::href($moreLink), set::target('_blank'), $lang->block->learnMore . ' >')),
            div
            (
                setClass('my-6 text-center'),
                btn
                (
                    setClass('primary'),
                    on::click("togglePage('pageTwo')"),
                    $lang->block->nextPage
                )
            )
        ),
        div
        (
            setClass('page-block pageTwo hidden'),
            img(set::src("{$imagePath}{$clientLang}_upgrade_guide2_{$version}.svg")),
            div(setClass('learn-more-link flex justify-end text-root text-primary-600'), a(set::href($moreLink), set::target('_blank'), $lang->block->learnMore . ' >')),
            div
            (
                setClass('my-6 text-center'),
                btn
                (
                    setClass('mr-4'),
                    on::click("togglePage('pageOne')"),
                    $lang->block->prevPage
                ),
                btn
                (
                    setClass('primary'),
                    on::click("togglePage('pageThree')"),
                    $lang->block->nextPage
                )
            )
        ),
        div
        (
            setClass('page-block pageThree hidden'),
            img(set::src("{$imagePath}{$clientLang}_upgrade_guide3_{$version}.svg")),
            div(setClass('learn-more-link flex justify-end text-root text-primary-600'), a(set::href($moreLink), set::target('_blank'), $lang->block->learnMore . ' >')),
            div
            (
                setClass('my-6 text-center'),
                btn
                (
                    setClass('mr-4'),
                    on::click("togglePage('pageTwo')"),
                    $lang->block->prevPage
                ),
                btn
                (
                    setClass('primary'),
                    on::click("togglePage('pageFour')"),
                    $lang->block->nextPage
                )
            )
        ),
        div
        (
            setClass('page-block pageFour hidden'),
            img(set::src("{$imagePath}{$clientLang}_upgrade_guide4_{$version}.svg")),
            div(setClass('learn-more-link flex justify-end text-root text-primary-600'), a(set::href($moreLink), set::target('_blank'), $lang->block->learnMore . ' >')),
            div
            (
                setClass('my-6 text-center'),
                btn
                (
                    setClass('mr-4'),
                    on::click("togglePage('pageThree')"),
                    $lang->block->prevPage
                ),
                btn
                (
                    setClass('primary'),
                    setData('dismiss', 'modal'),
                    $lang->block->experience
                )
            )
        )
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
