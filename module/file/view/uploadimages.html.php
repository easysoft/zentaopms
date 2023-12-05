<?php
/**
 * The uploadImages view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
#uploader .uploader-files {border-bottom: 1px solid #eee; margin: 0 0 10px 0; min-height: 164px;}
.file-list .file[data-status=queue] .file-status>.icon:before {content: '\e92b'}
.uploader-files .file-status > .icon:before, .file-list .file-status > .icon:before {content: '\e92f'}
</style>
<main id='main'>
  <div class='container'>
    <div id='mainContent'>
      <div class='main-header'>
        <h2><?php echo $lang->file->uploadImages;?></h2>
      </div>
      <div class='uploader' id='uploader' data-url='<?php echo inlink('uploadImages', "module=$module&params=$params&uid=$uid");?>'>
        <div class='uploader-message text-center'>
          <div class='content'></div>
          <button type='button' class='close'>×</button>
        </div>
        <div class='uploader-files file-list file-list-grid' data-drag-placeholder="<?php echo $lang->file->dragFile;?>"></div>
        <div class='text-info space-sm'><?php echo $this->lang->file->uploadImagesExplain?></div>
        <div class='uploader-footer'>
          <div class='uploader-status pull-right text-muted'></div>
          <button type='button' class='btn btn-secondary uploader-btn-browse btn-wide'><i class='icon icon-plus'></i><?php echo $lang->file->addFile;?></button> &nbsp; 
          <button type='button' class='btn btn-primary uploader-btn-start btn-wide'><i class='icon icon-arrow-up'></i><?php echo $lang->file->beginUpload;?></button>
        </div>
      </div>
    </div>
  </div>
</main>
<script>
$('#uploader').uploader({
    filters:
    {
        mime_types: [
            {title: 'uploadImages', extensions: 'jpg,gif,png,jpeg,bmp'},
        ],
        prevent_duplicates: true
    },
    onUploadComplete: function(files)
    {
        if(files && files.length)
        {
            setTimeout(function()
            {
                location.href = createLink('file', 'uploadImages', 'module=' + module + '&params=' + params + '&uid=' + uid + '&locate=true');
            }, 1000);
        }
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
