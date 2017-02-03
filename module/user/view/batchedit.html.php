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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['user']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $lang->user->batchEdit;?></strong>
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
$minWidth = (count($visibleFields) > 7) ? 'w-120px' : '';
?>
<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
  <table class='table table-form table-fixed'>
    <thead>
      <tr>
        <th class='w-30px'><?php echo $lang->idAB;?></th> 
        <th class='w-150px<?php echo zget($visibleFields, 'dept', ' hidden')?>'>         <?php echo $lang->user->dept;?></th>
        <th class='<?php echo $minWidth?>'><?php echo $lang->user->account;?></th>
        <th class='<?php echo $minWidth?>'><?php echo $lang->user->realname;?></th>
        <th class='w-120px'><?php echo $lang->user->role;?></th>
        <th class='<?php echo $minWidth . zget($visibleFields, 'commiter', ' hidden')?>'><?php echo $lang->user->commiter;?></th>
        <th class='<?php echo $minWidth . zget($visibleFields, 'email', ' hidden')?>'>   <?php echo $lang->user->email;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'join', ' hidden')?>'>         <?php echo $lang->user->join;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'skype', ' hidden')?>'>        <?php echo $lang->user->skype;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'qq', ' hidden')?>'>           <?php echo $lang->user->qq;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'yahoo', ' hidden')?>'>        <?php echo $lang->user->yahoo;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'gtalk', ' hidden')?>'>        <?php echo $lang->user->gtalk;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'wangwang', ' hidden')?>'>     <?php echo $lang->user->wangwang;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'mobile', ' hidden')?>'>       <?php echo $lang->user->mobile;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'phone', ' hidden')?>'>        <?php echo $lang->user->phone;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'address', ' hidden')?>'>      <?php echo $lang->user->address;?></th>
        <th class='w-120px<?php echo zget($visibleFields, 'zipcode', ' hidden')?>'>      <?php echo $lang->user->zipcode;?></th>
      </tr>
    </thead>
    <?php $depts = array('ditto' => $lang->user->ditto) + $depts;?>
    <?php $lang->user->roleList = array('' => '', 'ditto' => $lang->user->ditto) + $lang->user->roleList;?>
    <?php $first = true;?>
    <?php foreach($users as $user):?>
    <?php
    $dept  = ($first and empty($user->dept)) ? 0 : (empty($user->dept) ? 'ditto' : $user->dept); 
    $role  = ($first and empty($user->role)) ? 0 : (empty($user->role) ? 'ditto' : $user->role); 
    $first = false;
    ?>
    <tr class='text-center'>
      <td><?php echo $user->id;?></td>
      <td class='text-left<?php echo zget($visibleFields, 'dept', ' hidden')?>' style='overflow:visible'><?php echo html::select("dept[$user->id]", $depts, $dept, "class='form-control chosen'");?></td>
      <td><?php echo html::input("account[$user->id]",  $user->account, "class='form-control' autocomplete='off'");?></td>
      <td><?php echo html::input("realname[$user->id]", $user->realname, "class='form-control' autocomplete='off'");?></td>
      <td><?php echo html::select("role[$user->id]",    $lang->user->roleList, $role, "class='form-control'");?></td>
      <td class='<?php echo zget($visibleFields, 'commiter', 'hidden')?>'><?php echo html::input("commiter[$user->id]", $user->commiter, "class='form-control' autocomplete='off'");?></td>
      <td class='<?php echo zget($visibleFields, 'email', 'hidden')?>'>   <?php echo html::input("email[$user->id]",    $user->email, "class='form-control' autocomplete='off'");?></td>
      <td class='<?php echo zget($visibleFields, 'join', 'hidden')?>'>    <?php echo html::input("join[$user->id]",     $user->join, "class='form-control form-date'");?></td>
      <td class='<?php echo zget($visibleFields, 'skype', 'hidden')?>'>   <?php echo html::input("skype[$user->id]",    $user->skype, "class='form-control' autocomplete='off'");?></td>
      <td class='<?php echo zget($visibleFields, 'qq', 'hidden')?>'>      <?php echo html::input("qq[$user->id]",       $user->qq, "class='form-control' autocomplete='off'");?></td>
      <td class='<?php echo zget($visibleFields, 'yahoo', 'hidden')?>'>   <?php echo html::input("yahoo[$user->id]",    $user->yahoo, "class='form-control' autocomplete='off'");?></td>
      <td class='<?php echo zget($visibleFields, 'gtalk', 'hidden')?>'>   <?php echo html::input("gtalk[$user->id]",    $user->gtalk, "class='form-control' autocomplete='off'");?></td>
      <td class='<?php echo zget($visibleFields, 'wangwang', 'hidden')?>'><?php echo html::input("wangwang[$user->id]", $user->wangwang, "class='form-control' autocomplete='off'");?></td>
      <td class='<?php echo zget($visibleFields, 'mobile', 'hidden')?>'>  <?php echo html::input("mobile[$user->id]",   $user->mobile, "class='form-control' autocomplete='off'");?></td>
      <td class='<?php echo zget($visibleFields, 'phone', 'hidden')?>'>   <?php echo html::input("phone[$user->id]",    $user->phone, "class='form-control' autocomplete='off'");?></td>
      <td class='<?php echo zget($visibleFields, 'address', 'hidden')?>'> <?php echo html::input("address[$user->id]",  $user->address, "class='form-control' autocomplete='off'");?></td>
      <td class='<?php echo zget($visibleFields, 'zipcode', 'hidden')?>'> <?php echo html::input("zipcode[$user->id]",  $user->zipcode, "class='form-control' autocomplete='off'");?></td>
    </tr>
    <?php endforeach;?>
    <tr>
      <th colspan='2'><?php echo $lang->user->verifyPassword?></th>
      <td colspan='<?php echo count($visibleFields) + 2?>'>
        <div class="required required-wrapper"></div>
        <input type='text'     style="display:none"> <!-- for disable autocomplete all browser -->
        <input type='password' style="display:none"> <!-- for disable autocomplete all browser -->
        <?php echo html::password('verifyPassword', '', "class='form-control disabled-ie-placeholder' autocomplete='off' placeholder='{$lang->user->placeholder->verify}'");?>
      </td>
    </tr>
    <tr><td colspan='<?php echo count($visibleFields) + 4?>' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=user&section=custom&key=batchEditFields')?>
<?php include '../../common/view/customfield.html.php';?>
<?php include '../../common/view/footer.html.php';?>
