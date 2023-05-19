<?php
declare(strict_types=1);
/**
* The guide block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

/**
 * 获取区块左侧的帮助列表。
 * Get guide tabs on the left side.
 *
 * @param  string $blockNavCode
 * @return array
 */
function getGuideTabs(string $blockNavCode): array
{
    global $lang, $config, $app;

    $navTabs  = array();
    $selected = key($lang->block->guideTabs);
    foreach($lang->block->guideTabs as $tab => $tabName)
    {
        if(strpos($tab, 'download') !== false && (!isset($config->xxserver->installed) || !$config->xuanxuan->turnon)) continue;
        if($tab == 'downloadMoblie' && common::checkNotCN()) continue;
        if(($tab == 'preference' || $tab == 'systemMode') && $config->vision == 'lite') continue;
        if($tab == 'systemMode' && !common::hasPriv('custom', 'mode')) continue;
        if($tab == 'preference' && !common::hasPriv('my', 'preference')) continue;
        if($tab == 'visionSwitch' && !strpos($app->user->visions, ',')) continue;

        $navTabs[] = li
        (
            set('class', 'nav-item w-full' . ($tab == $selected ? ' active' : '')),
            a
            (
                set('class', 'ellipsis guide-tab text-dark'),
                set('data-tab', $tab),
                set('data-toggle', 'tab'),
                set('href', "#tab{$blockNavCode}Content{$tab}"),
                $tabName
            ),
            span
            (
                set('class', 'link flex-1 text-right px-4 hidden'),
                icon
                (
                    set('class', 'text-primary'),
                    'arrow-right'
                )
            )
        );
    }
    return $navTabs;
}

/**
 * 获取区块右侧显示的帮助信息。
 * Get guide information.
 *
 * @param  string $blockNavCode
 * @return array
 */
function getGuideInfo($blockNavID): array
{
    global $lang;

    $selected = key($lang->block->guideTabs);
    $tabItems = array();
    foreach($lang->block->guideTabs as $tab => $tabName)
    {
        include_once (strtolower($tab) . '.html.php');
        $function = '\zin\print' . ucfirst($tab);
        $tabItems[] = div
        (
            set('class', 'tab-pane h-full' . ($tab == $selected ? ' active' : '')),
            set('id', "tab{$blockNavID}Content{$tab}"),
            $function()
        );
    }
    return $tabItems;
}


$blockNavCode          = 'nav-' . uniqid();
$config->URSRList      = $URSRList;
$config->programID     = $programID;
$config->programs      = $programs;
$config->URSR          = $URSR;
$config->programLink   = $programLink;
$config->productLink   = $productLink;
$config->projectLink   = $projectLink;
$config->executionLink = $executionLink;

$usedMode = zget($config->global, 'mode', 'light');
jsVar('changeModeTips', sprintf($lang->custom->changeModeTips, $lang->custom->modeList[$usedMode == 'light' ? 'ALM' : 'light']));
div
(
    set('class', 'guide-block of-hidden'),
    div
    (
        set('class', 'flex h-full'),
        cell
        (
            set('width', '22%'),
            set('class', 'bg-secondary-pale'),
            ul
            (
                set('class', 'nav nav-tabs nav-stacked h-full of-y-auto of-x-hidden'),
                getGuideTabs($blockNavCode)
            ),
        ),
        cell
        (
            set('class', 'tab-content'),
            set('width', '78%'),
            getGuideInfo($blockNavCode)
        )
    )
);

render();
