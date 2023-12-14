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

$this->loadModel('file');

/* Unset useless export type. */
if(isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], 'calendar')) unset($lang->exportTypeList['selected']);

/* Generate custom export fields. */
$hideExportRange     = isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], 'kanban');
$customExportRowList = array();
$isCustomExport      = (!empty($customExport) and !empty($allExportFields));
$showBizGuide        = $config->edition == 'open' && empty($config->{$this->moduleName}->closeBizGuide) ? true : false;
$bizGuideLink        = common::checkNotCN() ? 'https://www.zentao.pm/page/zentao-pricing.html' : 'https://www.zentao.net/page/enterprise.html';
$bizName             = $showBizGuide ? "<a href='{$bizGuideLink}' target='_blank' class='text-primary'>{$lang->bizName}</a>" : '';

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

    $defaultExportFields = implode(',', $selectedFields);

    $templates       = $this->file->getExportTemplate($app->moduleName);
    $templateList    = array();
    $templatePairs[] = $this->lang->file->defaultTPL;
    foreach($templates as $template)
    {
        $templatePairs[$template->id] = ($template->public ? "[{$this->lang->public}] " : '') . $template->title;
        $templateList[] = span(setID("template{$template->id}"), setClass('hidden'), set('data-public', $template->public), set('data-title', $template->title), $template->content);
    }

    /* Choose template. */
    $customExportRowList[] = formRow
    (
        setID('tplBox'),
        formGroup
        (
            set::label($lang->file->tplTitleAB),
            inputGroup
            (
                select(set::name('template'), set::items($templatePairs), on::change('setTemplate(e.target)'), set::required(true)),
                span
                (
                    setClass('input-group-addon'),
                    checkbox(setID('showCustomFieldsBox'), set::checked(false), on::change('setExportTPL'), $lang->file->setExportTPL)
                )
            ),
            $templateList ? $templateList : null
        )
    );

    /* Panel for customize template. */
    $customExportRowList[] = formRow
    (
        setClass('customFieldsBox'),
        formGroup
        (
            set::width('full'),
            panel
            (
                set::title($lang->file->exportFields),
                setClass('w-full'),
                control
                (
                    set::type('picker'),
                    set::name('exportFields[]'),
                    set::items($exportFieldPairs),
                    set::value($selectedFields),
                    set::multiple(true),
                    set::required(true)
                ),
                inputGroup
                (
                    $lang->file->tplTitle,
                    setClass('mt-4'),
                    input(set::name('title'), set::value($lang->file->defaultTPL)),
                    hasPriv('file', 'setPublic') ? div
                    (
                        setClass('input-group-addon'),
                        checkbox(set::name('public'), set::value(1), $lang->public)
                    ) : null,
                    btn(setClass('btn-link'), on::click('window.saveTemplate'), icon('save')),
                    btn(setClass('btn-link'), on::click('window.deleteTemplate'), icon('trash'))
                )
            )
        )
    );
}

