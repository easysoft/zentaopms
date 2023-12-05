<?php
/**
 * The space file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: space.html.php 935 2021-12-07 14:31:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix table-row">
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->kanban->featureBar['space'] as $key => $label):?>
    <?php $active = $browseType == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($browseType == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('space', "browseType=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('showClosed', array('1' => $lang->kanban->showClosed), '', $this->cookie->showClosed ? 'checked=checked' : '');?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(!empty($unclosedSpace) and $browseType != 'involved') common::printLink('kanban', 'create', "spaceID=0&type={$browseType}", '<i class="icon icon-plus"></i> ' . $lang->kanban->create, '', 'class="btn btn-secondary iframe" data-width="75%"', '', true);?>
    <?php if($browseType != 'involved')common::printLink('kanban', 'createSpace', "type={$browseType}", '<i class="icon icon-plus"></i> ' . $lang->kanban->createSpace, '', 'class="btn btn-primary iframe" data-width="75%"', '', true);?>
  </div>
</div>
<div id='mainContent'>
  <?php if(empty($spaceList)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->kanbanspace->empty;?></span></p>
  </div>
  <?php else:?>
  <?php foreach($spaceList as $space):?>
  <?php $kanbanCount = 1;?>
  <div class='row cell' id='spaceList'>
    <div class='space'>
      <div class='row menu'>
        <div class='spaceTitle pull-left'>
          <div><i class='icon-cube'></i></div>
          <div>
            <p class='spaceName' title="<?php echo $space->name;?>">
              <?php if($space->status == 'closed'):?>
              <span class="label label-closed"><?php echo $lang->kanban->closed;?></span>
              <?php endif;?>
              <?php echo $space->name;?>
            </p>
          </div>
          <?php $spaceDescTitle = empty($space->desc) ? $lang->kanban->emptyDesc : str_replace("\n", '', strip_tags($space->desc));?>
          <?php $pattern        = '/<br[^>]*>|<img[^>]*>/';?>
          <?php $spaceDesc      = empty($space->desc) ? $lang->kanban->emptyDesc : preg_replace($pattern, '', $space->desc);?>
          <p><div><span class="text-limit hidden" data-limit-size="120"><?php echo $spaceDesc;?></span><a class="text-primary text-limit-toggle small" data-text-expand="<?php echo $lang->expand;?>"  data-text-collapse="<?php echo $lang->collapse;?>"></a></div></p>
        </div>
        <div class='spaceActions pull-right'>
          <?php $class = $space->status == 'closed' ? 'disabled' : '';?>
          <?php if($space->status != 'closed' and $browseType != 'involved' and !empty($unclosedSpace)) common::printLink('kanban', 'create', "spaceID={$space->id}&type={$space->type}", '<i class="icon icon-plus"></i> ' . $lang->kanban->create, '', "class='iframe btn btn-link' data-width='75%'", '', true);?>
          <div class="btn-group" id="setting?">
            <a href="javascript:;" data-toggle="dropdown" class="btn btn-link " style="border-radius: 4px;"><i class="icon icon-cog-outline position"></i><?php echo $lang->kanban->setting;?><span class="caret"></span></a>
            <ul class="dropdown-menu setting pull-right">
              <li><?php common::printLink('kanban', 'editSpace', "spaceID={$space->id}", '<i class="icon icon-cog-outline"></i> ' . $lang->kanban->settingSpace, '', "class='iframe' data-width='75%'", '', true);?></li>
              <li><?php if($class == 'disabled'):?>
              <?php common::printLink('kanban', 'activateSpace', "spaceID={$space->id}", '<i class="icon icon-magic"></i> ' . $lang->kanban->activateSpace, '', "class='iframe'", '', true);?>
              <?php else:?>
              <?php common::printLink('kanban', 'closeSpace', "spaceID={$space->id}", '<i class="icon icon-off"></i> ' . $lang->kanban->closeSpace, '', "class='iframe'", '', true);?>
              <?php endif;?></li>
              <li><?php common::printLink('kanban', 'deleteSpace', "spaceID={$space->id}", '<i class="icon icon-trash"></i> ' . $lang->kanban->deleteSpace, 'hiddenwin', '', '', true);?></li>
            <ul>
         </div>
        </div>
      </div>
      <?php if(isset($space->kanbans)):?>
      <div class='kanbans row'>
      <?php foreach($space->kanbans as $kanbanID => $kanban):?>
        <div class='col' data-id='<?php echo $kanbanID?>'>
          <div class='panel' data-url='<?php echo $this->createLink('kanban', 'view', "kanbanID=$kanbanID");?>'>
            <div class='panel-heading'>
              <?php if($space->type == 'cooperation' and $kanban->owner == $this->app->user->account):?>
              <span class="label label-outline label-info kanban-label"><?php echo $lang->kanban->mine;?></span>
              <?php endif;?>
              <div class='kanban-name'>
                <?php if($kanban->status == 'closed'):?>
                <span class="label label-closed"><?php echo $lang->kanban->closed;?></span>
                <?php endif;?>
                <strong title='<?php echo $kanban->name;?>'><?php echo $kanban->name;?></strong>
              </div>
              <?php
              $canEdit     = common::hasPriv('kanban','edit');
              $canDelete   = common::hasPriv('kanban','delete');
              $canClose    = (common::hasPriv('kanban', 'close') and $kanban->status == 'active');
              $canActivate = (common::hasPriv('kanban', 'activate') and $kanban->status == 'closed');
              ?>
              <?php if($canEdit or $canDelete or $canClose or $canActivate):?>
              <div class='kanban-actions kanban-actions<?php echo $kanbanID;?>'>
                <div class='dropdown'>
                  <?php echo html::a('javascript:;', "<i class='icon icon-ellipsis-v'></i>", '', "data-toggle='dropdown' class='btn btn-link'");?>
                  <ul class='dropdown-menu <?php echo $kanbanCount % 4 == 0 ? 'pull-left' : 'pull-right';?>'>
                    <?php
                    if($canEdit)
                    {
                        echo '<li>';
                        common::printLink('kanban', 'edit',   "kanbanID={$kanban->id}", '<i class="icon icon-edit"></i> ' . $lang->kanban->edit, '', "class='iframe' data-width='75%'", '', true);
                        echo '</li>';
                    }
                    if($canClose)
                    {
                        echo "<li>";
                        common::printLink('kanban', 'close',  "kanbanID={$kanban->id}", '<i class="icon icon-off"></i> ' . $lang->kanban->close, '', "class='iframe' data-width='75%'", '', true);
                        echo '</li>';
                    }
                    if($canActivate)
                    {
                        echo "<li>";
                        common::printLink('kanban', 'activate',  "kanbanID={$kanban->id}", '<i class="icon icon-magic"></i> ' . $lang->kanban->activate, '', "class='iframe' data-width='75%'", '', true);
                        echo '</li>';
                    }
                    if($canDelete)
                    {
                        echo '<li>';
                        common::printLink('kanban', 'delete', "kanbanID={$kanban->id}&confirm=no&browseType=$browseType", '<i class="icon icon-trash"></i> ' . $lang->kanban->delete, 'hiddenwin');
                        echo '</li>';
                    }
                    ?>
                  </ul>
                </div>
              </div>
              <?php endif;?>
              <?php $kanbanCount ++;?>
            </div>
            <div class='panel-body'>
              <?php $kanbanDescTitle = str_replace("\n", '', strip_tags($kanban->desc));?>
              <?php $kanbanDesc      = str_replace("\n", '', preg_replace($pattern, '', $kanban->desc));?>
              <div class='kanban-desc' title="<?php echo $kanbanDescTitle;?>"><?php echo $kanbanDesc;?></div>
              <div class='kanban-footer'>
              <?php $count     = 0;?>
              <?php $teamPairs = array_filter(explode(',', ",$kanban->createdBy,$kanban->owner,$kanban->team"));?>
              <?php $teamPairs = array_unique($teamPairs);?>
              <?php
              foreach($teamPairs as $index => $team)
              {
                  if(!isset($users[$team])) unset($teamPairs[$index]);
              }
              ?>
              <?php $teamCount = count($teamPairs);?>
                <div class="clearfix">
                  <?php if(!empty($teamPairs)):?>
                  <div class='kanban-members pull-left'>
                    <?php foreach($teamPairs as $member):?>
                    <?php
                    if($count > 2) break;
                    if(!isset($users[$member]))
                    {
                        $teamCount --;
                        continue;
                    }
                    $count ++;
                    ?>
                    <div title="<?php echo $users[$member];?>">
                      <?php echo html::middleAvatar(array('avatar' => $usersAvatar[$member], 'account' => $member, 'name' => $users[$member]), 'avatar-circle avatar-' . zget($userIdPairs, $member)); ?>
                    </div>
                    <?php endforeach;?>
                    <?php if($teamCount > 3):?>
                    <?php if($teamCount > 4) echo '<span>…</span>';?>
                    <div title="<?php echo $users[end($teamPairs)];?>">
                      <?php echo html::middleAvatar(array('avatar' => $usersAvatar[end($teamPairs)], 'account' => end($teamPairs), 'name' => $users[end($teamPairs)]), 'avatar-circle avatar-' . zget($userIdPairs, end($teamPairs))); ?>
                    </div>
                    <?php endif;?>
                  </div>
                  <?php endif;?>
                  <?php $teamCountLang = ($teamCount > 1) ? $lang->kanban->teamSumCount : str_replace("Pers", "Person", $lang->kanban->teamSumCount);?>
                  <div class='kanban-members-total pull-left'><?php echo sprintf($teamCountLang, $teamCount);?></div>
                  <?php $cardsCount = ($kanban->cardsCount > 1) ? str_replace("Card", "Cards", $lang->kanban->cardsCount) : $lang->kanban->cardsCount;?>
                  <div class='kanban-members-total pull-right'><?php echo empty($kanban->cardsCount) ? $lang->kanban->noCard : sprintf($cardsCount, $kanban->cardsCount);?></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach;?>
      </div>
      <?php else:?>
      <div class="table-empty-tip <?php if($this->cookie->theme == 'blue') echo 'noBorder';?>">
        <p><span class="text-muted"><?php echo $lang->kanban->empty;?></span></p>
      </div>
      <?php endif;?>
    </div>
  </div>
  <?php endforeach;?>
  <?php endif;?>
</div>
<?php if(!empty($spaceList)):?>
<div id='spaceListFooter' class='table-footer'>
  <?php $pager->show('right', 'pagerjs');?>
</div>
<?php endif?>
<?php include '../../common/view/footer.html.php';?>
