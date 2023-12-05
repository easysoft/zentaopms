<?php
/**
 * The project view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->my->featureBar['project'] as $key => $label):?>
    <?php $active = $status == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($status == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('project', "type=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($projects)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->project->empty;?></span>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' id='projectForm' method='post' data-ride='table' data-checkable='false'>
    <table class='table has-sort-head table-fixed' id='projectList'>
      <?php $vars = "status=$status&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&orderBy=%s"; ?>
      <thead>
        <tr>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
          <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->project->name);?></th>
          <th class='c-user'><?php common::printOrderLink('PM', $orderBy, $vars, $lang->project->PM);?></th>
          <?php if($status == 'openedbyme'):?>
          <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->project->status);?></th>
          <?php endif;?>
          <th class='text-right c-budget'><?php common::printOrderLink('budget', $orderBy, $vars, $lang->project->budget);?></th>
          <th class='c-date c-begin'><?php common::printOrderLink('begin', $orderBy, $vars, $lang->project->begin);?></th>
          <th class='c-date c-end'><?php common::printOrderLink('end', $orderBy, $vars, $lang->project->end);?></th>
          <th class='text-center c-actions-6'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody id='projectTableList'>
        <?php
        $waitCount      = 0;
        $doingCount     = 0;
        $suspendedCount = 0;
        $closedCount    = 0;
        ?>
        <?php foreach($projects as $project):?>
        <?php if($project->status == 'wait')      $waitCount ++;?>
        <?php if($project->status == 'doing')     $doingCount ++;?>
        <?php if($project->status == 'suspended') $suspendedCount ++;?>
        <?php if($project->status == 'closed')    $closedCount ++;?>
        <tr>
          <td class='c-id'><?php printf('%03d', $project->id);?></td>
          <td class='c-name text-left' title='<?php echo $project->name?>'>
            <?php
            $suffix      = '';
            $projectType = $project->model == 'scrum' ? 'sprint' : $project->model;
            if(isset($project->delay)) $suffix = "<span class='label label-danger label-badge'>{$lang->project->statusList['delay']}</span></div>";
            if(!empty($suffix)) echo '<div class="project-name has-suffix">';
            echo html::a($this->createLink('project', 'index', "projectID=$project->id"), "<i class='icon icon-{$projectType} text-muted'></i> " . $project->name, '', "data-app='project' title='{$project->name}'");
            if(!empty($suffix)) echo $suffix;
            ?>
          </td>
          <td class='c-manager'>
            <?php if(!empty($project->PM)):?>
            <?php $userName = zget($users, $project->PM);?>
            <?php echo html::smallAvatar(array('avatar' => $usersAvatar[$project->PM], 'account' => $project->PM, 'name' => $userName), "avatar-circle avatar-{$project->PM}"); ?>
            <?php $userID = isset($PMList[$project->PM]) ? $PMList[$project->PM]->id : '';?>
            <?php echo html::a($this->createLink('user', 'profile', "userID=$userID", '', true), $userName, '', "title='{$userName}' data-toggle='modal' data-type='iframe' data-width='600'");?>
            <?php endif;?>
          </td>
          <?php if($status == 'openedbyme'):?>
          <td class='c-status'><span class="status-project status-<?php echo $project->status?>"><?php echo zget($lang->project->statusList, $project->status, '');?></span></td>
          <?php endif;?>
          <?php $projectBudget = in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? round((float)$project->budget / 10000, 2) . $this->lang->project->tenThousand : round((float)$project->budget, 2);?>
          <td class='text-right c-budget'><?php echo $project->budget != 0 ? zget($lang->project->currencySymbol, $project->budgetUnit) . ' ' . $projectBudget : $lang->project->future;?></td>
          <td class='text-left'><?php echo $project->begin;?></td>
          <td class='text-left'><?php echo $project->end == LONG_TIME ? $this->lang->project->longTime : $project->end;?></td>
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
              <button type='button' class='btn dropdown-toggle' data-toggle='dropdown' title="<?php echo $this->lang->more;?>"><i class='icon-ellipsis-v'></i></button>
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
      <div class="table-statistic"><?php echo $status == 'openedbyme' ? sprintf($lang->project->allSummary, count($projects), $waitCount, $doingCount, $suspendedCount, $closedCount) : sprintf($lang->project->summary, count($projects));?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
