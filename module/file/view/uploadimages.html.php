<?php
/**
 * The uploadImages view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php
js::import($jsRoot . 'uploader/min.js');
css::import($jsRoot . 'uploader/min.css');
js::set('module', $module);
js::set('params', $params);
js::set('uid', $uid);
?>
<style>
#uploader{padding: 20px;}
#uploader > span{display:block; margin-bottom:10px; color: #29a8cd;}
</style>
<div class='uploader' id='uploader' data-url='<?php echo inlink('uploadImages', "module=$module&params=$params&uid=$uid");?>'>
  <div class='uploader-message text-center'>
    <div class='content'></div>
    <button type='button' class='close'>×</button>
  </div>
  <div class='uploader-files file-list file-list-grid' data-drag-placeholder="<?php echo $lang->file->dragFile;?>"></div>
  <hr/>
  <span><?php echo $this->lang->file->uploadImagesExplain?></span>
  <div class='uploader-footer'>
    <div class='uploader-status pull-right text-muted'></div>
    <button type='button' class='btn btn-primary uploader-btn-browse'><i class='icon icon-plus'></i><?php echo $lang->file->addFile;?></button>
    <button type='button' class='btn btn-success uploader-btn-start'><i class='icon icon-cloud-upload'></i><?php echo $lang->file->beginUpload;?></button>
  </div>
</div>
<script>
$('#uploader').uploader({
    onUploadComplete:function()
    {
        location.href = createLink('file', 'uploadImages', 'module=' + module + '&params=' + params + '&uid=' + uid + '&locate=true');
    },
    onBeforeUpload: function(file)
    {
        this.plupload.setOption(
        {
            'multipart_params':
            {
              label: file.ext ? file.name.substr(0, file.name.length - file.ext.length - 1) : file.name,
              uuid: file.id,
              size: file.size
            }
        });
    }
});
</script>
<?php include '../../common/view/footer.lite.html.php';?>
