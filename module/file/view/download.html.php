<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('fileID', $file->id);?>
<style>
#imageFile{text-align: center;}
#imageFile img{margin: 10px auto;}
#txtFile{padding: 5px 10px;}
#txtFile div{overflow-x: auto;}
#titlebar span{float: right; padding-right: 25px;}
</style>
<div id='titlebar'>
  <div class='heading'>
    <strong><?php echo $lang->file->common;?></strong>
    <small class='text-muted'><?php echo $lang->file->preview;?></small>
    <?php if($fileType == 'txt'):?>
    <span><?php echo html::select('charset', $config->file->charset, $charset, "onchange='setCharset(this.value)'");?></span>
    <?php endif;?>
  </div>
</div>
<?php if($fileType == 'image'):?>
<div id='imageFile'>
<?php echo html::image($file->webPath);?>
</div>
<?php else:?>
<div id='txtFile'>
  <div>
  <?php
  $fileContent = file_get_contents($file->realPath);
  if($charset != $config->charset)
  {
      $fileContent = helper::convertEncoding($fileContent, $charset, $config->charset);
  }
  else
  {
      if(!extension_loaded('mbstring'))
      {
          $encoding = mb_detect_encoding($fileContent, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
          if($encoding != 'UTF-8') $fileContent = helper::convertEncoding($fileContent, $encoding, $config->charset);
      }
      else
      {
          $encoding = 'UTF-8';
          if($config->default->lang == 'zh-cn') $encoding = 'GBK';
          if($config->default->lang == 'zh-tw') $encoding = 'BIG5';
          $fileContent = helper::convertEncoding($fileContent, $encoding, $config->charset);
      }
  }
  a($fileContent);
  ?>
  </div>
</div>
<?php endif;?>
<script>
function setCharset(charset)
{
    var param = (config.requestType == 'PATH_INFO' ? '?' : '&') + 'charset=' + charset;
    var link  = createLink('file', 'download', 'fileID=' + fileID + '&mouse=left') + param;
    location.href = link;
}
</script>
<?php include '../../common/view/footer.lite.html.php';?>
