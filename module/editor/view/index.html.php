<?php
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'dev/view/header.html.php';?>
<?php js::set('moduleTree', $moduleTree);?>
<?php if(common::hasPriv('editor', 'turnon')):?>
<div id='mainMenu' class='clearfix menu-secondary'>
  <div class="pull-left">
    <?php
    echo $lang->editor->turnOff;
    echo html::a($this->createLink('editor', 'turnon', 'status=0'), $lang->dev->switchList['0'], '', "class='btn btn-sm'");
    ?>
  </div>
</div>
<?php endif;?>
<div id='mainContent' class='main-row'>
  <div class='side-col' id='sidebar'>
    <div class='cell module-tree'>
      <div class='panel panel-sm with-list'>
        <div class='panel-heading'><i class='icon-list'></i> <strong><?php echo $lang->editor->moduleList?></strong></div>
        <div id="moduleTree" class="menu-active-primary menu-hover-primary"></div>
      </div>
    </div>
  </div>
  <div class='side-col w-350px'>
    <div class='cell module-col'>
      <iframe frameborder='0' name='extendWin' id='extendWin' width='100%'></iframe>
    </div>
  </div>
  <div class='main-col main-content module-content'>
    <iframe frameborder='0' name='editWin' id='editWin' width='100%'></iframe>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
