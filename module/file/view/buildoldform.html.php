<?php
$maxUploadSize = strtoupper(ini_get('upload_max_filesize'));
js::set('dangerExtensions', ',' . $this->config->file->dangers . ',');
js::set('maxUploadSize', $maxUploadSize);
?>
<div class="file-input-list" data-append-way="before" data-filedName="<?php echo $filesName;?>" name="<?php echo $filesName;?>[]" data-provide="fileInputList" data-each-file-max-size="<?php echo $maxUploadSize; ?>" data-file-size-error="<?php echo sprintf($lang->file->errorFileSize, $maxUploadSize); ?>">
  <div class="file-input">
    <div class="file-input-edit input-group">
      <div class="input-group-cell"><i class="icon icon-paper-clip text-muted"></i></div>
      <input type="text" name="<?php echo $labelsName;?>[]" class="form-control file-editbox" placeholder="<?php echo $lang->file->title;?>">
      <div class="input-group-btn">
        <button type="button" class="btn btn-success file-name-confirm"><i class="icon icon-check"></i></button>
        <button type="button" class="btn btn-gray file-name-cancel"><i class="icon icon-close"></i></button>
      </div>
    </div>
    <div class="file-input-normal input-group">
      <div class="input-group-cell"><i class="icon icon-paper-clip text-muted"></i></div>
      <div class="input-group-cell">
        <span class="file-title"></span>
        <small class="file-size muted"></small>
      </div>
      <div class="input-group-btn">
        <button type="button" class="fileAction btn btn-link file-input-rename" title="<?php echo $lang->file->edit;?>"><i class='icon icon-pencil-alt'></i></button>
        <button type="button" class="fileAction btn btn-link file-input-delete" title="<?php echo $lang->delete;?>"><i class='icon icon-trash'></i></button>
      </div>
    </div>
    <input type="file" name="<?php echo $filesName;?>[]" onchange="checkDangerExtension(this)" />
  </div>
  <button type="button" class="btn btn-link file-input-btn"><i class="icon icon-plus"></i> <?php echo $lang->file->addFile;?></button> <small class="muted"><?php echo sprintf($lang->file->maxUploadSize, $maxUploadSize);?></small>
  <input type="file" id="file-input-multiple" multiple />
</div>

<script>
maxUploadSize = unitConversion(maxUploadSize);

/**
 * Conversion unit to byte.
 *
 * @param  string $maxUploadSize
 * @access public
 * @return int
 */
function unitConversion(maxUploadSize)
{
    var sizeOfString = maxUploadSize;
    if(sizeOfString.indexOf('K') != -1) maxUploadSize = parseFloat(maxUploadSize) * 1024;
    if(sizeOfString.indexOf('M') != -1) maxUploadSize = parseFloat(maxUploadSize) * 1024 * 1024;
    if(sizeOfString.indexOf('G') != -1) maxUploadSize = parseFloat(maxUploadSize) * 1024 * 1024 * 1024;
    return parseFloat(maxUploadSize);
}

/**
 * Get all files total size.
 *
 * @return int
 */
function getTotalSize()
{
    var totalSize = 0;

    $('input[type="file"]').each(function(index, element)
    {
        if (element.multiple) return;

        if (!element.files) return;

        if (!element.files[0]) return;

        totalSize += element.files[0].size;
    });

    return totalSize;
}

/**
 * Check danger extension and file size.
 *
 * TODO Rename.
 * @param  object $file
 * @access public
 * @return void
 */
function checkDangerExtension(file)
{
    var fileName  = $(file).val();
    var index     = fileName.lastIndexOf(".");
    var fileSize  = $(file)[0].files[0].size;
    var totalSize = getTotalSize();

    if(index >= 0)
    {
        extension = fileName.substr(index + 1);
        if(dangerExtensions.lastIndexOf(',' + extension + ',') >= 0)
        {
            alert(<?php echo json_encode($this->lang->file->dangerFile);?>);
            $(file).val('');
            return false;
        }

        if(fileSize == 0)
        {
            alert(<?php echo json_encode($this->lang->file->fileContentEmpty);?>);
            $(file).val('');
            return false;
        }

        if(totalSize >= maxUploadSize)
        {
            alert(<?php echo json_encode(sprintf($lang->file->errorFileSize, $maxUploadSize));?>);
            $(file).val('');
            return false;
        }
    }
}
</script>

<style>
#file-input-multiple {display: none;}
.file-input .file-editbox {max-width: 40%;}
.file-input-list .input-group-btn {float: left}
.fileAction {color: #0c64eb !important;}
.backgroundColor {background: #eff5ff;}
</style>
