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

$buildModule = $app->tab == 'project' ? 'projectbuild' : 'build';
$cols        = array();
foreach($config->build->defaultFields['linkBug'] as $field) $cols[$field] = zget($config->bug->dtable->fieldList, $field, array());
$cols = array_map(function($col){$col['show'] = true; return $col;}, $cols);
$cols['resolvedBy']['type'] = 'html';

div(setID('searchFormPanel'), set('data-module', 'bug'), searchToggle(set::open(true), set::module('bug')));
dtable
(
    set::id('unlinkBugList'),
    set::userMap($users),
    set::checkable(true),
    set::cols($cols),
    set::data(array_values($allBugs)),
    set::onRenderCell(jsRaw('window.renderBugCell')),
    set::footToolbar(array
    (
        'items' => array(array
        (
            'text'      => $lang->build->linkBug,
            'btnType'   => 'primary',
            'className' => 'size-sm linkObjectBtn',
            'data-type' => 'bug',
            'data-url'  => inlink('linkBug', "buildID=$build->id&browseType=$browseType&param=$param"),
        ))
    )),
    set::footer(array('checkbox', 'toolbar', array('html' => html::a(inlink('view', "buildID=$build->id&type=bug"), $lang->goback, '', "class='btn size-sm'")), 'flex', 'pager')),
    set::footPager(usePager(array
    (
        'recPerPage'  => $pager->recPerPage,
        'recTotal'    => $pager->recTotal,
        'linkCreator' => helper::createLink($buildModule, 'view', "buildID={$build->id}&type=bug&link=true&param=" . helper::safe64Encode("&browseType={$browseType}&param={$param}") . "&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}")
    ))),
);

$jsonUsers = json_encode($users);
h::js
(
<<<EOD
const users = {$jsonUsers};
window.renderBugCell = function(result, info)
{
    const bug = info.row.data;
    if(info.col.name == 'resolvedBy' && result)
    {
        if(bug.status == 'resolved' || bug.status == 'closed') return [users[bug.resolvedBy]];

        let html = '';
        html += "<select name='resolvedBy[" + bug.id + "]' id='resolvedBy_" + bug.id + "'>";
        for(account in users)
        {
            realname = users[account];
            selected = account == '{$app->user->account}' ? 'selected' : '';
            html += "<option value='" + account + "' " + selected + ">" + realname + "</option>";
        }
        html += '</select>';
        return [html];
    }
    return result;
};
EOD
);

render();
