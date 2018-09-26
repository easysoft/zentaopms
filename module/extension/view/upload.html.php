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
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->extension->upload;?></h2>
    </div>
    <?php if(!empty($error)):?>
    <div class='text-left'>
      <div class='container mw-500px'>
        <p class='text-danger'><?php echo $error;?></p>
        <?php echo html::commonButton($lang->extension->refreshPage, 'onclick=location.href=location.href', 'btn btn-primary');?>
      </div>
    </div>
    <?php else:?>
    <form method='post' target='hiddenwin' enctype='multipart/form-data' style='padding: 20px 20%'>
      <div class='input-group'>
        <input type='file' name='file' class='form-control' />
        <span class='input-group-btn'><?php echo html::submitButton($lang->extension->install, '', 'btn btn-primary');?></span>
      </div>
    </form>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
