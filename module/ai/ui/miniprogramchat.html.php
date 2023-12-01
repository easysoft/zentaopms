<?php
declare(strict_types=1);

namespace zin;

jsVar('prompt', $miniProgram->prompt);
jsVar('regenerateLang', $lang->ai->miniPrograms->regenerate);
jsVar('emptyNameWarning', $lang->ai->miniPrograms->field->emptyNameWarning);

$formGroups = array();
foreach($fields as $field)
{
    if($field->type === 'textarea')
    {
        $control = textarea(
            set::rows(1),
            set::name("field-{$field->id}"),
            set::placeholder($field->placeholder),
            setData('name', $field->name)
        );
    }
    elseif($field->type === 'radio')
    {
        $options = explode(',', $field->options);
        $control = picker(
            set::name("field-{$field->id}"),
            set::items(array_combine($options, $options)),
            setData('name', $field->name)
        );
    }
    elseif($field->type === 'checkbox')
    {
        $options = explode(',', $field->options);
        $control = picker(
            set::name("field-{$field->id}"),
            set::items(array_combine($options, $options)),
            set::multiple(true),
            setData('name', $field->name)
        );
    }
    else
    {
        $control = input(
            set::name("field-{$field->id}"),
            set::placeholder($field->placeholder),
            setData('name', $field->name)
        );
    }

    $formGroups[] = formGroup(
        set::label($field->name),
        set::required($field->required === '1'),
        $control
    );
}

jsVar('fields', $fields);

list($iconName, $iconTheme) = explode('-', $miniProgram->icon);
$star = in_array($miniProgram->id, $collectedIDs) ? 'star' : 'star-empty';
$delete = $star === 'star' ? 'true' : 'false';

div(
    setClass('mini-program fixed flex'),
    div(
        setClass('detail col shadow-md'),
        div(
            setClass('header', 'relative'),
            div(
                setClass('program-avatar center'),
                setStyle(array('border' => "1px solid {$config->ai->miniPrograms->themeList[$iconTheme][1]}", 'background-color' => $config->ai->miniPrograms->themeList[$iconTheme][0])),
                html($config->ai->miniPrograms->iconList[$iconName])
            ),
            div(
                setClass('content'),
                div(
                    setClass('title pb-2'),
                    $miniProgram->name
                ),
                div(
                    setClass('desc'),
                    set::title($miniProgram->desc),
                    $miniProgram->desc
                )
            ),
            btn(
                setClass('ghost btn-star absolute'),
                set::size('md'),
                setData('url', createLink('ai', 'collectMiniProgram', "appID={$miniProgram->id}&delete={$delete}")),
                on::click('window.aiMiniProgramChat.handleStarBtnClick'),
                html(html::image("static/svg/{$star}.svg", "class='$star'")),
                $lang->ai->miniPrograms->collect
            )
        ),
        div(
            setClass('body'),
            div(
                setClass('language-model'),
                div(
                    setClass('content flex gap-2.5'),
                    span(
                        setStyle(array('color' => 'var(--color-slate-700)')),
                        $lang->ai->modelCurrent
                    ),
                    span($lang->ai->miniPrograms->modelList[$miniProgram->model]),
                ),
                btn(
                    set::size('md'),
                    setClass('ghost'),
                    set::icon('trash'),
                    $lang->ai->chatReset,
                    on::click('window.aiMiniProgramChat.handleRestBtnClick')
                ),
            ),
            div(
                setClass('form-container p-1'),
                form(
                    set::grid(false),
                    set::actions(array()),
                    $formGroups
                ),
            )
        ),
        div(
            setClass('footer'),
            btn(
                setClass('primary block w-full'),
                $lang->ai->miniPrograms->generate,
                on::click('window.aiMiniProgramChat.startAIChat')
            )
        )
    ),
    div(
        setClass('chat')
    )
);

render();
