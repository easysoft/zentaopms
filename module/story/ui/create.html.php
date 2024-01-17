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
    on::click('#loadURS', "loadURS('allURS')"),
    on::click('#loadProductPlans', "loadProductPlans('{$productID}')"),
    on::change('[name=module]', 'loadURS'),
    on::change('[name=product]', 'loadProduct'),
    on::change('[name^=branches]', 'loadBranch'),
    on::change('[name=source]', "toggleFeedback(e.target)"),
    on::change('[name=region]', 'setLane')
);

isset($fields['branches']) && $type == 'story' ? formRow
(
    setID('addBranchesBox'),
    setClass('hidden'),
    formGroup
    (
        set::width('1/4'),
        set::label(sprintf($lang->product->branch, $lang->product->branchName[$product->type])),
        inputGroup
        (
            setID('branchBox'),
            div(setID('branches'), setClass('form-group-wrapper'))
        )
    ),
    formGroup
    (
        set::width('1/4'),
        set::label($lang->story->module),
        set::required(strpos(",{$this->config->story->create->requiredFields},", ",module,") !== false),
        inputGroup
        (
            setID('moduleIdBox'),
            div(setID('modules'), setClass('form-group-wrapper'))
        )
    ),
    formGroup
    (
        set::label($lang->story->plan),
        set::required(strpos(",{$this->config->story->create->requiredFields},", ",plan,") !== false),
        inputGroup
        (
            setID('planIdBox'),
            div(setID('plans'), setClass('form-group-wrapper'))
        )
    ),
    formGroup
    (
        set::width('50px'),
        setClass('c-actions'),
        btn(setClass('btn-link addNewLine'),    set::title(sprintf($lang->story->addBranch,    $lang->product->branchName[$product->type])), icon('plus')),
        btn(setClass('btn-link removeNewLine'), set::title(sprintf($lang->story->deleteBranch, $lang->product->branchName[$product->type])), icon('trash'))
    )
) : null;

render();
