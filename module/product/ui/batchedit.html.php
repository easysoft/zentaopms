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

jsVar('lines', $lines);

$items = array();
$items['productIdList'] = array('name' => 'productIdList', 'label' => '', 'control' => 'hidden', 'hidden' => true);
$items['id']            = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '60px');
foreach($fields as $fieldName => $field)
{
    if($config->systemMode == 'light' && $fieldName == 'line') continue;
    $items[$fieldName] = array('name' => $fieldName, 'label' => $field['title'], 'control' => $field['control'], 'width' => $field['width'], 'required' => $field['required'], 'items' => zget($field, 'options', array()));
    if($items[$fieldName]['control'] == 'select') $items[$fieldName]['control'] = 'picker';
}
$items['acl']['control'] = array('control' => $items['acl']['control'], 'inline' => true);

/* Build form field value for batch edit. */
$fieldNameList = array_keys($items);

formBatchPanel
(
    on::change('[data-name="program"]', 'loadProductLines'),
    set::title($lang->product->batchEdit),
    set::mode('edit'),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'batchEditFields')),
    set::items($items),
    set::data(array_values($products)),
    set::onRenderRow(jsRaw('renderRowData'))
);

render();
