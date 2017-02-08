<style>
.fileBox {margin-bottom: 10px; width: 100%}
table.fileBox td {padding: 0!important}
.fileBox .input-control > input[type='file'] {width: 100%; height: 100%; height: 26px; line-height: 26px; border: none; position: relative;}
.fileBox td .btn {border-radius: 0; border-left: none}
.file-wrapper.form-control {border-right: 0}
.file-wrapper.form-control .fileControl {width:100%;}
@-moz-document url-prefix(){.file-wrapper.form-control .fileControl {margin-top:-3px;}}
</style>
<div id='fileform'>
  <?php 
  js::set('dangerFiles', $this->config->file->dangers);
  /* Define the html code of a file row. */
  $fileRow = <<<EOT
  <table class='fileBox' id='fileBox\$i'>
    <tr>
      <td class='w-p45'><div class='form-control file-wrapper'><input type='file' name='{$filesName}[]' class='fileControl'  tabindex='-1' onchange='checkSizeAndType(this)'/></div></td>
      <td class=''><input type='text' name='{$labelsName}[]' class='form-control' placeholder='{$lang->file->label}' tabindex='-1' /></td>
      <td class='w-30px'><a href='javascript:void(0);' onclick='addFile(this)' class='btn btn-block'><i class='icon-plus'></i></a></td>
      <td class='w-30px'><a href='javascript:void(0);' onclick='delFile(this)' class='btn btn-block'><i class='icon-remove'></i></a></td>
    </tr>
  </table>
EOT;
  for($i = 1; $i <= $fileCount; $i ++) echo str_replace('$i', $i, $fileRow);
?>
</div>

<script language='javascript'>
$(function()
{
    var maxUploadInfo = maxFilesize();
    parentTag = $('#fileform').parent();
    if(parentTag.get(0).tagName == 'TD')
    {
        parentTag.parent().find('th').append(maxUploadInfo); 
    }
    if(parentTag.get(0).tagName == 'FIELDSET')
    {
        parentTag.find('legend').append(maxUploadInfo);
    }
});

/**
 * Check file size and type.
 * 
 * @param  obj $obj 
 * @access public
 * @return void
 */
function checkSizeAndType(obj)
{
    if(typeof($(obj)[0].files) != 'undefined')
    {
        var maxUploadInfo = '<?php echo strtoupper(ini_get('upload_max_filesize'));?>';
        var sizeType = {'K': 1024, 'M': 1024 * 1024, 'G': 1024 * 1024 * 1024};
        var unit = maxUploadInfo.replace(/\d+/, '');
        var maxUploadSize = maxUploadInfo.replace(unit,'') * sizeType[unit];
        var fileSize = 0;
        $(obj).closest('#fileform').find(':file').each(function()
        {
            /* Check file type. */
            fileName = $(this)[0].files[0].name;
            dotPos   = fileName.lastIndexOf('.');
            fileType = fileName.substring(dotPos + 1);
            if((',' + dangerFiles + ',').indexOf((',' + fileType + ',')) != -1) alert('<?php echo $lang->file->dangerFile?>');

            if($(this).val()) fileSize += $(this)[0].files[0].size;
        })
        if(fileSize > maxUploadSize) alert('<?php echo $lang->file->errorFileSize?>');//Check file size.
    }
}

/**
 * Show the upload max filesize of config.  
 */
function maxFilesize(){return "(<?php printf($lang->file->maxUploadSize, ini_get('upload_max_filesize'));?>)";}

/**
 * Set the width of the file form.
 * 
 * @param  float  $percent 
 * @access public
 * @return void
 */
function setFileFormWidth(percent)
{
    totalWidth = Math.round($('#fileform').parent().width() * percent);
    titleWidth = totalWidth - $('.fileControl').width() - $('.fileLabel').width() - $('.icon').width();
    if($.browser.mozilla) titleWidth  -= 8;
    if(!$.browser.mozilla) titleWidth -= 12;
    $('#fileform .text-3').css('width', titleWidth + 'px');
};

/**
 * Add a file input control.
 * 
 * @param  object $clickedButton 
 * @access public
 * @return void
 */
function addFile(clickedButton)
{
    fileRow = <?php echo json_encode($fileRow);?>;
    fileRow = fileRow.replace('$i', $('.fileID').size() + 1);

    /* Get files and labels name.*/
    filesName  = $(clickedButton).closest('tr').find('input[type="file"]').attr('name');
    labelsName = $(clickedButton).closest('tr').find('input[type="text"]').attr('name');

    /* Add file input control and set files and labels name in it.*/
    $fileBox = $(clickedButton).closest('.fileBox').after(fileRow).next('.fileBox');
    $fileBox.find('input[type="file"]').attr('name', filesName);
    $fileBox.find('input[type="text"]').attr('name', labelsName);

    setFileFormWidth(<?php echo $percent;?>);
    updateID();
}

/**
 * Delete a file input control.
 * 
 * @param  object $clickedButton 
 * @access public
 * @return void
 */
function delFile(clickedButton)
{
    if($('.fileBox').size() == 1) return;
    $(clickedButton).closest('.fileBox').remove();
    updateID();
}

/**
 * Update the file id labels.
 * 
 * @access public
 * @return void
 */
function updateID()
{
    i = 1;
    $('.fileID').each(function(){$(this).html(i ++)});
}

$(function(){setFileFormWidth(<?php echo $percent;?>)});
</script>
