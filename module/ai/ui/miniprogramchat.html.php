<?php
declare(strict_types=1);

namespace zin;

jsVar('prompt', $miniProgram->prompt);
jsVar('regenerateLang', $lang->ai->miniPrograms->regenerate);
jsVar('emptyNameWarning', $lang->ai->miniPrograms->field->emptyNameWarning);

$isDeleted = $miniProgram->deleted === '1';

$formGroups = array();
if(count($fields) !== 0)
{
    foreach($fields as $field)
    {
        if($field->type === 'textarea')
        {
            $control = textarea(
                set::rows(1),
                set::name("field-{$field->id}"),
                set::placeholder($field->placeholder),
                set::disabled($isDeleted),
                setData('name', $field->name)
            );
        }
        elseif($field->type === 'radio')
        {
            $options = explode(',', $field->options);
            $control = picker(
                set::name("field-{$field->id}"),
                set::items(array_combine($options, $options)),
                set::disabled($isDeleted),
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
                set::disabled($isDeleted),
                setData('name', $field->name)
            );
        }
        else
        {
            $control = input(
                set::name("field-{$field->id}"),
                set::placeholder($field->placeholder),
                set::disabled($isDeleted),
                setData('name', $field->name)
            );
        }

        $formGroups[] = formGroup(
            set::label($field->name),
            set::required($field->required === '1'),
            $control
        );
    }
}

jsVar('fields', $fields);

list($iconName, $iconTheme) = explode('-', $miniProgram->icon);
$star = in_array($miniProgram->id, $collectedIDs) ? 'star' : 'star-empty';
$delete = $star === 'star' ? 'true' : 'false';

div(
    setClass('mini-program fixed flex'),
    div(
        setClass('detail col shadow-md h-full flex-none'),
        div(
            setClass('header relative flex flex-none'),
            div(
                setClass('program-avatar center flex-none'),
                setStyle(array('border' => "1px solid {$config->ai->miniPrograms->themeList[$iconTheme][1]}", 'background-color' => $config->ai->miniPrograms->themeList[$iconTheme][0])),
                html($config->ai->miniPrograms->iconList[$iconName])
            ),
            div(
                setClass('content flex-1 overflow-hidden'),
                div(
                    setClass('title pb-2 ellipsis'),
                    $miniProgram->name
                ),
                div(
                    setClass('desc'),
                    set::title($miniProgram->desc),
                    $miniProgram->desc
                )
            ),
            $isDeleted
                ? label(
                    setClass('danger'),
                    $lang->ai->prompts->deleted
                )
                : btn(
                    setClass('ghost btn-star absolute'),
                    set::size('md'),
                    setData('url', createLink('ai', 'collectMiniProgram', "appID={$miniProgram->id}&delete={$delete}")),
                    on::click('window.aiMiniProgramChat.handleStarBtnClick'),
                    html(html::image("static/svg/{$star}.svg", "class='$star'")),
                    $lang->ai->miniPrograms->collect
                )
        ),
        div(
            setClass('body col overflow-hidden', empty($formGroups) ? 'flex-none' : 'flex-1'),
            div(
                setClass('language-model flex flex-none justify-between items-center'),
                div(
                    setClass('content flex gap-2.5'),
                    span(
                        setStyle(array('color' => 'var(--color-slate-700)')),
                        $lang->ai->modelCurrent
                    ),
                    span($lang->ai->miniPrograms->modelList[$miniProgram->model]),
                ),
                empty($formGroups)
                    ? null
                    : btn(
                        setClass('ghost'),
                        set::size('md'),
                        set::disabled($isDeleted),
                        set::icon('trash'),
                        $lang->ai->chatReset,
                        on::click('window.aiMiniProgramChat.handleRestBtnClick')
                    )
            ),
            empty($formGroups)
                ? null
                : div(
                    setClass('form-container p-1 flex-1 overflow-y-auto'),
                    form(
                        set::grid(false),
                        set::actions(array()),
                        $formGroups
                    )
                )
        ),
        div(
            setClass('footer flex-none'),
            btn(
                setClass('primary block w-full'),
                set::disabled($isDeleted),
                $lang->ai->miniPrograms->generate,
                on::click('window.aiMiniProgramChat.startAIChat')
            )
        )
    ),
    div(
        setClass('chat shadow-md flex-1 center'),
        div(
            setClass('chat-tip flex'),
            html(<<<END
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.3334 6.66665C13.3334 8.93588 11.9162 10.8741 9.91858 11.6445H8.00008H6.08158C4.08395 10.8741 2.66675 8.93588 2.66675 6.66665C2.66675 3.72111 5.05455 1.33331 8.00008 1.33331C10.9456 1.33331 13.3334 3.72111 13.3334 6.66665Z" stroke="#FF9F46" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9.91858 11.6445L9.69221 14.361C9.67781 14.5338 9.53338 14.6667 9.36001 14.6667H6.64011C6.46674 14.6667 6.32231 14.5338 6.30794 14.361L6.08154 11.6445" stroke="#FF9F46" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8.47095 4.78107L7.05673 6.19528L9.17805 6.90239L7.52814 8.5523" stroke="#FF9F46" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            END),
            $lang->ai->miniPrograms->chatTip
        )
    )
);

render();
