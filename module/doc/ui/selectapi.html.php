<?php
declare(strict_types=1);
/**
 * The selectlibtype view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song<songchenxuan@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

// In this page, $objectType = api
// $params may have four key: apiType|objectID|executionID|libID

$apiType  = isset($params['apiType'])  ? $params['apiType']  : 'product';
$products = $this->loadModel('product')->getPairs();
$projects = $this->loadModel('project')->getPairsByProgram(0, 'all', false, 'order_asc');

if($apiType == 'product')
{
    $useType  = $apiType;
    $objectID = isset($params['objectID']) ? (int)$params['objectID'] : key($products);
    $libPairs = $this->doc->getLibs($useType, '', '', (int)$objectID);
}
elseif($apiType == 'project')
{
    $objectID   = isset($params['objectID']) ? (int)$params['objectID'] : key($projects);
    $executions = $this->loadModel('execution')->getPairs($objectID, 'all', 'multiple,leaf,noprefix');
    $executionID = (isset($params['executionID']) && isset($executions[$params['executionID']])) ? $params['executionID'] : '';

    $useType  = $executionID ? 'execution' : 'project';
    $useID    = $executionID ? $executionID : $objectID;
    $libPairs = $this->doc->getLibs($useType, '', '', (int)$useID);
}
elseif($apiType == 'nolink')
{
    $libs     = $this->doc->getApiLibs(0, 'nolink');
    $libPairs = array();
    foreach($libs as $libID => $lib) $libPairs[$libID] = $lib->name;
}

$libID = isset($params['libID']) && isset($libPairs[$params['libID']]) ? $params['libID'] : key($libPairs);

$modules  = $this->loadModel('tree')->getOptionMenu((int)$libID, 'doc', 0);
$moduleID = key($modules);

form
(
    setID('selectLibTypeForm'),
    set::submitBtnText($lang->doc->nextStep),
    on::change('[name=rootSpace]', "changeSpace"),
    on::change('[name=apiType]',   "reloadApiByApiType"),
    on::change('[name=project]',   "reloadApi"),
    on::change('[name=execution]', "reloadApi"),
    on::change('[name=product]',   "reloadApi"),
    on::change('[name=lib]',       "reloadApi"),
    formGroup
    (
        set::label($lang->doc->selectSpace),
        radioList(set::name('rootSpace'), set::items($spaceList), set::value($objectType), set::inline(true))
    ),
    formRow
    (
        formGroup
        (
            set::width('2/5'),
            set::label($lang->doc->apiType),
            set::control(array('control' => 'picker', 'name' => 'apiType', 'items' => $lang->doc->apiTypeList, 'value' => $apiType, 'required' => true))
        )
    ),
    $apiType == 'project' ? formRow
    (
        formGroup
        (
            set::label($lang->doc->project),
            set::width('2/5'),
            set::control(array('control' => 'picker', 'name' => 'project', 'items' => $projects, 'value' => $objectID, 'required' => true))
        ),
        formGroup
        (
            set::width('2/5'),
            set::label($lang->doc->execution),
            set::labelClass('executionTH'),
            set::control(array('control' => 'picker', 'name' => 'execution', 'items' => $executions, 'value' => $executionID))
        ),
        formGroup
        (
            setClass('executionHelp'),
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->doc->placeholder->execution),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ) : null,
    $apiType == 'product' ? formRow
    (
        formGroup
        (
            set::width('4/5'),
            set::label($lang->doc->product),
            set::required(true),
            set::control(array('control' => 'picker', 'name' => 'product', 'items' => $products, 'value' => $objectID, 'required' => true))
        )
    ) : null,
    formGroup
    (
        set::width('4/5'),
        set::label($lang->doc->lib),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'lib', 'items' => $libPairs, 'value' => $libID, 'required' => true))
    ),
    formGroup
    (
        setClass('moduleBox'),
        set::width('4/5'),
        set::label($lang->doc->module),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'module', 'items' => $modules, 'value' => $moduleID, 'required' => true))
    )
);
