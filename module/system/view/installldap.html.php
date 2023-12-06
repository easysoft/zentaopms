<?php
/**
 * The edit LDAP view file of system module of chandao.net.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php $ldapLinked = $this->loadModel('instance')->countLDAP();?>
<?php js::set('errors', $lang->system->errors);?>
<?php js::set('notices', $lang->system->notices);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->createLink('system', 'index'), "<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='main-header'>
      <h2><?php echo $lang->system->installLDAP;?></h2>
    </div>
    <?php include('./ldapform.html.php');?>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>

