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
      <span class="text-muted"><?php echo $lang->project->empty;?></span>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' id='projectForm' method='post' data-ride='table' data-nested='true' data-checkable='false'>
    <table class='table table-fixed' id='projectList'>
      <thead>
        <tr>
          <th class='c-id w-50px'><?php echo $lang->idAB;?></th>
          <th><?php echo $lang->project->name;?></th>
          <?php if($status == 'openedbyme'):?>
          <th class='w-80px'> <?php echo $lang->project->status;?></th>
          <?php endif;?>
          <th class='w-100px'><?php echo $lang->project->begin;?></th>
          <th class='w-100px'><?php echo $lang->project->end;?></th>
          <th class='text-right w-100px'><?php echo $lang->project->budget;?></th>
          <th class='w-100px'><?php echo $lang->project->PM;?></th>
          <th class='text-center w-180px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody id='projectTableList'>
        <?php foreach($projects as $project):?>
        <tr>
          <td class='c-id'><?php printf('%03d', $project->id);?></td>
          <td class='c-name text-left' title='<?php echo $project->name?>'>
            <?php
            if(isset($this->config->maxVersion))
            {
                if($project->model === 'waterfall') echo "<span class='project-type-label label label-outline label-warning'>{$lang->project->waterfall}</span> ";
                if($project->model === 'scrum')     echo "<span class='project-type-label label label-outline label-info'>{$lang->project->scrum}</span> ";
            }
            echo html::a($this->createLink('project', 'index', "projectID=$project->id", '', '', $project->id), $project->name, '', "data-group='project'");
            ?>
          </td>
          <?php if($status == 'openedbyme'):?>
          <td class='c-status'><span class="status-project status-<?php echo $project->status?>"><?php echo zget($lang->project->statusList, $project->status, '');?></span></td>
          <?php endif;?>
          <td class='text-left'><?php echo $project->begin;?></td>
          <td class='text-left'><?php echo $project->end == LONG_TIME ? $this->lang->project->longTime : $project->end;?></td>
          <?php $projectBudget = in_array($this->app->getClientLang(), ['zh-cn','zh-tw']) ? round((float)$project->budget / 10000, 2) . $this->lang->project->tenThousand : round((float)$project->budget, 2);?>
          <td class='text-right'><?php echo $project->budget != 0 ? zget($lang->project->currencySymbol, $project->budgetUnit) . ' ' . $projectBudget : $lang->project->future;?></td>
          <td>
            <?php $userID = isset($PMList[$project->PM]) ? $PMList[$project->PM]->id : ''?>
            <?php if(!empty($project->PM)) echo html::a($this->createLink('user', 'profile', "userID=$userID", '', true), zget($users, $project->PM), '', "data-toggle='modal' data-type='iframe' data-width='600'");?>
          </td>
          <td class='c-actions'>
            <?php if($project->status == 'wait' || $project->status == 'suspended') common::printIcon('project', 'start', "projectID=$project->id", $project, 'list', 'play', '', 'iframe', true);?>
            <?php if($project->status == 'doing')  common::printIcon('project', 'close',    "projectID=$project->id", $project, 'list', 'off',   '', 'iframe', true);?>
            <?php if($project->status == 'closed') common::printIcon('project', 'activate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe', true);?>
            <?php if(common::hasPriv('project','suspend') || (common::hasPriv('project','close') && $project->status != 'doing') || (common::hasPriv('project','activate') && $project->status != 'closed')):?>
            <div class='btn-group'>
              <button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>" style="width: 16px; padding-left: 0px;"></button>
              <ul class='dropdown-menu pull-right text-center' role='menu' style="min-width:auto; padding: 5px 10px;">
              <?php common::printIcon('project', 'suspend', "projectID=$project->id", $project, 'list', 'pause', '', 'iframe', true);?>
              <?php if($project->status != 'doing')  common::printIcon('project', 'close',    "projectID=$project->id", $project, 'list', 'off',   '', 'iframe', true);?>
              <?php if($project->status != 'closed') common::printIcon('project', 'activate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe', true);?>
              </ul>
            </div>
            <?php endif;?>
            <?php common::printIcon('project', 'edit', "projectID=$project->id", $project, 'list', 'edit', '',  '', false, "data-group='project'", '', $project->id);?>
            <?php common::printIcon('project', 'manageMembers', "projectID=$project->id", $project, 'list', 'group', '', '', false, "data-group='project'", '', $project->id);?>
            <?php common::printIcon('project', 'group', "projectID=$project->id", $project, 'list', 'lock', '',  '', false, "data-group='project'", '', $project->id);?>
            <?php if(common::hasPriv('project','manageProducts') || common::hasPriv('project','whitelist') || common::hasPriv('project','delete')):?>
            <div class='btn-group'>
              <button type='button' class='btn dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>"><i class='icon-more-alt'></i></button>
              <ul class='dropdown-menu pull-right text-center' role='menu'>
                <?php common::printIcon('project', 'manageProducts', "projectID=$project->id", $project, 'list', 'link', '', '', false, "data-group='project'", '', $project->id);?>
                <?php common::printIcon('project', 'whitelist', "projectID=$project->id&module=project", $project, 'list', 'shield-check', '', '', false, "data-group='project'", '', $project->id);?>
                <?php if(common::hasPriv('project','delete')) echo html::a($this->createLink("project", "delete", "projectID=$project->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn' title='{$this->lang->project->delete}' data-group='my'");?>
              </ul>
            </div>
            <?php endif;?>
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
