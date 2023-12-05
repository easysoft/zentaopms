<?php
/**
 * The install domain view file of system module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
  <?php echo html::a($this->createLink('system', 'index'), "<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='main-header'>
      <h2><?php echo $lang->system->domain->common;?></h2>
      <div class='smtp-button-group btn-toolbar pull-right'>
      <?php echo html::a($this->inLink('editDomain'), $lang->system->domain->editDomain, '', "class='btn btn-primary'");?>
      </div>
    </div>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th><?php echo $lang->system->domain->currentDomain;?></th>
          <td><?php echo zget($domainSettings, 'customDomain', '');?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->domain->expiredDate;?></th>
          <td><?php echo zget($cert, 'expiredDate', '');?></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
