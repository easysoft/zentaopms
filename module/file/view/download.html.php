<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('fileID', $file->id);?>
<style>
#imageFile{text-align: center; padding: 0; margin: 0 -10px;}
#imageFile img{max-width:100%;}
#txtFile {padding: 5px 0; margin: 0 -10px;}
#txtFile pre {margin: 0;}
#txtFile div {overflow-x: auto;}
#titlebar span{float: right; padding-right: 25px;}
.main-header .btn-toolbar{margin-left:8px;}
</style>
<main id="main">
  <div class="container">
    <div id='mainContent' class='main-content'>
    <div class='main-header clearfix'>
        <h2 class='pull-left'><?php echo $lang->file->preview;?></h2>
        <?php if($fileType == 'txt'):?>
        <div class='btn-toolbar pull-left w-120px'><?php echo html::select('charset', $config->file->charset, $charset, "onchange='setCharset(this.value)' class='form-control'");?></div>
        <?php endif;?>
    </div>
    <?php if($fileType == 'image'):?>
    <div id='imageFile'><?php echo html::image($this->createLink('file', 'read', "fileID=$file->id"));?></div>
    <?php else:?>
    <div id='txtFile'>
        <?php
        $fileContent = file_get_contents($file->realPath);
        if($charset != $config->charset)
        {
            $fileContent = helper::convertEncoding($fileContent, $charset . "//IGNORE", $config->charset);
        }
        else
        {
            if(extension_loaded('mbstring'))
            {
                $encoding = mb_detect_encoding($fileContent, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
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
        echo "<pre>" . htmlspecialchars($fileContent) . "</pre>";
        ?>
    </div>
    <?php endif;?>
    </div>
  </div>
</main>
<script>
function setCharset(charset)
{
    var param = (config.requestType == 'PATH_INFO' ? '?' : '&') + 'charset=' + charset;
    var link  = createLink('file', 'download', 'fileID=' + fileID + '&mouse=left') + param;
    location.href = link;
}
</script>
<?php include '../../common/view/footer.lite.html.php';?>
