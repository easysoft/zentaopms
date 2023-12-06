<?php
/**
 * The stakeholder view of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->project->confirmDelete);?>
<style>.unlinkBtn {float: left; margin-right: 10px;}</style>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('program', 'stakeholder', "programID=$programID"), '<span class="text">' . $lang->program->stakeholder . '</span>', '', 'class="btn btn-link btn-active-text"');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('program', 'createStakeholder', "programID=$programID", "<i class='icon icon-plus'></i> " . $lang->program->createStakeholder, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent' class='main-row fade'>
  <div class='main-col'>
    <?php if(!empty($stakeholders)):?>
    <form class='main-table table-user' data-ride='table' action='' method='post' id='userListForm'>
      <table class='table has-sort-head' id='userList'>
        <thead>
        <tr>
          <th class='c-id'>
            <div class='checkbox-primary check-all' title="<?php echo $this->lang->selectAll;?>"><label></label></div>
            <?php echo $lang->idAB;?>
          </th>
          <th><?php echo $lang->user->realname;?></th>
          <th class="w-100px"><?php echo $lang->program->stakeholderType;?></th>
          <th class="w-120px"><?php echo $lang->user->role;?></th>
          <th class="w-120px"><?php echo $lang->user->phone;?></th>
          <th class="w-120px"><?php echo $lang->user->qq;?></th>
          <th class="w-120px"><?php echo $lang->user->weixin;?></th>
          <th class="w-200px"><?php echo $lang->user->email;?></th>
          <th class='c-actions w-60px'><?php echo $lang->actions;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($stakeholders as $stakeholder):?>
        <tr>
          <td class='c-id'>
            <?php echo html::checkbox('stakeholderIDList', array($stakeholder->id => ''));?>
            <?php printf('%03d', $stakeholder->id);?>
          </td>
          <?php $isKey = $stakeholder->key ? " <i class='icon icon-star-empty'></i>" : '';?>
          <?php $title = $stakeholder->key ? $lang->program->isStakeholderKey : '';?>
          <td title="<?php echo $title;?>"><?php echo $stakeholder->realname . $isKey;?></td>
          <td title='<?php echo zget($lang->stakeholder->fromList, $stakeholder->from, '');?>'><?php echo zget($lang->stakeholder->fromList, $stakeholder->from, '');?></td>
          <td><?php echo zget($lang->user->roleList, $stakeholder->role, '');?></td>
          <td title="<?php echo $stakeholder->phone;?>"><?php echo $stakeholder->phone;?></td>
          <td title="<?php echo $stakeholder->qq;?>"><?php echo $stakeholder->qq;?></td>
          <td title="<?php echo $stakeholder->weixin;?>"><?php echo $stakeholder->weixin;?></td>
          <td title="<?php echo $stakeholder->email;?>"><?php echo $stakeholder->email;?></td>
          <td class='c-actions'>
            <?php
            $deleteClass = common::hasPriv('program', 'unlinkStakeholder') ? 'btn' : 'btn disabled';
            echo html::a($this->createLink('program', 'unlinkStakeholder', "id=$stakeholder->id&programID=$programID&confirm=no"), '<i class="icon-unlink"></i>', 'hiddenwin', "title='{$lang->program->unlinkStakeholder}' class='{$deleteClass}'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if($stakeholders):?>
      <div class='table-footer'>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions">
          <?php $actionLink = $this->createLink('program', 'batchUnlinkStakeholders', "programID=$programID");?>
          <?php echo html::commonButton($lang->program->unlink, "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"", "btn unlinkBtn");?>
          <div class="table-statistic"></div>
        </div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
    <?php else:?>
    <div class='table-empty-tip'><?php echo $lang->noData;?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
