<?php
$sessionString  = $config->requestType == 'PATH_INFO' ? '?' : '&';
$sessionString .= session_name() . '=' . session_id();
?>
<style>
.files-list {margin: 0;}
.files-list > .list-group-item {padding: 0px; border:0px;}
.files-list > .list-group-item a, .files-list > .list-group-item span{color: #666}
.files-list > .list-group-item:hover a, .files-list > .list-group-item:hover span{color: #333}
.files-list > .list-group-item > .right-icon {opacity: 0.01; transition: all 0.3s;}
.files-list > .list-group-item:hover >
.right-icon {opacity: 1}
.files-list .btn-icon > i {font-size:15px}
</style>
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
function downloadFile(fileID, extension, imageWidth)
{
    if(!fileID) return;
    var fileTypes     = 'txt,jpg,jpeg,gif,png,bmp';
    var sessionString = '<?php echo $sessionString;?>';
    var windowWidth   = $(window).width();
    var url           = createLink('file', 'download', 'fileID=' + fileID + '&mouse=left') + sessionString;
    width = (windowWidth > imageWidth) ? ((imageWidth < windowWidth*0.5) ? windowWidth*0.5 : imageWidth) : windowWidth;
    if(fileTypes.indexOf(extension) >= 0)
    {
        $('<a>').modalTrigger({url: url, type: 'iframe', width: width}).trigger('click');
    }
    else
    {
        window.open(url, '_blank');
    }
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
          $uploadDate = $lang->file->uploadDate . substr($file->addedDate, 0, 10);
          $fileTitle  = "<li title='{$uploadDate}' class='list-group-item'><i class='icon-file-text text-muted icon'></i> &nbsp;" . $file->title .'.' . $file->extension;
          $imageWidth = 0;
          if(stripos('jpg|jpeg|gif|png|bmp', $file->extension) !== false)
          {
              $imageSize  = getimagesize($file->realPath);
              $imageWidth = $imageSize ? $imageSize[0] : 0;
          }
          echo html::a($this->createLink('file', 'download', "fileID=$file->id") . $sessionString, $fileTitle, '_blank', "onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth)\"");

          /* Show size info. */
          if($file->size < 1024)
          {
              echo "<span>(" . $file->size . 'B' . ")</span>";
          }
          elseif($file->size < 1024 * 1024)
          {
              $file->size = round($file->size / 1024, 2);
              echo "<span>(" . $file->size . 'K' . ")</span>";
          }
          elseif($file->size < 1024 * 1024 * 1024)
          {
              $file->size = round($file->size / (1024 * 1024), 2);
              echo "<span>(" . $file->size . 'M' . ")</span>";
          }
          else
          {
              $file->size = round($file->size / (1024 * 1024 * 1024), 2);
              echo "<span>(" . $file->size . 'G' . ")</span>";
          }

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
