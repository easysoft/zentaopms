<?php
declare(strict_types=1);
/**
 * The UI view file of story module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('twinsCount', $twinsCount);
jsVar('langTwins', $lang->story->twins . ': ');
if(!empty($errorTips)) js("zui.Modal.alert({message: '{$errorTips}', icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'});\n");

unset($lang->story->reasonList['subdivided']);
$fields = $config->story->form->batchClose;
$fields['closedReason']['options'] = array_filter($lang->story->reasonList);

$items = array();
$items['storyIdList'] = array('name' => 'storyIdList', 'label' => '', 'control' => 'hidden', 'hidden' => true);
$items['id']          = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '60px');
foreach($fields as $fieldName => $field)
{
    $items[$fieldName] = array('name' => $fieldName, 'label' => zget($lang->story, $fieldName), 'control' => $field['control'], 'width' => $field['width'], 'required' => $field['required'], 'items' => zget($field, 'options', array()));
}
$items['comment']['label'] = $lang->comment;

/* Build form field value for batch edit. */
$fieldNameList = array_keys($items);
$data          = array();
foreach($stories as $storyID => $story)
{
    $data[$storyID] = $story;
    foreach($fieldNameList as $fieldName)
    {
        if($fieldName == 'storyIdList') $data[$storyID]->storyIdList = $story->id;
        if(!isset($story->$fieldName)) $story->$fieldName = '';
    }
}

formBatchPanel
(
    set::title($lang->story->batchClose),
    set::mode('edit'),
    set::items($items),
    set::data(array_values($data)),
    set::onRenderRow(jsRaw('renderRowData')),
);

render();
