<?php
declare(strict_types=1);
/**
* The UI file of story module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wang Yidong <yidong@easycorp.ltd>
* @package     story
* @link        https://www.zentao.net
*/
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/inputinject.html.php');

data('storyType', $type);
data('gradeRule', $gradeRule);
if($app->tab == 'product') data('activeMenuID', $type);

$createFields = useFields('story.create');

if((isset($fields['branch']) && $type == 'story') || $type != 'story')
{
    $createFields->field('source')->width('1/2');
    $createFields->field('sourceNote')->width('1/2');
}

if(isset($this->config->{$type}->custom->createFields));
{
    $customCreateFields = ',' . $this->config->story->custom->createFields . ',';
    if(str_contains($customCreateFields, ',source,')) $createFields->field('sourceNote')->pinned();
    if(str_contains($customCreateFields, ',sourceNote,')) $createFields->field('source')->pinned();
}

/* Set layout in execution tab. */
if(!empty($objectID))
{
    if($app->tab != 'project' && $app->tab != 'execution') $createFields->remove('parent');
    $createFields->field('source')->className('full:w-1/2');
    $createFields->field('sourceNote')->className('full:w-1/2');

    $orders         = 'product,module,twinsStory,parent,grade,assignedTo,reviewer,region,lane,title,category,pri,estimate,spec,verify,files';
    $fullModeOrders = 'product,module,twinsStory,plan,parent,grade,reviewer,region,lane,assignedTo,category,title,pri,estimate,spec,verify,files';
    if(!isset($fields['plan']))
    {
        $createFields->field('source')->width('1/2');
        $createFields->field('sourceNote')->width('1/2');
        $createFields->field('category')->width('1/2');
        $createFields->field('pri')->width('1/4');
        $createFields->field('estimate')->width('1/4');
        $orders         = 'product,module,twinsStory,parent,grade,assignedTo,category,reviewer,region,lane,title,pri,estimate,spec,verify,files';
        $fullModeOrders = 'product,module,twinsStory,parent,grade,assignedTo,category,reviewer,region,lane,title,pri,estimate,spec,verify,files';
    }
    else
    {
        $createFields->field('category')->className('full:w-1/6');
        $createFields->field('pri')->className('full:w-1/6');
        $createFields->field('estimate')->className('full:w-1/6');
        $orders         = 'product,module,twinsStory,reviewer,region,lane,parent,grade,assignedTo,category,title,pri,estimate,spec,verify,files';
        $fullModeOrders = 'product,module,twinsStory,reviewer,region,lane,plan,parent,grade,assignedTo,title,category,pri,estimate,spec,verify,files';
    }

    $createFields->orders($orders);
    $createFields->fullModeOrders($fullModeOrders);
}
/* Set layout in product tab. */
else
{
    $createFields->field('source')->className('full:w-1/2');
    $createFields->field('sourceNote')->className('full:w-1/2');
    if($type == 'story')
    {
        $createFields->field('category')->width('1/2')->className('full:w-1/6');
        $createFields->field('pri')->width('1/4')->className('full:w-1/6');
        $createFields->field('estimate')->width('1/4')->className('full:w-1/6');
    }

    $fullModeOrders = 'product,module,twinsStory,plan,parent,grade,assignedTo,reviewer,region,lane,title,category,pri,estimate,spec,verify,files';
    if($type != 'story') $fullModeOrders = 'product,module,twinsStory,plan,parent,grade,reviewer,region,lane,assignedTo,category,title,pri,estimate,spec,verify,files';
    if($type == 'story' and isset($fields['branch']))
    {
        $fullModeOrders = 'product,module,twinsStory,plan,parent,grade,reviewer,region,lane,assignedTo,title,category,pri,estimate,spec,verify,files';
    }

    $createFields->orders('product,module,twinsStory,parent,grade,reviewer,region,lane,assignedTo,category,title,pri,estimate,spec,verify,files');
    $createFields->fullModeOrders($fullModeOrders);
}

$params = $app->getParams();
array_shift($params);
jsVar('createParams', http_build_query($params));
jsVar('storyType', $type);
jsVar('langSource', $lang->story->source);
jsVar('langSourceNote', $lang->story->sourceNote);
jsVar('feedbackSource', $config->story->feedbackSource);

$pinnedItems = !empty($this->config->{$type}->custom->createFields) ? $this->config->{$type}->custom->createFields : array();

$createFields->autoLoad('product', array('items' => 'product,module,twinsStory,plan,parent,grade,reviewer,region,lane,assignedTo,' . (!empty($lang->{$type}->flowExtraFields) ? implode(',', $lang->{$type}->flowExtraFields) : ''), 'updateOrders' => true));
if($type != 'story') $createFields->autoLoad('branch', 'module');

formGridPanel
(
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $lang->story->create,
        !$forceReview ? checkbox(setID('needNotReview'), set::rootClass('text-base font-medium'), set::value(1), set::text($lang->story->needNotReview), set::checked($needReview), on::change('toggleReviewer(e.target)')) : null
    )),
    set::actions(array
    (
        array('text' => $lang->save,             'data-status' => 'active', 'class' => 'primary',   'btnType' => 'submit'),
        array('text' => $lang->story->saveDraft, 'data-status' => 'draft',  'class' => 'secondary', 'btnType' => 'submit'),
        !isInModal() ? array('text' => $lang->goback, 'back' => true) : null
    )),
    set::fields($createFields),
    set::pinnedItems($pinnedItems),
    set::data($initStory),
    on::change('[name=parent]', 'loadGrade'),
    on::change('[name=source]', "toggleFeedback(e.target)"),
    on::change('[name=region]', 'setLane'),
    set::loadUrl($loadUrl)
);
