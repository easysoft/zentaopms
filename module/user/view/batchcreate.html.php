<?php
/**
 * The batch create view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::import($jsRoot . 'md5.js');?>
<?php js::set('roleGroup', $roleGroup);?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->user->batchCreate;?></h2>
    <div class="pull-right btn-toolbar">
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=user&section=custom&key=batchCreateFields')?>
      <?php include '../../common/view/customfield.html.php';?>
    </div>
  </div>
  <?php
  $visibleFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if($field) $visibleFields[$field] = '';
  }
  $minWidth = (count($visibleFields) > 5) ? 'w-150px' : '';
  ?>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchCreateForm">
    <div class="table-responsive">
      <table class="table table-form">
        <thead>
          <tr class='text-center'>
            <th class='w-40px'><?php echo $lang->idAB;?></th> 
            <th class='w-150px<?php echo zget($visibleFields, 'dept', ' hidden')?>'><?php echo $lang->user->dept;?></th>
            <th class='w-130px required'><?php echo $lang->user->account;?></th>
            <th class='w-130px required'><?php echo $lang->user->realname;?></th>
            <th class='w-120px'><?php echo $lang->user->role;?></th>
            <th class='w-120px'><?php echo $lang->user->group;?></th>
            <th class='<?php echo zget($visibleFields, 'email', "$minWidth hidden", $minWidth)?>'><?php echo $lang->user->email;?></th>
            <th class='w-90px<?php echo zget($visibleFields, 'gender', ' hidden')?>'><?php echo $lang->user->gender;?></th>
            <th class="<?php echo $minWidth;?> required"><?php echo $lang->user->password;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'commiter', ' hidden')?>'><?php echo $lang->user->commiter;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'join', ' hidden')?>'>    <?php echo $lang->user->join;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'skype', ' hidden')?>'>   <?php echo $lang->user->skype;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'qq', ' hidden')?>'>      <?php echo $lang->user->qq;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'yahoo', ' hidden')?>'>   <?php echo $lang->user->yahoo;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'gtalk', ' hidden')?>'>   <?php echo $lang->user->gtalk;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'wangwang', ' hidden')?>'><?php echo $lang->user->wangwang;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'mobile', ' hidden')?>'>  <?php echo $lang->user->mobile;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'phone', ' hidden')?>'>   <?php echo $lang->user->phone;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'address', ' hidden')?>'> <?php echo $lang->user->address;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'zipcode', ' hidden')?>'> <?php echo $lang->user->zipcode;?></th>
          </tr>
        </thead>
        <tbody>
        <?php $depts = $depts + array('ditto' => $lang->user->ditto)?>
        <?php $lang->user->roleList = $lang->user->roleList + array('ditto' => $lang->user->ditto)?>
        <?php $groupList = $groupList + array('ditto' => $lang->user->ditto)?>
        <?php for($i = 0; $i < $config->user->batchCreate; $i++):?>
        <tr class='text-center'>
          <td><?php echo $i+1;?></td>
          <td class='text-left<?php echo zget($visibleFields, 'dept', ' hidden')?>' style='overflow:visible'><?php echo html::select("dept[$i]", $depts, $i > 0 ? 'ditto' : $deptID, "class='form-control chosen'");?></td>
          <td><?php echo html::input("account[$i]", '', "class='form-control account_$i' autocomplete='off' onchange='changeEmail($i)'");?></td>
          <td><?php echo html::input("realname[$i]", '', "class='form-control' autocomplete='off'");?></td>
          <td><?php echo html::select("role[$i]", $lang->user->roleList, $i > 0 ? 'ditto' : '', "class='form-control' onchange='changeGroup(this.value, $i)'");?></td>
          <td class='text-left' style='overflow:visible'><?php echo html::select("group[$i]", $groupList, $i > 0 ? 'ditto' : '', "class='form-control chosen'");?></td>
          <td <?php echo zget($visibleFields, 'email', "class='hidden'")?>><?php echo html::input("email[$i]", '', "class='form-control email_$i' onchange='setDefaultEmail($i)' autocomplete='off'");?></td>
          <td <?php echo zget($visibleFields, 'gender', "class='hidden'")?>><?php echo html::radio("gender[$i]", (array)$lang->user->genderList, 'm');?></td>
          <td align='left'>
            <div class='input-group'>
            <?php
            echo html::input("password[$i]", '', "class='form-control' autocomplete='off' onkeyup='toggleCheck(this, $i)'");
            if($i != 0) echo "<span class='input-group-addon'><input type='checkbox' name='ditto[$i]' id='ditto$i' " . ($i> 0 ? "checked" : '') . " /> {$lang->user->ditto}</span>";
            ?>
            </div>
          </td>
          <td class='<?php echo zget($visibleFields, 'commiter', 'hidden')?>'><?php echo html::input("commiter[$i]", '', "class='form-control' autocomplete='off'");?></td>
          <td class='<?php echo zget($visibleFields, 'join', 'hidden')?>'>    <?php echo html::input("join[$i]",     '', "class='form-control form-date' autocomplete='off'");?></td>
          <td class='<?php echo zget($visibleFields, 'skype', 'hidden')?>'>   <?php echo html::input("skype[$i]",    '', "class='form-control' autocomplete='off'");?></td>
          <td class='<?php echo zget($visibleFields, 'qq', 'hidden')?>'>      <?php echo html::input("qq[$i]",       '', "class='form-control' autocomplete='off'");?></td>
          <td class='<?php echo zget($visibleFields, 'yahoo', 'hidden')?>'>   <?php echo html::input("yahoo[$i]",    '', "class='form-control' autocomplete='off'");?></td>
          <td class='<?php echo zget($visibleFields, 'gtalk', 'hidden')?>'>   <?php echo html::input("gtalk[$i]",    '', "class='form-control' autocomplete='off'");?></td>
          <td class='<?php echo zget($visibleFields, 'wangwang', 'hidden')?>'><?php echo html::input("wangwang[$i]", '', "class='form-control' autocomplete='off'");?></td>
          <td class='<?php echo zget($visibleFields, 'mobile', 'hidden')?>'>  <?php echo html::input("mobile[$i]",   '', "class='form-control' autocomplete='off'");?></td>
          <td class='<?php echo zget($visibleFields, 'phone', 'hidden')?>'>   <?php echo html::input("phone[$i]",    '', "class='form-control' autocomplete='off'");?></td>
          <td class='<?php echo zget($visibleFields, 'address', 'hidden')?>'> <?php echo html::input("address[$i]",  '', "class='form-control' autocomplete='off'");?></td>
          <td class='<?php echo zget($visibleFields, 'zipcode', 'hidden')?>'> <?php echo html::input("zipcode[$i]",  '', "class='form-control' autocomplete='off'");?></td>
        </tr>
        <?php endfor;?>
        <tr>
          <th colspan='2'><?php echo $lang->user->verifyPassword?></th>
          <td colspan='<?php echo count($visibleFields) + 4?>'>
            <div class="required required-wrapper"></div>
            <input type='password' style="display:none"> <!-- for disable autocomplete all browser -->
            <?php echo html::password('verifyPassword', '', "class='form-control disabled-ie-placeholder' autocomplete='off' placeholder='{$lang->user->placeholder->verify}'");?>
          </td>
        </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="<?php echo count($visibleFields) + 6?>" class="text-center form-actions">
              <?php echo html::submitButton($lang->save, '', 'btn btn-wide btn-primary');?>
              <?php echo html::backButton('', '', "btn btn-wide");?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php echo html::hidden('verifyRand', $rand);?>
<?php include '../../common/view/footer.html.php';?>
