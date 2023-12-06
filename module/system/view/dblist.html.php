<?php
/**
 * The adminer view file of system module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->createLink('system', 'index'), "<i class='icon icon-back icon-sm'></i> " . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='main-header'>
      <h2><?php echo $lang->system->dbList;?></h2>
    </div>
    <table class='table table-bordered text-center'>
      <tr>
        <th><?php echo $lang->system->dbName;?></th>
        <th><?php echo $lang->system->dbType;?></th>
        <th><?php echo $lang->system->dbStatus;?></th>
        <th><?php echo $lang->system->action?></th>
      </tr>
      <?php foreach($dbList as $db):?>
      <tr>
        <td><?php echo $db->name;?></td>
        <td><?php echo $db->db_type?></td>
        <td><?php echo zget($lang->instance->statusList, $db->status);?></td>
        <td><?php $this->system->printDBAction($db);?></td>
      <tr>
      <?php endforeach;?>
    </table>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>

