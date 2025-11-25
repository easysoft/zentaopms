<?php if($files):?>
<?php $sessionString = session_name() . '=' . session_id();?>
<?php if($fieldset == 'true'):?>
<div class="detail">
  <div class="detail-title"><?php echo $lang->file->common;?> <i class="icon icon-paper-clip icon-sm"></i></div>
  <div class="detail-content">
<?php endif;?>
<style>
.file {padding-top: 2px;}
ul.files-list {margin-bottom: unset}
.files-list>li>a {display: inline; word-wrap: break-word;}
.files-list>li>.right-icon {opacity: 1;}
.fileAction {color: #0c64eb !important;}
.renameFile {display: flex;}
.renameFile .input-group {margin-left: 10px;}
.renameFile .icon {margin-top: 8px;}
.renameFile .input-group-addon {width: 60px;}
.backgroundColor {background: #eff5ff; }
.icon.icon-file-text {padding-left: 7px}
.right-icon .btn {padding: 0 6px;}
li.file:hover .right-icon{display: inline !important;}
</style>
<script>
 /**
 * Delete a file.
 *
 * @param  int    $fileID
 * @param  object $obj
 * @access public
 * @return void
 */
function deleteFile(fileID, obj)
{
    if(!fileID) return;

    <?php if($showDelete and $method == 'edit'):?>
    $('<input />').attr('type', 'hidden').attr('name', 'deleteFiles[' + fileID + ']').attr('value', fileID).appendTo('ul.files-list');
    $(obj).closest('li.file').addClass('hidden');
    <?php else:?>
    hiddenwin.location.href = createLink('file', 'delete', 'fileID=' + fileID);
    <?php endif;?>
}

/**
 * Download a file, append the mouse to the link. Thus we call decide to open the file in browser no download it.
 *
 * @param  int    $fileID
 * @param  int    $extension
 * @param  int    $imageWidth
 * @param  string $fileTitle
 * @access public
 * @return void
 */
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

/* Show edit box for editing file name. */
/**
 * Show edit box for editing file name.
 *
 * @param  int    $fileID
 * @access public
 * @return void
 */
function showRenameBox(fileID)
{
    $('#renameFile' + fileID).closest('li').addClass('hidden');
    $('#renameBox' + fileID).closest('li').removeClass('hidden');
}

/**
 * Show File.
 *
 * @param  int    $fileID
 * @access public
 * @return void
 */
function showFile(fileID)
{
    $('#renameBox' + fileID).closest('li').addClass('hidden');
    $('#renameFile' + fileID).closest('li').removeClass('hidden');
}

/**
 * Smooth refresh file name.
 *
 * @param  int    $fileID
 * @access public
 * @return void
 */
function setFileName(fileID)
{
    var fileName  = $('#fileName' + fileID).val();
    var extension = $('#extension' + fileID).val();
    var postData  = {'fileName' : fileName, 'extension' : extension};
    $.ajax(
    {
        url:createLink('file', 'edit', 'fileID=' + fileID),
        dataType: 'json',
        method: 'post',
        data: postData,
        success: function(data)
        {
            $('#fileTitle' + fileID).html("<i class='icon icon-file-text'></i> &nbsp;" + data['title']);
            $('#renameFile' + fileID).closest('li').removeClass('hidden');
            $('#renameBox' + fileID).closest('li').addClass('hidden');
        }
    })
}
</script>
    <ul class="files-list">
      <?php foreach($files as $file):?>
        <?php if(common::hasPriv('file', 'download')):?>
          <?php
          $uploadDate = $lang->file->uploadDate . substr($file->addedDate, 0, 10);
          $fileTitle  = "<i class='icon icon-file-text'></i> &nbsp;" . $file->title;
          if(strpos($file->title, ".{$file->extension}") === false && $file->extension != 'txt') $fileTitle .= ".{$file->extension}";
          $imageWidth = 0;
          if(stripos('jpg|jpeg|gif|png|bmp', $file->extension) !== false)
          {
              $imageSize  = $this->file->getImageSize($file);
              $imageWidth = $imageSize[0];
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
          echo "<li class='file' title='{$uploadDate}'>" . html::a($downloadLink, $fileTitle . " <span class='text-muted'>({$fileSize})</span>", '_blank', "id='fileTitle$file->id'  onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth, '$file->title')\"");

          $objectType = zget($this->config->file->objectType, $file->objectType);
          if(common::hasPriv($objectType, 'view', $object))
          {
              echo "<span class='right-icon' style='display: none'>&nbsp; ";

              /* Determines whether the file supports preview. */
              if($file->extension == 'txt')
              {
                  $extension = 'txt';
                  if(($position = strrpos($file->title, '.')) !== false) $extension = substr($file->title, $position + 1);
                  if($extension != 'txt') $mode = 'down';
                  $file->extension = $extension;
              }

              /* For the open source version of the file judgment. */
              if(stripos('txt|jpg|jpeg|gif|png|bmp', $file->extension) !== false)
              {
                  echo html::a($downloadLink, "<i class='icon icon-eye'></i>", '_blank', "class='fileAction btn btn-link text-primary' title='{$lang->file->preview}' onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth, '$file->title')\"");
              }

              /* For the max version of the file judgment. */
              if(isset($this->config->file->libreOfficeTurnon) and $this->config->file->libreOfficeTurnon == 1)
              {
                  $officeTypes = 'doc|docx|xls|xlsx|ppt|pptx|pdf';
                  if(stripos($officeTypes, $file->extension) !== false)
                  {
                      echo html::a($downloadLink, "<i class='icon icon-eye'></i>", '_blank', "class='fileAction btn btn-link text-primary' title='{$lang->file->preview}' onclick=\"return downloadFile($file->id, '$file->extension', $imageWidth, '$file->title')\"");
                  }
              }

              common::printLink('file', 'download', "fileID=$file->id", "<i class='icon icon-download'></i>", '_blank', "class='fileAction btn btn-link text-primary' title='{$lang->file->downloadFile}'");
              if(common::hasPriv($objectType, 'edit', $object))
              {
                  if($showEdit and common::hasPriv('file', 'edit')) echo html::a('###', "<i class='icon icon-pencil-alt'></i>", '', "id='renameFile$file->id' class='fileAction btn btn-link edit text-primary' onclick='showRenameBox($file->id)' title='{$lang->file->edit}'");
                  if($showDelete and common::hasPriv('file', 'delete')) echo html::a('###', "<i class='icon icon-trash'></i>", '', "class='fileAction btn btn-link text-primary' onclick='deleteFile($file->id, this)' title='$lang->delete'");
              }
              echo '</span>';
          }
          echo '</li>';?>

          <li class='file hidden'>
            <div>
              <?php
              if(strrpos($file->title, '.') !== false)
              {
                  /* Fix the file name exe.exe */
                  $title     = explode('.', $file->title);
                  $extension = end($title);
                  if($file->extension == 'txt' && $extension != $file->extension) $file->extension = $extension;
                  array_pop($title);
                  $file->title = join('.', $title);
              }
              ?>
              <div class='renameFile w-300px' id='renameBox<?php echo $file->id;?>'>
                <i class='icon icon-file-text'></i>
                <div class='input-group'>
                  <input type="text" id="<?php echo 'fileName' . $file->id?>" value="<?php echo $file->title;?>" class='form-control'/>
                  <input type="hidden" id="extension<?php echo $file->id?>" value="<?php echo $file->extension;?>"/>
                  <strong class='input-group-addon'>.<?php echo $file->extension;?></strong>
                </div>
                <div class="input-group-btn">
                  <button type="button" class="btn btn-success file-name-confirm" onclick="setFileName(<?php echo $file->id;?>)" style="border-radius: 0px 2px 2px 0px; border-left-color: transparent;"><i class="icon icon-check"></i></button>
                  <button type="button" class="btn btn-gray file-name-cancel" onclick="showFile(<?php echo $file->id;?>)" style="border-radius: 0px 2px 2px 0px; border-left-color: transparent;"><i class="icon icon-close"></i></button>
                </div>
              </div>
            </div>
          </li>

        <?php endif;?>
      <?php endforeach;?>
    </ul>
<?php if($fieldset == 'true'):?>
  </div>
</div>
<?php endif;?>
<?php endif;?>
