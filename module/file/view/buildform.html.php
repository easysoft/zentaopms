<div id='fileform'>
  <?php 
  /* Define the html code of a file row. */
  $fileRow = <<<EOT
  <div class='fileBox input-group' id='fileBox\$i'>
    <span class='input-control w-p45'><input type='file' name='files[]' class='fileControl'  tabindex='-1' /></span>
    <span class="input-group-addon">{$lang->file->label}</span>
    <input type='text' name='labels[]' class='form-control' placeholder='{$lang->file->label}' tabindex='-1' />
    <span class='input-group-btn'>
      <a href='javascript:void();' onclick='addFile(this)' class='btn'><i class='icon-plus'></i></a>
    </span>
    <span class='input-group-btn'>
      <a href='javascript:void();' onclick='delFile(this)' class='btn'><i class='icon-remove'></i></a>
    </span>
  </div>
EOT;
  for($i = 1; $i <= $fileCount; $i ++) echo str_replace('$i', $i, $fileRow);
?>
</div>

<?php if(!$writeable):?>
<div class='text-danger help-block'> <?php echo $this->lang->file->errorUnwritable;?> </div>
<?php else:?>
<div class="file-form">
  <?php for($i = 0; $i < $fileCount; $i ++):?>
  <div class='form-group clearfix'>
    <div class='col-sm-5' style='padding-left:0px'><input type='file' class='form-control' name='files[]' id="file<?php echo $i;?>"  tabindex='-1' /></div>
    <div class='col-sm-7' style='padding-right:0px'><input type='text' id='label<?php echo $i;?>' name='labels[]' class='form-control' tabindex='-1' placeholder='<?php echo $lang->file->label;?>'/></div>
  </div>
  <?php endfor;?>
</div>
<?php endif;?>


<script language='javascript'>
$(function()
{
    var maxUploadInfo = maxFilesize();
    parentTag = $('#fileform').parent();
    if(parentTag.attr('tagName') == 'TD') parentTag.parent().find('th').append(maxUploadInfo); 
    if(parentTag.attr('tagName') == 'FIELDSET') parentTag.find('legend').append(maxUploadInfo);
});

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
