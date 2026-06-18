<?php
declare(strict_types=1);
/**
 * The createLib view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     api
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('productLang', $lang->productCommon);
jsVar('projectLang', $lang->projectCommon);
jsVar('window.libType', $type);
if($this->config->edition != 'open')
{
    jsVar('hasImportOpenAPIPriv', hasPriv('api', 'importOpenApi'));
    jsVar('importBtnText', $lang->import);
    jsVar('saveBtnText', $lang->save);
}
formPanel
(
    set::className('createLibForm'),
    set::title($title),
    set::labelWidth('110px'),
    formHidden('createMode', $createMode),
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
            set::items(createLink('product', 'ajaxGetDropMenu', "objectID=$objectID&module=&method=&extra=selectmode&useLink=0")),
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
            set::items(createLink('project', 'ajaxGetDropMenu', "objectID=$objectID&module=&method=&extra=selectmode&useLink=0")),
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
    $createMode === 'import' && $this->config->edition != 'open' && hasPriv('api', 'importOpenApi')
    ? formGroup
    (
        setID('importFileBox'),
        set::label($lang->api->importFile),
        set::required(true),
        fileSelector(setID('files'), set::name('files'), set::accept('.json,.yaml'), set::maxFileCount(1), set::multiple(false), set::required(true)),
        span(setClass('text-gray'), $lang->api->importFileTip)
    ) : null,
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
                    setClass('w-full'),
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
                    setClass('w-full'),
                    inputGroup
                    (
                        $lang->doclib->user,
                        userPicker(set::items($users))
                    )
                )
            )
        )
    )
);
/* ====== Render page ====== */
render();
