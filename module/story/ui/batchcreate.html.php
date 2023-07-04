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

if($forceReview) $config->story->create->requiredFields .= ',reviewer';

/* Generate fields for the batch create form. */
$fnGenerateFields = function() use ($config, $lang, $fields, $execution)
{
    $visibleFields  = array_keys($fields);
    $requiredFields = array();
    foreach(explode(',', $config->story->create->requiredFields) as $field)
    {
        if(!$field) continue;

        $requiredFields[$field] = '';
        if(strpos(",{$config->story->list->customBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
    }
    unset($visibleFields['module']);

    $items = array();

    /* Field of id. */
    $items[] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '32px');

    $cols = array('normal', 'branch', 'module', 'plan', 'region', 'lane', 'title', 'spec', 'source', 'sourceNote', 'verify', 'category', 'pri', 'estimate', 'reviewer', 'keywords');
    foreach($cols as $col)
    {
        if(!isset($fields[$col])) continue;
        if(in_array($col, array('region', 'lane')) && (!isset($execution) || $execution->type != 'kanban')) continue;

        $field = $fields[$col];
        $field['name'] = $col;
        if(isset($field['options']) && !isset($field['items'])) $field['items'] = $field['options'];

        switch($col)
        {
            case 'normal':
            case 'platform':
            case 'branch':
                $items[] = array_merge($field, array('label' => $lang->product->branch));
                break;
            case 'module':
                $items[] = array_merge($field, array('label' => $lang->story->module, 'required' => true, 'ditto' => true));
                break;
            case 'plan':
                $items[] = array_merge($field, array('label' => $lang->story->plan, 'required' => true, 'ditto' => true));
                break;
            case 'region':
                $items[] = array_merge($field, array('label' => $lang->kanbancard->region));
                break;
            case 'lane':
                $items[] = array_merge($field, array('label' => $lang->kanbancard->lane));
                break;
            case 'title':
                $items[] = array_merge($field, array('label' => $lang->story->title, 'required' => true));
                break;
            case 'spec':
                $items[] = array_merge($field, array('label' => $lang->story->spec));
                break;
            case 'source':
                $items[] = array_merge($field, array('label' => $lang->story->source));
                break;
            case 'sourceNote':
                $items[] = array_merge($field, array('label' => $lang->story->sourceNote));
                break;
            case 'verify':
                $items[] = array_merge($field, array('label' => $lang->story->verify));
                break;
            case 'category':
                $items[] = array_merge($field, array('label' => $lang->story->category, 'ditto' => true));
                break;
            case 'pri':
                $items[] = array_merge($field, array('label' => $lang->story->pri));
                break;
            case 'estimate':
                $items[] = array_merge($field, array('label' => $lang->story->estimate));
                break;
            case 'reviewer':
                $field['control'] = 'select';
                $items[] = array_merge($field, array('label' => $lang->story->reviewedBy, 'ditto' => true));
                break;
            case 'keywords':
                $items[] = array_merge($field, array('label' => $lang->story->keywords));
                break;
        }
    }

    return $items;
};

/* Generate customized fields. */
$fnGenerateCustomizedFields = function() use ($showFields, $customFields)
{
    $showFields = ",$showFields,";
    $fields = array();
    foreach($customFields as $name => $text)
    {
        $fields[] = array
        (
            'name' => $name,
            'text' => $text,
            'show' => strpos($showFields, ",$name,") !== false
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
