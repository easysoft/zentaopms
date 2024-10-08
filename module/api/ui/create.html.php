<?php
namespace zin;
formPanel
(
    to::heading
    (
        div
        (
            setClass('panel-title text-lg'),
            $title
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->api->lib),
        input
        (
            set::name(''),
            set::value($libName),
            set::disabled(true)
        )
    ),
    formHidden('lib', $libID),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->api->module),
        picker
        (
            set::items($moduleOptionMenu),
            set::name('module'),
            set::required(true),
            set::value($moduleID)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->api->title),
        set::required(true),
        set::name('title')
    ),
    formRow
    (
        formGroup
        (
            set::width('330px'),
            set::required(true),
            set::label($lang->api->path),
            inputGroup
            (
                picker
                (
                    set::required(true),
                    set::items($lang->api->protocalOptions),
                    set::name('protocol')
                ),
                picker
                (
                    set::required(true),
                    set::width('1/5'),
                    set::items($lang->api->methodOptions),
                    set::name('method')
                )
            )
        ),
        formGroup
        (
            input
            (
                set::name('path')
            )
        )
    ),
    formGroup
    (
        set::width('2/5'),
        set::label($lang->api->requestType),
        picker
        (
            set::name('requestType'),
            set::items($lang->api->requestTypeOptions)
        )
    ),
    formGroup
    (
        set::label($lang->api->status),
        radioList
        (
            set::name('status'),
            set::inline(true),
            set::value('done'),
            set::items($lang->api->statusOptions)
        )
    ),
    formGroup
    (
        set::width('2/5'),
        set::label($lang->api->owner),
        picker
        (
            set::name('owner'),
            set::items($allUsers),
            set::value($app->user->account)
        )
    ),
    formGroup
    (
        setID('form-header'),
        setClass('params-group'),
        set::label($lang->api->header),
        h::table
        (
            setClass('table condensed bordered'),
            h::tr
            (
                h::th
                (
                    width('300px'),
                    $lang->struct->field
                ),
                h::th
                (
                    width('70px'),
                    $lang->struct->required
                ),
                h::th
                (
                    $lang->struct->desc
                ),
                h::th
                (
                    width('100px')
                )
            ),
            h::tr
            (
                setClass('input-row'),
                h::td
                (
                    input()
                ),
                h::td
                (
                    html("<input type='checkbox' />")
                ),
                h::td
                (
                    textarea
                    (
                        set::rows(1)
                    )
                ),
                h::td
                (
                    div
                    (
                        setClass('pl-2 flex self-center line-btn'),
                        btn
                        (
                            setClass('btn ghost btn-add'),
                            icon('plus')
                        ),
                        btn
                        (
                            setClass('btn ghost btn-delete'),
                            icon('trash')
                        )
                    )
                )
            )
        )
    ),
    formGroup
    (
        setID('form-query'),
        setClass('params-group'),
        set::label($lang->api->query),
        h::table
        (
            setClass('table condensed bordered'),
            h::tr
            (
                h::th
                (
                    width('300px'),
                    $lang->struct->field
                ),
                h::th
                (
                    width('70px'),
                    $lang->struct->required
                ),
                h::th
                (
                    $lang->struct->desc
                ),
                h::th
                (
                    width('100px')
                )
            ),
            h::tr
            (
                setClass('input-row'),
                h::td
                (
                    input()
                ),
                h::td
                (
                    html("<input type='checkbox' />")
                ),
                h::td
                (
                    textarea
                    (
                        set::rows(1)
                    )
                ),
                h::td
                (
                    div
                    (
                        setClass('pl-2 flex self-center line-btn'),
                        btn
                        (
                            setClass('btn ghost btn-add'),
                            icon('plus')
                        ),
                        btn
                        (
                            setClass('btn ghost btn-delete'),
                            icon('trash')
                        )
                    )
                )
            )
        )
    ),
    formGroup
    (
        setID('form-paramsType'),
        setClass('params-group'),
        set::label($lang->struct->type),
        radioList
        (
            set::name('type'),
            set::inline(true),
            set::value('formData'),
            set::items($lang->struct->typeOptions)
        )
    ),
    formGroup
    (
        setID('form-params'),
        setClass('params-group'),
        set::label($lang->api->params),
        h::table
        (
            setClass('table condensed bordered'),
            h::tr
            (
                h::th
                (
                    width('300px'),
                    $lang->struct->field
                ),
                h::th
                (
                    width('100px'),
                    $lang->struct->paramsType
                ),
                h::th
                (
                    width('70px'),
                    $lang->struct->required
                ),
                h::th
                (
                    $lang->struct->desc
                ),
                h::th
                (
                    width('100px')
                )
            ),
            h::tr
            (
                setClass('input-row'),
                setData(array('level' => 1, 'key' => 'origin', 'parent' => '0')),
                h::td
                (
                    input
                    (
                        set::name('')
                    )
                ),
                h::td
                (
                    select
                    (
                        setClass('objectType'),
                        set::name(''),
                        set::value('object'),
                        set::items($lang->api->paramsTypeOptions)
                    )
                ),
                h::td
                (
                    html("<input type='checkbox' />")
                ),
                h::td
                (
                    textarea
                    (
                        set::rows(1)
                    )
                ),
                h::td
                (
                    div
                    (
                        setClass('pl-2 flex self-center line-btn'),
                        btn
                        (
                            setClass('btn ghost btn-split hidden'),
                            icon('split')
                        ),
                        btn
                        (
                            setClass('btn ghost btn-add'),
                            icon('plus')
                        ),
                        btn
                        (
                            setClass('btn ghost btn-delete'),
                            icon('trash')
                        )
                    )
                )
            )
        )
    ),
    formHidden('params', '{"header":[],"params":[],"paramsType":"formData","query":[]}'),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->api->paramsExample),
        textarea
        (
            set::name('paramsExample')
        )
    ),
    formGroup
    (
        setID('form-response'),
        setClass('response'),
        set::label($lang->api->response),
        h::table
        (
            setClass('table condensed bordered'),
            h::tr
            (
                h::th
                (
                    width('300px'),
                    $lang->struct->field
                ),
                h::th
                (
                    width('100px'),
                    $lang->struct->paramsType
                ),
                h::th
                (
                    width('70px'),
                    $lang->struct->required
                ),
                h::th
                (
                    $lang->struct->desc
                ),
                h::th
                (
                    width('100px')
                )
            ),
            h::tr
            (
                setClass('input-row'),
                setData(array('level' => 1, 'key' => 'origin', 'parent' => '0')),
                h::td
                (
                    input
                    (
                        set::name('')
                    )
                ),
                h::td
                (
                    select
                    (
                        setClass('objectType'),
                        set::name(''),
                        set::value('object'),
                        set::items($lang->api->paramsTypeOptions)
                    )
                ),
                h::td
                (
                    html("<input type='checkbox' />")
                ),
                h::td
                (
                    textarea
                    (
                        set::rows(1)
                    )
                ),
                h::td
                (
                    div
                    (
                        setClass('pl-2 flex self-center line-btn'),
                        btn
                        (
                            setClass('btn ghost btn-split'),
                            icon('split')
                        ),
                        btn
                        (
                            setClass('btn ghost btn-add'),
                            icon('plus')
                        ),
                        btn
                        (
                            setClass('btn ghost btn-delete'),
                            icon('trash')
                        )
                    )
                )
            )
        )
    ),
    formHidden('response', '[]'),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->api->responseExample),
        textarea
        (
            set::name('responseExample')
        )
    ),
    formGroup
    (
        set::label($lang->api->desc),
        editor
        (
            set::name('desc')
        )
    )
);
