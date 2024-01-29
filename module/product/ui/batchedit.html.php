<?php
declare(strict_types=1);
/**
 * The batchedit view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();
$items['productIdList'] = array('name' => 'productIdList', 'label' => '', 'control' => 'hidden', 'hidden' => true);
$items['id']            = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '60px');
foreach($fields as $fieldName => $field)
{
    $items[$fieldName] = array('name' => $fieldName, 'label' => $field['title'], 'control' => $field['control'], 'width' => $field['width'], 'required' => $field['required'], 'items' => zget($field, 'options', array()));
    if($items[$fieldName]['control'] == 'select') $items[$fieldName]['control'] = 'picker';
}
$items['acl']['control'] = array('type' => $items['acl']['control'], 'inline' => true);

$extendFields = $this->product->getFlowExtendFields();
foreach($extendFields as $extendField)
{
    $items[$extendField->field] = array('name' => $extendField->field, 'label' => $extendField->name,  'required' => strpos(",$extendField->rules,", ',1,') !== false, 'control' => $extendField->control, 'items' => zget($extendField, 'options', array()));
    if($extendField->control == 'select') $items[$extendField->field]['control'] = 'picker';
    if($extendField->control == 'multi-select')
    {
        $items[$extendField->field]['control']  = 'picker';
        $items[$extendField->field]['multiple'] = true;
    }
}

/* Build form field value for batch edit. */
$fieldNameList = array_keys($items);
$productData   = array();
foreach($products as $product)
{
    foreach($fieldNameList as $fieldName) $productData[$product->id][$fieldName] = zget($product, $fieldName, $fieldName == 'productIdList' ? $product->id : '');
}

formBatchPanel
(
    set::title($lang->product->batchEdit),
    set::mode('edit'),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'batchEditFields')),
    set::items($items),
    set::data(array_values($productData))
);

render();
