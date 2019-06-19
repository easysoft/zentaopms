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
    <h2><?php echo $lang->dev->moduleList;?></h2>
  </div>
  <table class='table table-form'>
    <?php foreach($lang->dev->groupList as $group => $groupName):?>
    <tr>
      <th class='w-100px text-top'>
        <div class='item'><?php echo $groupName;?></div>
      </th>
      <td>
        <div class='clearfix'>
          <?php foreach($modules[$group] as $module):?>
          <div class='item'><?php echo html::a($this->createLink('translate', 'module', "language=$language&module=$module"), zget($lang->dev->tableList, $module, $module));?></div>
          <?php endforeach;?>
        </div>
      </td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?> 
