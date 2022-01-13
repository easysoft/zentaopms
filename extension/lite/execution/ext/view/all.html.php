<?php
/**
 * The html template file of all method of execution module for lite vision of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     execution
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php include $this->app->getModuleRoot() . '/common/view/sortable.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php foreach($lang->execution->featureBar['all'] as $key => $label):?>
    <?php echo html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, "status=$key&projectID=$projectID&orderBy=$orderBy&productID=$productID"), "<span class='text'>{$label}</span>" . ($status == $key ? ' <span class="label label-light label-badge">' . count($executionStats) . '</span>' : ''), '', "class='btn btn-link' id='{$key}Tab' data-app='$from'");?>
    <?php endforeach;?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php if(common::hasPriv('execution', 'create')) echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-sm icon-plus'></i> " . $lang->execution->createKanban, '', "class='btn btn-primary create-execution-btn' data-app='$from'");?>
  </div>
</div>
<div id='mainContent' class="main-row fade cell">
  <?php if(empty($executionStats)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $from == 'execution' ? $lang->execution->noExecutions : $lang->execution->noExecution;?></span>
      <?php if(common::hasPriv('execution', 'create')):?>
      <?php echo html::a($this->createLink('execution', 'create', "projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->execution->createKanban, '', "class='btn btn-info' data-app='$from'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
    <div class='kanban-cards'>
      <?php foreach($executionStats as $index => $execution):?>
        <?php $executionStatus = $this->processStatus('execution', $execution);?>
        <div id="execution-<?php echo $execution->id;?>" class='kanban-card col'>
          <div class="panel">
            <div class="panel-heading">
              <span class="label execution-status-<?php echo $execution->status;?>"><?php echo $executionStatus;?></span>
              <strong class="kanban-name" title='<?php echo $execution->name;?>'><?php echo $execution->name;?></strong>
              <div class='kanban-actions' id='kanban-actions-<?php echo $execution->id;?>'>
                <div class='dropdown'>
                  <?php echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");?>
                  <ul class='dropdown-menu <?php echo ($index + 1) % 4 == 0 ? 'pull-left' : 'pull-right';?>'>
                    <?php
                    if(common::hasPriv('execution','edit'))
                    {
                        echo '<li>';
                        common::printLink('execution', 'edit',   "executionID={$execution->id}", '<i class="icon icon-edit"></i> ' . $lang->execution->edit, '', "class='iframe' data-width='75%'", '', true);
                        echo '</li>';
                    }
                    if(common::hasPriv('execution','close'))
                    {
                        $class = $execution->status == 'closed' ? 'disabled' : '';
                        echo "<li class='{$class}'>";
                        common::printLink('execution', 'close',  "executionID={$execution->id}", '<i class="icon icon-off"></i> ' . $lang->execution->close, '', "class='iframe {$class}' data-width='75%'", '', true);
                        echo '</li>';
                    }
                    if(common::hasPriv('execution','delete'))
                    {
                        echo '<li>';
                        common::printLink('execution', 'delete', "executionID={$execution->id}", '<i class="icon icon-trash"></i> ' . $lang->execution->delete, 'hiddenwin');
                        echo '</li>';
                    }
                    ?>
                  </ul>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="kanban-desc">
                <?php echo $execution->desc;?>
              </div>
              <div class="kanban-footer">
                <div class='execution-members pull-left'>
                  <?php foreach($executionTeams[$execution->id] as $member):?>
                  <div title="<?php echo $users[$member->account];?>">
                    <?php echo html::smallAvatar(array('avatar' => $usersAvatar[$member->account], 'account' => $member->account)); ?>
                  </div>
                  <?php endforeach;?>
                </div>
                <div class='execution-members-count pull-left'><?php echo sprintf($lang->project->teamSumCount, count($executionTeams[$execution->id]));?></div>
                <div class='execution-acl pull-right'>
                  <span><i class="icon icon-<?php echo $execution->acl == 'private' ? 'lock' : 'unlock-alt';?>"></i> <?php echo zget($lang->project->acls, $execution->acl, '');?></span>
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
.kanban-card .execution-members {float: left; height: 24px; line-height: 24px;}
.kanban-card .execution-members > div {display: inline-block; height: 24px;}
.kanban-card .execution-members > div + div {margin-left: -5px;}
.kanban-card .execution-members > div > .avatar {display: inline-block; width: 24px; height: 24px; line-height: 24px; margin-right: 1px;}
.kanban-card .execution-members > span {display: inline-block; color: transparent; width: 2px; height: 2px; background-color: #8990a2; position: relative; border-radius: 50%; top: 3px; margin: 0 3px;}
.kanban-card .execution-members > span:before,
.kanban-card .execution-members > span:after {content: ''; display: block; position: absolute; width: 2px; height: 2px; background-color: #8990a2; top: 0; border-radius: 50%}
.kanban-card .execution-members > span:before {left: -4px;}
.kanban-card .execution-members > span:after {right: -4px;}
.kanban-card .execution-members-count {display: inline-block; margin-left: 6px; position: relative; top: 3px}
.kanban-card .execution-acl {position: absolute; right: 0px; bottom: 2px; color: #838a9d;}
</style>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
