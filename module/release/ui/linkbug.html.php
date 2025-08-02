<?php
declare(strict_types=1);
/**
 * The linkBug view file of relase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     release
 * @link        https://www.zentao.net
 */
namespace zin;

$buildModule = $app->rawModule == 'projectrelease' ? 'projectrelease' : 'release';

$cols = array();
foreach($config->release->dtable->defaultFields['linkBug'][$type] as $field)
{
    $cols[$field] = zget($config->bug->dtable->fieldList, $field, array());
    if($field == 'title')
    {
        $cols['title']['data-toggle'] = 'modal';
        $cols['title']['data-size']   = 'lg';
    }
}
$cols = array_map(function($col){$col['show'] = true; return $col;}, $cols);

searchForm
(
    set('zui-key', 'searchForm'),
    set::module('bug'),
    set::simple(true),
    set::show(true),
    set::onSearch(jsRaw("window.onSearchLinks.bind(null, '{$type}')"))
);

$checkedRows = array_values(array_filter(array_map(function($bug) use($type) {return ($type == 'leftBug' or $bug->status == 'resolved' or $bug->status == 'closed') ? $bug->id : 0;}, $allBugs)));
dtable
(
    set::id($type == 'leftBug' ? 'linkLeftBugList': 'linkBugList'),
    set::userMap($users),
    set::checkable(true),
    set::cols($cols),
    set::data($allBugs),
    set::checkedRows($checkedRows),
    set::loadPartial(true),
    set::footToolbar(array
    (
        'items' => array(array
        (
            'text'         => $lang->release->linkBug,
            'btnType'      => 'primary',
            'className'    => 'size-sm linkObjectBtn',
            'data-type'    => 'bug',
            'data-url'     => createLink($buildModule, 'linkBug', "releaseID={$release->id}&browseType={$browseType}&param={$param}&type={$type}"),
            'zui-on-click' => 'handleLinkObjectClick($target)'
        ))
    )),
    set::extraHeight('+144'),
    set::footer(array('checkbox', 'toolbar', array('html' => html::a(createLink($buildModule, 'view', "releaseID=$release->id&type=$type"), $lang->goback, '', "class='btn size-sm'")), 'flex', 'pager')),
    set::footPager(usePager())
);

render();
