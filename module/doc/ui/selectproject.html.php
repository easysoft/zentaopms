<?php
declare(strict_types=1);
/**
 * The selectlibtype view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

// In this page, $objectType = project
// $params may have three key: objectID|executionID|docType|libID

$projects    = $this->loadModel('project')->getPairsByProgram(0, 'all', false, 'order_asc');
$objectID    = isset($params['objectID']) ? (int)$params['objectID'] : key($projects);
$docType     = isset($params['docType'])  ? $params['docType']  : 'doc';
$executions  = $docType == 'doc' ? $this->loadModel('execution')->getPairs($objectID, 'all', 'multiple,leaf,noprefix') : array();
$executionID = (isset($params['executionID']) && isset($executions[$params['executionID']])) ? $params['executionID'] : '';

$useType  = $executionID ? 'execution' : 'project';
$useID    = $executionID ? $executionID : $objectID;
if($docType == 'doc')
{
    $libPairs = $this->doc->getLibs($useType, '', '', (int)$useID);
}
else
{
    $libs     = $this->doc->getApiLibs(0, $useType, (int)$useID);
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
    on::change('[name=type]',      "reloadProject"),
    on::change('[name=project]',   "reloadProject"),
    on::change('[name=execution]', "reloadProject"),
    on::change('[name=lib]',       "reloadProject"),
    formGroup
    (
        set::label($lang->doc->selectSpace),
        radioList(set::name('rootSpace'), set::items($spaceList), set::value($objectType), set::inline(true))
    ),
    formRow
    (
        setID('docType'),
        formGroup
        (
            set::label($lang->doc->type),
            radioList(set::name('type'), set::items($typeList), set::value($docType), set::inline(true))
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->doc->project),
            set::width('2/5'),
            set::control(array('control' => 'picker', 'name' => 'project', 'items' => $projects, 'value' => $objectID, 'required' => true))
        ),
        $docType == 'doc' ? formGroup
        (
            set::width('2/5'),
            set::label($lang->doc->execution),
            set::labelClass('executionTH'),
            set::control(array('control' => 'picker', 'name' => 'execution', 'items' => $executions, 'value' => $executionID))
        ) : null,
        $docType == 'doc' ? formGroup
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
        ) : null
    ),
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
