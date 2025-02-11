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
if(!isset($hideExportRange)) $hideExportRange = isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], 'kanban');
$customExportRowList = array();
$isCustomExport      = (!empty($customExport) and !empty($allExportFields));
$showBizGuide        = $config->edition == 'open' && empty($config->{$this->moduleName}->closeBizGuide) ? true : false;
$bizGuideLink        = common::checkNotCN() ? 'https://www.zentao.pm/page/zentao-pricing.html' : 'https://www.zentao.net/page/enterprise.html';
$bizName             = $showBizGuide ? "<a href='{$bizGuideLink}' target='_blank' class='text-primary'>{$lang->bizName}</a>" : '';
$defaultExportFields = '';

if($isCustomExport)
{
    if(is_string($allExportFields)) $allExportFields = explode(',', $allExportFields);
    $hasDefaultField  = isset($selectedFields);
    $selectedFields   = $hasDefaultField ? explode(',', $selectedFields) : array();
    $moduleName       = $this->moduleName;
    $moduleLang       = $moduleName == 'caselib' ? $lang->testcase : $lang->$moduleName;

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
                picker(set::name('template'), set::items($templatePairs), on::change('setTemplate(e.target)'), set::required(true)),
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
    $customExportRowList[] = div
    (
        setClass('customFieldsBox border p-4'),
        formRow
        (
            formGroup
            (
                set::label($lang->file->exportFields),
                control
                (
                    set::type('picker'),
                    set::name('exportFields[]'),
                    set::items($exportFieldPairs),
                    set::value($selectedFields),
                    set::multiple(true),
                    set::required(true)
                )
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->file->tplTitle),
                set::className('mt-4'),
                inputGroup
                (
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

if($app->rawModule == 'epic')        $lang->exportTypeList['all'] = $lang->ERCommon . ' - ' . $lang->exportTypeList['all'];
if($app->rawModule == 'requirement') $lang->exportTypeList['all'] = $lang->URCommon . ' - ' . $lang->exportTypeList['all'];

$isNotZh = strpos($app->getClientLang(), 'zh-') === false;
formPanel
(
    setID('exportPanel'),
    pageCSS('.form-horz .form-label.required:after{content:""}'), // Remove required tag.
    pageCSS('.modal-content{padding-top: 0.5rem; padding-left: 0.75rem; padding-right: 0.75rem; padding-bottom: 1.25rem;}'),
    $isNotZh ? pageCSS('#exportPanel .form-label{width: 70px}') : null,
    $isNotZh ? pageCSS('#exportPanel .form-group{padding-left: 70px}') : null,
    $isNotZh ? pageCSS('#exportPanel .customFieldsBox .form-label{width: 100px}') : null,
    $isNotZh ? pageCSS('#exportPanel .customFieldsBox .form-group{padding-left: 100px}') : null,
    setCssVar('--form-horz-label-width', '4rem'),
    set::target('_self'),
    set::actions(array()),
    on::submit('setDownloading'),
    formGroup
    (
        set::width('full'),
        set::label($lang->file->fileName),
        set::name('fileName'),
        set::value(isset($fileName) ? $fileName : $lang->file->untitled),
        on::change('onChangeFileName'),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->file->extension),
        set::name('fileType'),
        set::items($lang->exportFileTypeList),
        on::change('onChangeFileType'),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->file->encoding),
        set::control('picker'),
        set::name('encode'),
        set::items($config->charsets[$this->cookie->lang]),
        set::value($this->config->zin->lang == 'zh-cn' ? 'gbk' : 'utf-8'),
        set::required(true)
    ),
    /* Fields for KanBan. */
    formRow
    (
        $hideExportRange ? setClass('hidden') : null,
        setClass('exportRange'),
        formGroup
        (
            set::label($lang->file->exportRange),
            set::control('picker'),
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
            setClass('form-actions col text-center'),
            div
            (
                btn
                (
                    on::click()->do("$(target).parent().addClass('disabled');$(target).parent().attr('disabled');$(target).closest('.form-actions').append('<span class=\"text-gray\">{$lang->file->waitDownloadTip}</span>');"),
                    set::btnType('submit'),
                    set::type('primary'),
                    $lang->export
                )
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
    $('[name=encode]').removeAttr('disabled');

    /* Doesn't support Opera, omit it. */
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;

    $.cookie.set('downloading', 0, {expires:config.cookieLife, path:config.webRoot});

    var time = setInterval(function()
    {
        if($.cookie.get('downloading') == 1)
        {
            const modal = zui.Modal.query(event.target);
            if(modal) modal.hide();
            if(!modal) parent.$.closeModal();
            $.cookie.set('downloading', null, {expires:config.cookieLife, path:config.webRoot});
            clearInterval(time);
        }
    }, 300);

    return true;
}

/* If file type is CSV, then user can select encode type. */
window.onChangeFileType = function(event)
{
    if($(event.target).attr('name') != 'fileType') return;

    var fileType     = $(event.target).val();
    var encodePicker = $('[name="encode"]').zui('picker');

    if(fileType === 'csv')
    {
        encodePicker.render({disabled: false});
        return;
    }

    encodePicker.$.setValue('utf-8');
    encodePicker.render({disabled: true});

    $('#tplBox').toggleClass('hidden', fileType == 'word');
    $('.customFieldsBox').toggleClass('hidden', fileType == 'word' || !$('#showCustomFieldsBox').prop('checked'));
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
window.waitDom('[name=fileType]', function(){ $('[name=fileType]').trigger('change');})

window.saveTemplate = function()
{
    var customFieldsBox = $('.customFieldsBox');
    var publicBox       = customFieldsBox.find('input[name="public"]');
    var title           = customFieldsBox.find('[name="title"]').val();
    var content         = customFieldsBox.find('[name^="exportFields"]').val();
    var isPublic        = (publicBox.length > 0 && publicBox.prop('checked')) ? 1 : 0;
    if(!title || !content) return;

    const saveTemplateLink = $.createLink('file', 'ajaxSaveTemplate', 'module={$this->moduleName}');
    $.post(saveTemplateLink, {title:title, content:content.join(','), public:isPublic}, function(data)
    {
        if(data.indexOf('alert') == -1)
        {
            $('#tplBox').html(data);
            customFieldsBox.find('[name=title]').val(title);
        }
        else
        {
            data = JSON.parse(data);
            zui.Modal.alert(data.alert);
        }
    });
};

window.deleteTemplate = function()
{
    var template   = $('#tplBox [name="template"]');
    var templateID = template.val();
    if(templateID == 0) return;

    const deleteLink = $.createLink('file', 'ajaxDeleteTemplate', 'templateID=' + templateID);
    $.get(deleteLink, function()
    {
        var templatePicker = template.zui('picker');
        var newItems       = templatePicker.options.items.filter(item => item.value != templateID);
        templatePicker.render({items: newItems});
        templatePicker.$.setValue(newItems.length > 0 ? newItems[0].value : '');
    });
};

window.setTemplate = function(obj)
{
    var templateID = $(obj).val();
    var template  =  $('#template' + templateID);
    var exportFields = template.length > 0 ? template.html() : '{$defaultExportFields}';
    exportFields = exportFields.split(',');

    const customFieldsBox = $('.customFieldsBox');
    customFieldsBox.find('[name^="exportFields"]').zui('picker').$.setValue(exportFields);
    customFieldsBox.find('input[name="public"]').prop('checked', template.data('public'));
    customFieldsBox.find('[name="title"]').val(templateID != '0' ? template.data('title') : '{$lang->file->defaultTPL}');
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
    let checkedList = dtable ? dtable.$.getChecks() : [];
    if(checkedList.length)
    {
        if(window.config.currentModule == 'testcase') checkedList.forEach(function(item, index){ checkedList[index] = item.replace('case_', '');});
        if(window.config.currentModule == 'product') checkedList.forEach(function(item, index){if(item.indexOf('-')) checkedList[index] = item.substr(item.indexOf('-') + 1);});
        if(window.config.currentModule == 'testtask' && window.config.currentMethod == 'cases')
        {
            let caseIDList = [];
            checkedList.forEach(function(item, index) {
                let testrun = dtable.options.data.find(obj => obj.id == item);
                let caseID  = testrun ? testrun.case : null;

                if(caseID) caseIDList.push(caseID);
            });
            checkedList = caseIDList;
        }

        waitDom('#exportPanel [name=exportType]', function(){ $('#exportPanel [name=exportType]').zui('picker').$.setValue('selected');});
        $.cookie.set('checkedItem', checkedList.join(','), {expires:config.cookieLife, path:config.webRoot});
    }
    else
    {
        $.cookie.set('checkedItem', '', {expires:config.cookieLife, path:config.webRoot});
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
