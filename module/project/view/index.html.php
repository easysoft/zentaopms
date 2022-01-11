<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if($project->model != 'kanban'):?>
<?php echo $this->fetch('block', 'dashboard', "module=project&type={$project->model}&projectID={$project->id}");?>
<?php else:?>
<div class='clearfix' id='mainMenu'>
  <div class='btn-toolbar pull-left'>
    <?php
      foreach($lang->project->featureBar as $label => $labelName)
      {
          $active = $browseType == $label ? 'btn-active-text' : ''; 
          echo html::a($this->createLink('project', 'index', "projectID=$project->id&browseType=" . $label), '<span class="text">' . $labelName . '</span> ' . ($browseType == $label ? "<span class='label label-light label-badge'>0</span>" : ''), '', "class='btn btn-link $active'");
      }
    ?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php echo html::a($this->createLink('execution', 'create', "projectID=$project->id"), "<i class='icon icon-sm icon-plus'></i> " . $lang->project->createKanban, '', "class='btn btn-primary create-execution-btn'");?>
  </div>
</div>
<div id="mainContent">
  <div class="row cell" id='cards'>
    <?php if(empty($kanbanList)):?>
    <div class="table-empty-tip">
      <p> 
        <span class="text-muted"><?php echo $lang->noData;?></span>
        <?php common::printLink('execution', 'create', "projectID=$project->id", '<i class="icon icon-plus"></i> ' . $lang->project->createKanban, '', 'class="btn btn-info"');?>
      </p>
    </div>
    <?php else:?>
    <?php foreach ($kanbanList as $kanbanID => $kanban):?>
    <div class='col' data-id='<?php echo $kanbanID?>'>
      <div class='panel'>
        <div class='panel-heading'>
           <div class='kanban-name'>
             <span class="label label-closed"><?php echo zget($lang->execution->statusList, $kanban->status);?></span>
             <strong title='<?php echo $kanban->name;?>'><?php echo $kanban->name;?></strong>
           </div>
        </div>
        <div class='panel-body'>
          <div class='kanban-desc' title="<?php echo strip_tags(htmlspecialchars_decode($kanban->desc));?>"><?php echo strip_tags(htmlspecialchars_decode($kanban->desc));?></div>
          <div class='kanban-footer'>
            <div class="clearfix">
              <?php $members = zget($memberGroup, $kanbanID, array());?>
              <?php if(!empty($members)):?>
              <div class='kanban-members pull-left'>
                <?php $count = 0;?>
                <?php foreach($members as $member):?>
                <?php if($count > 2) break;?>
                <?php $count ++;?>
                <div title="<?php echo $member->realname;?>">
                  <?php echo html::smallAvatar(array('avatar' => $usersAvatar[$member->account], 'account' => $member->account)); ?>
                </div>
                <?php endforeach;?>
                <?php if(count($members) > 4):?>
                <?php echo '<span>…</span>';?>
                <?php $lastMember = end($members);?>
                <div title="<?php echo $lastMember->realname;?>">
                  <?php echo html::smallAvatar(array('avatar' => $usersAvatar[$lastMember->account], 'account' => $lastMember->account)); ?>
                </div>
                <?php endif;?>
              </div>
              <?php endif;?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
    <?php endif;?>
  </div>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
