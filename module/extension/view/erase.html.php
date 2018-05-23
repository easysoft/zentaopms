<?php
/**
 * The erase view file of extension module of ZenTaoPMS.
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
      <h2>
        <span class='prefix' title='EXTENSION'><?php echo html::icon($lang->icons['extension']);?></span>
        <strong><?php echo $title;?></strong>
        <small class='text-danger'><?php echo $lang->extension->erase;?> <?php echo html::icon('trash');?></small>
      </h2>
    </div>
    <div class='alert alert-success with-icon'>
      <i class='icon-check-circle'></i>
      <div class='content'>
        <h3><?php echo $title;?></h3>
        <?php if($removeCommands):?>
        <p><strong><?php echo $lang->extension->unremovedFiles;?></strong></p>
        <p><?php echo join($removeCommands, '<br />');?></p>
        <?php endif;?>
        <p class='text-center'><?php echo html::commonButton($lang->extension->viewAvailable, 'onclick=parent.location.href="' . inlink('browse', 'type=available') . '"');?></p>
      </div>
    </div>
  </div>
</div>
</body>
</html>
