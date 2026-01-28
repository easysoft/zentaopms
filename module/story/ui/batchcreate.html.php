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

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

if($app->tab == 'product') data('activeMenuID', $type);
data('storyType', $type);
jsVar('storyType', $type);
jsVar('productID', $productID);
jsVar('executionID', $executionID);
jsVar('branch', $branch);
jsVar('storyID', $storyID);
data('gradeRule', $gradeRule);

!isAjaxRequest() && dropmenu();

foreach(explode(',', $this->config->{$type}->create->requiredFields) as $requiredField)
{
    if(isset($customFields[$requiredField]) and strpos(",{$showFields},", ",{$requiredField},") === false) $showFields .= ',' . $requiredField;
}

$roadmaps = !empty($roadmaps) ? $roadmaps : array();

/* Generate fields for the batch create form. */
$fnGenerateFields = function() use ($app, $lang, $type, $fields, $stories, $customFields, $showFields, $storyID, $gradeRule, $roadmaps)
{
    global $config;

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
        if(str_contains(",{$config->{$app->rawModule}->create->requiredFields},", ",{$colName},")) $cols[$index]['required'] = true;
        if(isset($customFields[$colName]) && strpos(",$showFields,", ",$colName,") === false) $cols[$index]['hidden'] = true;
        if($colName == 'sourceNote' && strpos(",$showFields,", ",source,") === false) $cols[$index]['hidden'] = true;
        if($colName == 'plan' && $type != 'story') $cols[$index]['multiple'] = true;
        if($colName == 'grade' && $gradeRule == 'stepwise') $cols[$index]['disabled'] = true;
        if($colName == 'parent' && $storyID > 0 ) $cols[$index]['hidden'] = true;
        if($colName == 'source')   $cols[$index]['items']    = $lang->{$type}->sourceList;
        if($colName == 'category') $cols[$index]['items']    = $lang->{$type}->categoryList;
        if($colName == 'roadmap')  $cols[$index]['items']    = $roadmaps;
        if($colName == 'grade')    $cols[$index]['required'] = true;
        if($colName == 'pri')
        {
            $cols[$index]['items']   = $lang->{$type}->priList;
            $cols[$index]['value']   = (string)$col['value'];
            $cols[$index]['default'] = (string)$col['default'];
        }
    }

    return $cols;
};

$storyTypeRadio = null;
if($storyID > 0 && $story->type != 'story' && $config->{$story->type}->gradeRule != 'stepwise')
{
    $gradeRule   = $this->config->{$story->type}->gradeRule;
    $isLastGrade = $story->grade == $maxGradeGroup[$story->type];
    $disabled    = $story->isParent == '1' || $isLastGrade || ($gradeRule == 'stepwise' && !$isLastGrade);

    $title = '';
    if($story->isParent == '1')                   $title = $lang->story->errorCannotSplit;
    if($isLastGrade)                              $title = $lang->story->errorMaxGradeSubdivide;
    if(!$isLastGrade && $gradeRule == 'stepwise') $title = $lang->story->errorStepwiseSubdivide;

    if($story->type == 'epic')        unset($lang->story->typeList['story']);
    if($story->type == 'requirement') unset($lang->story->typeList['epic']);
    if($story->type == 'requirement' && $config->vision == 'or') unset($lang->story->typeList['story']);

    $storyTypeRadio = radioList
    (
        set::name('typeSwitcher'),
        set::items($lang->story->typeList),
        set::inline(true),
        set::value($type),
        set::disabled($disabled),
        $title ? set::title($title) : null,
        on::change('switchType')
    );
}

formBatchPanel
(
    setID('dataform'),
    on::change('[data-name="branch"]', 'setModuleAndPlanByBranch'),
    on::change('[data-name="parent"]', 'setGrade'),
    on::change('[data-name="region"]', 'changeRegion'),
    $stories ? set::data($stories) : null,
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    set::title($storyID ? $storyTitle . $lang->hyphen . $this->lang->story->subdivide : $this->lang->story->batchCreate),
    $storyTypeRadio ? set::headingActionsClass('flex-auto row-reverse justify-between w-11/12') : null,
    $storyTypeRadio ? to::headingActions($storyTypeRadio) : null,
    set::uploadParams('module=story&params=' . helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID&storyID=$storyID&executionID=$executionID&plan={$planID}&type=$type")),
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
    formHidden('status', '')
);

render();
