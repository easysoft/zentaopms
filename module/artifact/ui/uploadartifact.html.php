<?php
declare(strict_types=1);
/**
 * The upload artifact view file of artifact module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     artifact
 * @link        https://www.zentao.net
 */
namespace zin;
formPanel
(
    set::formID('artifactUploadForm'),
    set::title($title),
    set::submitBtnText($lang->save),
    set::ajax(array('beforeSubmit' => jsRaw('window.checkArtifactBeforeUpload'), 'onFail' => jsRaw('window.artifactOnFail'))),
    set::actions(array('submit', array('text' => $lang->cancel, 'data-on' => 'click', 'data-call' => 'cancelArtifactUpload'))),
    formHidden('artifactFormUrl', createLink('artifact', 'uploadArtifact', "artifactID={$artifactLibID}&path={$path}")),
    formHidden('artifactNameRequired', $lang->artifact->nameRequired),
    formHidden('artifactRenameExists', $lang->artifact->renameExists),
    formGroup
    (
        set::label(''),
        set::labelWidth('0'),
        fileSelector
        (
            set::name('file'),
            set::multiple(false),
            set::renameBtn(false),
            set::removeBtn(false),
            set::maxFileCount(1),
            set::onAdd(jsRaw('window.artifactOnFileAdd'))
        ),
        div
        (
            setID('artifactExistsBlock'),
            setClass('hidden mt-2 w-full'),
            div
            (
                setClass('flex items-center'),
                span(setClass('mr-2 whitespace-nowrap'), $lang->artifact->existsTitle . ' ' . $lang->artifact->selectHandleType . '：'),
                radioList
                (
                    set::name('artifactHandleType'),
                    set::value('overwrite'),
                    set::inline(true),
                    set::items(array('overwrite' => $lang->artifact->overwrite, 'rename' => $lang->artifact->rename)),
                    on::change('[name="artifactHandleType"]')->call('toggleArtifactRenameForm')
                )
            ),
            div
            (
                setID('artifactRenameForm'),
                setClass('hidden mt-2'),
                div
                (
                    setClass('flex items-center'),
                    span(setClass('text-danger mr-1'), '*'),
                    span(setClass('mr-1 whitespace-nowrap'), $lang->artifact->fileName . '：'),
                    input(set::id('artifactNewName'), set::name('artifactNewName'), set::class('form-control'))
                ),
                div(setID('artifactNameError'), setClass('text-danger hidden mt-1'), $lang->artifact->nameExists)
            )
        )
    )
);
