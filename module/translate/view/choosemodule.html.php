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
    <h2><?php echo "[{$config->langs[$language]}] " . $lang->dev->moduleList . ' > ' . $lang->translate->chooseModule;?></h2>
  </div>
  <table class='table table-bordered table-hover'>
    <thead>
      <tr>
        <th><?php echo $lang->translate->group;?></th>
        <th><?php echo $lang->dev->moduleList;?></th>
        <th class='w-80px'><?php echo $lang->translate->allTotal;?></th>
        <th class='w-100px'><?php echo $lang->translate->translatedTotal;?></th>
        <th class='w-100px'><?php echo $lang->translate->changedTotal;?></th>
        <th class='w-100px'><?php echo $lang->translate->reviewedTotal;?></th>
        <th class='w-80px'><?php echo $lang->translate->translatedProgress;?></th>
        <th class='w-80px'><?php echo $lang->translate->reviewedProgress;?></th>
        <th class='w-80px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($lang->dev->groupList as $group => $groupName):?>
      <?php $i = 0;?>
      <?php foreach($modules[$group] as $module):?>
      <?php $moduleStatistics = $statistics[$module];?>
      <tr>
        <?php if($i == 0):?>
        <th rowspan='<?php echo count($modules[$group]);?>' class='w-100px text-top'>
          <div class='item'><?php echo $groupName;?></div>
        </th>
        <?php endif;?>
        <td><?php echo zget($lang->dev->tableList, $module, $module);?></td>
        <td class='text-center'><?php echo $moduleStatistics->count;?></td>
        <td class='text-center'><?php echo $moduleStatistics->translated + $moduleStatistics->reviewed;?></td>
        <td class='text-center'><?php echo $moduleStatistics->changed;?></td>
        <td class='text-center'><?php echo $moduleStatistics->reviewed;?></td>
        <td class='text-center'><?php echo (round(($moduleStatistics->translated + $moduleStatistics->reviewed) / $moduleStatistics->count, 3) * 100) . '%';?></td>
        <td class='text-center'><?php echo (round($moduleStatistics->reviewed / $moduleStatistics->count, 3) * 100) . '%';?></td>
        <td>
          <?php
          if(common::hasPriv('translate', 'module')) echo html::a($this->createLink('translate', 'module', "language=$language&module=$module"), $lang->translate->common);
          if(common::hasPriv('translate', 'review') and $config->translate->needReview) echo html::a($this->createLink('translate', 'review', "language=$language&module=$module"), $lang->translate->review);
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
