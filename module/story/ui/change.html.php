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
jsVar('lastReviewer', explode(',', $lastReviewer));
jsVar('storyID', $story->id);
jsVar('oldStoryTitle', $story->title);
jsVar('oldStorySpec', $story->spec);
jsVar('oldStoryVerify', $story->verify);
jsVar('changed', 0);
jsVar('storyType', $story->type);
jsVar('rawModule', $this->app->rawModule);
jsVar('page', $this->app->rawMethod);

$formTitle = div
(
    setClass('flex items-center pb-3'),
    div($lang->story->changed),
    entityLabel
    (
        set::level(1),
        setClass('pl-2'),
        set::entityID($story->id),
        set::reverse(true),
        span(setID('storyTitle'), $story->title)
    )
);

$formItems = array();
$formItems['reviewer'] = formGroup
(
    set::width('full'),
    set::label($fields['reviewer']['title']),
    inputGroup
    (
        picker
        (
            setID('reviewer'),
            set::name('reviewer[]'),
            set::multiple(true),
            set::items($fields['reviewer']['options']),
            set::value($fields['reviewer']['default'])
        ),
        !$forceReview ? span
        (
            setClass('input-group-addon'),
            checkbox
            (
                setID('needNotReview'),
                set::name('needNotReview'),
                set::checked($needReview),
                set::text($lang->story->needNotReview),
                set::value(1)
            )
        ) : null,
        formHidden('needNotReview', $forceReview ? 0 : 1)
    ),
    set::required(true)
);
$formItems['title'] = formGroup
(
    set::width('full'),
    set::label($fields['title']['title']),
    inputGroup
    (
        inputControl
        (
            input
            (
                set::name('title'),
                set::value($fields['title']['default'])
            ),
            set::suffixWidth('40'),
            to::suffix
            (
                colorPicker
                (
                    set::name('color'),
                    set::type('color'),
                    set::value($fields['color']['default']),
                    set::syncColor('#title, #storyTitle')
                )
            )
        ),
        empty($story->twins) ? null : span
        (
            setClass('input-group-addon'),
            checkbox
            (
                setID('relievedTwins'),
                set::name('relievedTwins'),
                set::value(1),
                $lang->story->relievedTwinsRelation
            )
        )
    ),
    set::required($fields['title']['required'])
);
$formItems['hidden'] = formRow
(
    set::hidden(true),
    formGroup
    (
        input(set::type('hidden'), set::name('status'), set::value($fields['status']['default'])),
        input(set::type('hidden'), set::name('lastEditedDate'), set::value($story->lastEditedDate))
    )
);
$formItems['spec'] = formGroup
(
    set::width('full'),
    set::label($fields['spec']['title']),
    set::required(strpos(",{$this->config->story->change->requiredFields},", ",spec,") !== false),
    set::tip($lang->story->specTemplate),
    editor
    (
        set::name('spec'),
        html($fields['spec']['default'])
    )
);
unset($fields['reviewer'], $fields['title'], $fields['color'], $fields['status'], $fields['lastEditedDate'], $fields['spec']);

foreach($fields as $field => $attr)
{
    $fieldName = zget($attr, 'name', $field);
    $control   = array();
    $control['type'] = $attr['control'];
    if(!empty($attr['options'])) $control['items'] = $attr['options'];

    if($attr['control'] == 'editor')
    {
        $formItems[$field] = formGroup
        (
            set::width('full'),
            set::label($attr['title']),
            set::required($attr['required']),
            editor
            (
                set::name($fieldName),
                html($attr['default'])
            )
        );
    }
    else
    {
        $formItems[$field] = formGroup
        (
            set::width('full'),
            set::name($fieldName),
            set::label($attr['title']),
            set::control($control),
            set::value($attr['default']),
            set::required($attr['required'])
        );
    }

}
$formItems['file'] = formGroup
(
    set::width('full'),
    set::label($lang->attach),
    $story->files ? fileList
    (
        set::files($story->files),
        set::fieldset(false),
        set::object($story)
    ) : null,
    upload()
);
if($this->config->vision != 'or') $formItems['affected'] = $getAffectedTabs($story, $users);

formPanel
(
    setID('dataform'),
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    set::actions(array
    (
        array('text' => $lang->save,               'data-status' => 'active', 'class' => 'primary',   'btnType' => 'submit'),
        array('text' => $lang->story->doNotSubmit, 'data-status' => 'draft',  'class' => 'secondary', 'btnType' => 'submit'),
        array('text' => $lang->goback,             'data-back'   => 'APP',    'class' => 'open-url')
    )),
    $formTitle,
    $formItems,
    h::hr(),
    history()
);

render();
