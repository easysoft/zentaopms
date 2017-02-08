<?php
/**
 * The upload view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon('upload');?></span>
    <strong><?php echo $lang->extension->upload;?></strong>
  </div>
</div>
<?php if(!empty($error)):?>
<div class='panel panel-body text-left'>
  <div class='container mw-500px'>
    <p class='text-danger'><?php echo $error;?></p>
  </div>
  <hr>
  <?php echo html::commonButton($lang->extension->refreshPage, 'onclick=location.href=location.href');?>
</div>
<?php else:?>
<form method='post' enctype='multipart/form-data' style='padding: 5% 20%'>
  <div class='input-group'>
    <input type='file' name='file' class='form-control' />
    <span class='input-group-btn'><?php echo html::submitButton($lang->extension->install);?></span>
  </div>
</form>
<?php endif;?>
</body>
</html>
