<?php
/**
 * The createlib view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: createlib.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['doclib']);?></span>
    <strong><small class='text-muted'><i class='icon icon-plus'></i></small> <?php echo $lang->doc->createLib;?></strong>
  </div>
</div>
<div class='main'>
  <form method='post' class='form-condensed' style='margin: 30px 50px'>
    <div class='input-group'>
      <?php echo html::input('name', '', "class='form-control' placeholder='{$lang->doc->libName}'");?>
      <span class='input-group-btn'><?php echo html::submitButton();?></span>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
