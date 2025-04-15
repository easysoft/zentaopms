<?php
declare(strict_types=1);
/**
 * The change view file of story module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;
include './affected.html.php';

data('activeMenuID', $story->type);
jsVar('storyID', $story->id);
jsVar('storyType', $story->type);
jsVar('rawModule', $this->app->rawModule);
jsVar('isMultiple', count($reviewers) > 1);
jsVar('isLastOne', $isLastOne);

if($story->isParent == '1') $fields['estimate']['readonly'] = true;

$formItems = array();
foreach($fields as $field => $attr)
{
    $width     = zget($attr, 'width', '1/3');
    $fieldName = zget($attr, 'name', $field);
    $control   = array();
    $control['type'] = $attr['control'];
    if(!empty($attr['options'])) $control['items'] = $attr['options'];
    if($field == 'duplicateStory')
    {
        $control['items']         = createLink('story', 'ajaxGetDuplicatedStories', "storyID=$story->id&productID=$story->product");
        $control['required']      = false;
        $control['maxItemsCount'] = 100;
    }

    $formItems[$field] = formRow
    (
        in_array($field, array('assignedTo', 'duplicateStory', 'pri', 'estimate')) ? setID($field . 'Box') : null,
        in_array($field, array('closedReason', 'duplicateStory', 'pri', 'estimate', 'status')) ? set::hidden(true) : null,

        $field == 'closedReason' ? setID('rejectedReasonBox') : null,
        $field == 'assignedTo'   ? set::hidden(!$isLastOne) : null,
        $field == 'result'       ? on::change('switchShow(e.target);') : null,
        $field == 'closedReason' ? on::change('setStory(e.target);') : null,

        formGroup
        (
            set::width($width),
            set::name($fieldName),
            set::label($attr['title']),
            set::control($control),
            set::value($attr['default']),
            set::required($attr['required']),
            set::readonly(!empty($attr['readonly']))
        )
    );
}
if(strpos('or,lite', $this->config->vision) === false) $formItems['affected'] = $getAffectedTabs($story, $users);

modalHeader();
div
(
    setClass('panel-form mx-auto'),
    formPanel($formItems),
    h::hr(setClass('mt-6 mb-6')),
    history(set::objectID($story->id))
);

render();
