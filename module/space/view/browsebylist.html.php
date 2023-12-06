<?php
/**
 * The browse view file of space module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     space
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<div class='main-col'>
  <?php if(empty($instances)):?>
  <div class="table-empty-tip">
    <p><?php echo html::a($this->createLink('store', 'browse'), $lang->space->noApps . ', ' . $lang->space->notice->toInstall, '', "class='btn btn-info'");?></p>
  </div>
  <?php else:?>
  <form class='main-table table-user' data-ride='table' method='post' data-checkable='false' id='appListForm'>
    <table class="table has-sort-head">
      <thead>
        <tr>
          <th><?php echo $lang->instance->name;?></th>
          <th><?php echo $lang->instance->appName;?></th>
          <th><?php echo $lang->version;?></th>
          <th><?php echo $lang->space->status;?></th>
          <th><?php echo $lang->operation;?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($instances as $instance):?>
        <tr>
          <td><?php echo html::a($this->createLink('instance', 'view', "id=$instance->id"), $instance->name);?></td>
          <td><?php echo html::a($this->createLink('store', 'appview', "id=$instance->appID"), $instance->appName);?></td>
          <td><?php echo $instance->appVersion;?></td>
          <td class="instance-status" instance-id="<?php echo $instance->id;?>" data-status="<?php echo $instance->status;?>">
            <?php $this->instance->printStatus($instance);?>
          </td>
          <td>
            <?php $this->instance->printIconActions($instance);?>
            <?php if(!empty($instance->latestVersion)):?>
            <?php echo html::a(helper::createLink('instance', 'upgrade', "id=$instance->id", '', true), "<i class='icon-sync'></i>", '', "class='btn btn-action iframe' title='{$lang->space->upgrade}' data-width='500' data-app='space'");?>
            <?php endif;?>
          </td>
        </tr>
      <?php endforeach;?>
      </tbody>
    </table>
    <?php if($instances):?>
    <div class='table-footer'>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
  <?php endif;?>
</div>
