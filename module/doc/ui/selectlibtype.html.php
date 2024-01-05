<?php
declare(strict_types=1);
/**
 * The selectlibtype view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

to::header
(
    entityLabel
    (
        set::level(1),
        set::text($lang->doc->create),
    ),
);

form
(
    setID('selectLibTypeForm'),
    set::submitBtnText($lang->doc->nextStep),
    formGroup
    (
        set::label($lang->doc->space),
        radioList
        (
            set::name('space'),
            set::items($spaceList),
            set::value(key($spaceList)),
            set::inline(true),
            on::change('changeSpace')
        )
    ),
    formRow
    (
        setID('docType'),
        formGroup
        (
            set::label($lang->doc->type),
            radioList
            (
                set::name('type'),
                set::items($typeList),
                set::value('doc'),
                set::inline(true),
                on::change('changeDocType')
            )
        )
    ),
    formRow
    (
        setClass('apiTypeTR hidden'),
        formGroup
        (
            set::width('2/5'),
            set::label($lang->doc->apiType),
            picker
            (
                set::id('apiType'),
                set::name('apiType'),
                set::items($lang->doc->apiTypeList),
                set::value(''),
                on::change('changeApiType')
            )
        )
    ),
    formRow
    (
        setClass('projectTR hidden'),
        formGroup
        (
            set::label($lang->doc->project),
            set::width('2/5'),
            set::required(true),
            picker
            (
                setID('projectBox'),
                set::name('project'),
                set::items($projects),
                set::value(key($projects))
            )
        ),
        formGroup
        (
            set::width('2/5'),
            set::label($lang->doc->execution),
            set::labelClass('executionTH'),
            picker
            (
                setID('executionBox'),
                set::name('execution'),
                set::items(array()),
                set::value(''),
                on::change("loadObjectModules('execution')")
            )
        ),
        formGroup
        (
            setClass('executionHelp'),
            icon
            (
                'help',
                set('data-toggle', 'tooltip'),
                set('data-title', $lang->doc->placeholder->execution),
                set('data-placement', 'right'),
                set('data-type', 'white'),
                set('data-class-name', 'text-gray border border-light'),
                setClass('ml-2 mt-2 text-gray')
            )
        )
    ),
    formRow
    (
        setClass('productTR hidden'),
        formGroup
        (
            set::width('4/5'),
            set::label($lang->doc->product),
            set::required(true),
            picker
            (
                set::name('product'),
                set::items($products),
                set::value(key($products)),
                on::change("loadObjectModules('product')")
            )
        )
    ),
    formGroup
    (
        set::width('4/5'),
        set::label($lang->doc->libAndModule),
        set::required(true),
        picker
        (
            setClass('moduleBox'),
            set::name('module'),
            set::items(array()),
            set::value('')
        )
    )
);

/* ====== Render page ====== */
render();
