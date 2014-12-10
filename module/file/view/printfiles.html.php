<?php
$sessionString  = $config->requestType == 'PATH_INFO' ? '?' : '&';
$sessionString .= session_name() . '=' . session_id();
?>
<style> .files-list {margin: 0;} .files-list > .list-group-item {padding: 0px; border:0px;} .files-list > .list-group-item a {color: #666} .files-list > .list-group-item:hover a {color: #333} .files-list > .list-group-item > .right-icon {opacity: 0.01; transition: all 0.3s;} .files-list > .list-group-item:hover > .right-icon {opacity: 1} .files-list .btn-icon > i {font-size:15px}</style>
<script language='Javascript'>
$(function(){
     $(".edit").modalTrigger({width:350, type:'iframe'});
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
  <div class='list-group files-list'>
  <?php
  foreach($files as $file)
  {
      if(common::hasPriv('file', 'download'))
      {
          echo "<li class='list-group-item'><i class='icon-file-text text-muted icon'></i> &nbsp;";
          echo html::a($this->createLink('file', 'download', "fileID=$file->id") . $sessionString, $file->title .'.' . $file->extension, '_blank', "onclick='return downloadFile($file->id)'");
          echo "<span class='right-icon'>";
          common::printLink('file', 'edit', "fileID=$file->id", "<i class='icon-pencil'></i>", '', "class='edit btn-icon' title='{$lang->file->edit}'");
          if(common::hasPriv('file', 'delete')) echo html::a('###', "<i class='icon-remove'></i>", '', "class='btn-icon' onclick='deleteFile($file->id)' title='$lang->delete'");
          echo '</span>';
          echo '</li>';
      }
  }
  ?>
  </div>
<?php if($fieldset == 'true') echo '</fieldset>';?>
