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

$forceReview = $this->story->checkForceReview();

$params = $app->getParams();
array_shift($params);
jsVar('createParams', http_build_query($params));
jsVar('storyType', $type);
jsVar('feedbackSource', $config->story->feedbackSource);
jsVar('branchCount', isset($fields['branches']['options']) ? count($fields['branches']['options']) : 0);

formPanel
(
    on::click('#saveButton', 'customSubmit'),
    on::click('#saveDraftButton', 'customSubmit'),
    set::id('dataform'),
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $lang->story->create,
        $forceReview ? checkbox(set::id('needNotReview'), set::rootClass('text-base font-medium'), set::value(1), set::text($lang->story->needNotReview), set::checked($needReview), on::change('toggleReviewer(e.target)')) : null
    )),
    set::actions(array
    (
        array('text' => $lang->save,             'id' => 'saveButton',      'class' => 'primary'),
        array('text' => $lang->story->saveDraft, 'id' => 'saveDraftButton', 'class' => 'secondary'),
        array('text' => $lang->goback, 'data-back' => 'APP', 'class' => 'open-url')
    )),
    set::customFields(true),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->story->module),
            inputGroup
            (
                span
                (
                    set::id('moduleIdBox'),
                    picker(setID('module'), set::name('module'), set::items($fields['module']['options']), set::value($fields['module']['default']), set::required(true))
                ),
                count($fields['module']['options']) == 1 ? btn(set::url($this->createLink('tree', 'browse', "rootID=$productID&view=story&currentModuleID=0&branch=$branch")), setClass('primary'), set('data-toggle', 'modal'), $lang->tree->manage) : null,
                count($fields['module']['options']) == 1 ? btn(set('data-on', 'click'), set('data-call', 'loadProductModules'), set('data-params', $productID), setClass('refresh'), icon('refresh')) : null
            )
        )
    ),
    $type != 'story' ? formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->story->assignedTo),
            inputGroup
            (
                set::id('assignedToBox'),
                picker(setID('assignedTo'), set::name('assignedTo'), set::items($fields['assignedTo']['options']), set::value($fields['assignedTo']['default']))
            )
        ),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->story->source),
            picker(setID('source'), set::name('source'), set::items($fields['source']['options']), set::value($fields['source']['default']))
        ),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->story->sourceNote),
            input(set::name('sourceNote'), set::value($fields['sourceNote']['default']))
        )
    ) : null,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->story->reviewedBy),
            inputGroup
            (
                set::id('reviewerBox'),
                picker
                (
                    setID('reviewer'),
                    set::name('reviewer[]'),
                    set::multiple(true),
                    set::items($fields['reviewer']['options']),
                    set::value($fields['reviewer']['default'])
                )
            ),
            formHidden('needNotReview', 1)
        )
    ),
    $type == 'story' && !$this->config->URAndSR ? formRow
    (
        setClass($hiddenParent ? 'hidden' : ''),
        formGroup
        (
            set::label($lang->story->parent),
            set::name('parent'),
            set::items($fields['parent']['options']),
            set::value($fields['parent']['default'])
        )
    ) : null,
    isset($executionType) && $executionType == 'kanban' ? formRow
    (
        formGroup
        (
            set::width('1/2'),
            setID($fields['region']['title']),
            set::label($fields['region']['title']),
            set::name('region'),
            set::items($fields['region']['options']),
            set::value($fields['region']['default']),
            set('data-on', 'change'),
            set('data-call', 'setLane')
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($fields['lane']['title']),
            set::name('lane'),
            set::items($fields['lane']['options']),
            set::value($fields['lane']['default'])
        )
    ) : null,
    formRow
    (
        formGroup
        (
            set::width('3/4'),
            set::label($lang->story->title),
            set::required($fields['title']['required']),
            input(set::name('title'), set::value($fields['title']['default']))
        ),
        formGroup
        (
            setClass('no-background'),
            set::width('1/4'),
            inputGroup
            (
                $lang->story->pri,
                priPicker(set::name('pri'), set::items($fields['pri']['options']), set::value($fields['pri']['default'])),
                $lang->story->estimateAB,
                input(set::name('estimate'), set::placeholder($lang->story->hour), set::value($fields['estimate']['default']))
            )
        )
    ),
    formGroup
    (
        set::label($lang->story->spec),
        editor
        (
            set::name('spec'),
            set::placeholder($lang->story->specTemplate . "\n" . $lang->noticePasteImg),
            set::required($fields['spec']['required']),
            html($fields['spec']['default'])
        )
    ),
    formGroup
    (
        set::label($lang->story->legendAttach),
        fileSelector()
    ),
    formGroup
    (
        set::label($lang->story->mailto),
        picker
        (
            setID('mailto'),
            set::name('mailto[]'),
            set::items($fields['mailto']['options']),
            set::value($fields['mailto']['default']),
            set::multiple(true)
        )
    ),
    formGroup
    (
        set::label($lang->story->keywords),
        set::name('keywords'),
        set::control('input'),
        set::values($fields['keywords']['default'])
    ),
    formHidden('type', $type)
);

isset($fields['branches']) && $type == 'story' ? formRow
(
    set::id('addBranchesBox'),
    setClass('hidden'),
    formGroup
    (
        set::width('1/4'),
        set::label(sprintf($lang->product->branch, $lang->product->branchName[$product->type])),
        inputGroup
        (
            set::id('branchBox'),
            div(setID('branches'), setClass('form-group-wrapper'))
        )
    ),
    formGroup
    (
        set::width('1/4'),
        set::label($lang->story->module),
        inputGroup
        (
            set::id('moduleIdBox'),
            div(setID('modules'), setClass('form-group-wrapper'))
        )
    ),
    formGroup
    (
        set::label($lang->story->plan),
        inputGroup
        (
            set::id('planIdBox'),
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
