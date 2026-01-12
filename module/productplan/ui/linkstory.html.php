<?php
declare(strict_types=1);
/**
 * The linkStory view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

$cols = array();
foreach($config->productplan->defaultFields['linkStory'] as $field) $cols[$field] = zget($config->story->dtable->fieldList, $field, array());
$cols = array_map(function($col){$col['show'] = true; return $col;}, $cols);
$cols['title']['link']         = $this->createLink('story', 'storyView', "storyID={id}");
$cols['title']['title']        = $lang->productplan->storyTitle;
$cols['title']['nestedToggle'] = true;
$cols['title']['data-toggle']  = 'modal';
$cols['title']['data-size']    = 'lg';
$cols['plan']['name']          = 'planTitle';
$cols['plan']['sortType']      = false;
$cols['assignedTo']['type']    = 'user';
$cols['module']['type']        = 'text';
$cols['module']['map']         = $modules;

foreach($allStories as $story) $story->estimate = helper::formatHours($story->estimate) . $config->hourUnit;

$config->product->search['fields']['title'] = $lang->productplan->storyTitle;
searchForm
(
    set('zui-key', 'searchForm'),
    set::module('story'),
    set::simple(true),
    set::show(true),
    set::onSearch(jsRaw("window.onSearchLinks.bind(null, 'story')"))
);

dtable
(
    setID('unlinkStoryList'),
    set::userMap($users),
    set::cols($cols),
    set::data(array_values($allStories)),
    set::orderBy($orderBy),
    set::sortLink(inlink('linkStory', "planID={$plan->id}&browseType=$browseType&param=$param&orderBy={name}_{sortType}")),
    set::noNestedCheck(),
    set::onRenderCell(jsRaw('window.renderStoryCell')),
    set::extraHeight('+144'),
    set::loadPartial(true),
    set::footToolbar(array('items' => array(array
        (
            'text'         => $lang->productplan->linkStory,
            'btnType'      => 'secondary',
            'className'    => 'size-sm linkObjectBtn',
            'data-type'    => 'story',
            'data-url'     => inlink('linkStory', "planID={$plan->id}&browseType=$browseType&param=$param&orderBy=$orderBy"),
            'zui-on-click' => 'handleLinkObjectClick($target)'
        ))
    )),
    set::footer(array('checkbox', 'toolbar', array('html' => html::a(inlink('view', "planID=$plan->id&type=story"), $lang->goback, '', "class='btn size-sm'")), 'flex', 'pager')),
    set::footPager(usePager())
);

render();
