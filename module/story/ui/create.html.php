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

data('storyType', $type);
data('activeMenuID', $type);

$forceReview  = $this->story->checkForceReview();
$createFields = useFields('story.create');
$createFields->field('needNotReview')->value($forceReview ? 0 : 1);
if(!$forceReview) $createFields->field('reviewer')->hidden(true);

if((isset($fields['branch']) && $type == 'story') || $type != 'story')
{
    $createFields->field('source')->width('1/2');
    $createFields->field('sourceNote')->width('1/2');
}

if(isset($this->config->story->custom->createFields));
{
    $customCreateFields = ',' . $this->config->story->custom->createFields . ',';
    if(str_contains($customCreateFields, ',source,')) $createFields->field('sourceNote')->pinned();
    if(str_contains($customCreateFields, ',sourceNote,')) $createFields->field('source')->pinned();
}

/* Set layout in execution tab. */
if(!empty($objectID))
{
    $createFields->remove('parent');
    $createFields->field('source')->className('full:w-1/2');
    $createFields->field('sourceNote')->className('full:w-1/2');

    $orders         = 'product,module,twinsStory,URS,assignedTo,reviewer,region,lane,title,category,pri,estimate,spec,verify,files';
    $fullModeOrders = 'product,module,twinsStory,plan,URS,reviewer,region,lane,assignedTo,category,title,pri,estimate,spec,verify,files';
    if(!isset($fields['plan']))
    {
        $createFields->field('category')->width('1/6');
        $createFields->field('pri')->width('1/6');
        $createFields->field('estimate')->width('1/6');
        $createFields->field('source')->width('1/2');
        $createFields->field('sourceNote')->width('1/2');
        $fullModeOrders = 'product,module,twinsStory,URS,assignedTo,reviewer,region,lane,title,category,pri,estimate,spec,verify,files';

        if(!isset($fields['URS']))
        {
            $createFields->field('category')->width('1/2');
            $createFields->field('pri')->width('1/4');
            $createFields->field('estimate')->width('1/4');
            $orders         = 'product,module,twinsStory,assignedTo,category,reviewer,region,lane,title,pri,estimate,spec,verify,files';
            $fullModeOrders = 'product,module,twinsStory,assignedTo,category,reviewer,region,lane,title,pri,estimate,spec,verify,files';
        }
    }
    elseif(isset($fields['URS']))
    {
        $createFields->field('category')->width('1/6')->className('full:w-1/2');
        $createFields->field('pri')->width('1/6')->className('full:w-1/4');
        $createFields->field('estimate')->width('1/6')->className('full:w-1/4');
    }
    elseif(!isset($fields['URS']))
    {
        $createFields->field('category')->className('full:w-1/6');
        $createFields->field('pri')->className('full:w-1/6');
        $createFields->field('estimate')->className('full:w-1/6');
        $orders         = 'product,module,twinsStory,reviewer,region,lane,assignedTo,category,title,pri,estimate,spec,verify,files';
        $fullModeOrders = 'product,module,twinsStory,reviewer,region,lane,plan,assignedTo,title,category,pri,estimate,spec,verify,files';
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
        if(!isset($fields['branch']) || !isset($fields['URS']))
        {
            $createFields->field('category')->width('1/2')->className('full:w-1/6');
            $createFields->field('pri')->width('1/4')->className('full:w-1/6');
            $createFields->field('estimate')->width('1/4')->className('full:w-1/6');
        }
    }

    $fullModeOrders = 'product,module,twinsStory,plan,URS,parent,assignedTo,reviewer,region,lane,title,category,pri,estimate,spec,verify,files';
    if($type != 'story') $fullModeOrders = 'product,module,twinsStory,plan,URS,parent,reviewer,region,lane,assignedTo,category,title,pri,estimate,spec,verify,files';
    if($type == 'story' and isset($fields['branch']))
    {
        $fullModeOrders = 'product,module,twinsStory,plan,URS,parent,reviewer,region,lane,assignedTo,category,title,pri,estimate,spec,verify,files';
        if(!isset($fields['URS'])) $fullModeOrders = 'product,module,twinsStory,reviewer,region,lane,plan,assignedTo,title,category,pri,estimate,spec,verify,files';
    }

    $createFields->orders('product,module,twinsStory,URS,parent,reviewer,region,lane,assignedTo,category,title,pri,estimate,spec,verify,files');
    $createFields->fullModeOrders($fullModeOrders);
}

$params = $app->getParams();
array_shift($params);
jsVar('createParams', http_build_query($params));
jsVar('storyType', $type);
jsVar('feedbackSource', $config->story->feedbackSource);

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
        array('text' => $lang->goback,           'data-back'   => 'APP',    'class' => 'open-url')
    )),
    set::fields($createFields),
    on::click('#loadURS', "loadURS"),
    on::click('#loadProductPlans', "loadProductPlans('{$productID}')"),
    on::change('[name=module]', 'loadURS'),
    on::change('[name=product]', 'loadProduct'),
    on::change('[name=source]', "toggleFeedback(e.target)"),
    on::change('[name=region]', 'setLane')
);

render();
