<?php
/**
 * The space file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: space.html.php 935 2021-12-07 14:31:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix table-row">
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->kanbanspace->featureBar as $key => $label):?>
    <?php $active = $browseType == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($browseType == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('space', "browseType=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('kanban', 'create', '', '<i class="icon icon-plus"></i> ' . $lang->kanban->create, '', 'class="btn btn-secondary iframe"', '', true);?>
    <?php common::printLink('kanban', 'createSpace', '', '<i class="icon icon-plus"></i> ' . $lang->kanban->createSpace, '', 'class="btn btn-primary iframe"', '', true);?>
  </div>
</div>
<div id='mainContent'>
  <?php if(empty($spaces)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->kanbanspace->empty;?></span></p>
  </div>
  <?php else:?>
  <?php foreach($spaces as $space):?>
  <div class='row cell' id='spaces'>
    <div class='space'>
      <div class='row menu'>
        <div class='spaceTitle pull-left'>
          <div><i class='icon-zone'></i></div>
          <div>
            <h4>
              <?php echo $space->name;?>
              <?php echo isset($space->kanbans) ? count($space->kanbans) : '';?>
            </h4>
          </div>
        </div>
        <div class='spaceActions pull-right'>
          <?php common::printLink('kanban', 'create', "spaceID={$space->id}", '<i class="icon icon-plus"></i> ' . $lang->kanban->create, '', "class='iframe'", '', true);?>
          <?php common::printLink('kanban', 'editSpace', "spaceID={$space->id}", '<i class="icon icon-cog-outline"></i> ' . $lang->kanban->setting, '', "class='iframe'", '', true);?>
          <?php common::printLink('kanban', 'closeSpace', "spaceID={$space->id}", '<i class="icon icon-off"></i> ' . $lang->close, '', "class='iframe'", '', true);?>
          <?php common::printLink('kanban', 'deleteSpace', "spaceID={$space->id}", '<i class="icon icon-trash"></i> ' . $lang->delete, 'hiddenwin', '', '', true);?>
        </div>
      </div>
      <?php if(isset($space->kanbans)):?>
      <div class='kanbans row'>
      <?php foreach($space->kanbans as $kanbanID => $kanban):?>
        <div class='col' data-id='<?php echo $kanbanID?>'>
          <div class='panel' data-url='<?php echo $this->createLink('kanban', 'view', "kanbanID=$kanbanID");?>'>
            <div class='panel-heading'>
              <div class='kanban-name'>
                <?php if($kanban->status == 'closed'):?>
                <span class="label label-closed"><?php echo $lang->kanban->closed;?></span>
                <?php endif;?>
                <strong title='<?php echo $kanban->name;?>'><?php echo $kanban->name;?></strong>
              </div>
              <div class='kanban-actions'>
                <div class='dropdown'>
                </div>
              </div>
            </div>
            <div class='panel-body'>
              <div class='kanban-desc'><?php echo strip_tags(htmlspecialchars_decode($kanban->desc));?></div>
              <div class='kanban-footer'>
              <?php $count     = 0;?>
              <?php $teamPairs = array_filter(explode(',', $kanban->team));?>
              <?php $teamCount = count($teamPairs);?>
                <div class="clearfix">
                  <?php if(!empty($teamPairs)):?>
                  <div class='kanban-members pull-left'>
                    <?php foreach($teamPairs as $member):?>
                    <?php
                    if($count > 2) continue;
                    if(!isset($users[$member]))
                    {
                        $teamCount --;
                        continue;
                    }
                    $count ++;
                    ?>
                    <a href='<?php echo helper::createLink('kanban', 'view', "kanbanID=$kanbanID");?>' title="<?php echo $users[$member];?>">
                      <?php echo html::smallAvatar(array('avatar' => $usersAvatar[$member], 'account' => $member)); ?>
                    </a>
                    <?php endforeach;?>
                    <?php if($teamCount > 3):?>
                    <?php echo '<span>…</span>';?>
                    <a href='<?php echo helper::createLink('kanban', 'view', "kanbanID=$kanbanID");?>' title="<?php echo $users[$member];?>">
                      <?php echo html::smallAvatar(array('avatar' => $usersAvatar[end($teamPairs)], 'account' => $member)); ?>
                    </a>
                    <?php endif;?>
                  </div>
                  <?php endif;?>
                  <div class='kanban-members-total pull-left'><?php echo html::a(helper::createLink('kanban', 'view', "kanbanID=$kanbanID"), sprintf($lang->kanban->teamSumCount, $teamCount));?></div>
                </div>
                <div class='kanbanAcl'>
                  <?php $icon = $kanban->acl == 'open' ? 'unlock' : 'lock';?>
                    <i class="<?php echo 'icon-' . $icon;?>"></i>
                  <?php echo zget($lang->kanban->aclGroup, $kanban->acl, '');?>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach;?>
      </div>
      <?php else:?>
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->kanban->empty;?></span></p>
      </div>
      <?php endif;?>
    </div>
  </div>
  <?php endforeach;?>
  <?php endif;?>
</div>
<?php if(!empty($spaces)):?>
<div id='spacesFooter' class='table-footer'>
  <?php $pager->show('right', 'pagerjs');?>
</div>
<?php endif?>
<?php include '../../common/view/footer.html.php';?>
