<?php
/**
 * The install ldap view file of system module of ZenTaoPMS.
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
<?php js::set('copySuccess', $lang->system->copySuccess);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->createLink('system', 'index'), "<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='main-header'>
      <h2><?php echo $lang->system->oss->common;?></h2>
      <div class='ldap-button-group btn-toolbar pull-right'>
        <?php echo html::commonButton($lang->system->oss->manage, "id='ossManage'", "btn label label-outline label-primary label-lg");?>
      </div>
    </div>
    <table class='table table-form instance-status'>
      <tbody>
        <tr>
          <th><?php echo $lang->system->oss->apiURL;?></th>
          <td><?php echo zget($ossDomain->extra_hosts, 'api', '');?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->oss->accessKey;?></th>
          <td><?php echo $ossAccount->username;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->system->oss->secretKey;?></th>
          <td>
            <input id='ossSK' class='hidden' value='<?php echo $ossAccount->password;?>' />
            <?php echo html::commonButton($lang->system->copy, "id='copySKBtn'");?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="ossAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-400px">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        <h2 class="text-center"><?php echo $lang->system->accountInfo;?></h2>
        <p><?php echo $lang->system->oss->user;?>：<span id='ossAdmin'></span></p>
        <p>
          <?php echo $lang->system->oss->password;?>：<input id='ossPassword' class='hidden' readonly />
          <?php echo html::commonButton($lang->system->copy, "id='copyPassBtn'", 'btn btn-link');?>
        </p>
        <div class="text-center">
          <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
          <?php echo html::a('#', $lang->system->visit, '_blank', "id='ossVisitUrl' class='btn btn-primary btn-wide'");?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
