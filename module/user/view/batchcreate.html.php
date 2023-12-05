<?php
/**
 * The batch create view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::import($jsRoot . 'md5.js');?>
<?php js::set('roleGroup', $roleGroup);?>
<?php js::set('companies', html::select("company", $companies));?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->user->batchCreate;?></h2>
    <div class="user-type"><?php echo html::radio('type', $lang->user->typeList , 'inside', "onclick='changeType(this.value)'");?></div>
    <div class="pull-right btn-toolbar">
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=user&section=custom&key=batchCreateFields')?>
      <?php include '../../common/view/customfield.html.php';?>
    </div>
  </div>
  <?php
  $visibleFields  = array();
  $requiredFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if(strpos(",{$config->user->availableBatchCreateFields},", ",{$field},") === false) continue;
      if($field) $visibleFields[$field] = '';
  }

  foreach(explode(',', $config->user->create->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->user->availableBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  $minWidth = (count($visibleFields) > 3) ? 'w-150px' : '';
  $showVisionList = count($visionList) > 1;
  ?>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchCreateForm">
    <?php echo html::hidden('userType', 'inside');?>
    <div class="table-responsive">
      <table class="table table-form">
        <thead>
          <tr class='text-center'>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='c-company hide<?php echo zget($requiredFields, 'company', '', ' required');?>'><?php echo $lang->user->company;?></th>
            <th class='c-dept<?php echo zget($visibleFields, 'dept', ' hidden') . zget($requiredFields, 'dept', '', ' required');?>'>              <?php echo $lang->user->dept;?></th>
            <th class='accountThWidth required'><?php echo $lang->user->account;?></th>
            <th class='c-realname required'><?php echo $lang->user->realname;?></th>
            <?php if($showVisionList):?>
              <th class='c-visions required'><?php echo $lang->user->visions;?></th>
            <?php endif;?>
            <th class='c-role<?php echo zget($requiredFields, 'role', '', ' required')?>'><?php echo $lang->user->role;?></th>
            <th class='c-group'><?php echo $lang->user->group;?></th>
            <th class='<?php echo zget($visibleFields, 'email', "$minWidth hidden", $minWidth) . zget($requiredFields, 'email', '', ' required')?>'><?php echo $lang->user->email;?></th>
            <th class='genderThWidth<?php echo zget($visibleFields, 'gender', ' hidden')?>'><?php echo $lang->user->gender;?></th>
            <th class="<?php echo $minWidth;?> required"><?php echo $lang->user->password;?></th>
            <th class='c-commiter<?php echo zget($visibleFields, 'commiter', ' hidden') . zget($requiredFields, 'commiter', '', ' required')?>'>       <?php echo $lang->user->commiter;?></th>
            <th class='c-join<?php echo zget($visibleFields, 'join', ' hidden')?>'>    <?php echo $lang->user->join;?></th>
            <th class='c-contact<?php echo zget($visibleFields, 'skype', ' hidden')?>'>   <?php echo $lang->user->skype;?></th>
            <th class='c-contact<?php echo zget($visibleFields, 'qq', ' hidden')?>'>      <?php echo $lang->user->qq;?></th>
            <th class='c-contact<?php echo zget($visibleFields, 'dingding', ' hidden')?>'><?php echo $lang->user->dingding;?></th>
            <th class='c-contact<?php echo zget($visibleFields, 'weixin', ' hidden')?>'>  <?php echo $lang->user->weixin;?></th>
            <th class='c-contact<?php echo zget($visibleFields, 'mobile', ' hidden')?>'>  <?php echo $lang->user->mobile;?></th>
            <th class='c-contact<?php echo zget($visibleFields, 'slack', ' hidden')?>'>   <?php echo $lang->user->slack;?></th>
            <th class='c-contact<?php echo zget($visibleFields, 'whatsapp', ' hidden')?>'><?php echo $lang->user->whatsapp;?></th>
            <th class='c-contact<?php echo zget($visibleFields, 'phone', ' hidden')?>'>   <?php echo $lang->user->phone;?></th>
            <th class='c-contact<?php echo zget($visibleFields, 'address', ' hidden')?>'> <?php echo $lang->user->address;?></th>
            <th class='c-contact<?php echo zget($visibleFields, 'zipcode', ' hidden')?>'> <?php echo $lang->user->zipcode;?></th>
          </tr>
        </thead>
        <tbody>
        <?php $depts = $depts + array('ditto' => $lang->user->ditto)?>
        <?php $lang->user->roleList = $lang->user->roleList + array('ditto' => $lang->user->ditto)?>
        <?php $groupList  = $groupList + array('ditto' => $lang->user->ditto);?>
        <?php $visionList = $visionList + array('ditto' => $lang->user->ditto);?>
        <?php for($i = 1; $i <= $config->user->batchCreate; $i++):?>
        <tr class='text-center'>
          <td><?php echo $i;?></td>
          <td class='text-left hide' style='overflow:visible'>
            <div class='input-group'>
              <?php echo html::select("company[$i]", $companies, $i > 1 ? 'ditto' : '', "class='form-control chosen'");?>
              <span class='input-group-addon'><?php echo html::checkBox("new[$i]", $lang->company->create);?></span>
            </div>
          </td>
          <td class='text-left<?php echo zget($visibleFields, 'dept', ' hidden')?>' style='overflow:visible'><?php echo html::select("dept[$i]", $depts, $i > 1 ? 'ditto' : $deptID, "class='form-control chosen'");?></td>
          <td><?php echo html::input("account[$i]", '', "class='form-control account_$i' onchange='changeEmail($i)'");?></td>
          <td><?php echo html::input("realname[$i]", '', "class='form-control'");?></td>
          <?php if($showVisionList):?>
            <td class='text-left' style='overflow:visible'>
              <?php echo html::select("visions[$i][]", $visionList, $i > 1 ? 'ditto' : (isset($visionList[$this->config->vision]) ? $this->config->vision : key($visionList)), "class='form-control chosen' multiple");?>
            </td>
          <?php else:?>
            <?php echo html::hidden("visions[$i][]", $this->config->vision);?>
          <?php endif;?>
          <td><?php echo html::select("role[$i]", $lang->user->roleList, $i > 1 ? 'ditto' : '', "class='form-control' onchange='changeGroup(this.value, $i)'");?></td>
          <td class='text-left' style='overflow:visible'><?php echo html::select("group[$i][]", $groupList, $i > 1 ? 'ditto' : '', "class='form-control chosen' multiple");?></td>
          <td <?php echo zget($visibleFields, 'email', "class='hidden'")?>><?php echo html::input("email[$i]", '', "class='form-control email_$i' onchange='setDefaultEmail($i)'");?></td>
          <td <?php echo zget($visibleFields, 'gender', "class='hidden'")?>><?php echo html::radio("gender[$i]", (array)$lang->user->genderList, 'm');?></td>
          <td align='left'>
            <div class='input-group'>
            <?php
            echo html::input("password[$i]", '', "class='form-control' onkeyup='toggleCheck(this, $i)' oninput=\"this.value = this.value.replace(/[^\\x00-\\xff]/g, '');\"");
            echo "<span class='input-group-addon passwordStrength'></span>";
            if($i != 1) echo "<span class='input-group-addon passwordBox'><input type='checkbox' name='ditto[$i]' id='ditto$i' " . ($i > 1 ? "checked" : '') . " /> {$lang->user->ditto}</span>";
            ?>
            </div>
          </td>
          <td class='<?php echo zget($visibleFields, 'commiter', 'hidden')?>'><?php echo html::input("commiter[$i]", '', "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'join', 'hidden')?>'>    <?php echo html::input("join[$i]",     '', "class='form-control form-date'");?></td>
          <td class='<?php echo zget($visibleFields, 'skype', 'hidden')?>'>   <?php echo html::input("skype[$i]",    '', "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'qq', 'hidden')?>'>      <?php echo html::input("qq[$i]",       '', "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'dingding', 'hidden')?>'><?php echo html::input("dingding[$i]", '', "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'weixin', 'hidden')?>'>  <?php echo html::input("weixin[$i]",   '', "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'mobile', 'hidden')?>'>  <?php echo html::input("mobile[$i]",   '', "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'slack', 'hidden')?>'>   <?php echo html::input("slack[$i]",    '', "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'whatsapp', 'hidden')?>'><?php echo html::input("whatsapp[$i]", '', "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'phone', 'hidden')?>'>   <?php echo html::input("phone[$i]",    '', "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'address', 'hidden')?>'> <?php echo html::input("address[$i]",  '', "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'zipcode', 'hidden')?>'> <?php echo html::input("zipcode[$i]",  '', "class='form-control'");?></td>
        </tr>
        <?php endfor;?>
        <tr>
          <th colspan='2'><?php echo $lang->user->verifyPassword?></th>
          <td colspan='3'>
            <div class="required required-wrapper"></div>
            <input type='password' style="display:none"> <!-- for disable autocomplete all browser -->
            <?php echo html::password('verifyPassword', '', "class='form-control disabled-ie-placeholder' placeholder='{$lang->user->placeholder->verify}'");?>
          </td>
        </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="<?php echo count($visibleFields) + 6?>" class="text-center form-actions">
              <?php echo html::submitButton($lang->save);?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php echo html::hidden('verifyRand', $rand);?>
<?php js::set('passwordStrengthList', $lang->user->passwordStrengthList)?>
<?php js::set('batchCreateCount', $config->user->batchCreate)?>
<?php include '../../common/view/footer.html.php';?>
