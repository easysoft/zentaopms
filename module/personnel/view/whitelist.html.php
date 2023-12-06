<?php
/**
 * The whitelist view of personnel module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     personnel
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <?php $tab = $module == 'program' ? ($from == 'project' || $from == 'my' ? "data-group='project'" : "data-group='program'") : '';?>
  <div class="btn-toolbar pull-left">
    <?php if($module == 'program') echo html::a($goback, $lang->goback, '', 'class="btn btn-secondary"');?>
    <?php $vars = $module == 'program' ? "objectID=$objectID&programID=$programID&module=$module&from=$from" : "objectID=$objectID";?>
    <?php echo html::a($this->createLink($module, 'whitelist', $vars), '<span class="text">' . $lang->personnel->whitelist . '</span>', '', "class='btn btn-link btn-active-text' $tab");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php $vars = $module == 'program' ? "objectID=$objectID&deptID=0&copyID=0&programID=$programID&from=$from" : "objectID=$objectID";?>
    <?php common::printLink($module, 'addWhitelist', $vars, "<i class='icon icon-plus'></i> " . $lang->personnel->addWhitelist, '', "class='btn btn-primary' $tab");?>
  </div>
</div>
<div id='mainContent' class='main-row fade'>
  <div class='main-col'>
    <?php if(!empty($whitelist)):?>
    <form class='main-table table-user' action='' method='post' id='userListForm'>
      <table class='table has-sort-head' id='userList'>
        <thead>
        <tr>
          <th class='c-id'>
            <?php echo $lang->idAB;?>
          </th>
          <th><?php echo $lang->user->realname;?></th>
          <th class="c-dept"><?php echo $lang->user->dept;?></th>
          <th class="c-role"><?php echo $lang->user->role;?></th>
          <th class="c-phone"><?php echo $lang->user->phone;?></th>
          <th class="c-qq"><?php echo $lang->user->qq;?></th>
          <th class="c-weixin"><?php echo $lang->user->weixin;?></th>
          <th class="c-email"><?php echo $lang->user->email;?></th>
          <th class='c-actions-2'><?php echo $lang->actions;?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($whitelist as $user):?>
        <tr>
          <td class='c-id'>
            <?php printf('%03d', $user->id);?>
          </td>
          <td><?php echo $user->realname;?></td>
          <td class='c-dept' title="<?php echo zget($depts, $user->dept);?>"><?php echo zget($depts, $user->dept);?></td>
          <td title="<?php echo zget($lang->user->roleList, $user->role)?>"><?php echo zget($lang->user->roleList, $user->role);?></td>
          <td title="<?php echo $user->phone;?>"><?php echo $user->phone;?></td>
          <td title="<?php echo $user->qq;?>"><?php echo $user->qq;?></td>
          <td title="<?php echo $user->weixin;?>"><?php echo $user->weixin;?></td>
          <td title="<?php echo $user->email;?>"><?php echo $user->email;?></td>
          <td class='c-actions'>
            <?php
            if($this->app->tab == 'program') $module = 'program';
            if(common::hasPriv($module, 'unbindWhitelist')) echo html::a($this->createLink($module, 'unbindWhitelist', "id=$user->id&confirm=no"), '<i class="icon-unlink"></i>', 'hiddenwin', "title='{$lang->personnel->delete}' class='btn' $tab");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if($whitelist):?>
      <div class='table-footer'>
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
