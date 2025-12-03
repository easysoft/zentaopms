<?php
declare(strict_types=1);

namespace zin;

jsVar('regenerateLang',   $lang->aiapp->regenerate);
jsVar('emptyNameWarning', $lang->aiapp->emptyNameWarning);
jsVar('clearContextLang', $lang->aiapp->clearContext);
jsVar('newChatTip',       $lang->aiapp->newChatTip);
jsVar('newVersionTip',    $lang->aiapp->newVersionTip);

jsVar('prompt', $miniProgram->prompt);
jsVar('knowledgeLibs', $miniProgram->knowledgeLib);
jsVar('postLink', createLink('aiapp', 'miniProgramChat', "id={$miniProgram->id}"));
jsVar('messages', $messages);
jsVar('isAppDisabled', $miniProgram->published === '0');
jsVar('pathname', $miniProgram->name);

$app->loadLang('ai');

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
                set::className('form-field'),
                setData('name', $field->name),
                setData('fid', $field->id)
            );
        }
        elseif($field->type === 'radio')
        {
            $options = explode(',', $field->options);
            $control = picker(
                set::name("field-{$field->id}"),
                set::items(array_combine($options, $options)),
                set::disabled($isDeleted),
                setData('name', $field->name),
                setData('fid', $field->id)
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
                setData('name', $field->name),
                setData('fid', $field->id)
            );
        }
        else
        {
            $control = input(
                set::name("field-{$field->id}"),
                set::placeholder($field->placeholder),
                set::disabled($isDeleted),
                set::className('form-field'),
                setData('name', $field->name),
                setData('fid', $field->id)
            );
        }

        $formGroups[] = formGroup(
            set::label($field->name),
            set::required($field->required === '1'),
            setData('name', $field->name),
            $control
        );
    }
}

jsVar('fields', $fields);

list($iconName, $iconTheme) = explode('-', $miniProgram->icon);
$star = in_array($miniProgram->id, $collectedIDs) ? 'star' : 'star-empty';
$delete = $star === 'star' ? 'true' : 'false';

btn(
    setClass('hidden'),
    setID('open-dialog'),
    setData('toggle', 'modal'),
    setData('target', '#disabled-dialog')
);

div(
    setClass('modal'),
    setData('backdrop', 'static'),
    setID('disabled-dialog'),
    div(
        setClass('modal-dialog shadow size-sm bd-none'),
        div(
            setClass('modal-content'),
            div(
                setClass('modal-body'),
                html('<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12.013" cy="12.0163" r="12" transform="rotate(0.0777774 12.013 12.0163)" fill="#FFA34D"/>
                <path d="M11.5573 14.7861C12.0809 14.7867 12.6054 14.2164 12.6063 13.6455L13.0103 6.08095C13.0112 5.51 13.0129 4.36812 11.442 4.36619C10.002 4.36442 9.8696 5.36341 9.86853 6.07709L10.2499 13.6426C10.5109 14.2138 11.0337 14.7854 11.5573 14.7861ZM11.5551 16.2134C10.7697 16.2124 9.98317 16.9252 9.98167 17.9243C9.98039 18.7807 10.6336 19.6379 11.55 19.6391C12.4664 19.6402 13.122 18.9273 13.1235 17.9282C13.125 16.929 12.3406 16.2144 11.5551 16.2134Z" fill="white"/>
                </svg>'),
                $lang->aiapp->disabledTip
            ),
            div(
                setClass('modal-footer'),
                btn(
                    setClass('primary'),
                    setData('dismiss', 'modal'),
                    on::click('window.aiBrowseMiniProgram.backToSquare'),
                    $lang->confirm
                )
            )
        )
    )
);

$starBtn = common::hasPriv('aiapp', 'collectMiniProgram')
    ? btn(
        setClass('ghost btn-star absolute'),
        set::size('md'),
        setData('url', createLink('aiapp', 'collectMiniProgram', "appID={$miniProgram->id}&delete={$delete}")),
        on::click('window.aiBrowseMiniProgram.handleStarBtnClick'),
        html(html::image("static/svg/{$star}.svg", "class='$star'")),
        $lang->aiapp->collect
    )
    : null;

$generateBtn = common::hasPriv('aiapp', 'miniProgramChat')
    ? btn(
        setClass('primary block w-full generate-btn'),
        set::disabled($isDeleted),
        $lang->aiapp->generate,
        on::click('window.aiBrowseMiniProgram.startAIChat')
    )
    : null;

div(
    setClass('mini-program fixed flex'),
    div(
        setClass('detail col shadow ring rounded h-full flex-none'),
        div(
            setClass('header relative flex flex-none'),
            div(
                setID('program-avatar'),
                setClass('program-avatar center flex-none'),
                setStyle(array('border' => "1px solid {$config->ai->miniPrograms->themeList[$iconTheme][1]}", 'background-color' => $config->ai->miniPrograms->themeList[$iconTheme][0])),
                html($config->ai->miniPrograms->iconList[$iconName])
            ),
            div(
                setClass('content flex-1 overflow-hidden'),
                div(
                    setClass('title pb-2 ellipsis'),
                    set::title($miniProgram->name),
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
                    $lang->aiapp->deleted
                )
                : $starBtn
        ),
        div(
            setClass('body col overflow-hidden', empty($formGroups) ? 'flex-none' : 'flex-1'),
            div(
                setClass('language-model flex flex-none justify-between items-center'),
                div(
                    setClass('content flex gap-2.5'),
                    span(
                        setStyle(array('color' => 'var(--color-slate-700)')),
                        $lang->aiapp->modelCurrent
                    ),
                    zui::aiModelName($miniProgram->model)
                ),
                empty($formGroups)
                    ? null
                    : btn(
                        setClass('ghost'),
                        set::size('md'),
                        set::disabled($isDeleted),
                        set::icon('trash'),
                        $lang->aiapp->clear,
                        on::click('window.aiBrowseMiniProgram.handleRestBtnClick')
                    )
            ),
            empty($formGroups)
                ? null
                : div(
                    setClass('form-container p-1 flex-1 overflow-y-auto'),
                    setID('miniProgramForm'),
                    form(
                        set::grid(false),
                        set::actions(array()),
                        $formGroups
                    )
                )
        ),
        div(
            setClass('footer flex-none'),
            $generateBtn
        )
    ),
    common::hasPriv('aiapp', 'miniProgramChat') ? div
    (
        setID('aiChatView'),
        setClass('shadow rounded flex-1 ring'),
        div
        (
            setClass('center h-full'),
            div
            (
                setClass('row items-center gap-2'),
                icon('lightbulb text-warning'),
                div(html(str_replace('{zaiConfigUrl}', createLink('zai', 'setting'), $lang->aiapp->langData->zaiConfigNotValid)))
            )
        ),
        on::init()->call('window.aiBrowseMiniProgram.initAIChatView', array(
            'creatingChat'  => array('title' => $miniProgram->name, 'chatType' => 'miniprogram'),
            'noContexts'    => true,
            'hideHeader'    => true,
            'sendbox'       => array('placeholder' => $lang->aiapp->continueasking),
            'noMessagesTip' => array('html' => "<div class='row items-center gap-2'><i class='icon icon-lightbulb text-warning'></i><div>{$lang->aiapp->chatTip}</div></div>")
        ))
    ) : null
);

render();
