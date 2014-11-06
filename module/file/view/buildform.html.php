<style>
.fileBox {margin-bottom: 10px; width: 100%}
table.fileBox td {padding: 0!important}
.fileBox .input-control > input[type='file'] {width: 100%; height: 100%; height: 26px; line-height: 26px; border: none; position: relative;}
.fileBox td .btn {border-radius: 0; border-left: none}
.file-wrapper.form-control {border-right: 0}
</style>
<div id='fileform'>
  <?php 
  /* Define the html code of a file row. */
  $fileRow = <<<EOT
  <table class='fileBox' id='fileBox\$i'>
    <tr>
    <td class='w-p45'><div class='form-control file-wrapper'><input type='file' name='files[]' class='fileControl'  tabindex='-1' onchange='checkSize(this)'/></div></td>
      <td class=''><input type='text' name='labels[]' class='form-control' placeholder='{$lang->file->label}' tabindex='-1' /></td>
      <td class='w-30px'><a href='javascript:void();' onclick='addFile(this)' class='btn btn-block'><i class='icon-plus'></i></a></td>
      <td class='w-30px'><a href='javascript:void();' onclick='delFile(this)' class='btn btn-block'><i class='icon-remove'></i></a></td>
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
 * Check file size.
 * 
 * @param  obj $obj 
 * @access public
 * @return void
 */
function checkSize(obj)
{
    if(typeof($(obj)[0].files) != 'undefined')
    {
        var maxUploadInfo = '<?php echo strtoupper(ini_get('upload_max_filesize'));?>';
        var sizeType = {'K': 1024, 'M': 1024 * 1024, 'G': 1024 * 1024 * 1024};
        var unit = maxUploadInfo.replace(/\d+/, '');
        var maxUploadSize = maxUploadInfo.replace(unit,'') * sizeType[unit];
        var fileSize = 0;
        $(obj).parents('#fileform').find(':file').each(function()
        {
            if($(this).val()) fileSize += $(this)[0].files[0].size;
        })
        if(fileSize > maxUploadSize) alert('<?php echo $lang->file->errorFileSize?>');
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
    $(clickedButton).closest('.fileBox').after(fileRow);

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
