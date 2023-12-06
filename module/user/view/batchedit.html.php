<?php
/**
 * The batch create view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::import($jsRoot . 'md5.js');?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->user->batchEdit;?></h2>
    <div class="pull-right btn-toolbar">
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=user&section=custom&key=batchEditFields')?>
      <?php include '../../common/view/customfield.html.php';?>
    </div>
  </div>
  <?php
  $visibleFields  = array();
  $requiredFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if(strpos(",{$config->user->availableBatchEditFields},", ",{$field},") === false) continue;
      if($field)$visibleFields[$field] = '';
  }

  foreach(explode(',', $config->user->edit->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->user->availableBatchEditFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  $minWidth = (count($visibleFields) > 7) ? 'w-120px' : '';
  $showVisionList = count($visionList) > 1;
  ?>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchCreateForm">
    <div class="table-responsive">
      <table class="table table-form">
        <thead>
          <tr class='text-center'>
            <th class='w-30px'><?php echo $lang->idAB;?></th>
            <th class='w-150px<?php echo zget($visibleFields, 'dept', ' hidden')?>'>         <?php echo $lang->user->dept;?></th>
            <th class='<?php echo $minWidth?> required'><?php echo $lang->user->account;?></th>
            <th class='<?php echo $minWidth?> required'><?php echo $lang->user->realname;?></th>
            <?php if($showVisionList):?>
              <th class='w-130px required'><?php echo $lang->user->visions;?></th>
            <?php endif;?>
            <th class='w-120px'><?php echo $lang->user->role;?></th>
            <th class='w-120px'><?php echo $lang->user->type;?></th>
            <th class='<?php echo $minWidth . zget($visibleFields, 'commiter', ' hidden')?>'><?php echo $lang->user->commiter;?></th>
            <th class='<?php echo $minWidth . zget($visibleFields, 'email', ' hidden')?>'>   <?php echo $lang->user->email;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'join', ' hidden')?>'>         <?php echo $lang->user->join;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'skype', ' hidden') . zget($requiredFields, 'skype', '', ' required')?>'>      <?php echo $lang->user->skype;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'qq', ' hidden') . zget($requiredFields, 'qq', '', ' required')?>'>            <?php echo $lang->user->qq;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'dingding', ' hidden') . zget($requiredFields, 'dingding', '', ' required')?>'><?php echo $lang->user->dingding;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'weixin', ' hidden') . zget($requiredFields, 'weixin', '', ' required')?>'>    <?php echo $lang->user->weixin;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'mobile', ' hidden') . zget($requiredFields, 'mobile', '', ' required')?>'>    <?php echo $lang->user->mobile;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'slack', ' hidden') . zget($requiredFields, 'slack', '', ' required')?>'>      <?php echo $lang->user->slack;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'whatsapp', ' hidden') . zget($requiredFields, 'whatsapp', '', ' required')?>'><?php echo $lang->user->whatsapp;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'phone', ' hidden') . zget($requiredFields, 'phone', '', ' required')?>'>      <?php echo $lang->user->phone;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'address', ' hidden')?>'>      <?php echo $lang->user->address;?></th>
            <th class='w-120px<?php echo zget($visibleFields, 'zipcode', ' hidden')?>'>      <?php echo $lang->user->zipcode;?></th>
          </tr>
        </thead>
        <tbody>
        <?php $depts = array('ditto' => $lang->user->ditto) + $depts;?>
        <?php $lang->user->roleList = array('' => '', 'ditto' => $lang->user->ditto) + $lang->user->roleList;?>
        <?php $first = true;?>
        <?php foreach($users as $user):?>
        <?php
        $dept  = ($first and empty($user->dept)) ? 0 : (empty($user->dept) ? 'ditto' : $user->dept);
        $role  = ($first and empty($user->role)) ? 0 : (empty($user->role) ? 'ditto' : $user->role);
        $type  = empty($user->type) ? 'inside' : $user->type;
        $first = false;
        ?>
        <tr class='text-center'>
          <td><?php echo $user->id;?></td>
          <td class='text-left<?php echo zget($visibleFields, 'dept', ' hidden')?>' style='overflow:visible'><?php echo html::select("dept[$user->id]", $depts, $dept, "class='form-control chosen'");?></td>
          <td><?php echo html::input("account[$user->id]",  $user->account, "class='form-control' readonly");?></td>
          <td><?php echo html::input("realname[$user->id]", $user->realname, "class='form-control'");?></td>
          <?php if($showVisionList):?>
            <td class='text-left'><?php echo html::select("visions[$user->id][]", $visionList, $user->visions, "class='form-control chosen' multiple");?></td>
          <?php else:?>
            <?php echo html::hidden("visions[$user->id][]", $this->config->vision);?>
          <?php endif;?>
          <td><?php echo html::select("role[$user->id]",    $lang->user->roleList, $role, "class='form-control'");?></td>
          <td><?php echo html::select("type[$user->id]",    $lang->user->typeList, $type, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'commiter', 'hidden')?>'><?php echo html::input("commiter[$user->id]", $user->commiter, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'email', 'hidden')?>'>   <?php echo html::input("email[$user->id]",    $user->email, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'join', 'hidden')?>'>    <?php echo html::input("join[$user->id]",     $user->join, "class='form-control form-date'");?></td>
          <td class='<?php echo zget($visibleFields, 'skype', 'hidden')?>'>   <?php echo html::input("skype[$user->id]",    $user->skype, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'qq', 'hidden')?>'>      <?php echo html::input("qq[$user->id]",       $user->qq, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'dingding', 'hidden')?>'><?php echo html::input("dingding[$user->id]", $user->dingding, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'weixin', 'hidden')?>'>  <?php echo html::input("weixin[$user->id]",   $user->weixin, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'mobile', 'hidden')?>'>  <?php echo html::input("mobile[$user->id]",   $user->mobile, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'slack', 'hidden')?>'>   <?php echo html::input("slack[$user->id]",    $user->slack, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'whatsapp', 'hidden')?>'><?php echo html::input("whatsapp[$user->id]", $user->whatsapp, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'phone', 'hidden')?>'>   <?php echo html::input("phone[$user->id]",    $user->phone, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'address', 'hidden')?>'> <?php echo html::input("address[$user->id]",  $user->address, "class='form-control'");?></td>
          <td class='<?php echo zget($visibleFields, 'zipcode', 'hidden')?>'> <?php echo html::input("zipcode[$user->id]",  $user->zipcode, "class='form-control'");?></td>
        </tr>
        <?php endforeach;?>
        <tr>
          <th colspan='4'><?php echo $lang->user->verifyPassword?></th>
          <td colspan='2'>
            <div class="required required-wrapper"></div>
            <input type='text'     style="display:none"> <!-- for disable autocomplete all browser -->
            <input type='password' style="display:none"> <!-- for disable autocomplete all browser -->
            <?php echo html::password('verifyPassword', '', "class='form-control disabled-ie-placeholder' placeholder='{$lang->user->placeholder->verify}'");?>
          </td>
        </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="<?php echo count($visibleFields) + 6;?>" class="text-center form-actions">
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
<?php include '../../common/view/footer.html.php';?>
