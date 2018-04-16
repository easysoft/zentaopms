<?php $maxUploadSize = strtoupper(ini_get('upload_max_filesize'));?>

<div class="file-input-list" data-provide="fileInputList" data-each-file-max-size="<?php echo $maxUploadSize;?>" data-file-size-error="<?php echo sprintf($lang->file->errorFileSize, $maxUploadSize);?>">
  <div class="file-input">
  <div class="file-input-empty"><button type="button" class="btn btn-link file-input-btn"><i class="icon icon-plus"></i> <?php echo $lang->file->addFile;?></button> <small class="muted"><?php echo sprintf($lang->file->maxUploadSize, $maxUploadSize);?></small></div>
    <div class="file-input-edit input-group">
      <div class="input-group-cell"><i class="icon icon-paper-clip text-muted"></i></div>
      <input type="text" name="labels[]" class="form-control file-editbox" placeholder="<?php echo $lang->file->title;?>">
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
        <button type="button" class="btn btn-link file-input-rename"><?php echo $lang->file->edit;?></button>
        <button type="button" class="btn btn-link file-input-delete"><?php echo $lang->delete;?></button>
      </div>
    </div>
    <input type="file" name="files[]">
  </div>
</div>
