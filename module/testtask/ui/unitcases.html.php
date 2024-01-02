<?php
declare(strict_types=1);
/**
 * The unitcases view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('allTestcases', $lang->testcase->allTestcases);

$canExport = hasPriv('testcase',  'export');

featureBar();

toolbar
(
    set::items
    (
        array
        (
            $canExport ? array('class' => 'ghost', 'icon' => 'export', 'text' => $lang->export, 'url' => $this->createLink('testcase', 'export', "productID=$productID&orderBy=t1.id&taskID=$taskID&browseType="), 'data-toggle' => 'modal') : null,
            array('class' => 'ghost', 'icon' => 'back', 'text' => $lang->goback, 'url' => $this->session->testtaskList ? $this->session->testtaskList : $this->createLink('testtask', 'browseUnits'))
        )
    )
);

$config->testcase->actionList['runResult']['url'] = array('module' => 'testtask', 'method' => 'results', 'params' => 'runID={id}&caseID={case}');

$cols = $config->testtask->unitgroup->dtable->fieldList;
$cols['actions']['list']         = $config->testcase->actionList;
$cols['actions']['width']        = '60px';
$cols['id']['name']              = 'case';
$cols['title']['link']['params'] = 'caseID={case}';
$cols['bugs']['link']['params']  = 'runID={id}&caseID={case}';
foreach($cols as $field => $param) $cols[$field]['sortType'] = false;

$runs = initTableData($runs, $cols);

dtable
(
    set::id('groupCaseTable'),
    set::userMap($users),
    set::cols($cols),
    set::data($runs),
    set::plugins(array('cellspan')),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::getCellSpan(jsRaw('window.getCellSpan'))
);

render();
