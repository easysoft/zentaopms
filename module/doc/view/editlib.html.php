<?php
/**
 * The editlib file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: editlib.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php if($libID == 'product' or $libID == 'project'):?>
<div class='main'>
  <div class='alert alert-info'>
    <i class='icon-info-sign'></i>
    <div class='content'><h5><?php echo $lang->doc->errorEditSystemDoc;?></h5></div>
  </div>
</div>
<?php else:?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['doclib']);?></span>
    <strong><small class='text-muted'><i class='icon icon-pencil'></i></small> <?php echo $lang->doc->editLib;?></strong>
  </div>
</div>
<div class='main'>
  <form method='post' class='form-condensed'>
    <div class='form-group'>
      <label for="name"><?php echo $lang->doc->libName;?></label>
      <?php echo html::input('name', $libName, "class='form-control'");?>
    </div>
    <?php echo html::submitButton();?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.lite.html.php';?>
