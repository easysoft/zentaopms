<?php
declare(strict_types=1);
/**
 * The edit struct view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     api
 * @link        https://www.zentao.net
 */
namespace zin;

$attributes = array();
if(!empty($struct->attribute))
{
    $buildAttributes = function($struct, $data, $typeList, $level = 0) use (&$buildAttributes)
    {
        global $lang;

        $hidden       = $struct->type == 'formData' ? 'hidden' : '';
        $attributes[] = h::tr
        (
            setClass('input-row'),
            set('data-level', $level + 1),
            set('data-key', $data['key']),
            set('data-parent', isset($data['parent']) ? $data['parent'] : 0),
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
                $data['required'] ? html("<input type='checkbox' checked/>") : html("<input type='checkbox' />")
            ),
            h::td
            (
                textarea
                (
                    set::rows(1),
                    set::name(''),
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
            foreach($data['children'] as $attribute) $attributes[] = $buildAttributes($struct, $attribute, $typeList, $level);
        }

        return $attributes;
    };

    foreach($struct->attribute as $attribute) $attributes[] = $buildAttributes($struct, $attribute, $typeOptions);
}
else
{
    $attributes = h::tr
    (
        setClass('input-row'),
        set('data-level', 1),
        set('data-key', 'origin'),
        set('data-parent', '0'),
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
                set::rows(1),
                set::name('')
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

formPanel
(
    to::heading
    (
        div
        (
            setClass('panel-title text-lg'),
            $lang->api->editStruct
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->api->structName),
        set::name('name'),
        set::value($struct->name),
        set::required(true)
    ),
    formGroup
    (
        set::id('form-paramsType'),
        setClass('params-group struct'),
        set::label($lang->struct->type),
        radioList
        (
            set::name('type'),
            set::inline(true),
            set::value($struct->type),
            set::items($lang->struct->typeOptions)
        )
    ),
    formGroup
    (
        set::id('form-params'),
        setClass('params-group struct'),
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
            $attributes
        )
    ),
    formHidden('attribute', $struct->attribute),
    formGroup
    (
        set::label($lang->api->desc),
        editor
        (
            set::name('desc'),
            html($struct->desc)
        )
    )
);

render();
