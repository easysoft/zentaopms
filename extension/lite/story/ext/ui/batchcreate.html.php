<?php
declare(strict_types=1);
/**
* The UI file of story module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     story
* @link        https://www.zentao.net
*/

namespace zin;

!isAjaxRequest() && dropmenu();

$showFields = array('module', 'title', 'spec', 'pri', 'estimate', 'reviewer', 'keywords');
foreach($fields as $fieldKey => $fieldConfig)
{
    if(!in_array($fieldKey, $showFields)) unset($fields[$fieldKey]);
}

/* Generate fields for the batch create form. */
$fnGenerateFields = function() use ($lang, $fields)
{
    /* Generate fields with the appropriate properties. */
    $items   = array();
    $items[] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '32px');

    return array_merge($items, array_map(function($name, $field)
    {
        $field['name'] = $name;
        if(!empty($field['options'])) $field['items'] = $field['options'];
        if(!empty($field['default'])) $field['value'] = $field['default'];
        if($field['control'] == 'select') $field['control'] = 'picker';
        unset($field['options']);

        return $field;
    }, array_keys($fields), array_values($fields)));
};

formBatchPanel
(
    on::click('#saveButton', 'customSubmit'),
    on::click('#saveDraftButton', 'customSubmit'),
    set::id('dataform'),
    set::title($storyID ? $storyTitle . $lang->colon . $this->lang->story->subdivide : $this->lang->story->batchCreate),
    set::uploadParams('module=story&params=' . helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID&storyID=$storyID&executionID=$executionID&plan=&type=$type")),
    set::pasteField('title'),
    set::customFields(array('list' => array(), 'show' => $showFields, 'key' => 'batchCreateFields')),
    set::items($fnGenerateFields()),
    set::actions(array
    (
        array('text' => $lang->save,             'id' => 'saveButton',      'class' => 'primary'),
        array('text' => $lang->story->saveDraft, 'id' => 'saveDraftButton', 'class' => 'secondary'),
        array('text' => $lang->goback, 'data-back' => 'APP', 'class' => 'open-url'),
    ))
);

render();