formPanel
(
    css('.form-grid .form-label.required:after{content:""}'), // Remove required tag.
    css('.modal-content{padding-top: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; padding-bottom: 1.25rem;}'),
    setCssVar('--form-grid-label-width', '4rem'),
    set::target('_self'),
    set::actions(array()),
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
        set::label($lang->file->extension),
        set::control('select'),
        set::name('fileType'),
        set::items($lang->exportFileTypeList),
        on::change('onChangeFileType'),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->file->encoding),
        set::control('select'),
        set::name('encode'),
        set::items($config->charsets[$this->cookie->lang]),
        set::value($this->config->zin->lang == 'zh-cn' ? 'gbk' : 'utf-8'),
        set::required(true)
    ),
    /* Fields for KanBan. */
    formRow
    (
        $hideExportRange ? setClass('hidden') : null,
        formGroup
        (
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
    $customExportRowList,
    formRow
    (
        setClass('justify-center'),
        div
        (
            setClass('form-actions'),
            btn
            (
                set::btnType('submit'),
                set::type('primary'),
                $lang->export
            )
        )
    ),
    $showBizGuide ? formRow
    (
        setClass('justify-center bizGuideBox'),
        div
        (
            span
            (
                setClass('text-gray'),
                html(sprintf($lang->file->bizGuide, $bizName))
            ),
            a
            (
                setID('closeBizGuideButton'),
                setClass('btn btn-default ghost text-gray'),
                set::href('#'),
                icon(
                    'close',
                    setStyle('font-size', '12px')
                ),
                on::click('closeBizGuide')
            )
        )
    ) : null
);

set::title($lang->file->exportData);

h::js
(
<<<JAVASCRIPT
window.setDownloading = function(event)
{
    /* Doesn't support Opera, omit it. */
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;

    $.cookie.set('downloading', 0, {expires:config.cookieLife, path:config.webRoot});

    var time = setInterval(function()
    {
        if($.cookie.get('downloading') == 1)
        {
            $(event.target).closest('div.modal')[0].classList.remove('show');
            $.cookie.set('downloading', null, {expires:config.cookieLife, path:config.webRoot});
            clearInterval(time);
        }
    }, 300);

    return true;
}

/* If file type is CSV, then user can select encode type. */
window.onChangeFileType = function(event)
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

window.onChangeFileName = function(event)
{
    var objFileName = $(event.target);

    if(objFileName.val() == '')
    {
        objFileName.val('{$lang->file->untitled}');
        return;
    }
}

window.saveTemplate = function()
{
    var customFieldsBox = $('.customFieldsBox');
    var publicBox       = customFieldsBox.find('input[name="public"]');
    var title           = customFieldsBox.find('#title').val();
    var content         = customFieldsBox.find('#exportFields').val();
    var isPublic        = (publicBox.length > 0 && publicBox.prop('checked')) ? 1 : 0;
    if(!title || !content) return;

    saveTemplateLink = $.createLink('file', 'ajaxSaveTemplate', 'module={$this->moduleName}');
    $.post(saveTemplateLink, {title:title, content:content, public:isPublic}, function(data)
    {
        var defaultValue = $('#tplBox [name="template"]').val();
        $('#tplBox').html(data);
    });
};

window.deleteTemplate = function()
{
    var template   = $('#tplBox [name="template"]');
    var templateID = template.val();
    if(templateID == 0) return;

    deleteLink = $.createLink('file', 'ajaxDeleteTemplate', 'templateID=' + templateID);
    $.get(deleteLink, function()
    {
        template.find('option[value="'+ templateID +'"]').remove();
        setTemplate(template);
    });
};

window.setTemplate = function(obj)
{
    var templateID = $(obj).val();
    var template  =  $('#template' + templateID);
    var exportFields = template.length > 0 ? template.html() : '{$defaultExportFields}';
    exportFields = exportFields.split(',');

    const fieldsPicker = zui.Picker.query('div.modal-body form .form-group .panel .panel-body div');
    if(fieldsPicker && fieldsPicker.ref && fieldsPicker.ref.current)
    {
        fieldsPicker.ref.current.setState({value:exportFields});
    }

    var customFieldsBox = $('.customFieldsBox');
    customFieldsBox.find('input[name="public"]').prop('checked', template.data('public'));
    customFieldsBox.find('#title').val(template.data('title'));
};

window.setExportTPL = function()
{
    $('.customFieldsBox').toggleClass('hidden', !$('#showCustomFieldsBox').prop('checked'));
};
setExportTPL();

/* Auto select selected item for exportRange. */
if($('.dtable .dtable-header .has-checkbox').length > 0)
{
    const dtable = zui.DTable.query($('.dtable .dtable-header .has-checkbox').closest('.dtable')[0]);
    const checkedList = dtable.$.getChecks();
    if(checkedList.length)
    {
        if(window.config.currentModule == 'testcase') checkedList.forEach(function(item, index){ checkedList[index] = item.replace('case_', '');});
        if(window.config.currentModule == 'product') checkedList.forEach(function(item, index){if(item.indexOf('-')) checkedList[index] = item.substr(item.indexOf('-') + 1);});

        $('#exportType').val('selected');
        $.cookie.set('checkedItem', checkedList.join(','), {expires:config.cookieLife, path:config.webRoot});
    }
}


/**
 * 关闭升级到企业版提示。
 * Close the biz guide.
 *
 * @access public
 * @return void
 */
window.closeBizGuide = function()
{
    let closeBizGuideLink = $.createLink('file', 'ajaxcloseBizGuide', 'module={$this->moduleName}');
    $.get(closeBizGuideLink);
    $('.bizGuideBox').remove();
}

JAVASCRIPT
);

render();
