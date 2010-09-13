<?php if($fieldset == 'true'):?>
<fieldset>
  <legend><?php echo $lang->file->common;?></legend>
<?php endif;?>
  <div>
  <?php
  foreach($files as $file)
  {
      if(common::hasPriv('file' , 'download')) echo html::a($this->createLink('file', 'download', "fileID=$file->id"), $file->title, '_blank', "onclick='return downloadFile($file->id)'");
      if(common::hasPriv('file', 'delete'))    echo html::commonButton(' x ', "onclick=deleteFile($file->id)");
  }
  ?>
  </div>
<?php if($fieldset == 'true') echo '</fieldset>';?>
