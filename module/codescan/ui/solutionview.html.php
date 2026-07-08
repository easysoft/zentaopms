<?php
declare(strict_types=1);
/**
 * The solution view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

/* 初始化主栏内容。Init sections in main column. */
$canUnlink = hasPriv('codescan', 'unlinkset');
$canView   = hasPriv('codescan', 'rulesetview');

$setItems     = array();
$childActions = array();
$rulesetList  = zget($solution, 'ruleSets', array());
foreach($rulesetList as $ruleset)
{
    if($canUnlink) $childActions = array(array(
        'icon'         => 'unlink',
        'url'          => inLink('unlinkset', "solutionID={$solutionID}&setID={$ruleset->id}"),
        'text'         => $lang->codescan->unlinkSet,
        'innerClass'    => 'ajax-submit',
        'data-confirm' => $lang->codescan->notice->confirmUnlinkSet
    ));

    $setItems[] = div
    (
        setClass('pb-4 pr-4 w-1/3'),
        col
        (
            setClass('setting-box cursor-pointer border border-hover rounded-md px-2 py-1 h-36', $canView ? 'open-url' : null),
            set('data-id', $ruleset->id),
            $canView ? set('data-url', inLink('rulesetview', "setID={$ruleset->id}")) : null,
            div
            (
                setClass('flex justify-between items-center h-6'),
                div
                (
                    setClass('clip font-bold text-md my-2'),
                    set::title($ruleset->name),
                    $ruleset->name
                ),
                $canUnlink ? dropdown
                (
                    setClass('size-sm ghost w-4 flex-none'),
                    btn
                    (
                        on::click()->prevent(),
                        setClass('btn dropdown-toggle ghost'),
                        set::icon('ellipsis-v'),
                        set::caret(false)
                    ),
                    set::items($childActions)
                ) : null
            ),
            div
            (
                setClass('h-6 mb-2 tb-2'),
                span
                (
                    setClass('label secondary-pale'),
                    zget($lang->codescan->statusList, $ruleset->status)
                )
            ),
            div
            (
                setClass('h-12 mb-2'),
                set::title($ruleset->desc),
                p
                (
                    setClass('line-clamp-2'),
                    html($ruleset->desc)
                )
            ),
            div
            (
                setClass('flex items-center h-6'),
                set::title(zget($langList, $ruleset->lang)),
                cell
                (
                    set::className('flex items-center mr-2'),
                    avatar(set::text(zget($langList, $ruleset->lang)), set::size('sm'))
                ),
                zget($langList, $ruleset->lang)
            )
        )
    );
}

$setHtml = div
(
    setClass('flex flex-wrap hidden ruleset-block'),
    $setItems
)->render();

$sections = array();
$sections[] = setting()
    ->title($lang->codescan->rulesets)
    ->control('html')
    ->content($setHtml);

/* 基本信息。Legend basic items. */
$items[] = array(
    'label'   => $lang->codescan->name,
    'control' => 'html',
    'content' => "<p>{$solution->name}</p>"
);

$solutionLang = empty($solution->langs) ? array() : array_column($solution->langs, 'lang');
foreach($solutionLang as &$codeLang) $codeLang = zget($langList, $codeLang);
if(!empty($solution->plugins)) foreach($solution->plugins as &$codePlugin) $codePlugin = zget($pluginList, $codePlugin);
$items[] = array(
    'label'   => $lang->codescan->language,
    'control' => 'html',
    'content' => '<p>' . implode(',', $solutionLang) . '</p>'
);
$items[] = array(
    'label'   => $lang->codescan->tool,
    'control' => 'html',
    'content' => '<p>' . (isset($solution->plugins) ? implode(',', $solution->plugins) : '') . '</p>'
);
$items[] = array(
    'label'   => $lang->codescan->status,
    'content' => zget($lang->codescan->statusList, $solution->status)
);
$items[] = array(
    'label'   => $lang->codescan->createdBy,
    'content' => zget($users, $solution->createdBy)
);
$items[] = array(
    'label'   => $lang->codescan->createTime,
    'content' => date('Y-m-d H:i', strtotime($solution->createdDate))
);
$items[] = array(
    'label'   => $lang->codescan->updatedBy,
    'content' => zget($users, $solution->editedBy)
);
$items[] = array(
    'label'   => $lang->codescan->updateTime,
    'content' => date('Y-m-d H:i', strtotime($solution->editedDate))
);
$items[] = array(
    'label'   => $lang->codescan->desc,
    'control' => 'html',
    'content' => '<p>' . (common::checkNotCN() && isset($ruleset->descEn) ? $ruleset->descEn : $solution->desc) . '</p>'
);

$tabs[] = setting()
    ->group('basic')
    ->title($lang->basicInfo)
    ->control('datalist')
    ->labelWidth(80)
    ->items($items);

$actionList = $this->loadModel('common')->buildOperateMenu($solution);
foreach($actionList as $actionType => $typeActions)
{
    foreach($typeActions as $key => $action)
    {
        $actionList[$actionType][$key]['className'] = isset($action['className']) ? $action['className'] . ' ghost' : 'ghost';
        $actionList[$actionType][$key]['url']       = str_replace('{id}', (string)$solutionID, $action['url']);

        if($actionType == 'suffixActions') unset($actionList[$actionType][$key]['text']);
    }
}

$hasDivider = !empty($actionList['mainActions']) && !empty($actionList['suffixActions']);
if(!empty($actionList)) $actionList = array_merge($actionList['mainActions'], $hasDivider ? array(array('type' => 'divider')) : array(), $actionList['suffixActions']);
detail
(
    set::title($solution ? $solution->name : ''),
    set::backBtn('codescan-solution,codescan-planview'),
    set::object($solution),
    set::history(empty($actions) ? false : array('objectType' => 'codescansolution')),
    set::sections($sections),
    set::actions($actionList),
    set::tabs($tabs),
);

h::js('$(".detail-section .ruleset-block").removeClass("hidden");');
