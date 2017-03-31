<?php
/**
 * The export view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php $this->app->loadLang('file');?>
<script>
function setDownloading()
{
    if($.browser.opera) return true;   // Opera don't support, omit it.

    $.cookie('downloading', 0);
    time = setInterval("closeWindow()", 300);
    return true;
}

function closeWindow()
{
    if($.cookie('downloading') == 1)
    {
        parent.$.closeModal();
        $.cookie('downloading', null);
        clearInterval(time);
    }
}
function switchEncode(fileType)
{
    $('#encode').removeAttr('disabled');
    if(fileType != 'csv')
    {
        $('#encode').val('utf-8');
        $('#encode').attr('disabled', 'disabled');
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
        $("#tplBox #template").chosen(defaultChosenOptions).on('chosen:showing_dropdown', function()
        {
            var $this = $(this);
            var $chosen = $this.next('.chosen-container').removeClass('chosen-up');
            var $drop = $chosen.find('.chosen-drop');
            $chosen.toggleClass('chosen-up', $drop.height() + $drop.offset().top - $(document).scrollTop() > $(window).height());
        });
        $inputGroup.find('#title').val('');
    });
}

/* Set template. */
function setTemplate(templateID)
{
    $template    =  $('#tplBox #template' + templateID);
    exportFields = $template.size() > 0 ? $template.html() : defaultExportFields;
    exportFields = exportFields.split(',');
    $('#exportFields').val('');
    for(i in exportFields) $('#exportFields').find('option[value="' + exportFields[i] + '"]').attr('selected', 'selected');
    $('#exportFields').trigger("chosen:updated");
}

/* Delete template. */
function deleteTemplate()
{
    templateID = $('#tplBox #template').val();
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
    $('#customFields').toggle();
    $('.mb-150px').toggle();
}

$(document).ready(function()
{
    $('#fileType').change();
    <?php if($this->cookie->checkedItem):?>
    setTimeout(function()
    {
        $('#exportType').val('selected');
    }, 150);
    <?php endif;?>
});
</script>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['export']);?></span>
    <strong><?php echo $lang->export;?></strong>
  </div>
</div>
<?php $isCustomExport = (!empty($customExport) and !empty($allExportFields));?>
<form class='form-condensed' method='post' target='hiddenwin' style='padding: 40px 1% 50px'>
  <table class='w-p100 table-fixed'>
    <tr>
      <td>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->setFileName;?></span>
          <?php echo html::input('fileName', '', "class='form-control' autocomplete='off'");?>
        </div>
      </td>
      <td class='w-60px'>
        <?php echo html::select('fileType',   $lang->exportFileTypeList, '', 'onchange=switchEncode(this.value) class="form-control"');?>
      </td>
      <td class='w-80px'>
        <?php echo html::select('encode',     $config->charsets[$this->cookie->lang], 'utf-8', key($lang->exportFileTypeList) == 'csv' ? "class='form-control'" : "class='form-control'");?>
      </td>
      <td class='w-90px'>
        <?php echo html::select('exportType', $lang->exportTypeList, 'all', "class='form-control'");?>
      </td>
      <?php if($isCustomExport):?>
      <td class='w-110px' style='overflow:visible'>
        <span id='tplBox'><?php echo $this->fetch('file', 'buildExportTPL', 'module=' . $this->moduleName);?></span>
      </td>
      <?php endif?>
      <td style='width:<?php echo $isCustomExport ? '130px' : '70px'?>'>
        <div class='input-group'>
          <?php echo html::submitButton($lang->export, "onclick='setDownloading();' ");?>
          <?php if($isCustomExport):?>
          <button type='button' onclick='setExportTPL()' class='btn'><?php echo $lang->file->setExportTPL?></button>
          <?php endif;?>
        </div>
      </td>
    </tr>
  </table>
  <?php if($isCustomExport):?>
  <?php
  $allExportFields  = explode(',', $allExportFields);
  $selectedFields   = array();
  $exportFieldPairs = array();
  $moduleName = $this->moduleName;
  $moduleLang = $lang->$moduleName;
  foreach($allExportFields as $key => $field)
  {
      $field                    = trim($field);
      $selectedFields[]         = $field;
      $exportFieldPairs[$field] = isset($moduleLang->$field) ? $moduleLang->$field : (isset($lang->$field) ? $lang->$field : $field);
  }
  ?>
  <div class='mb-150px' style='margin-bottom:245px'></div>
  <div class='panel' id='customFields' style='margin-bottom:150px;display:none'>
    <div class='panel-heading'><strong><?php echo $lang->file->exportFields?></strong></div>
    <div class='panel-body'>
      <p><?php echo html::select('exportFields[]', $exportFieldPairs, $selectedFields, "class='form-control chosen' multiple")?></p>
      <div>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->file->tplTitle;?></span>
          <?php echo html::input('title', '', "class='form-control' autocomplete='off'")?>
          <?php if(common::hasPriv('file', 'setPublic')):?>
          <span class='input-group-addon'><?php echo html::checkbox('public', array(1 => $lang->public));?></span>
          <?php endif?>
          <span class='input-group-btn'><button id='saveTpl' type='button' onclick='saveTemplate()' class='btn btn-primary'><?php echo $lang->save?></button></span>
          <span class='input-group-btn'><button type='button' onclick='deleteTemplate()' class='btn'><?php echo $lang->delete?></button></span>
        </div>
      </div>
    </div>
  </div>
  <?php js::set('defaultExportFields', join(',', $selectedFields));?>
  <?php endif?>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
