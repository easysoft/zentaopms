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

jsVar('storyType', $type);

!isAjaxRequest() && dropmenu();

/* Generate fields for the batch create form. */
$fnGenerateFields = function() use ($lang, $fields, $stories, $customFields, $showFields)
{
    /* Generate fields with the appropriate properties. */
    $items   = array();
    $items[] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '32px');
    if($stories) $items[] = array('name' => 'uploadImage', 'label' => '', 'control' => 'hidden', 'hidden' => true);
    unset($fields['color']);

    $cols = array_merge($items, array_map(function($name, $field)
    {
        if($name == 'title') $field['control'] = 'colorInput';

        $field['name'] = $name;
        if(!empty($field['options'])) $field['items'] = $field['options'];
        if(!empty($field['default'])) $field['value'] = $field['default'];
        if($field['control'] == 'select') $field['control'] = 'picker';
        if($field['control'] == 'picker' and !isset($field['items'])) $field['items'] = array();
        unset($field['options']);

        return $field;
    }, array_keys($fields), array_values($fields)));

    /* Hide columns that are not displayed. */
    foreach($cols as $index => $col)
    {
        $colName = $col['name'];
        if(isset($customFields[$colName]) && strpos(",$showFields,", ",$colName,") === false) $cols[$index]['hidden'] = true;
        if($colName == 'sourceNote' && strpos(",$showFields,", ",source,") === false) $cols[$index]['hidden'] = true;
    }
    return $cols;
};

formBatchPanel
(
    setID('dataform'),
    $stories ? set::data($stories) : null,
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    set::title($storyID ? $storyTitle . $lang->colon . $this->lang->story->subdivide : $this->lang->story->batchCreate),
    set::uploadParams('module=story&params=' . helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID&storyID=$storyID&executionID=$executionID&plan=&type=$type")),
    set::pasteField('title'),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'batchCreateFields')),
    set::items($fnGenerateFields()),
    set::actions(array
    (
        array('text' => $lang->save,             'data-status' => 'active', 'class' => 'primary',   'btnType' => 'submit'),
        array('text' => $lang->story->saveDraft, 'data-status' => 'draft',  'class' => 'secondary', 'btnType' => 'submit'),
        array('text' => $lang->goback,           'data-back'   => 'APP',    'class' => 'open-url')
    )),
    formHidden('type', $type),
    formHidden('status')
);

render();
