<?php
$sessionString  = $config->requestType == 'PATH_INFO' ? '?' : '&';
$sessionString .= session_name() . '=' . session_id();
?>
<style>.button-c {padding:1px}</style>
<script language='Javascript'>
$(function(){
     $(".edit").colorbox({width:400, height:230, iframe:true, transition:'none', scrolling:true});
})

/* Delete a file. */
function deleteFile(fileID)
{
    if(!fileID) return;
    hiddenwin.location.href =createLink('file', 'delete', 'fileID=' + fileID);
}
/* Download a file, append the mouse to the link. Thus we call decide to open the file in browser no download it. */
function downloadFile(fileID)
{
    if(!fileID) return;
    var sessionString = '<?php echo $sessionString;?>';
    var url = createLink('file', 'download', 'fileID=' + fileID + '&mouse=left') + sessionString;
    window.open(url, '_blank');
    return false;
}
</script>
<?php if($fieldset == 'true'):?>
<fieldset>
  <legend><?php echo $lang->file->common;?></legend>
<?php endif;?>
  <div>
  <?php
  foreach($files as $file)
  {
      if(common::hasPriv('file', 'download'))
      {
          echo '<div>';
          echo html::a($this->createLink('file', 'download', "fileID=$file->id") . $sessionString, $file->title .'.' . $file->extension, '_blank', "onclick='return downloadFile($file->id)'");
          common::printLink('file', 'edit', "fileID=$file->id", "<i class='icon-pencil'></i>", '', "class='edit link-icon' title='{$lang->file->edit}'");
          if(common::hasPriv('file', 'delete')) echo html::a('###', "<i class='icon-remove'></i>", '', "class='link-icon' onclick='deleteFile($file->id)' title='$lang->delete'");
          echo '</div>';
      }
  }
  ?>
  </div>
<?php if($fieldset == 'true') echo '</fieldset>';?>
