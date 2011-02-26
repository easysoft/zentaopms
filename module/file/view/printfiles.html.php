<style>.button-c {padding:1px}</style>
<script language='Javascript'>
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
    var URL = createLink('file', 'download', 'fileID=' + fileID + '&mouse=left');
    window.open(URL, '_blank');
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
      if(common::hasPriv('file' , 'download')) echo html::a($this->createLink('file', 'download', "fileID=$file->id"), $file->title .'.' . $file->extension, '_blank', "onclick='return downloadFile($file->id)'");
      if(common::hasPriv('file', 'delete'))    echo html::commonButton(' x ', "onclick='deleteFile($file->id)'");
  }
  ?>
  </div>
<?php if($fieldset == 'true') echo '</fieldset>';?>
