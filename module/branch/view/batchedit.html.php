<?php
/**
 * The batchedit of branch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     branch
 * @version     $Id: batchedit.html.php 4903 2021-11-09 13:14:59Z hfz $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('canSetDefault', common::hasPriv('branch', 'setDefault'));?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->branch->common . '-' . $lang->branch->batchEdit;?></h2>
  </div>
  <form class="load-indicator main-form" method='post' target='hiddenwin' enctype='multipart/form-data' id='dataform'>
    <table class="table table-form">
      <thead>
        <tr>
          <th class='c-id'><?php echo $lang->branch->id;?></th>
          <th class='required c-name'><?php echo sprintf($lang->branch->name, $lang->product->branchName[$product->type]);?></th>
          <th class='c-desc'><?php echo sprintf($lang->branch->desc, $lang->product->branchName[$product->type]);?></th>
          <th class='c-status'><?php echo $lang->branch->status;?></th>
          <!-- The default branch is hidden for the moment,until this demand has been completed -->
          <th class='c-default text-center hidden'><?php echo $lang->branch->defaultBranch;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($branchList as $branch):?>
        <?php $disabled = $branch->id == BRANCH_MAIN ? 'disabled' : '';?>
        <tr>
          <td><?php echo ($branch->id == BRANCH_MAIN ? '' : $branch->id) . html::hidden("IDList[$branch->id]", $branch->id);?></td>
          <td><?php echo html::input("name[$branch->id]", $branch->name,  "class='form-control chosen' $disabled");?></td>
          <td><?php echo html::input("desc[$branch->id]", $branch->desc, "class='form-control' $disabled");?></td>
          <td><?php echo html::select("status[$branch->id]", $lang->branch->statusList, $branch->status, "class='form-control' chosen $disabled onchange='canSetDefaultBranch(this)'");?></td>
          <!-- The default branch is hidden for the moment,until this demand has been completed -->
          <td class='text-center hidden'><input type='radio' name='default' value='<?php echo $branch->id;?>' <?php if($branch->default) echo 'checked';?> <?php if($branch->status == 'closed' or !common::hasPriv('branch', 'setDefault')) echo 'disabled';?>></td>
        </tr>
        <?php endforeach;?>
        <tr>
          <td colspan='6' class='form-actions text-center'>
            <?php echo html::submitButton() . html::backButton();?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
