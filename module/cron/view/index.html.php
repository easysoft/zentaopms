<?php
/**
 * The index view file of cron module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     cron
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'><?php // common::printAdminSubMenu('system');?></div>
</div>
<div id='mainContent' class='main-content'>
  <?php if(!empty($config->global->cron)):?>
  <div class='main-header'>
    <h2><?php echo $lang->cron->list?></h2>
    <div class='btn-toolbar pull-right'>
    <?php if(common::hasPriv('cron', 'openProcess') and !empty($config->global->cron)) echo html::a(inlink('openProcess'), $lang->cron->openProcess, 'hiddenwin', "class='btn'")?>
    <?php if(common::hasPriv('cron', 'turnon') and !empty($config->global->cron)) echo html::a(inlink('turnon'), $lang->cron->turnonList[0], 'hiddenwin', "class='btn'");?>
      <?php if(common::hasPriv('cron', 'create')) echo html::a(inlink('create'), $lang->cron->create, '', "class='btn btn-primary'")?>
    </div>
  </div>
  <table class='table table-condensed table-bordered table-fixed main-table'>
    <thead>
      <tr>
        <th class='c-minute text-center'><?php echo $lang->cron->m?></th>
        <th class='c-hour text-center'><?php echo $lang->cron->h?></th>
        <th class='c-dom text-center'><?php echo $lang->cron->dom?></th>
        <th class='c-mon text-center'><?php echo $lang->cron->mon?></th>
        <th class='c-dow text-center'><?php echo $lang->cron->dow?></th>
        <th><?php echo $lang->cron->command?></th>
        <th class='c-remark'><?php echo $lang->cron->remark?></th>
        <th class='c-full-date text-center'><?php echo $lang->cron->lastTime?></th>
        <th class='c-status text-center'><?php echo $lang->cron->status?></th>
        <th class='c-actions text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody class='text-center'>
    <?php foreach($crons as $cron):?>
      <tr>
        <td><?php echo $cron->m;?></td>
        <td><?php echo $cron->h;?></td>
        <td><?php echo $cron->dom;?></td>
        <td><?php echo $cron->mon;?></td>
        <td><?php echo $cron->dow;?></td>
        <td class='text-left' title='<?php echo $cron->command?>'><?php echo $cron->command;?></td>
        <td class='text-left' title='<?php echo $cron->remark?>'><?php echo $cron->remark;?></td>
        <td><?php echo substr($cron->lastTime, 2, 17);?></td>
        <td><?php echo zget($lang->cron->statusList, $cron->status, '');?></td>
        <td class='text-center'>
          <?php
          if(common::hasPriv('cron', 'toggle') and !empty($cron->command)) echo html::a(inlink('toggle', "id=$cron->id&status=" . ($cron->status == 'stop' ? 'normal' :  'stop')), $cron->status == 'stop' ? $lang->cron->toggleList['start'] : $lang->cron->toggleList['stop'], 'hiddenwin');
          if(!empty($cron->command) and common::hasPriv('cron', 'edit')) echo html::a(inlink('edit', "id=$cron->id"), $lang->edit);
          if($cron->buildin == 0 and common::hasPriv('cron', 'delete')) echo html::a(inlink('delete', "id=$cron->id"), $lang->delete, 'hiddenwin');
          ?>
        </td>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <div class='space'></div>
  <div class='alert alert-info no-margin'><?php echo $lang->cron->notice->help?></div>
  <?php else:?>
  <div class='container mw-700px'>
    <div class='panel-body'>
      <?php
      echo $lang->cron->introduction;
      if(common::hasPriv('cron', 'turnon')) printf($lang->cron->confirmOpen, inlink('turnon'));
      ?>
    </div>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
