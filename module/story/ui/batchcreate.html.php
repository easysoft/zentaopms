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

/* Generate fields for the batch create form. */
$fnGenerateFields = function() use ($config, $lang, $forceReview, $fields, $showFields)
{
    /* Gather the fields that are displayed. */
    $visibleFields  = array_keys($fields);
    foreach(explode(',', $showFields) as $field)
    {
        if($field) $visibleFields[$field] = '';
    }
    $visibleFields['module']   = '';
    $visibleFields['title']    = '';
    $visibleFields['category'] = '';
    $forceReview && $visibleFields['reviewer'] = '';

    /* Collect required fields. */
    $requiredFields = array();
    if($forceReview) $config->story->create->requiredFields .= ',reviewer';
    foreach(explode(',', $config->story->create->requiredFields) as $field)
    {
        if(!$field) continue;

        $requiredFields[$field] = '';
        if(strpos(",{$config->story->list->customBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
    }

    /* Generate fields with the appropriate properties. */
    $items = array();
    $items[] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '32px');

    return array_merge($items, array_map(function($name, $field) use($requiredFields, $visibleFields)
    {
        $field['name'] = $name;
        if(isset($field['options']) && !isset($field['items'])) $field['items'] = $field['options'];

        /* Set required flag to field. */
        if(isset($requiredFields[$name])) $field['required'] = true;

        /* Set hidden property to field. */
        if(!isset($visibleFields[$name])) $field['hidden'] = true;
        if($name == 'sourceNote' && isset($visibleFields['source']))
        {
            unset($field['hidden']);
        }

        return $field;
    }, array_keys($fields), array_values($fields)));
};

/* Generate customized fields. */
$fnGenerateCustomizedFields = function() use ($showFields, $customFields)
{
    $showFields = ",$showFields,";
    $fields = array();
    $defaultFields = explode(',', 'branch,platform,plan,spec,pri,estimate');
    foreach($customFields as $name => $text)
    {
        $fields[] = array
        (
            'name'    => $name,
            'text'    => $text,
            'show'    => strpos($showFields, ",$name,") !== false,
            'default' => in_array($name, $defaultFields)
        );
    }

    return $fields;
};

formBatchPanel
(
    set::title($storID ? $storyTitle . ' - ' . $this->lang->story->subdivide : $this->lang->story->batchCreate),
    set::uploadParams(createLink('file', 'uploadImages', 'module=story&params=' . helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID&storyID=$storyID&executionID=$executionID&plan=&type=$type"))),
    set::pasteField('title'),
    set::customFields(array('items' => $fnGenerateCustomizedFields())),
    set::items($fnGenerateFields()),
);

render();
