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
    <?php echo html::a($this->createLink('project', 'execution', "status=$key&projectID=$projectID"), "<span class='text'>{$label}</span>" . ($status == $key ? ' <span class="label label-light label-badge">' . count($kanbans) . '</span>' : ''), '', "class='btn btn-link" . ($status == $key ? ' btn-active-text' : '') . "' id='{$key}Tab' data-app='project'");?>
    <?php endforeach;?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php if(common::hasPriv('kanban', 'create')) echo html::a($this->createLink('kanban', 'create', "projectID=$projectID", 'html', true), "<i class='icon icon-sm icon-plus'></i> " . $lang->execution->createKanban, '', "class='btn btn-primary create-execution-btn iframe' data-app='project'");?>
  </div>
</div>
<div id='mainContent' class="main-row fade cell">
  <?php if(empty($kanbans)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->execution->noExecution;?></span>
      <?php if(common::hasPriv('kanban', 'create')):?>
      <?php echo html::a($this->createLink('kanban', 'create', "projectID=$projectID", 'html', true), "<i class='icon icon-plus'></i> " . $lang->execution->createKanban, '', "class='btn btn-info iframe' data-app='project'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
    <div class='kanban-cards'>
      <?php foreach($kanbans as $index => $kanban):?>
        <div id="kanban-<?php echo $kanban->id;?>" class='kanban-card col' data-url='<?php echo $this->createLink('kanban', 'view', "kanbanID=$kanban->id");?>'>
          <div class="panel">
            <div class="panel-heading">
              <span class="label kanban-status-<?php echo $kanban->status;?>"><?php echo zget($lang->execution->statusList, $kanban->status);?></span>
              <strong class="kanban-name" title='<?php echo $kanban->name;?>'><?php echo $kanban->name;?></strong>
              <div class='kanban-actions' id='kanban-actions-<?php echo $kanban->id;?>'>
                <div class='dropdown'>
                  <?php echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");?>
                  <ul class='dropdown-menu <?php echo ($index + 1) % 4 == 0 ? 'pull-left' : 'pull-right';?>'>
                    <?php
                    if(common::hasPriv('kanban','edit'))
                    {
                        echo '<li>';
                        common::printLink('kanban', 'edit',   "kanbanID={$kanban->id}", '<i class="icon icon-edit"></i> ' . $lang->kanban->edit, '', "class='iframe' data-width='75%'", '', true);
                        echo '</li>';
                    }
                    if(common::hasPriv('kanban','close'))
                    {
                        $class = $kanban->status == 'closed' ? 'disabled' : '';
                        echo "<li class='{$class}'>";
                        common::printLink('kanban', 'close',  "kanbanID={$kanban->id}", '<i class="icon icon-off"></i> ' . $lang->kanban->close, '', "class='iframe {$class}' data-width='75%'", '', true);
                        echo '</li>';
                    }
                    if(common::hasPriv('kanban','delete'))
                    {
                        echo '<li>';
                        common::printLink('kanban', 'delete', "kanbanID={$kanban->id}", '<i class="icon icon-trash"></i> ' . $lang->kanban->delete, 'hiddenwin');
                        echo '</li>';
                    }
                    ?>
                  </ul>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="kanban-desc">
                <?php echo $kanban->desc;?>
              </div>
              <div class="kanban-footer">
                <div class='kanban-members pull-left'>
                  <?php $teams = explode(',', trim($kanban->team, ','));?>
                  <?php foreach($teams as $account):?>
                  <div title="<?php echo zget($users, $account);?>">
                    <?php echo html::smallAvatar(array('avatar' => zget($usersAvatar, $account), 'account' => $account)); ?>
                  </div>
                  <?php endforeach;?>
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
