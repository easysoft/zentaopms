<?php
declare(strict_types=1);
/**
 * The execution view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
include './featurebar.html.php';

$app->loadLang('execution');
$app->loadModuleConfig('execution');

jsVar('delayed', $lang->execution->delayed);
jsVar('executionTypeList', $lang->user->executionTypeList);
jsVar('edition', $config->edition);

$cols = array();
foreach($config->user->defaultFields['execution'] as $field) $cols[$field] = zget($config->execution->dtable->fieldList, $field, array());
$cols['name']['nestedToggle'] = false;
$cols['name']['type']         = 'text';
$cols['name']['link']         = $config->user->execution->dtable->name['link'];

$cols['id']    = array('type' => 'checkID', 'title' => $lang->idAB, 'checkbox' => false);
$cols['role']  = array('type' => 'user',    'title' => $lang->team->role, 'sortType' => false);
$cols['join']  = array('type' => 'date',    'title' => $lang->team->join, 'sortType' => false);
$cols['hours'] = array('type' => 'number',  'width' => 100, 'title' => $lang->team->hours, 'sortType' => false);
$cols['name']['name'] = 'name';

$cols = array_map(function($col)
{
    unset($col['fixed'], $col['group']);
    return $col;
}, $cols);

panel
(
    setClass('list'),
    set::title(null),
    dtable
    (
        set::bordered(true),
        set::cols($cols),
        set::data(array_values($executions)),
        set::orderBy($orderBy),
        set::sortLink(inlink('execution', "userID={$user->id}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
        set::onRenderCell(jsRaw('window.renderCell')),
        set::footPager(usePager())
    )
);

render();
