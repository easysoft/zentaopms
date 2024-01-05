<?php
declare(strict_types=1);
/**
 * The linkBug view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('users', $users);
$buildModule = $app->tab == 'project' ? 'projectbuild' : 'build';
$cols        = array();
foreach($config->build->defaultFields['linkBug'] as $field)
{
    $cols[$field] = zget($config->bug->dtable->fieldList, $field, array());
    if($field == 'resolvedBy')
    {
        $cols['resolvedByControl']                 = $cols['resolvedBy'];
        $cols['resolvedByControl']['type']         = 'control';
        $cols['resolvedByControl']['control']      = 'picker';
        $cols['resolvedByControl']['controlItems'] = $users;
        $cols['resolvedByControl']['defaultValue'] = $app->user->account;
        unset($cols['resolvedBy']);
    }

    if($field != 'resolvedBy') $cols[$field]['sort'] = true;
    if(!empty($cols[$field]['sortType'])) unset($cols[$field]['sortType']);
}
$cols = array_map(function($col){$col['show'] = true; return $col;}, $cols);

searchForm
(
    set::module('bug'),
    set::simple(true),
    set::show(true),
    set::onSearch(jsRaw("window.onSearchLinks.bind(null, 'bug')"))
);

dtable
(
    setID('unlinkBugList'),
    set::userMap($users),
    set::checkable(true),
    set::cols($cols),
    set::data(array_values($allBugs)),
    set::loadPartial(true),
    set::extraHeight('+144'),
    set::onRenderCell(jsRaw('window.renderBugCell')),
    set::plugins(array('form')),
    set::footToolbar(array
    (
        'items' => array(array
        (
            'text'      => $lang->build->linkBug,
            'btnType'   => 'primary',
            'className' => 'size-sm linkObjectBtn',
            'data-type' => 'bug',
            'data-url'  => createLink($buildModule, 'linkBug', "buildID=$build->id&browseType=$browseType&param=$param")
        ))
    )),
    set::footer(array('checkbox', 'toolbar', array('html' => html::a(createLink($buildModule, 'view', "buildID=$build->id&type=bug"), $lang->goback, '', "class='btn size-sm'")), 'flex', 'pager')),
    set::footPager(usePager())
);

render();
