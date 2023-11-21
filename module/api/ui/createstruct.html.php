<?php
declare(strict_types=1);
/**
 * The create struct view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     api
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    to::heading
    (
        div
        (
            setClass('panel-title text-lg'),
            $lang->api->createStruct
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->api->structName),
        set::name('name'),
        set::required(true)
    ),
    formGroup
    (
        setID('form-paramsType'),
        setClass('params-group struct'),
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
            h::tr
            (
                setClass('input-row'),
                setData(array('level' => 1, 'key' => 'origin', 'parent' => '0')),
                h::td
                (
                    input()
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
    formHidden('attribute'),
    formGroup
    (
        set::label($lang->api->desc),
        editor
        (
            set::name('desc')
        )
    )
);

render();
