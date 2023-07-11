<?php
declare(strict_types=1);
/**
 * The linkstory file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('orderBy',  $orderBy);
jsVar('sortLink', createLink('mr', 'linkStory', "MRID=$MRID&productID=$product->id&browseType=$browseType&param=$param&orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));

detailHeader
(
    to::prefix(''),
    to::title
    (
        $lang->mr->linkStory,
    )
);

$footToolbar = array('items' => array
(
    array('text' => $lang->mr->linkStory, 'className' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('mr', 'linkStory', "MRID=$MRID&productID=$product->id&browseType=$browseType&param=$param&orderBy=$orderBy"))
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary', 'data-type' => 'stories'));

div(setID('searchFormPanel'), set('data-module', 'story'), searchToggle(set::open(true), set::module('story')));

div
(
    set('class', 'mr-linkstory-title'),
    icon('unlink'),
    span
    (
        set('class', 'font-semibold ml-2'),
        $lang->productplan->unlinkedStories . "({$pager->recTotal})"
    )
);
$config->repo->storyDtable->fieldList['module']['map'] = $modules;
$config->repo->storyDtable->fieldList['title']['width'] = '100';
$allStories = initTableData($allStories, $config->repo->storyDtable->fieldList);
$data = array_values($allStories);
dtable
(
    set::userMap($users),
    set::data($data),
    set::cols($config->repo->storyDtable->fieldList),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::sortLink(jsRaw('createSortLink')),
    set::footPager(usePager()),
);

render();
