<?php
declare(strict_types=1);
/**
 * The browse view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

$lang->testcase->typeList[''] = $lang->testcase->allType;
if(!isset($param)) $param = 0;

$hasUnitPriv   = common::hasPriv('testtask', 'browseunits');
$dropdownItems = array();
foreach($lang->testcase->typeList as $type => $typeName)
{
    if($hasUnitPriv and $type == 'unit')
    {
        $url  = $this->createLink('testtask', 'browseUnits', "productID=$productID&browseType=newest&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1&projectID=$projectID");
        $text = $lang->testcase->browseUnits;
    }
    elseif(isset($groupBy))
    {
        $url  = $this->createLink('testcase', 'groupCase', "productID=$productID&branch=$branch&groupBy=story&projectID=$projectID&caseType=$type");
        $text = $typeName;
    }
    else
    {
        $url  = $this->createLink('testcase', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&caseType=$type");
        $text = $typeName;
    }

    $dropdownItems[] = array('text' => $text, 'url' => $url, 'active' => $type == $caseType);
}

$currentTypeName = zget($lang->testcase->typeList, $caseType, '');
$currentLabel    = empty($currentTypeName) ? $lang->testcase->allType : $currentTypeName;
featureBar
(
    to::before
    (
        dropdown
        (
            btn
            (
                setClass('dropdown-toggle'),
                $currentLabel
            ),
            set::items($dropdownItems)
        )
    )
);

toolbar
(
    btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule")),
            $lang->testcase->create
        ),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items
            (
                array
                (
                    array('text' => $lang->testcase->create,      'url' => helper::createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule")),
                    array('text' => $lang->testcase->batchCreate, 'url' => helper::createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule")),
                    array('text' => $lang->testcase->newScene,    'url' => helper::createLink('testcase', 'createScene', "productID=$productID&branch=$branch&moduleID=$initModule"))
                )
            ),
            set::placement('bottom-end'),
        )
    )
);

$closeLink = $browseType == 'bymodule' ? createLink($currentModule, $currentMethod, $projectParam . "productID=$productID&branch=$branch&browseType=$browseType&param=0&caseType=&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("caseModule")';
sidebar
(
    moduleMenu(set(array
    (
        'modules'   => $moduleTree,
        'activeKey' => $moduleID,
        'closeLink' => $closeLink
    )))
);

$this->testcase->buildOperateMenu(null, 'browse');

foreach($cases as $case)
{
    $actions = array();
    foreach($this->config->testcase->dtable->fieldList['actions']['actionsMap'] as $actionCode => $actionMap)
    {
        $isClickable = $this->testcase->isClickable($case, $actionCode);

        $actions[] = $isClickable ? $actionCode : array('name' => $actionCode, 'disabled' => true);
    }
    $case->actions = $actions;
}

$cols = array_values($config->testcase->dtable->fieldList);
$data = array_values($cases);

$footToolbar = array('items' => array
(
    array('type' => 'btn-group', 'items' => array
    (
        array('text' => $lang->testtask->runCase, 'className' => 'batch-btn', 'data-url' => helper::createLink('testtask', 'batchRun', "productID=$productID&orderBy=$orderBy")),
        array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => helper::createLink('bug', 'batchEdit', "productID={$product->id}&branch=$branch")),
        array('caret' => 'up', 'btnType' => 'primary', 'url' => '#navActions', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    )),
    array('caret' => 'up', 'text' => $lang->testcase->moduleAB, 'btnType' => 'primary', 'url' => '#navModule', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    array('text' => $lang->testcase->importToLib, 'btnType' => 'primary', 'data-toggle' => 'modal', 'data-url' => '#importToLib'),
    array('caret' => 'up', 'text' => $lang->testcase->scene, 'btnType' => 'primary', 'url' => '#navScene','data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
));

$typeItems = array();
foreach($lang->testcase->typeList as $key => $result) $typeItems[] = array('text' => $result, 'className' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchCaseTypeChange', "result=$key"));

zui::menu
(
    set::id('navActions'),
    set::class('menu dropdown-menu'),
    set::items(array
    (
        array('text' => $lang->delete, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchDelete', "productID=$productID")),
        array('text' => $lang->testcase->type, 'class' => 'not-hide-menu', 'items' => $typeItems),
        array('text' => $lang->testcase->confirmStoryChange, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchConfirmStoryChange', "productID=$productID")),
    ))
);

$moduleItems = array();
foreach($modules as $moduleId => $module) $moduleItems[] = array('text' => $module, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchChangeModule', "moduleID=$moduleId"));

menu
(
    set::id('navModule'),
    set::class('dropdown-menu'),
    set::items($moduleItems)
);

$sceneItems = array();
foreach($iscenes as $sceneID => $scene) $sceneItems[] = array('text' => $scene, 'class' => 'batch-btn ajax-btn', 'data-url' => helper::createLink('testcase', 'batchChangeScene', "sceneId=$sceneID"));

menu
(
    set::id('navScene'),
    set::class('dropdown-menu'),
    set::items($sceneItems)
);

dtable
(
    set::cols($cols),
    set::data($data),
    set::footPager(usePager()),
    set::checkable(true),
    set::footToolbar($footToolbar),
);

render();
