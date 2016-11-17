<?php
/**
 * The index view file of cron module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     cron
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'><?php echo $lang->cron->common;?></div>
  <div class='actions'>
    <?php if(common::hasPriv('cron', 'openProcess') and !empty($config->global->cron)) echo html::a(inlink('openProcess'), $lang->cron->openProcess, 'hiddenwin', "class='btn'")?>
    <?php if(common::hasPriv('cron', 'turnon') and !empty($config->global->cron)) echo html::a(inlink('turnon'), $lang->cron->turnonList[0], 'hiddenwin', "class='btn'");?>
  </div>
</div>

<?php if(!empty($config->global->cron)):?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->cron->list?></strong>
    <div class='panel-actions pull-right'>
      <?php if(common::hasPriv('cron', 'create'))      echo html::a(inlink('create'), $lang->cron->create, '', "class='btn btn-primary btn-sm'")?>
    </div>
  </div>
  <table class='table table-condensed table-bordered active-disabled table-fixed'>
    <thead>
      <tr>
        <th class='w-60px'><?php echo $lang->cron->m?></th>
        <th class='w-60px'><?php echo $lang->cron->h?></th>
        <th class='w-60px'><?php echo $lang->cron->dom?></th>
        <th class='w-60px'><?php echo $lang->cron->mon?></th>
        <th class='w-60px'><?php echo $lang->cron->dow?></th>
        <th><?php echo $lang->cron->command?></th>
        <th class='w-100px'><?php echo $lang->cron->remark?></th>
        <th class='w-120px'><?php echo $lang->cron->lastTime?></th>
        <th class='w-60px'><?php echo $lang->cron->status?></th>
        <th class='w-100px'><?php echo $lang->actions;?></th>
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
        <td><?php echo substr($cron->lastTime, 2);?></td>
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
</div>
<div class='alert alert-info'><?php echo $lang->cron->notice->help?></div>
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
<?php include '../../common/view/footer.html.php';?>

