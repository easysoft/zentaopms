<?php
/**
 * The erase view file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='prefix' title='EXTENSION'><?php echo html::icon($lang->icons['extension']);?></span>
        <strong><?php echo $title;?></strong>
        <small class='text-danger'><?php echo $lang->extension->erase;?> <?php echo html::icon('close');?></small>
      </h2>
    </div>
    <div class='alert alert-success with-icon'>
      <i class='icon-check-circle'></i>
      <div class='content'>
        <h3><?php echo $title;?></h3>
        <?php if($removeCommands):?>
        <p><strong><?php echo $lang->extension->unremovedFiles;?></strong></p>
        <p><?php echo join('<br />', $removeCommands);?></p>
        <?php endif;?>
        <p class='text-center'><?php echo html::commonButton($lang->extension->viewAvailable, 'onclick=parent.location.href="' . inlink('browse', 'type=available') . '"');?></p>
      </div>
    </div>
  </div>
</div>
</body>
</html>
