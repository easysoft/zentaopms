<?php
/**
 * The instance list view file of space module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   space
 * @version   $Id$
 * @link      https://www.zentao.net
 */
?>
<div class='main-cell' id='cardsContainer'>
  <?php if(empty($instances)):?>
  <div class="table-empty-tip">
    <p><?php echo html::a($this->createLink('store', 'browse'), $lang->space->noApps . ', ' . $lang->space->notice->toInstall, '', "class='btn btn-info'");?></p>
  </div>
  <?php else:?>
  <div class="row">
    <?php foreach ($instances as $instance):?>
    <div class='col-sm-4 col-md-3 col-lg-3' data-id='<?php echo $instance->id;?>'>
      <div class='panel'>
        <div class='panel-heading'>
          <div class="q-card-title">
            <a href="<?php echo helper::createLink('instance', 'view', "id=$instance->id");?>">
              <?php echo $instance->name;?>&nbsp;
            </a>
          </div>
          <?php if(!empty($instance->latestVersion) && empty($instance->solution)):?>
          <div class="q-metal"><?php echo empty($instance->latestVersion->change_log_url) ? $lang->space->upgrade : html::a($instance->latestVersion->change_log_url, $lang->space->upgrade, '_blank');?></div>
          <?php endif;?>
        </div>
        <div class='panel-body'>
          <div class="instance-detail">
            <a href="<?php echo helper::createLink('instance', 'view', "id=$instance->id");?>">
              <div class='instance-logo'>
                <?php echo html::image($instance->logo ? $instance->logo : '', "referrer='origin'");?>
              </div>
              <p class="instance-intro"><?php echo $instance->introduction;?>&nbsp;</p>
            </a>
          </div>
          <div class="instance-actions">
            <?php $canVisit = $this->instance->canDo('visit', $instance);?>
            <?php echo html::a($this->instance->url($instance), $lang->instance->visit, '_blank', "class='btn btn-primary' title='{$lang->instance->visit}'". ($canVisit ? '' : ' disabled style="pointer-events: none;"'));?>
            <?php if(!empty($instance->latestVersion) && empty($instance->solution)):?>
            <?php echo html::a(helper::createLink('instance', 'upgrade', "id=$instance->id", '', true), "<i class='icon-sync'></i>" . $lang->space->upgrade, '', "class='btn btn-link iframe' title='{$lang->space->upgrade}' data-width='500' data-app='space'");?>
            <?php endif;?>
            <?php if($instance->solution):?>
            <?php echo html::a(helper::createLink('solution', 'view', "id=$instance->solution"), $instance->solutionData->name, '', "title='{$instance->solutionData->name}' class='label label-success label-outline solution-link text-ellipsis'");?>
            <?php endif;?>
          </div>
        </div>
        <div class='panel-footer instance-footer'>
          <?php $channel = zget($lang->instance->channelList, $instance->channel, '');?>
          <div class="pull-left"><?php echo $instance->appVersion . ($config->cloud->api->switchChannel && $channel ? " ($channel)" : '');?></div>
          <div class="pull-right instance-status" instance-id="<?php echo $instance->id;?>" data-status="<?php echo $instance->status;?>">
            <?php $this->instance->printStatus($instance);?>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
  <div class='table-footer'><?php $pager->show('right', 'pagerjs', 4800, 12);?></div>
  <?php endif;?>
<div>
