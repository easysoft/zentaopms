<?php
declare(strict_types=1);

namespace zin;

jsVar('regenerateLang',   $lang->aiapp->regenerate);
jsVar('emptyNameWarning', $lang->aiapp->emptyNameWarning);
jsVar('clearContextLang', $lang->aiapp->clearContext);
jsVar('newChatTip',       $lang->aiapp->newChatTip);
jsVar('newVersionTip',    $lang->aiapp->newVersionTip);

jsVar('prompt', $miniProgram->prompt);
jsVar('postLink', createLink('aiapp', 'miniProgramChat', "id={$miniProgram->id}"));
jsVar('messages', $messages);
jsVar('isAppDisabled', $miniProgram->published === '0');
jsVar('hasModels', $hasModels);

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
                set::disabled($isDeleted || !$hasModels),
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
                set::disabled($isDeleted || !$hasModels),
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
                set::disabled($isDeleted || !$hasModels),
                setData('name', $field->name),
                setData('fid', $field->id)
            );
        }
        else
        {
            $control = input(
                set::name("field-{$field->id}"),
                set::placeholder($field->placeholder),
                set::disabled($isDeleted || !$hasModels),
                setData('name', $field->name),
                setData('fid', $field->id)
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

$inputContainer = common::hasPriv('aiapp', 'miniProgramChat')
    ? div(
        setClass('input-container'),
        setClass(array('hidden' => !empty($messages))),
        html(<<<END
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M23.6037 4.22186C23.6037 3.95812 23.5162 3.73818 23.3401 3.56236L20.4372 0.659451C20.2614 0.483472 20.0415 0.395508 19.7775 0.395508C19.5134 0.395508 19.2938 0.483472 19.1178 0.659451L0.263943 19.5132C0.0877584 19.6893 0 19.9094 0 20.1732C0 20.4372 0.0877584 20.6569 0.263943 20.8329L3.16675 23.7356C3.34293 23.9117 3.56256 23.9996 3.82661 23.9996C4.09039 23.9996 4.31028 23.9121 4.48631 23.7356L23.3404 4.88171C23.5162 4.70558 23.6037 4.4859 23.6037 4.22186ZM17.0504 8.5176L15.4818 6.94884L19.7774 2.65314L21.3459 4.22186L17.0504 8.5176Z" fill="url(#paint0_linear_2090_3242)"/>
            <path d="M3.16577 3.12833C3.22352 3.31694 3.49052 3.31693 3.54825 3.12832L3.7657 2.41793C3.78514 2.3544 3.83487 2.30468 3.8984 2.28523L4.60876 2.06778C4.79737 2.01005 4.79738 1.74305 4.60878 1.6853L3.89839 1.4678C3.83487 1.44835 3.78515 1.39862 3.7657 1.3351L3.54825 0.624699C3.49052 0.43609 3.22352 0.436082 3.16577 0.624687L2.94826 1.33511C2.92881 1.39863 2.8791 1.44835 2.81558 1.46779L2.10516 1.6853C1.91655 1.74305 1.91656 2.01005 2.10517 2.06778L2.81557 2.28523C2.87909 2.30468 2.92882 2.3544 2.94826 2.41792L3.16577 3.12833Z" fill="url(#paint1_linear_2090_3242)"/>
            <path d="M6.96529 5.5089C7.09233 5.5478 7.19177 5.64724 7.23066 5.77428L7.66577 7.19548C7.78126 7.57268 8.31524 7.57268 8.43073 7.19548L8.86584 5.77428C8.90473 5.64724 9.00417 5.5478 9.13121 5.5089L10.5523 5.07381C10.9295 4.95833 10.9295 4.42434 10.5523 4.30886L9.13122 3.87383C9.00418 3.83493 8.90473 3.73549 8.86584 3.60845L8.43073 2.18726C8.31524 1.81006 7.78126 1.81006 7.66577 2.18726L7.23066 3.60845C7.19177 3.73549 7.09232 3.83493 6.96528 3.87383L5.54423 4.30886C5.16702 4.42434 5.16701 4.95833 5.54422 5.07381L6.96529 5.5089Z" fill="url(#paint2_linear_2090_3242)"/>
            <path d="M22.6639 10.8506C22.6003 10.8311 22.5506 10.7814 22.5312 10.7178L22.3138 10.0077C22.2561 9.81905 21.989 9.81906 21.9313 10.0077L21.714 10.7178C21.6945 10.7814 21.6448 10.8311 21.5813 10.8506L20.8708 11.068C20.6822 11.1258 20.6822 11.3928 20.8708 11.4505L21.5813 11.668C21.6448 11.6874 21.6945 11.7372 21.714 11.8007L21.9313 12.5108C21.9891 12.6994 22.2561 12.6994 22.3138 12.5108L22.5312 11.8007C22.5506 11.7372 22.6003 11.6874 22.6639 11.668L23.3744 11.4505C23.563 11.3928 23.563 11.1258 23.3744 11.068L22.6639 10.8506Z" fill="url(#paint3_linear_2090_3242)"/>
            <path d="M12.5485 3.12824C12.6063 3.31685 12.8733 3.31687 12.931 3.12826L13.1485 2.41792C13.168 2.3544 13.2177 2.30468 13.2812 2.28523L13.9915 2.06778C14.1802 2.01004 14.1802 1.74305 13.9916 1.6853L13.2812 1.46779C13.2177 1.44835 13.168 1.39863 13.1485 1.33511L12.931 0.62476C12.8733 0.436148 12.6063 0.436166 12.5485 0.624785L12.3312 1.33508C12.3117 1.39862 12.262 1.44835 12.1985 1.4678L11.488 1.6853C11.2994 1.74304 11.2994 2.01005 11.488 2.06778L12.1985 2.28523C12.262 2.30467 12.3117 2.35441 12.3312 2.41794L12.5485 3.12824Z" fill="url(#paint4_linear_2090_3242)"/>
            <defs>
            <linearGradient id="paint0_linear_2090_3242" x1="26.2016" y1="-1.33315" x2="-0.000437567" y2="23.9992" gradientUnits="userSpaceOnUse">
            <stop stop-color="#FF2070"/>
            <stop offset="0.481728" stop-color="#9670FF"/>
            <stop offset="0.761875" stop-color="#258DFF"/>
            </linearGradient>
            <linearGradient id="paint1_linear_2090_3242" x1="5.6465" y1="-0.274854" x2="1.48041" y2="3.75296" gradientUnits="userSpaceOnUse">
            <stop stop-color="#FF2070"/>
            <stop offset="0.481728" stop-color="#9670FF"/>
            <stop offset="0.761875" stop-color="#258DFF"/>
            </linearGradient>
            <linearGradient id="paint2_linear_2090_3242" x1="12.6278" y1="0.388227" x2="4.29481" y2="8.44463" gradientUnits="userSpaceOnUse">
            <stop stop-color="#FF2070"/>
            <stop offset="0.481728" stop-color="#9670FF"/>
            <stop offset="0.761875" stop-color="#258DFF"/>
            </linearGradient>
            <linearGradient id="paint3_linear_2090_3242" x1="24.4122" y1="9.10797" x2="20.2464" y2="13.1359" gradientUnits="userSpaceOnUse">
            <stop stop-color="#FF2070"/>
            <stop offset="0.481728" stop-color="#9670FF"/>
            <stop offset="0.761875" stop-color="#258DFF"/>
            </linearGradient>
            <linearGradient id="paint4_linear_2090_3242" x1="15.0293" y1="-0.274854" x2="10.8632" y2="3.75291" gradientUnits="userSpaceOnUse">
            <stop stop-color="#FF2070"/>
            <stop offset="0.481728" stop-color="#9670FF"/>
            <stop offset="0.761875" stop-color="#258DFF"/>
            </linearGradient>
            </defs>
            </svg>
        END),
        html("<textarea placeholder='{$lang->aiapp->continueasking}' rows='1' class='chat-input-box'></textarea>"),
        btn(
            setClass('ghost send-btn'),
            on::click('window.aiBrowseMiniProgram.clearInputAndChat'),
            html(<<<END
                <svg width="17" height="20" viewBox="0 0 17 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.9398 4C14.1118 4.00003 14.2778 4.07496 14.4063 4.2106C14.5348 4.34623 14.6169 4.53312 14.6371 4.73583L14.642 4.83333V10.1908C14.642 11.7392 13.8036 13.0717 12.659 13.1617L12.5354 13.1667H5.10287L6.71226 15.0775C6.8382 15.2275 6.91131 15.4287 6.91676 15.6403C6.9222 15.8519 6.85957 16.0581 6.74158 16.2169C6.62359 16.3757 6.45909 16.4753 6.2815 16.4953C6.1039 16.5154 5.92652 16.4545 5.78539 16.325L5.71938 16.2558L2.91067 12.9225C2.77903 12.7662 2.70508 12.5543 2.70508 12.3333C2.70508 12.1124 2.77903 11.9004 2.91067 11.7442L5.71938 8.41083C5.84575 8.26138 6.01531 8.17461 6.19362 8.16814C6.37194 8.16168 6.54565 8.23601 6.67946 8.37604C6.81327 8.51607 6.89715 8.71129 6.91407 8.92206C6.93099 9.13283 6.87967 9.34334 6.77055 9.51083L6.71226 9.58917L5.10287 11.5H12.5354C12.8577 11.5 13.1927 11.0192 13.2334 10.3308L13.2376 10.1908V4.83333C13.2376 4.61232 13.3116 4.40036 13.4433 4.24408C13.575 4.0878 13.7536 4 13.9398 4Z" fill="#D8DBDE"/>
                </svg>
            END)
        )
    )
    : null;

div(
    setClass('mini-program fixed flex'),
    div(
        setClass('detail col shadow-md h-full flex-none'),
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
                    span($models[$miniProgram->model]),
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
    div(
        setClass('chat chat-nohistory shadow-md flex-1 center'),
        setClass(array('hidden' => !empty($messages))),
        div(
            setClass('chat-tip flex'),
            html(<<<END
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.3334 6.66665C13.3334 8.93588 11.9162 10.8741 9.91858 11.6445H8.00008H6.08158C4.08395 10.8741 2.66675 8.93588 2.66675 6.66665C2.66675 3.72111 5.05455 1.33331 8.00008 1.33331C10.9456 1.33331 13.3334 3.72111 13.3334 6.66665Z" stroke="#FF9F46" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9.91858 11.6445L9.69221 14.361C9.67781 14.5338 9.53338 14.6667 9.36001 14.6667H6.64011C6.46674 14.6667 6.32231 14.5338 6.30794 14.361L6.08154 11.6445" stroke="#FF9F46" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8.47095 4.78107L7.05673 6.19528L9.17805 6.90239L7.52814 8.5523" stroke="#FF9F46" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            END),
            $lang->aiapp->chatTip
        )
    ),
    div(
        setClass('chat chat-nomodel shadow-md flex-1 center hidden'),
        div(
            p(
                icon(set::name('exclamation'), setClass('mr-2'), setStyle(array('color' => 'rgb(255, 159, 70)'))),
                html($lang->aiapp->noModel[0])
            ),
            p(html($lang->aiapp->noModel[1]), setStyle('padding-left', '22px'))
        )
    ),
    div(
        setClass('chat chat-history shadow-md flex-1 col'),
        setClass(array('hidden' => empty($messages))),
        div(
            setClass('flex-1 message-list'),
        ),
        $inputContainer
    )
);

render();
