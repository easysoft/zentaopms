<?php
/**
 * The html template file of all method of execution module for lite vision of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     execution
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php include $this->app->getModuleRoot() . '/common/view/sortable.html.php';?>
<div class='clearfix' id='mainMenu'>
  <div class='btn-toolbar pull-left'>
    <?php
      foreach($lang->project->featureBar['execution'] as $label => $labelName)
      {
          $active = $status == $label ? 'btn-active-text' : '';
          echo html::a($this->createLink('project', 'execution', "status=$label&projectID=$projectID"), '<span class="text">' . $labelName . '</span> ' . ($status == $label ? "<span class='label label-light label-badge'>" . (int)count($kanbanList) . '</span>' : ''), '', "class='btn btn-link $active'");
      }
    ?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('execution', 'create', "projectID=$projectID", '<i class="icon icon-plus"></i> ' . $lang->project->createKanban, '', 'class="btn btn-primary"');?>
  </div>
</div>
<div id='mainContent' class="main-row fade cell">
  <?php if(empty($kanbanList)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->execution->noExecution;?></span>
      <?php if(common::hasPriv('execution', 'create') and $allExecutionsNum):?>
      <?php echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->project->createKanban, '', "class='btn btn-info' data-app='project'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
    <div class='kanban-cards'>
      <?php
        $kanbanview = 'kanban';
        if($this->cookie->kanbanview) $kanbanview = $this->cookie->kanbanview;

        if(!common::hasPriv('execution', $kanbanview))
        {
            foreach (explode('|', 'kanban|task|calendar|gantt|tree|grouptask') as $view)
            {
                if(common::hasPriv('execution', $view))
                {
                    $kanbanview = $view;
                    break;
                }
            }
        }
      ?>
      <?php foreach($kanbanList as $index => $kanban):?>
        <div id="kanban-<?php echo $kanban->id;?>" class='kanban-card col' data-url='<?php echo $this->createLink('execution', $kanbanview, "kanbanID=$kanban->id");?>'>
          <div class="panel">
            <div class="panel-heading">
              <span class="label kanban-status-<?php echo $kanban->status;?>"><?php echo zget($lang->execution->statusList, $kanban->status);?></span>
              <strong class="kanban-name" title='<?php echo $kanban->name;?>'><?php echo $kanban->name;?></strong>
              <?php $canActions = (common::hasPriv('execution','edit') or (!empty($executionActions) and isset($executionActions[$kanban->id])));?>
              <?php if($canActions):?>
              <div class='kanban-actions kanban-actions<?php echo $kanban->id;?>'>
                <div class='dropdown'>
                  <?php echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");?>
                  <ul class='dropdown-menu <?php echo ($index + 1) % 4 == 0 ? 'pull-left' : 'pull-right';?>'>
                    <?php
                    if(common::hasPriv('execution','edit'))
                    {
                        $this->app->loadLang('kanban');
                        echo '<li>';
                        common::printLink('execution', 'edit', "executionID={$kanban->id}", '<i class="icon icon-edit"></i> ' . $lang->kanban->edit, '', "class='iframe' data-width='75%'", '', true);
                        echo '</li>';
                    }
                    if(!empty($executionActions[$kanban->id]))
                    {
                        if(in_array('start', $executionActions[$kanban->id])) echo '<li>' . html::a(helper::createLink('execution', 'start', "executionID={$kanban->id}", '', true), '<i class="icon icon-play"></i>' . $lang->execution->start, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
                        if(in_array('putoff', $executionActions[$kanban->id])) echo '<li>' . html::a(helper::createLink('execution', 'putoff', "executionID=$kanban->id", '', true), '<i class="icon icon-calendar"></i>' . $lang->execution->putoff, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
                        if(in_array('suspend', $executionActions[$kanban->id])) echo '<li>' . html::a(helper::createLink('execution', 'suspend', "executionID=$kanban->id", '', true), '<i class="icon icon-pause"></i>' . $lang->execution->suspend, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
                        if(in_array('close', $executionActions[$kanban->id])) echo '<li>' . html::a(helper::createLink('execution', 'close', "executionID=$kanban->id", '', true), '<i class="icon icon-off"></i>' . $lang->execution->close, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
                        if(in_array('activate', $executionActions[$kanban->id])) echo '<li>' . html::a(helper::createLink('execution', 'activate', "executionID=$kanban->id", '', true), '<i class="icon icon-magic"></i>' . $lang->execution->activate, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
                        if(in_array('delete', $executionActions[$kanban->id])) echo '<li>' . html::a(helper::createLink('execution', 'delete', "executionID=$kanban->id&confirm=no&kanban=yes", '', true), '<i class="icon icon-trash"></i>' . $lang->kanban->delete, '', "target='hiddenwin'") . '</li>';
                    }?>
                  </ul>
                 </div>
              </div>
              <?php endif;?>
            </div>
            <div class="panel-body">
              <div class="kanban-desc">
                <?php echo $kanban->desc;?>
              </div>
              <div class="kanban-footer">
                <div class='kanban-members pull-left'>
                  <?php $teams = isset($memberGroup[$kanban->id]) ? $memberGroup[$kanban->id] : array();?>
                  <?php $count = 0;?>
                  <?php foreach($teams as $member):?>
                  <?php if($count > 2) break;?>
                  <?php $count ++;?>
                  <div title="<?php echo zget($users, $member->account);?>">
                    <?php echo html::smallAvatar(array('avatar' => zget($usersAvatar, $member->account), 'account' => $member->account, 'name' => isset($member->realname) ? $member->realname : '')); ?>
                  </div>
                  <?php endforeach;?>
                  <?php if(count($teams) > 4):?>
                  <?php echo '<span>… </span>';?>
                  <?php endif;?>
                  <?php if(count($teams) > 3):?>
                  <?php $lastMember = end($teams);?>
                  <div title="<?php echo $lastMember->realname;?>">
                  <?php echo html::smallAvatar(array('avatar' => $usersAvatar[$lastMember->account], 'account' => $lastMember->account, 'name' => $lastMember->realname), 'avatar-circle avatar-' . key((array)$lastMember)); ?>
                  </div>
                  <?php endif;?>
                </div>
                <div class='kanban-members-count pull-left'><?php echo sprintf($lang->project->teamSumCount, count($teams));?></div>
                <div class='kanban-acl pull-right'>
                  <span><i class="icon icon-<?php echo $kanban->acl == 'private' ? 'lock' : 'unlock-alt';?>"></i> <?php echo zget($lang->project->acls, $kanban->acl, '');?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach;?>
    </div>
  <?php endif;?>
</div>
<style>
.kanban-cards {padding-top: 12px; width: 100%;}
.kanban-card {width: 25%;}
.kanban-card .panel {margin: 0 0; border: 1px solid #DCDCDC; border-radius: 4px; box-shadow: none; cursor: pointer; height: 160px; margin-bottom: 5px;}
.kanban-card .panel:hover {border-color: #006AF1; box-shadow: 0 0 10px 0 rgba(0,0,100,.25);}
.kanban-card .panel:hover .kanban-actions {visibility: unset;}
.kanban-card .kanban-actions {position: absolute; top: 2px; right: 2px; float: right; visibility: hidden;}
.kanban-card .kanban-actions .dropdown-menu.pull-right {top:31px; right: -84px; left: auto;}
.kanban-card .kanban-actions .dropdown-menu.pull-left {top:31px; right:-1px; left: auto;}
.kanban-card .kanban-desc {color: #838a9d; word-break:break-all; height: 70px; overflow: hidden; display: -webkit-box; float:left;}
.kanban-card .kanban-footer {position: absolute; bottom: 10px; right: 10px; left: 15px;}
.kanban-card .kanban-members {float: left; height: 24px; line-height: 24px;}
.kanban-card .kanban-members > div {display: inline-block; height: 24px;}
.kanban-card .kanban-members > div + div {margin-left: -5px;}
.kanban-card .kanban-members > div > .avatar {display: inline-block; width: 24px; height: 24px; line-height: 24px; margin-right: 1px;}
.kanban-card .kanban-members > span {display: inline-block; color: transparent; width: 2px; height: 2px; background-color: #8990a2; position: relative; border-radius: 50%; top: 3px; margin: 0 3px;}
.kanban-card .kanban-members > span:before,
.kanban-card .kanban-members > span:after {content: ''; display: block; position: absolute; width: 2px; height: 2px; background-color: #8990a2; top: 0; border-radius: 50%}
.kanban-card .kanban-members > span:before {left: -4px;}
.kanban-card .kanban-members > span:after {right: -4px;}
.kanban-card .kanban-members-count {display: inline-block; margin-left: 6px; position: relative; top: 3px}
.kanban-card .kanban-acl {position: absolute; right: 0px; bottom: 2px; color: #838a9d;}
.kanban-card .label.kanban-status-wait {background-color: #E5E5E5; color: #808080;}
.kanban-card .label.kanban-status-doing {background-color: #DDF4DF; color: #38B03F;}
.kanban-card .label.kanban-status-suspended {background-color: #FFF0D5; color: #F1A325;}
.kanban-card .label.kanban-status-closed {background-color: #808080; color: #FFF;}
</style>
<script>
/* Make cards clickable. */
$('.kanban-cards').on('click', '.kanban-card', function(e)
{
    if(!$(e.target).closest('.kanban-actions').length)
    {
        window.location.href = $(this).data('url');
    }
});
</script>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
