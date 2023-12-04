<?php
/**
 * The create view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::import($jsRoot . 'md5.js');?>
<?php $lang->user->placeholder->password1 = zget($lang->user->placeholder->passwordStrength, !empty($config->safe->mode) ? $config->safe->mode : 0, '');?>
<?php js::set('holders', $lang->user->placeholder);?>
<?php js::set('roleGroup', $roleGroup);?>
<?php
$visionList     = getVisions();
$showVisionList = count($visionList) > 1;
?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><i class='icon icon-plus'></i> <?php echo $lang->user->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" id="createForm" method="post" target='hiddenwin'>
      <table align='center' class="table table-form">
        <?php $thClass = common::checkNotCN() ? 'w-enVerifyPassword' : 'w-verifyPassword';?>
        <tr>
          <th class='<?php echo $thClass?>'><?php echo $lang->user->type;?></th>
          <td colspan='2'><?php echo html::radio('type', $lang->user->typeList , 'inside', "onclick='changeType(this.value)'");?></td>
        </tr>
        <tr id='companyBox' class='hide'>
          <th><?php echo $lang->user->company;?></th>
          <td>
            <div class='input-group'>
            <?php echo html::select('company', $companies, '', "class='form-control chosen'");?>
            <span class='input-group-addon'><?php echo html::checkBox('new', $lang->company->create);?></span>
            </div>
          </td>
        </tr>
        <tr>
          <th class='<?php echo $thClass?>'><?php echo $lang->user->dept;?></th>
          <td class='w-p40'><?php echo html::select('dept', $depts, $deptID, "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->account;?></th>
          <td><?php echo html::input('account', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->password;?></th>
          <td>
            <input type='password' style="display:none"> <!-- for disable autocomplete all browser -->
            <span class='input-group'>
              <?php echo html::password('password1', '', "class='form-control' onkeyup='checkPassword(this.value)'");?>
              <span class='input-group-addon' id='passwordStrength'></span>
            </span>
          </td>
          <td><?php echo $lang->user->placeholder->password1;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->password2;?></th>
          <td><?php echo html::password('password2', '', "class='form-control'");?></td>
        </tr>
        <tr <?php if(!$showVisionList) echo "class='hide'";?>>
          <th><?php echo $lang->user->visions;?></th>
          <td><?php echo html::checkbox('visions', $visionList, isset($visionList[$this->config->vision]) ? $this->config->vision : key($visionList), "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->realname;?></th>
          <td><?php echo html::input('realname', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->join;?></th>
          <td><?php echo html::input('join', date('Y-m-d'), "class='form-control form-date'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->role;?></th>
          <td><?php echo html::select('role', $lang->user->roleList, '', "class='form-control' onchange='changeGroup(this.value)'");?></td>
          <td><?php echo $lang->user->placeholder->role?></td>
        </tr>
        <?php if(common::hasPriv('group', 'managemember')):?>
        <tr>
          <th><?php echo $lang->user->group;?></th>
          <td><?php echo html::select('group[]', $groupList, '', "multiple=multiple class='form-control chosen'");?></td>
          <td><?php echo $lang->user->placeholder->group?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->user->email;?></th>
          <td><?php echo html::input('email', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->commiter;?></th>
          <td><?php echo html::input('commiter', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->gender;?></th>
          <td><?php echo html::radio('gender', (array)$lang->user->genderList, 'm');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->verifyPassword;?></th>
          <td class="required">
            <?php echo html::password('verifyPassword', '', "class='form-control disabled-ie-placeholder' placeholder='{$lang->user->placeholder->verify}'");?>
          </td>
        </tr>
        <tr>
          <th></th>
          <td class='text-center form-actions'>
            <?php if(!$showVisionList) echo html::hidden("visions[]", $this->config->vision);?>
            <?php echo html::hidden('passwordLength', 0);?>
            <?php echo html::submitButton();?>
            <?php echo html::a($this->createLink('company', 'browse'), $lang->goback, '', "class='btn btn-wide'")?>
          </td>
        </tr>
      </table>
    </form>
    <?php echo html::hidden('verifyRand', $rand);?>
  </div>
</div>
<?php js::set('passwordStrengthList', $lang->user->passwordStrengthList)?>
<?php include '../../common/view/footer.html.php';?>
