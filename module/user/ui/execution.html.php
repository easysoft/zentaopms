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

jsVar('delayed', $lang->execution->delayed);
jsVar('executionTypeList', $lang->user->executionTypeList);
jsVar('edition', $config->edition);

$cols = array();
foreach($config->user->defaultFields['execution'] as $field) $cols[$field] = zget($config->execution->dtable->fieldList, $field, array());
$cols['name']['nestedToggle'] = false;
$cols['name']['type']         = 'text';
$cols['name']['link']         = array('module' => 'execution', 'method' => 'view', 'params' => 'executionID={id}');

$cols['id']    = array('type' => 'checkID', 'title' => $lang->idAB, 'checkbox' => false);
$cols['role']  = array('type' => 'user',    'title' => $lang->team->role);
$cols['join']  = array('type' => 'date',    'title' => $lang->team->join);
$cols['hours'] = array('type' => 'number',  'width' => 100, 'title' => $lang->team->hours);
$cols['name']['name'] = 'name';

$cols = array_map(function($col)
{
    unset($col['fixed'], $col['group']);
    $col['sortType'] = false;
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
        set::onRenderCell(jsRaw('window.renderCell')),
        set::footPager(usePager()),
    )
);

render();
