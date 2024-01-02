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

$footToolbar = array(
    'items' => array
    (
        array(
            'text'      => $lang->mr->linkStory,
            'className' => 'batch-btn ajax-btn',
            'data-app'  => $app->tab,
            'data-url'  => helper::createLink('mr', 'linkStory', "MRID=$MRID&productID=$product->id&browseType=$browseType&param=$param&orderBy=$orderBy")
        )
    ),
    'btnProps' => array('size' => 'sm', 'btnType' => 'secondary', 'data-type' => 'stories')
);

searchForm
(
    set::module('story'),
    set::simple(true),
    set::show(true)
);

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
$cols = array();
foreach($config->release->dtable->defaultFields['linkStory'] as $field) $cols[$field] = zget($config->release->dtable->story->fieldList, $field, array());
$cols = array_map(function($col){$col['show'] = true; return $col;}, $cols);
$cols['title']['data-toggle']  = '';
$cols['title']['nestedToggle'] = false;
$cols['title']['link']         = array('module' => 'story', 'method' => 'view', 'params' => 'storyID={id}', 'target' => '_blank');

$data = array_values($allStories);
dtable
(
    set::userMap($users),
    set::data($data),
    set::cols($cols),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::sortLink(jsRaw('createSortLink')),
    set::footer(array('checkbox', 'toolbar', array('html' => html::a(inlink('link', "MRID={$MRID}&type=story"), $lang->goback, '', "class='btn size-sm'")), 'flex', 'pager')),
    set::footPager(usePager())
);

render();
