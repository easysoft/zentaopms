<?php
/**
 * The export view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/chosen.html.php';?>
<?php $this->app->loadLang('file');?>
<style>
#customFields .panel {border: 1px solid #ddd; background: #fafafa; margin: 0;}
#customFields .panel-actions {padding: 0;}
#customFields .panel {position: relative;}
#customFields .panel:before, #customFields .panel:after {content: ' '; display: block; width: 0; height: 0; border-style: solid; border-width: 0 10px 10px 10px; border-color: transparent transparent #f1f1f1 transparent; position: absolute; left: 315px; top: -9px;}
#customFields .panel:before {border-color: transparent transparent #ddd transparent; top: -10px;}

#mainContent .c-name {width:120px;}
#mainContent .c-fileName {width:300px;}
</style>
<script>
function setDownloading()
{
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;   // Opera don't support, omit it.

    var $fileName = $('#fileName');
    if($fileName.val() === '') $fileName.val('<?php echo $lang->file->untitled;?>');

    $.cookie('downloading', 0);
    time = setInterval("closeWindow()", 300);
    $('#mainContent').addClass('loading');
    return true;
}

function closeWindow()
{
    if($.cookie('downloading') == 1)
    {
        $('#mainContent').removeClass('loading');
        parent.$.closeModal();
        $.cookie('downloading', null);
        clearInterval(time);
    }
}
function switchEncode(fileType)
{
    var $encode = $('#encode');
    if(fileType != 'csv') $encode.val('utf-8').attr('disabled', 'disabled');
    else $encode.removeAttr('disabled');
    $encode.trigger('chosen:updated');

    if(fileType == 'word')
    {
        $('#tplBox').closest('tr').addClass('hidden');
        $('#customFields').addClass('hidden');
    }
    else
    {
        $('#tplBox').closest('tr').removeClass('hidden');
    }
}

function saveTemplate()
{
    var $inputGroup = $('#customFields div.input-group');
    var $publicBox  = $inputGroup.find('input[id^="public"]');
    var title       = $inputGroup.find('#title').val();
    var content     = $('#customFields #exportFields').val();
    var isPublic    = ($publicBox.size() > 0 && $publicBox.prop('checked')) ? $publicBox.val() : 0;
    if(!title || !content) return;
    saveTemplateLink = '<?php echo $this->createLink('file', 'ajaxSaveTemplate', 'module=' . $this->moduleName);?>';
    $.post(saveTemplateLink, {title:title, content:content, public:isPublic}, function(data)
    {
        var defaultValue = $('#tplBox #template').val();
        $('#tplBox').html(data);
        if(data.indexOf('alert') >= 0) $('#tplBox #template').val(defaultValue);
        $("#tplBox #template").chosen().on('chosen:showing_dropdown', function()
        {
            var $this = $(this);
            var $chosen = $this.next('.chosen-container').removeClass('chosen-up');
            var $drop = $chosen.find('.chosen-drop');
            $chosen.toggleClass('chosen-up', $drop.height() + $drop.offset().top - $(document).scrollTop() > $(window).height());
        });
        $inputGroup.find('#title').val(title);
    });
}

/* Set template. */
function setTemplate(templateID)
{
    var $template=  $('#tplBox #template' + templateID);
    var exportFields = $template.size() > 0 ? $template.html() : defaultExportFields;
    exportFields = exportFields.split(',');
    $('#exportFields').val('');

    var optionHtml = '';
    for(i in exportFields)
    {
        $selectedOption = $('#exportFields').find('option[value="' + exportFields[i] + '"]');
        optionHtml += $selectedOption.attr('selected', 'selected').prop('outerHTML');
        $selectedOption.remove();
    }
    $('#exportFields option').each(function(){optionHtml += $(this).removeAttr('selected').prop('outerHTML')});
    $('#exportFields').html(optionHtml).trigger("chosen:updated");
}

