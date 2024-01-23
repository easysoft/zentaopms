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

data('activeMenuID', $type);

$forceReview  = $this->story->checkForceReview();
$createFields = useFields('story.create');
$createFields->field('needNotReview')->value($forceReview ? 0 : 1);
if(!$forceReview) $createFields->field('reviewer')->hidden(true);
if(isset($fields['branch']) && $type == 'story')
{
    $createFields->field('reviewer')->className('full:w-1/2');
    $createFields->field('source')->width('1/2');
    $createFields->field('sourceNote')->width('1/2');
}

$createFields->fullModeOrders('product,module,twinsStory,plan,URS,parent,assignedTo,reviewer,region,lane,title,category,pri,estimate,spec,verify,files');

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
