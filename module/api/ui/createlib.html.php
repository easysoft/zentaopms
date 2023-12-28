<?php
declare(strict_types=1);
/**
 * The createLib view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     api
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('productLang', $lang->productCommon);
jsVar('projectLang', $lang->projectCommon);
jsVar('window.libType', $type);
formPanel
(
    set::title($lang->api->createLib),
    formGroup
    (
        set::label($lang->api->libType),
        radioList
        (
            set::name('libType'),
            set::items($lang->api->libTypeList),
            set::value($type),
            set::inline(true),
            on::change('toggleLibType')
        )
    ),
    formRow
    (
        setID('productBox'),
        setClass($type != 'product' ? 'hidden' : ''),
        formGroup
        (
            set::label($lang->api->product),
            set::width('3/4'),
            set::name('product'),
            set::items($products),
            set::value($type == 'product' ? $objectID : 0),
            set::required(true)
        )
    ),
    formRow
    (
        setID('projectBox'),
        setClass($type != 'project' ? 'hidden' : ''),
        formGroup
        (
            set::label($lang->api->project),
            set::width('3/4'),
            set::name('project'),
            set::items($projects),
            set::value($type == 'project' ? $objectID : 0),
            set::required(true)
        )
    ),
    formGroup
    (
        set::label($lang->api->name),
        set::width('3/4'),
        set::name('name')
    ),
    formGroup
    (
        set::label($lang->api->baseUrl),
        set::width('3/4'),
        set::name('baseUrl'),
        set::placeholder($lang->api->baseUrlDesc)
    ),
    formRow
    (
        setID('aclBox'),
        formGroup
        (
            set::label($lang->api->control),
            radioList
            (
                set::name('acl'),
                set::items($lang->api->aclList),
                set::value('open'),
                on::change("toggleAcl('lib')")
            )
        )
    ),
    formRow
    (
        setID('whiteListBox'),
        setClass('hidden'),
        formGroup
        (
            set::label($lang->doc->whiteList),
            set::width('3/4'),
            div
            (
                setClass('w-full check-list'),
                div
                (
                    inputGroup
                    (
                        $lang->doclib->group,
                        picker
                        (
                            set::name('groups[]'),
                            set::items($groups),
                            set::multiple(true)
                        )
                    )
                ),
                div
                (
                    inputGroup
                    (
                        $lang->doclib->user,
                        users(set::items($users))
                    )
                )
            )
        )
    )
);
/* ====== Render page ====== */
render();
