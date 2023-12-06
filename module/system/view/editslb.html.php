<?php
/**
 * The edit SLB view file of system module of chandao.net.
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
<?php $ldapLinked = false;?>
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
    <h2><?php echo $lang->system->SLB->config;?></h2>
    </div>
    <form id='SLBForm' class='cell not-watch main-form'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->system->SLB->ipPool;?></th>
            <td class='required w-400px'>
            <?php echo html::input('ippool', zget($SLBSettings, 'ippool', ''), "class='form-control' placeholder='{$lang->system->SLB->ipPoolExample}'");?>
            </td>
            <td></td>
          </tr>
        </tbody>
      </table>
      <div class='text-center'><?php echo html::commonButton($lang->save, "id='submitBtn'", 'btn btn-wide btn-primary');?></div>
    </form>
  </div>
</div>
<div class="modal fade" id="waiting" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-400px">
    <div class="modal-content">
      <div class="modal-body">
        <h4><?php echo $lang->system->SLB->installing;?></h4>
        <div>
          <span id='message'><?php echo $lang->system->SLB->leftSeconds;?></span>
          <span id='timer'></span>
          <span><?php echo $lang->system->SLB->second;?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>

