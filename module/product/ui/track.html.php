<?php
declare(strict_types=1);
/**
* The UI file of product module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     product
* @link        https://www.zentao.net
*/

namespace zin;

$storyTypePairs['epic']        = $lang->story->viewByER;
$storyTypePairs['requirement'] = $lang->story->viewByUR;
$storyTypePairs['story']       = $lang->story->viewBySR;

$storyTypeLang = $lang->SRCommon;
if($storyType == 'requirement') $storyTypeLang = $lang->URCommon;
if($storyType == 'epic') $storyTypeLang = $lang->ERCommon;

featureBar
(
    to::leading
    (
        dropdown
        (
            to('trigger', btn(setClass('switchBtn'), $storyTypePairs[$storyType])),
            set::items(array(
                array('text' => $lang->story->viewByER, 'url' => createLink('product', 'track', "productID={$productID}&branch={$branch}&projectID={$projectID}&browseType=allstory&param=0&storyType=epic")),
                array('text' => $lang->story->viewByUR, 'url' => createLink('product', 'track', "productID={$productID}&branch={$branch}&projectID={$projectID}&browseType=allstory&param=0&storyType=requirement")),
                array('text' => $lang->story->viewBySR, 'url' => createLink('product', 'track', "productID={$productID}&branch={$branch}&projectID={$projectID}&browseType=allstory&param=0&storyType=story"))
            )),
            set::arrow(14),
            set::width('145px'),
        )
    ),
    li(searchToggle(set::open($browseType == 'bysearch' || $storyBrowseType == 'bysearch'), set::module($config->product->search['module']), set::text($lang->searchAB . $storyTypeLang)))
);

toolbar
(
    formSettingBtn(set::text($lang->settings))
);

jsVar('langStoryPriList', $lang->story->priList);
jsVar('langStoryStatusList', $lang->story->statusList);
jsVar('langStoryStageList', $lang->story->stageList);

empty($tracks) ? div(setClass('dtable-empty-tip bg-white shadow'), span(setClass('text-gray'), $lang->noData)) : div
(
    set::id('track'),
    zui::kanbanList
    (
        set::key('kanban'),
        set::items(array(array('data' => $tracks, 'getItem' => jsRaw('window.getItem'), 'canDrop' => jsRaw('window.canDrop')))),
        set::height('calc(100vh - 130px)')
    ),
    pager(setClass('justify-end'))
);
