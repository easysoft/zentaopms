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

// In this page, $objectType = product
// $params may have three key: objectID|docType|libID

$products = $this->loadModel('product')->getPairs();
$objectID = isset($params['objectID']) ? (int)$params['objectID'] : key($products);
$docType  = isset($params['docType'])  ? $params['docType']  : 'doc';

if($docType == 'doc')
{
    $libPairs = $this->doc->getLibs($objectType, '', '', (int)$objectID);
}
else
{
    $libs     = $this->doc->getApiLibs(0, $objectType, (int)$objectID);
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
    on::change('[name=type]',      "reloadProduct"),
    on::change('[name=product]',   "reloadProduct"),
    on::change('[name=lib]',       "reloadProduct"),
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
            set::width('4/5'),
            set::label($lang->doc->product),
            set::required(true),
            set::control(array('control' => 'picker', 'name' => 'product', 'items' => $products, 'value' => $objectID, 'required' => true))
        )
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

/* ====== Render page ====== */
render();
