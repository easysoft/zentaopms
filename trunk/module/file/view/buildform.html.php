<?php for($i = 0; $i < $fileCount; $i ++):?>
<input type='file' name='files[]'  />
<?php echo $lang->file->label;?><input type='text' name='labels[]' class='text-3' /><br />
<?php endfor;?>
