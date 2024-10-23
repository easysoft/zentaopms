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

form
(
    setID('selectLibTypeForm'),
    set::submitBtnText($lang->doc->nextStep),
    on::change('[name=rootSpace]', "changeSpace"),
    on::change('[name=type]',      "changeDocType"),
    on::change('[name=apiType]',   "changeApiType"),
    on::change('[name=project]',   "loadExecutions"),
    on::change('[name=execution]', "loadObjectModulesForSelect('execution')"),
    on::change('[name=product]',   "loadObjectModulesForSelect('product')"),
    on::change('[name=custom]',    "loadObjectModulesForSelect('custom')"),
    on::change('[name=lib]',       "loadLibModulesForSelect"),
    formGroup
    (
        set::label($lang->doc->selectSpace),
        radioList(set::name('rootSpace'), set::items($spaceList), set::value(key($spaceList)), set::inline(true))
    ),
    formRow
    (
        setID('docType'),
        formGroup
        (
            set::label($lang->doc->type),
            radioList(set::name('type'), set::items($typeList), set::value('doc'), set::inline(true))
        )
    ),
    formRow
    (
        setClass('apiTypeTR hidden'),
        formGroup
        (
            set::width('2/5'),
            set::label($lang->doc->apiType),
            set::control(array('control' => 'picker', 'name' => 'apiType', 'items' => $lang->doc->apiTypeList, 'value' => '', 'required' => true))
        )
    ),
    formRow
    (
        setClass('projectTR hidden'),
        formGroup
        (
            set::label($lang->doc->project),
            set::width('2/5'),
            set::control(array('control' => 'picker', 'name' => 'project', 'items' => $projects, 'value' => key($projects), 'required' => true))
        ),
        formGroup
        (
            setID('executionBox'),
            set::width('2/5'),
            set::label($lang->doc->execution),
            set::labelClass('executionTH'),
            set::control(array('control' => 'picker', 'name' => 'execution', 'items' => array(), 'value' => ''))
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
            set::control(array('control' => 'picker', 'name' => 'product', 'items' => $products, 'value' => key($products), 'required' => true))
        )
    ),
    formRow
    (
        setClass('customTR hidden'),
        formGroup
        (
            set::width('4/5'),
            set::label($lang->doc->space),
            set::required(true),
            set::control(array('control' => 'picker', 'name' => 'custom', 'items' => $spaces, 'value' => key($spaces), 'required' => true))
        )
    ),
    formGroup
    (
        set::width('4/5'),
        set::label($lang->doc->lib),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'lib', 'items' => array(), 'value' => '', 'required' => true))
    ),
    formGroup
    (
        setClass('moduleBox'),
        set::width('4/5'),
        set::label($lang->doc->module),
        set::required(true),
        set::control(array('control' => 'picker', 'name' => 'module', 'items' => array(), 'value' => '', 'required' => true))
    )
);

/* ====== Render page ====== */
render();
