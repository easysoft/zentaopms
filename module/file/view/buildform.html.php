<div id='fileform'>
  <?php 
  /* Define the html code of a file row. */
  $fileRow = <<<EOT
  <div class='fileBox' id='fileBox\$i'>
    <span class='icon'><span class='icon-file'></span>{$lang->file->common}<span class='fileID'>\$i</span></span>
    <input type='file' name='files[]' class='fileControl'  tabindex='-1' />
    <label tabindex='-1' class='fileLabel'>{$lang->file->label}</label>
    <input type='text' name='labels[]' class='text-3' tabindex='-1' /> 
    <input type='button' onclick='addFile(this)'  class='icon-add' value='&nbsp;'></input>
    <input type='button' onclick='delFile(this)'  class='icon-delete' value='&nbsp;'></input>
  </div>
EOT;
  for($i = 1; $i <= $fileCount; $i ++) echo str_replace('$i', $i, $fileRow);
  printf($lang->file->maxUploadSize, ini_get('upload_max_filesize'));
?>
</div>
<script language='javascript'>
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
    $(clickedButton).parent().after(fileRow);

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
    $(clickedButton).parent().remove();
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
