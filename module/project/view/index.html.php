<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if($project->model != 'kanban' or $config->vision == 'lite'):?>
<?php echo $this->fetch('block', 'dashboard', "module=project&type={$project->model}&projectID={$project->id}");?>
<?php else:?>
<div class='clearfix' id='mainMenu'>
  <div class='btn-toolbar pull-left'>
    <?php
      foreach($lang->project->featureBar['index'] as $label => $labelName)
      {
          $active = $browseType == $label ? 'btn-active-text' : '';
          echo html::a($this->createLink('project', 'index', "projectID=$project->id&browseType=" . $label), '<span class="text">' . $labelName . '</span> ' . ($browseType == $label ? "<span class='label label-light label-badge'>" . (int)count($kanbanList) . '</span>' : ''), '', "class='btn btn-link $active'");
      }
    ?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php common::printLink('execution', 'create', "projectID=$project->id", '<i class="icon icon-plus"></i> ' . $lang->project->createKanban, '', 'class="btn btn-primary" data-app="execution"');?>
  </div>
</div>
<div id="mainContent">
  <div class="row cell" id='cards'>
    <?php if(empty($kanbanList)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
        <?php common::printLink('execution', 'create', "projectID=$project->id", '<i class="icon icon-plus"></i> ' . $lang->project->createKanban, '', 'class="btn btn-info" data-app="execution"');?>
      </p>
    </div>
    <?php else:?>
    <?php $kanbanCount = 0;?>
    <?php foreach ($kanbanList as $kanbanID => $kanban):?>
    <div class='col' data-id='<?php echo $kanbanID?>'>
      <div class='panel' data-url='<?php echo $this->createLink('execution', 'kanban', "kanbanID=$kanbanID");?>'>
        <div class='panel-heading'>
           <div class='kanban-name'>
             <?php if($kanban->end < helper::today() and $kanban->status != 'done' and $kanban->status != 'closed' and $kanban->status != 'suspended'):?>
             <span class="label label-doing"><?php echo $lang->project->statusList['delay'];?></span>
             <?php else:?>
             <span class="label label-<?php echo $kanban->status;?>"><?php echo zget($lang->execution->statusList, $kanban->status);?></span>
             <?php endif;?>
             <strong title='<?php echo $kanban->name;?>'><?php echo $kanban->name;?></strong>
           </div>
           <?php
           $canActions = (common::hasPriv('execution','edit') or (!empty($executionActions) and isset($executionActions[$kanbanID])));
           $kanbanCount ++;
           ?>
           <?php if($canActions):?>
           <div class='kanban-actions kanban-actions<?php echo $kanbanID;?>'>
             <div class='dropdown'>
               <?php echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");?>
               <ul class='dropdown-menu <?php echo $kanbanCount % 4 == 0 ? 'pull-left' : 'pull-right';?>'>
                 <?php
                 if(common::hasPriv('execution','edit'))
                 {
                     $this->app->loadLang('kanban');
                     echo '<li>';
                     common::printLink('execution', 'edit', "executionID={$kanbanID}", '<i class="icon icon-edit"></i>' . $lang->kanban->edit, '', "class='iframe' data-width='75%'", '', true);
                     echo '</li>';
                 }
                 if(!empty($executionActions[$kanbanID]))
                 {
                     if(in_array('start', $executionActions[$kanbanID])) echo '<li>' . html::a(helper::createLink('execution', 'start', "executionID=$kanbanID", '', true), '<i class="icon icon-play"></i>' . $lang->execution->start, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
                     if(in_array('putoff', $executionActions[$kanbanID])) echo '<li>' . html::a(helper::createLink('execution', 'putoff', "executionID=$kanbanID", '', true), '<i class="icon icon-calendar"></i>' . $lang->execution->putoff, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
                     if(in_array('suspend', $executionActions[$kanbanID])) echo '<li>' . html::a(helper::createLink('execution', 'suspend', "executionID=$kanbanID", '', true), '<i class="icon icon-pause"></i>' . $lang->execution->suspend, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
                     if(in_array('close', $executionActions[$kanbanID])) echo '<li>' . html::a(helper::createLink('execution', 'close', "executionID=$kanbanID", '', true), '<i class="icon icon-off"></i>' . $lang->execution->close, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
                     if(in_array('activate', $executionActions[$kanbanID])) echo '<li>' . html::a(helper::createLink('execution', 'activate', "executionID=$kanbanID", '', true), '<i class="icon icon-magic"></i>' . $lang->execution->activate, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
                     if(in_array('delete', $executionActions[$kanbanID])) echo '<li>' . html::a(helper::createLink('execution', 'delete', "executionID=$kanbanID&confirm=no&kanban=yes", '', true), '<i class="icon icon-trash"></i>' . $lang->kanban->delete, '', "target='hiddenwin'") . '</li>';
                 }?>
               </ul>
             </div>
           </div>
           <?php endif;?>
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
                  <?php echo html::smallAvatar(array('avatar' => zget($usersAvatar, $member->account, ''), 'account' => $member->account, 'name' => $member->realname), 'avatar-circle avatar-' . zget($userIdPairs, $member->account, '')); ?>
                </div>
                <?php endforeach;?>
                <?php if(count($members) > 4):?>
                <?php echo '<span>…</span>';?>
                <?php endif;?>
                <?php if(count($members) > 3):?>
                <?php $lastMember = end($members);?>
                <div title="<?php echo $lastMember->realname;?>">
                  <?php echo html::smallAvatar(array('avatar' => $usersAvatar[$lastMember->account], 'account' => $lastMember->account, 'name' => $lastMember->realname), 'avatar-circle avatar-' . zget($userIdPairs, $lastMember->account)); ?>
                </div>
                <?php endif;?>
              </div>
              <div class='kanban-members-total pull-left'><?php echo sprintf($lang->project->teamSumCount, count($members));?></div>
              <?php endif;?>
              <div class='kanbanAcl'>
                <?php $icon = 'inherit-space';?>
                <?php if($kanban->acl == 'private') $icon = 'lock';?>
                <i class="<?php echo 'icon-' . $icon;?>"></i>
                <?php echo zget($lang->execution->kanbanAclList, $kanban->acl, '');?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
    <div class='col-xs-12' id='cardsFooter'>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </div>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
