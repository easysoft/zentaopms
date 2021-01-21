<?php
/**
 * The project view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->my->projectMenu as $key => $label):?>
    <?php $active = $status == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($status == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('project', "type=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <?php if(empty($projects)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->program->noPRJ;?></span>
      <?php if(common::hasPriv('program', 'createGuide')):?>
      <?php echo html::a($this->createLink('program', 'createGuide'), "<i class='icon icon-plus'></i> " . $lang->my->createProgram, '', "class='btn btn-info' data-toggle=modal");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' id='programForm' method='post' data-ride='table' data-nested='true' data-checkable='false'>
    <table class='table table-fixed' id='programList'>
      <thead>
        <tr>
          <th class='c-id w-50px'><?php echo $lang->idAB;?></th>
          <th><?php echo $lang->program->PRJName;?></th>
          <?php if($status == 'openedbyme'):?>
          <th class='w-80px'> <?php echo $lang->program->PRJStatus;?></th>
          <?php endif;?>
          <th class='w-100px'><?php echo $lang->program->begin;?></th>
          <th class='w-100px'><?php echo $lang->program->end;?></th>
          <th class='w-100px'><?php echo $lang->program->PRJBudget;?></th>
          <th class='w-100px'><?php echo $lang->program->PRJPM;?></th>
          <th class='text-center w-180px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody id='programTableList'>
        <?php foreach($projects as $project):?>
        <tr>
          <td class='c-id'><?php printf('%03d', $project->id);?></td>
          <td class='c-name text-left' title='<?php echo $project->name?>'>
            <?php echo html::a($this->createLink('program', 'index', "projectID=$project->id", '', '', $project->id), $project->name, '', "data-group='project'");?>
          </td>
          <?php if($status == 'openedbyme'):?>
          <td class='c-status'><span class="status-program status-<?php echo $project->status?>"><?php echo zget($lang->project->statusList, $project->status, '');?></span></td>
          <?php endif;?>
          <td class='text-left'><?php echo $project->begin;?></td>
          <td class='text-left'><?php echo $project->end == '0000-00-00' ? '' : $project->end;?></td>
          <td class='text-left'><?php echo $project->budget != 0 ? zget($lang->program->currencySymbol, $project->budgetUnit) . number_format($project->budget, 2) : $lang->program->future;?></td>
          <td>
            <?php $userID = isset($PMList[$project->PM]) ? $PMList[$project->PM]->id : ''?>
            <?php if(!empty($project->PM)) echo html::a($this->createLink('user', 'profile', "userID=$userID", '', true), zget($users, $project->PM), '', "data-toggle='modal' data-type='iframe' data-width='800'");?>
          </td>
          <td class='c-actions'>
            <?php if($project->status == 'wait' || $project->status == 'suspended') common::printIcon('program', 'PRJStart', "projectID=$project->id", $project, 'list', 'play', '', 'iframe', true);?>
            <?php if($project->status == 'doing')  common::printIcon('program', 'PRJClose',    "projectID=$project->id", $project, 'list', 'off',   '', 'iframe', true);?>
            <?php if($project->status == 'closed') common::printIcon('program', 'PRJActivate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe', true);?>
            <div class='btn-group'>
              <button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>" style="width: 16px; padding-left: 0px;"></button>
              <ul class='dropdown-menu pull-right text-center' role='menu' style="min-width:auto; padding: 5px 10px;">
              <?php common::printIcon('program', 'PRJSuspend', "projectID=$project->id", $project, 'list', 'pause', '', 'iframe', true);?>
              <?php if($project->status != 'doing')  common::printIcon('program', 'PRJClose',    "projectID=$project->id", $project, 'list', 'off',   '', 'iframe', true);?>
              <?php if($project->status != 'closed') common::printIcon('program', 'PRJActivate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe', true);?>
              </ul>
            </div>
            <?php common::printIcon('program', 'PRJEdit', "projectID=$project->id&programID=$project->parent&from=PRJ", $project, 'list', 'edit', '',  '', false, "data-group='project'", '', $project->id);?>
            <?php common::printIcon('program', 'PRJManageMembers', "projectID=$project->id", $project, 'list', 'group', '', '', false, "data-group='project'", '', $project->id);?>
            <?php common::printIcon('program', 'PRJGroup', "projectID=$project->id", $project, 'list', 'lock', '',  '', false, "data-group='project'", '', $project->id);?>
            <div class='btn-group'>
              <button type='button' class='btn dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>"><i class='icon-more-alt'></i></button>
              <ul class='dropdown-menu pull-right text-center' role='menu'>
                <?php common::printIcon('program', 'PRJManageProducts', "projectID=$project->id&programID=$project->parent&from=PRJ", $project, 'list', 'link', '', '', false, "data-group='project'", '', $project->id);?>
                <?php common::printIcon('program', 'PRJWhitelist', "projectID=$project->id&programID=$project->parent&module=program&from=PRJ", $project, 'list', 'shield-check', '', '', false, "data-group='project'", '', $project->id);?>
                <?php if(common::hasPriv('program','PRJDelete')) echo html::a($this->createLink("program", "PRJDelete", "projectID=$project->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$this->lang->program->PRJDelete}' data-group='my'");?>
              </ul>
            </div>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
