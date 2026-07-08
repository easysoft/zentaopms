<?php
declare(strict_types=1);
namespace zin;

formPanel
(
    set::title($lang->repo->editWebhook),
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::label($lang->repo->name),
        set::required(true),
        set::value($webhook->displayName)
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('targetURL'),
            set::label($lang->repo->targetURL),
            set::required(true),
            set::value($webhook->url)
        ),
        formGroup
        (
            set::width('1/2'),
            setClass('items-center'),
            checkbox
            (
                set::name('SSL'),
                set::rootClass('ml-4'),
                set::checked($webhook->insecure ? 0 : 1),
                set::value($webhook->insecure ? 0 : 1),
                set::text($lang->repo->webhook->SSL)
            )
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('key'),
        set::label($lang->repo->webhook->key),
        set::control(array('type' => 'password')),
        set::value($webhook->secret)
    ),
    formGroup
    (
        set::width('1/2'),
        setID('triggerEvent'),
        set::name('triggerEvent'),
        set::label($lang->repo->webhook->triggerEvent),
        set::required(true),
        set::control('radioListInline'),
        set::items($lang->repo->webhook->triggerEventList),
        set::value(empty($webhook->triggers) ? 0 : 1),
        on::change()->call('onChangeTriggerEvent')
    ),
    formGroup
    (
        set::width('1/2'),
        setID('customEvent'),
        set::name('customEvent'),
        empty($webhook->triggers) ? setClass('hidden') : null,
        set::label($lang->repo->webhook->customEvent),
        set::control('picker'),
        set::multiple(true),
        set::items($lang->repo->webhook->customEventList),
        empty($webhook->triggers) ? null : set::value($webhook->triggers)
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('desc'),
        set::label($lang->repo->webhook->desc),
        set::control(array('type' => 'textarea', 'rows' => 5)),
        set::value($webhook->description)
    )
);
