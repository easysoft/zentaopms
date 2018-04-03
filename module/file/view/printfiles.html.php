<?php
$sessionString  = $config->requestType == 'PATH_INFO' ? '?' : '&';
$sessionString .= session_name() . '=' . session_id();
?>
<?php if($files):?>
<?php if($fieldset == 'true'):?>
<fieldset>
  <legend><?php echo $lang->file->common;?></legend>
<?php endif;?>
<div class="detail">
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
  <div class="detail-title"><?php echo $lang->file->common;?> <i class="icon icon-paper-clip icon-sm"></i></div>
  <div class="detail-content">
    <ul class="files-list">
      <?php
      foreach($files as $file)
      {
          if(common::hasPriv('file', 'download'))
          {
              $uploadDate = $lang->file->uploadDate . substr($file->addedDate, 0, 10);
              $fileTitle  = "<li title='{$uploadDate}'><i class='icon icon-file-text'></i> &nbsp;" . $file->title .'.' . $file->extension;
              $imageWidth = 0;
              if(stripos('jpg|jpeg|gif|png|bmp', $file->extension) !== false)
              {
                  $imageSize  = getimagesize($file->realPath);
                  $imageWidth = $imageSize ? $imageSize[0] : 0;
              }

              $fileSize = 0;
              /* Show size info. */
              if($file->size < 1024)
              {
                  $fileSize = $file->size . 'B';
              }
              elseif($file->size < 1024 * 1024)
              {
                  $file->size = round($file->size / 1024, 2);
                  $fileSize = $file->size . 'K';
              }
              elseif($file->size < 1024 * 1024 * 1024)
              {
                  $file->size = round($file->size / (1024 * 1024), 2);
                  $fileSize = $file->size . 'M';
              }
              else
              {
                  $file->size = round($file->size / (1024 * 1024 * 1024), 2);
                  $fileSize = $file->size . 'G';
              }
              echo html::a($this->createLink('file', 'download', "fileID=$file->id") . $sessionString, $fileTitle . " ({$fileSize})", '_blank', "onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth)\"");


              echo "<span class='right-icon'>";
              common::printLink('file', 'edit', "fileID=$file->id", "<i class='icon-pencil'></i>", '', "class='edit btn-icon' title='{$lang->file->edit}'");
              if(common::hasPriv('file', 'delete')) echo html::a('###', "<i class='icon-remove'></i>", '', "class='btn-icon' onclick='deleteFile($file->id)' title='$lang->delete'");
              echo '</span>';
              echo '</li>';
          }
      }
      ?>
    </ul>
  </div>
</div>
<?php if($fieldset == 'true') echo '</fieldset>';?>
<?php endif;?>
