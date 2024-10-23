<?php
declare(strict_types=1);
/**
 * The selectcustomandmine view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song<songchenxuan@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

// $objectType = mine|custom
// $params may have two key: objectID|libID

$spaces   = $this->doc->getSubSpacesByType($objectType);
$objectID = isset($params['objectID']) ? $params['objectID'] : key($spaces);

$libs     = $this->doc->getLibs($objectType, '', '', (int)$objectID);
$libID    = isset($params['libID']) && isset($libs[$params['libID']]) ? $params['libID'] : key($libs);

$modules  = $this->loadModel('tree')->getOptionMenu((int)$libID, 'doc', 0);
$moduleID = key($modules);

form
(
    setID('selectLibTypeForm'),
    set::submitBtnText($lang->doc->nextStep),
    on::change('[name=rootSpace]', "changeSpace"),
    on::change('[name=mine]',      "reloadmineandcustom"),
    on::change('[name=custom]',    "reloadmineandcustom"),
    on::change('[name=lib]',       "reloadmineandcustom"),
    formGroup
    (
        set::label($lang->doc->selectSpace),
        radioList(set::name('rootSpace'), set::items($spaceList), set::value($objectType), set::inline(true))
    ),
    formRow
    (
        formGroup
        (
            set::width('4/5'),
            set::label($lang->doc->space),
            set::required(true),
            set::control(array('control' => 'picker', 'name' => $objectType, 'items' => $spaces, 'value' => $objectID, 'required' => true))
        )
    ),
    formGroup
    (
        set::width('4/5'),
        set::label($lang->doc->lib),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'lib', 'items' => $libs, 'value' => $libID, 'required' => true))
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
