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

$formItems = array();
foreach($fields as $field => $attr)
{
    $width     = zget($attr, 'width', '1/3');
    $fieldName = zget($attr, 'name', $field);
    $control   = array();
    $control['type'] = $attr['control'];
    if(!empty($attr['options'])) $control['items'] = $attr['options'];

    $formItems[$field] = formRow
    (
        formGroup
        (
            set::width($width),
            set::name($fieldName),
            set::label($attr['title']),
            set::control($control),
            set::value($attr['default']),
            set::required($attr['required'])
        )
    );
}
if($this->config->vision != 'or') $formItems['affected'] = $getAffectedTabs($story, $users);

$formItems['result']->add(on::change('switchShow(e.target);'));
$formItems['assignedTo']->add(setID('assignedToBox'))->add(set::hidden(!$isLastOne));
$formItems['closedReason']->add(setID('rejectedReasonBox'))->add(set::hidden(true))->add(on::change('setStory(e.target);'));
$formItems['duplicateStory']->add(setID('duplicateStoryBox'))->add(set::hidden(true));
$formItems['pri']->add(setID('priBox'))->add(set::hidden(true));
$formItems['estimate']->add(setID('estimateBox'))->add(set::hidden(true));
$formItems['childStories']->add(setID('childStoriesBox'))->add(set::hidden(true));
$formItems['status']->add(set::hidden(true));

modalHeader();
panel
(
    setClass('panel-form mx-auto'),
    form($formItems),
    h::hr(setClass('mt-6 mb-6')),
    history()
);

render();
