<?php
namespace zin;
$apiHeader = $apiQuery = $apiParams = $apiResponse = array();
$defaultTR = function()
{
    return h::tr
    (
        setClass('input-row'),
        h::td
        (
            input
            (
                set::name('')
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
                    setClass('btn ghost btn-add'),
                    icon('plus')
                ),
                btn
                (
                    setClass('btn ghost btn-delete'),
                    icon('trash')
                ),
            )
        )
    );
};

if(!empty($api->params['header']))
{
    foreach($api->params['header'] as $param)
    {
        $apiHeader[] = h::tr
        (
            setClass('input-row'),
            h::td
            (
                input
                (
                    set::name(''),
                    set::value($param['field'])
                )
            ),
            h::td
            (
                $param['required'] ? html("<input type='checkbox' checked/>") : html("<input type='checkbox'/>")
            ),
            h::td
            (
                textarea
                (
                    set::rows(1),
                    set::value($param['desc'])
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
        );
    }
}
else
{
    $apiHeader[] = $defaultTR();
}

if(!empty($api->params['query']))
{
    foreach($api->params['query'] as $query)
    {
        $apiQuery[] = h::tr
        (
            setClass('input-row'),
            h::td
            (
                input
                (
                    set::name(''),
                    set::value($query['field'])
                )
            ),
            h::td
            (
                $query['required'] ? html("<input type='checkbox' checked/>") : html("<input type='checkbox'/>")
            ),
            h::td
            (
                textarea
                (
                    set::rows(1),
                    set::value($query['desc'])
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
        );
    }
}
else
{
    $apiQuery[] = $defaultTR();
}

$parseTree = function($data, $typeList, $level = 0) use (&$parseTree)
{
    global $lang;

    $hidden = $data['paramsType'] == 'formData' ? 'hidden' : '';
    $tbody[] = h::tr
    (
        setClass('input-row'),
        setData(array('level' => $level + 1, 'key' => $data['key'], 'parent' => isset($data['parent']) ? $data['parent'] : 0)),
        h::td
        (
            $level ? setStyle('padding-left', ($level + 1) * 10 . 'px') : null,
            input
            (
                set::name(''),
                set::value($data['field'])
            )
        ),
        h::td
        (
            select
            (
                setClass('objectType'),
                set::name(''),
                set::value($data['paramsType']),
                set::items($typeList)
            )
        ),
        h::td
        (
            $data['required'] ? html("<input type='checkbox' checked/>") : html("<input type='checkbox'/>")
        ),
        h::td
        (
            textarea
            (
                set::rows(1),
                set::value($data['desc'])
            )
        ),
        h::td
        (
            div
            (
                setClass('pl-2 flex self-center line-btn'),
                btn
                (
                    setClass("btn ghost btn-split $hidden"),
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
    );

    if(isset($data['children']) && count($data['children']) > 0)
    {
        $level++;
        foreach($data['children'] as $item) $tbody[] = $parseTree($item, $typeList, $level);
    }

    return $tbody;
};

if(!empty($api->params['params']))
{
    foreach($api->params['params'] as $param) $apiParams = array_merge($apiParams, $parseTree($param, $typeOptions));
}
else
{
    $apiParams[] = h::tr
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
    );
}

if(!empty($api->response))
{
    foreach($api->response as $param) $apiResponse = array_merge($apiResponse, $parseTree($param, $typeOptions));
}
else
{
    $apiResponse[] = h::tr
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
    );
}

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
        set::label($lang->api->module),
        picker
        (
            set::items($moduleOptionMenu),
            set::name('module'),
            set::value($api->module),
            set::required(true)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::required(true),
        set::label($lang->api->title),
        input
        (
            set::name('title'),
            set::value($api->title)
        )
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
                    set::name('protocol'),
                    set::value($api->protocol)
                ),
                picker
                (
                    set::required(true),
                    set::width('1/5'),
                    set::items($lang->api->methodOptions),
                    set::name('method'),
                    set::value($api->method)
                )
            )
        ),
        formGroup
        (
            input
            (
                set::name('path'),
                set::value($api->path)
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
            set::value($api->requestType),
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
            set::value($api->status),
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
            set::value($api->owner)
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
            $apiHeader
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
            $apiQuery
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
            set::value(!empty($api->params['paramsType']) ? $api->params['paramsType'] : ''),
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
            $apiParams
        )
    ),
    formHidden('params', json_encode($api->params)),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->api->paramsExample),
        textarea
        (
            set::name('paramsExample'),
            set::value($api->paramsExample)
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
            $apiResponse
        )
    ),
    formHidden('response', json_encode($api->response)),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->api->responseExample),
        textarea
        (
            set::name('responseExample'),
            set::value($api->responseExample)
        )
    ),
    formGroup
    (
        set::label($lang->api->desc),
        editor
        (
            set::name('desc'),
            html($api->desc)
        )
    ),
    formHidden('editedDate', $api->editedDate)
);
