<?php
declare(strict_types=1);
/**
 * The groupcase view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('allTestcases', $lang->testcase->allTestcases);

include 'header.html.php';

$cols = $config->testcase->group->dtable->fieldList;
if(!empty($cols['actions']['list']))
{
    $executionID = ($app->tab == 'project' || $app->tab == 'execution') ? $this->session->{$app->tab} : '0';
    foreach($cols['actions']['list'] as $method => $methodParams)
    {
        if(!isset($methodParams['url'])) continue;

        $cols['actions']['list'][$method]['url'] = str_replace('%executionID%', (string)$executionID, $methodParams['url']);
    }
}
foreach($cols as $colName => $col) $cols[$colName]['sortType'] = false;

$cases = initTableData(array_values($cases), $cols);

dtable
(
    setID('groupCaseTable'),
    set::cols($cols),
    set::data($cases),
    set::userMap($users),
    set::plugins(array('cellspan')),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::getCellSpan(jsRaw('window.getCellSpan'))
);

render();
