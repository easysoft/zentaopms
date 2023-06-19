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
foreach($config->release->dtable->defaultFields['linkStory'] as $field) $cols[$field] = zget($config->story->dtable->fieldList, $field, array());
$cols = array_map(function($col){$col['show'] = true; return $col;}, $cols);
$cols['title']['link']         = $this->createLink('story', 'view', "storyID={id}");
$cols['title']['nestedToggle'] = false;

dtable
(
    set::id('unlinkStoryList'),
    set::userMap($users),
    set::cols($cols),
    set::data($allStories),
    set::footToolbar(array
    (
        'items' => array(array
        (
            'text'      => $lang->release->linkStory,
            'btnType'   => 'primary',
            'className' => 'size-sm linkObjectBtn',
            'data-type' => 'story',
            'data-url'  => inlink('linkStory', "releaseID={$release->id}&browseType={$browseType}&param={$param}"),
        ))
    )),
    set::footer(array('checkbox', 'toolbar', array('html' => html::a(inlink('view', "releaseID={$release->id}&type=story"), $lang->goback, '', "class='btn size-sm'")), 'flex', 'pager')),
    set::footPager(usePager()),
);

render('fragment');
