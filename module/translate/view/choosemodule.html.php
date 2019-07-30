<?php
/**
 * The choose module view of translate module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     translate
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
<?php echo html::a($this->createLink('translate', 'index'), $lang->translate->index) . ' > ';?>
<?php echo "[{$config->langs[$language]}] " . $lang->dev->moduleList . ' > ' . $lang->translate->chooseModule;?>
</h2>
  </div>
  <table class='table table-bordered table-hover'>
    <thead>
      <tr class='text-center'>
        <th><?php echo $lang->translate->group;?></th>
        <th class='text-left'><?php echo $lang->dev->moduleList;?></th>
        <th class='w-80px'><?php echo $lang->translate->allTotal;?></th>
        <th class='w-100px'><?php echo $lang->translate->translatedTotal;?></th>
        <th class='w-100px'><?php echo $lang->translate->changedTotal;?></th>
        <?php if($config->translate->needReview):?>
        <th class='w-100px'><?php echo $lang->translate->reviewedTotal;?></th>
        <?php endif;?>
        <th class='w-110px'><?php echo $lang->translate->translatedProgress;?></th>
        <?php if($config->translate->needReview):?>
        <th class='w-90px'><?php echo $lang->translate->reviewedProgress;?></th>
        <?php endif;?>
        <th class='thWidth'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody class='text-center'>
      <?php foreach($lang->dev->groupList as $group => $groupName):?>
      <?php if(!isset($modules[$group])) continue;?>
      <?php $i = 0;?>
      <?php foreach($modules[$group] as $module):?>
      <?php
      if(isset($statistics[$module]))
      {
          $moduleStatistics = $statistics[$module];
          $moduleStatistics->count = $this->translate->getLangItemCount($module);
      }
      else
      {
          $moduleStatistics = new stdclass();
          $moduleStatistics->count      = $this->translate->getLangItemCount($module);
          $moduleStatistics->translated = 0;
          $moduleStatistics->changed    = 0;
          $moduleStatistics->reviewed   = 0;
      }
      ?>
      <tr>
        <?php if($i == 0):?>
        <th rowspan='<?php echo count($modules[$group]);?>' class='w-100px text-middle'>
          <div><?php echo $groupName;?></div>
        </th>
        <?php endif;?>
        <td class='text-left'><?php echo zget($lang->dev->tableList, $module, $module);?></td>
        <td><?php echo $moduleStatistics->count;?></td>
        <td><?php echo $moduleStatistics->translated + $moduleStatistics->reviewed;?></td>
        <td><?php echo $moduleStatistics->changed;?></td>
        <?php if($config->translate->needReview):?>
        <td><?php echo $moduleStatistics->reviewed;?></td>
        <?php endif;?>
        <td><?php echo (round(($moduleStatistics->translated + $moduleStatistics->reviewed) / $moduleStatistics->count, 3) * 100) . '%';?></td>
        <?php if($config->translate->needReview):?>
        <td><?php echo (round($moduleStatistics->reviewed / $moduleStatistics->count, 3) * 100) . '%';?></td>
        <?php endif;?>
        <td>
          <?php
          if(common::hasPriv('translate', 'module')) echo html::a($this->createLink('translate', 'module', "language=$language&module=$module"), $lang->translate->common, '', "class='btn btn-sm btn-primary'");
          if(common::hasPriv('translate', 'review') and $config->translate->needReview) echo html::a($this->createLink('translate', 'review', "language=$language&module=$module"), $lang->translate->review, '', "class='btn btn-sm btn-primary'");
          ?>
        </td>
      </tr>
      <?php $i++;?>
      <?php endforeach;?>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?> 
