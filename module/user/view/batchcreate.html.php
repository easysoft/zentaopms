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
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('roleGroup', $roleGroup);?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['user']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchCreate']);?></small> <?php echo $lang->user->batchCreate;?></strong>
    <div class='actions'>
      <button type="button" class="btn btn-default" data-toggle="customModal"><i class='icon icon-cog'></i> </button>
    </div>
  </div>
</div>
<?php
$visibleFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field)$visibleFields[$field] = '';
}
$minWidth = (count($visibleFields) > 5) ? 'w-150px' : '';
?>
<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
  <table class='table table-form table-fixed'> 
    <thead>
      <tr>
        <th class='w-40px'><?php echo $lang->idAB;?></th> 
        <th class='w-150px<?php echo zget($visibleFields, 'dept', ' hidden')?>'><?php echo $lang->user->dept;?></th>
        <th class='w-130px'><?php echo $lang->user->account;?> <span class='required'></span></th>
        <th class='w-130px'><?php echo $lang->user->realname;?> <span class='required'></span></th>
        <th class='w-120px'><?php echo $lang->user->role;?> <span class='required'></span></th>
        <th class='w-120px'><?php echo $lang->user->group;?></th>
        <th class='<?php echo zget($visibleFields, 'email', "$minWidth hidden", $minWidth)?>'><?php echo $lang->user->email;?></th>
        <th class='w-90px<?php echo zget($visibleFields, 'gender', ' hidden')?>'><?php echo $lang->user->gender;?></th>
        <th class="<?php echo $minWidth?>"><?php echo $lang->user->password;?> <span class='required'></span></th>
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
      </itd>
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
    <tr><td colspan='<?php echo count($visibleFields) + 6?>' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=user&section=custom&key=batchCreateFields')?>
<?php include '../../common/view/customfield.html.php';?>
<?php include '../../common/view/footer.html.php';?>
