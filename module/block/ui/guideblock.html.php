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
$getGuideTabs = function(string $blockNavCode): array
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
            set('class', 'nav-item w-full'),
            a
            (
                set('class', 'ellipsis guide-tab text-dark title ' . ($tab == $selected ? ' active' : '')),
                set('data-tab', $tab),
                set('data-toggle', 'tab'),
                set('href', "#tab{$blockNavCode}Content{$tab}"),
                $tabName
            )
        );
    }
    return $navTabs;
};

/**
 * 获取区块右侧显示的帮助信息。
 * Get guide information.
 *
 * @param  string $blockNavCode
 * @return array
 */
$getGuideInfo = function($blockNavID, $URSRList): array
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
            $function($URSRList)
        );
    }

    return $tabItems;
};


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
blockPanel
(
    setClass('guide-block'),
    to::heading
    (
        !commonModel::isTutorialMode() ? a(
            set(
                array(
                    'href' => createLink('tutorial', 'start'),
                    'class' => 'btn btn-primary warning',
                    'data-toggle' => 'modal'
                )
            ),
            $lang->block->tutorial
        ) : null
    ),
    div
    (
        set('class', 'flex h-full overflow-hidden'),
        cell
        (
            set('width', '18%'),
            set('class', 'border-r overflow-y-auto'),
            ul
            (
                set('class', 'nav nav-tabs nav-stacked'),
                $getGuideTabs($blockNavCode)
            )
        ),
        cell
        (
            set('class', 'tab-content'),
            set('width', '82%'),
            $getGuideInfo($blockNavCode, $URSRList)
        )
    )
);

render();
