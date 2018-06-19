<?php
/**
 * The menu view of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     user
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='space'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <?php echo $lang->customMenu;?>
        <i class='icon icon-spin icon-spinner' id='loadingIcon'></i>
      </h2>
    </div>
    <div id='menuEditor'>
      <div class='panel panel-default'>
        <nav id='navbar' class='bg-primary'>
          <ul class='nav nav-default'></ul>
        </nav>
        <nav id='subNavbar'>
          <ul class='nav nav-default'></ul>
        </nav>
        <?php if($config->global->flow != 'onlyTest'):?>
        <nav id='mainMenu'>
          <ul class='nav nav-default'></ul>
        </nav>
        <?php endif;?>
      </div>
      <div class='text-left'>
        <?php if(common::hasPriv('custom', 'setPublic')):?>
        <div class='space-sm'>
          <?php echo html::checkbox('setPublic', array(1 => $lang->custom->setPublic));?>
        </div>
        <?php endif;?>
        <button id='saveMenuBtn' type='button' class='btn btn-primary btn-wide'><?php echo $lang->save ?></button> &nbsp;
        <button id='closeModalBtn' type='button' class='btn btn-wide'><?php echo $lang->close ?></button> &nbsp;
        <button id='resetMenuBtn' type='button' class='btn btn-wide'><?php echo $lang->custom->restore ?></button> &nbsp;
        <span class='text-danger'> &nbsp; <i class="icon icon-exclamation-sign"></i> <?php echo $lang->custom->menuTip ?></span>
      </div>
    </div>
  </div>
</div>
<script>
window.startMenu = {'module': '<?php echo $module ?>', 'method': '<?php echo $method ?>'};
</script>
<?php include '../../common/view/footer.html.php';?>
