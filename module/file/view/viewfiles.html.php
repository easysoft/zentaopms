<?php if($files):?>
<?php $sessionString = session_name() . '=' . session_id();?>
<?php if($fieldset == 'true'):?>
<div class="detail">
  <div class="detail-title"><?php echo $lang->file->common;?> <i class="icon icon-paper-clip icon-sm"></i></div>
  <div class="detail-content">
<?php endif;?>
  <style>
  .files-list>li>a {display: inline; word-wrap: break-word;}
  .files-list>li>.right-icon {opacity: 1;}
  </style>
  <script>
  /* Delete a file. */
  function deleteFile(fileID)
  {
      if(!fileID) return;
      hiddenwin.location.href = createLink('file', 'delete', 'fileID=' + fileID);
  }

  /* Download a file, append the mouse to the link. Thus we call decide to open the file in browser no download it. */
  function downloadFile(fileID, extension, imageWidth, fileTitle)
  {
      if(!fileID) return;
      var fileTypes      = 'txt,jpg,jpeg,gif,png,bmp';
      var windowWidth    = $(window).width();
      var width          = (windowWidth > imageWidth) ? ((imageWidth < windowWidth * 0.5) ? windowWidth * 0.5 : imageWidth) : windowWidth;
      var checkExtension = fileTitle.lastIndexOf('.' + extension) == (fileTitle.length - extension.length - 1);

      var url = createLink('file', 'download', 'fileID=' + fileID + '&mouse=left');
      url    += url.indexOf('?') >= 0 ? '&' : '?';
      url    += '<?php echo $sessionString;?>';

      if(fileTypes.indexOf(extension) >= 0 && checkExtension && config.onlybody != 'yes')
      {
          $('<a>').modalTrigger({url: url, type: 'iframe', width: width}).trigger('click');
      }
      else
      {
          url = url.replace('?onlybody=yes&', '?');
          url = url.replace('?onlybody=yes', '?');
          url = url.replace('&onlybody=yes', '');

          window.open(url, '_blank');
      }
      return false;
  }
  </script>
    <ul class="files-list">
      <?php
      foreach($files as $file)
      {
          if(common::hasPriv('file', 'download'))
          {
              $uploadDate = $lang->file->uploadDate . substr($file->addedDate, 0, 10);
              $fileTitle  = "<i class='icon icon-file-text'></i> &nbsp;" . $file->title;
              if(strpos($file->title, ".{$file->extension}") === false && $file->extension != 'txt') $fileTitle .= ".{$file->extension}";
              $imageWidth = 0;
              if(stripos('jpg|jpeg|gif|png|bmp', $file->extension) !== false)
              {
                  $imageSize  = $this->file->getImageSize($file);
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

              $downloadLink  = $this->createLink('file', 'download', "fileID=$file->id");
              $downloadLink .= strpos($downloadLink, '?') === false ? '?' : '&';
              $downloadLink .= $sessionString;
              echo "<li title='{$uploadDate}'>" . html::a($downloadLink, $fileTitle . " <span class='text-muted'>({$fileSize})</span>", '_blank', "onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth, '$file->title')\"");

              $objectType = zget($this->config->file->objectType, $file->objectType);
              if(common::hasPriv($objectType, 'edit', $object))
              {
                  echo "<span class='right-icon'>&nbsp; ";

                  /* Determines whether the file supports preview. */
                  if($file->extension == 'txt')
                  {
                      $extension = 'txt';
                      if(($postion = strrpos($file->title, '.')) !== false) $extension = substr($file->title, $postion + 1);
                      if($extension != 'txt') $mode = 'down';
                      $file->extension = $extension;
                  }

                  /* For the open source version of the file judgment. */
                  if(stripos('txt|jpg|jpeg|gif|png|bmp', $file->extension) !== false)
                  {
                      echo html::a($downloadLink, $lang->file->preview, '_blank', "class='text-primary' onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth, '$file->title')\"");
                  }

                  /* For the max version of the file judgment. */
                  if(isset($this->config->file->libreOfficeTurnon) and $this->config->file->libreOfficeTurnon == 1)
                  {
                      $officeTypes = 'doc|docx|xls|xlsx|ppt|pptx|pdf';
                      if(stripos($officeTypes, $file->extension) !== false)
                      {
                          echo html::a($downloadLink, $lang->file->preview, '_blank', "class='text-primary' onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth, '$file->title')\"");
                      }
                  }

                  common::printLink('file', 'download', "fileID=$file->id", $lang->file->downloadFile, '_blank', "class='text-primary' title='{$lang->file->downloadFile}'");
                  common::printLink('file', 'edit', "fileID=$file->id", $lang->file->edit, '', "data-width='400' class='edit iframe text-primary' title='{$lang->file->edit}'");
                  if(common::hasPriv('file', 'delete')) echo html::a('###', $lang->delete, '', "class='text-primary' onclick='deleteFile($file->id)' title='$lang->delete'");
                  echo '</span>';
              }
              echo '</li>';
          }
      }
      ?>
    </ul>
<?php if($fieldset == 'true'):?>
  </div>
</div>
<?php endif;?>
<?php endif;?>
