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
            $canExport ? array('class' => 'ghost', 'icon' => 'export', 'text' => $lang->export, 'url' => $this->createLink('testcase', 'export', "productID=$productID&orderBy=id&taskID=$taskID&browseType="), 'data-toggle' => 'modal') : null,
            array('class' => 'ghost', 'icon' => 'back', 'text' => $lang->goback, 'url' => $this->session->testtaskList ? $this->session->testtaskList : $this->createLink('testtask', 'browseUnits'))
        )
    )
);

$config->testcase->actionList['runResult']['url'] = array('module' => 'testtask', 'method' => 'results', 'params' => 'runID={id}&caseID={case}');
$config->testtask->unitgroup->dtable->fieldList['actions']['list']         = $config->testcase->actionList;
$config->testtask->unitgroup->dtable->fieldList['id']['name']              = 'case';
$config->testtask->unitgroup->dtable->fieldList['title']['link']['params'] = 'caseID={case}';
$config->testtask->unitgroup->dtable->fieldList['bugs']['link']['params']  = 'runID={id}&caseID={case}';

$runs = initTableData($runs, $config->testtask->unitgroup->dtable->fieldList);

dtable
(
    set::id('groupCaseTable'),
    set::userMap($users),
    set::cols($config->testtask->unitgroup->dtable->fieldList),
    set::data($runs),
    set::plugins(array('cellspan')),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::getCellSpan(jsRaw('window.getCellSpan')),
);

render();
