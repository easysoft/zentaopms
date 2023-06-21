<?php
declare(strict_types=1);
/**
* The UI file of file module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     file
* @link        https://www.zentao.net
*/

namespace zin;

$this->app->loadLang('file');

$isCustomExport = (!empty($customExport) and !empty($allExportFields));
if($isCustomExport)
{
    $allExportFields  = explode(',', $allExportFields);
    $hasDefaultField  = isset($selectedFields);
    $selectedFields   = $hasDefaultField ? explode(',', $selectedFields) : array();
    $moduleName       = $this->moduleName;
    $moduleLang       = $lang->$moduleName;

    $exportFieldPairs = array();
    foreach($allExportFields as $key => $field)
    {
        $field     = trim($field);
        $fieldName = isset($lang->$field) && is_string($lang->$field) ? $lang->$field : $field;
        $exportFieldPairs[$field] = isset($moduleLang->$field) && is_string($moduleLang->$field) ? $moduleLang->$field : $fieldName;
        if(!$hasDefaultField) $selectedFields[] = $field;
    }

    jsVar('defaultExportFields', implode(',', $selectedFields));
}

/* Unset useless export type. */
if(isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], 'calendar')) unset($lang->exportTypeList['selected']);

$hideExportRange = isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], 'kanban');

/* Generate custom export fields. */
$customExportRowList = array();
if($isCustomExport)
{
    /* Choose template. */
    $customExportRowList[] = formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->file->tplTitleAB),
            set::control('select'),
            set::name('template'),
            set::items(array())
        ),
        btn
        (
            setClass('ml-4'),
            $lang->file->setExportTPL
        )
    );

    /* Panel for customize template. */
    $customExportRowList[] = formRow
    (
        formGroup
        (
            set::width('full'),
            set::label(''),
            panel
            (
                set::title($lang->file->exportFields),
                setClass('w-full'),
                setStyle('background-color', 'rgb(249 250 251)'),
                set('headingActions', array(array('type'=>'button', 'icon'=>'close', 'size'=>'sm'))),
                select
                (
                    set::name('exportFields[]'),
                    set::items($exportFieldPairs),
                    set::multiple(true),
                ),
                inputGroup
                (
                    $lang->file->tplTitle,
                    setClass('mt-4'),
                    input(set::name('title'), set::value($lang->file->defaultTPL)),
                    hasPriv('file', 'setPublic') ? div
                    (
                        setClass('input-group-addon'),
                        div(input(set::type('checkbox'), set::name('public'))),
                        div(setClass('ml-2'), $lang->public)
                    ) : null,
                    btn(setClass('primary'), $lang->save),
                    btn($lang->delete)
                )
            )
        )
    );
}

form
(
    css('.form-grid .form-label.required:after{content:""}'), // Remove required tag.
    css('.modal-content{padding-top: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; padding-bottom: 1.25rem;}'),
    setCssVar('--form-grid-label-width', '4rem'),
    set::target('_self'),
    set::actions(array('submit')),
    set::submitBtnText($lang->export),
    on::submit('setDownloading'),
    formGroup
    (
        set::width('full'),
        set::label($lang->file->fileName),
        set::control('inputControl'),
        set::name('fileName'),
        set::value(isset($fileName) ? $fileName : $lang->file->untitled),
        on::change('onChangeFileName'),
        set::required(true)
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->file->extension),
        set::control('select'),
        set::name('fileType'),
        set::items($lang->exportFileTypeList),
        on::change('onChangeFileType'),
        set::required(true)
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->file->encoding),
        set::control('select'),
        set::name('encode'),
        set::items($config->charsets[$this->cookie->lang]),
        set::value($this->config->zin->lang == 'zh-cn' ? 'gbk' : 'utf-8'),
        set::required(true)
    ),
    /* Fields for KanBan. */
    formRow(
        $hideExportRange ? setClass('hidden') : null,
        formGroup
        (
            set::width('1/2'),
            set::label($lang->file->exportRange),
            set::control('select'),
            set::name('exportType'),
            set::items($lang->exportTypeList),
            set::required(true)
        )
    ),
    formRow
    (
        setClass('hidden'),
        formGroup
        (
            set::control('checkList'),
            set::name('part'),
            set::items(array(1 => $lang->file->batchExport)),
            set::value('')
        ),
        input
        (
            set::type('hidden'),
            set::name('limit'),
            set::value('')
        )
    ),
    /* Custom export. */
    $customExportRowList
);

set::title($lang->file->exportData);

js
(
<<<JAVASCRIPT
function setDownloading(event)
{
    /* Doesn't support Opera, omit it. */
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;

    $.cookie.set('downloading', 0);

    var time = setInterval(function()
    {
        if($.cookie.get('downloading') == 1)
        {
            $(event.target).closest('div.modal')[0].classList.remove('show');
            $.cookie.set('downloading', null);
            clearInterval(time);
        }
    }, 300);

    return true;
}

/* If file type is CSV, then user can select encode type. */
function onChangeFileType(event)
{
    var fileType = $(event.target).val();
    var encode   = $('#encode');

    if(fileType === 'csv')
    {
        encode.removeAttr('disabled');
        return;
    }

    encode.val('utf-8');
    encode.attr('disabled', 'disabled');
}

function onChangeFileName(event)
{
    var objFileName = $(event.target);

    if(objFileName.val() == '')
    {
        objFileName.val('{$lang->file->untitled}');
        return;
    }
}
JAVASCRIPT
);

render();