/* Delete template. */
function deleteTemplate()
{
    var templateID = $('#tplBox #template').val();
    if(templateID == 0) return;
    hiddenwin.location.href = createLink('file', 'ajaxDeleteTemplate', 'templateID=' + templateID);
    $('#tplBox #template').find('option[value="'+ templateID +'"]').remove();
    $('#tplBox #template').trigger("chosen:updated");
    $('#tplBox #template').change();
}

/**
 * Toggle export template box.
 *
 * @access public
 * @return void
 */
function setExportTPL()
{
    $('#customFields').toggleClass('hidden');
}

/**
 * Set whether part download.
 *
 * @param  input target
 * @access public
 * @return void
 */
function setPart(target)
{
    if($(target).prop("checked"))
    {
        $("#submit").attr("onclick", 'setPartDownloading();');
    }
    else
    {
        $("#submit").attr("onclick", 'setDownloading();');
    }
}

var partQueue = new Array();

/**
 * Set part down and begin the first part down.
 *
 * @access public
 * @return void
 */
function setPartDownloading()
{
    var partNum = 10000;
    var total   = $('.pager', window.parent.document).data('rec-total');
    for(var i = 0; i < total; i = i + partNum)
    {
        partQueue.push(i + ',' + partNum);
    }
    $.cookie('downloading', 0);
    $('#mainContent').addClass('loading');
    $("#limit").val(partQueue.shift());
    $("#submit").attr("onclick", 'startPartDownloading();');
    time = setInterval(function()
    {
        startPartDownloading();
    }, 1000);
}

/**
 * Start follow-up part down.
 *
 * @access public
 * @return void
 */
function startPartDownloading()
{
    if($.cookie('downloading') == 1)
    {
        var limit = partQueue.shift();
        if(limit)
        {
            $.cookie('downloading', 0);
            $("#limit").val(limit);
            $("#submit").attr("disabled", false).click();
        }
        else
        {
            $('#mainContent').removeClass('loading');
            parent.$.closeModal();
            $.cookie('downloading', null);
            clearInterval(time);
        }
    }
}

$(document).ready(function()
{
    $(document).on('change', '#template', function()
    {
        $('#title').val($(this).find('option:selected').text());
    });

    $('#fileType').change();
    setTimeout(function()
    {
        if($.cookie('checkedItem') !== '') $('#exportType').val('selected').trigger('chosen:updated');
    }, 150);

    if($('#customFields #exportFields').length > 0)
    {
        $('#customFields #exportFields').change(function()
        {
            setTimeout(function()
            {
                var optionHtml = '';
                var selected   = ',';
                $('#customFields #exportFields_chosen .chosen-choices li.search-choice').each(function(i)
                {
                    index = $(this).find('.search-choice-close').data('option-array-index');
                    optionHtml += $('#exportFields option').eq(index).attr('selected', 'selected').prop("outerHTML");
                    $(this).find('.search-choice-close').attr('data-option-array-index', i);
                    selected += index + ',';
                })
                $('#exportFields option').each(function(i)
                {
                    if(selected.indexOf(',' + i + ',') < 0) optionHtml += $(this).removeAttr('selected').prop("outerHTML");
                })
                $('#exportFields').html(optionHtml).trigger('chosen:updated');
            }, 100);
        })
    }
});
</script>
<?php
$isCustomExport = (!empty($customExport) and !empty($allExportFields));
if($isCustomExport)
{
    $allExportFields  = explode(',', $allExportFields);
    $hasDefaultField  = isset($selectedFields);
    $selectedFields   = $hasDefaultField ? explode(',', $selectedFields) : array();
    $exportFieldPairs = array();
    $moduleName = $this->moduleName;
    $moduleLang = $lang->$moduleName;
    foreach($allExportFields as $key => $field)
    {
        $field                    = trim($field);
        $exportFieldPairs[$field] = isset($moduleLang->$field) ? $moduleLang->$field : (isset($lang->$field) ? $lang->$field : $field);
        if(!is_string($exportFieldPairs[$field])) $exportFieldPairs[$field] = $field;
        if(!$hasDefaultField)$selectedFields[] = $field;
    }
    js::set('defaultExportFields', join(',', $selectedFields));
}
?>
<main id="main">
  <div class="container">
    <div id="mainContent" class='main-content load-indicator'>
      <div class='main-header'>
        <h2><?php echo $lang->export;?></h2>
      </div>
      <form class='main-form' method='post' target='hiddenwin'>
        <table class="table table-form">
          <tbody>
            <tr>
              <th class='c-name'><?php echo $lang->file->fileName;?></th>
              <td class="c-fileName"><?php echo html::input('fileName', isset($fileName) ? $fileName : '', "class='form-control' autofocus placeholder='{$lang->file->untitled}'");?></td>
              <td></td>
            </tr>
            <tr>
              <th><?php echo $lang->file->extension;?></th>
              <td><?php echo html::select('fileType', $lang->exportFileTypeList, '', 'onchange=switchEncode(this.value) class="form-control chosen" data-drop_direction="down"');?></td>
            </tr>
            <tr>
              <th><?php echo $lang->file->encoding;?></th>
              <td><?php echo html::select('encode', $config->charsets[$this->cookie->lang], 'utf-8', "class='form-control chosen'");?></td>
            </tr>
            <?php $hide = isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'kanban') !== false ? 'style="display:none"' : '';?>
            <tr <?php echo $hide;?>>
              <th><?php echo $lang->file->exportRange;?></th>
              <td>
                <?php if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'calendar') !== false) unset($lang->exportTypeList['selected']);?>
                <?php echo html::select('exportType', $lang->exportTypeList, 'all', "class='form-control chosen'");?>
              </td>
              <td class='checkbox part hidden'>
                <?php echo html::checkbox('part', array( 1 => $lang->file->batchExport), '', "onclick='setPart(this);'");?>
                <?php echo html::hidden('limit', '');?>
              </td>
            </tr>
            <?php if($isCustomExport):?>
            <tr>
              <th><?php echo $lang->file->tplTitleAB;?></th>
              <td id="tplBox"><?php echo $this->fetch('file', 'buildExportTPL', 'module=' . $this->moduleName);?></td>
              <td>
                <button type='button' onclick='setExportTPL()' class='btn'><?php echo $lang->file->setExportTPL?></button>
              </td>
            </tr>
            <tr id='customFields' class="hidden">
              <th></th>
              <td colspan="2">
                <div class='panel'>
                  <div class='panel-heading'>
                    <strong><?php echo $lang->file->exportFields?></strong>
                    <div class="panel-actions btn-toolbar">
                      <button type="button" class="btn btn-link" onclick="setExportTPL()"><i class="icon icon-close icon-sm muted"></i></button>
                    </div>
                  </div>
                  <div class='panel-body'>
                    <p><?php echo html::select('exportFields[]', $exportFieldPairs, $selectedFields, "class='form-control chosen' multiple")?></p>
                    <div>
                      <div class='input-group'>
                        <span class='input-group-addon'><?php echo $lang->file->tplTitle;?></span>
                        <?php echo html::input('title', $lang->file->defaultTPL, "class='form-control'")?>
                        <?php if(common::hasPriv('file', 'setPublic')):?>
                        <span class='input-group-addon'><?php echo html::checkbox('public', array(1 => $lang->public));?></span>
                        <?php endif?>
                        <span class='input-group-btn'><button id='saveTpl' type='button' onclick='saveTemplate()' class='btn btn-primary'><?php echo $lang->save?></button></span>
                        <span class='input-group-btn'><button type='button' onclick='deleteTemplate()' class='btn'><?php echo $lang->delete?></button></span>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <?php endif?>
            <tr>
              <th></th>
              <td class='text-center'>
                <?php echo html::submitButton($lang->export, "onclick='setDownloading();'", 'btn btn-primary');?>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</main>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
