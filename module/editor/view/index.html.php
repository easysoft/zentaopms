<?php
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../dev/view/header.html.php';?>
  <table class='w-p100'>
    <tr>
      <td class='w-200px'>
        <div class='panel panel-sm with-list'>
          <div class='panel-heading'><i class='icon-list'></i> <strong><?php echo $lang->editor->moduleList?></strong></div>
          <?php foreach($lang->dev->groupList as $group => $groupName):?>
          <div class='modulegroup'><?php echo $groupName?></div>
          <?php foreach($modules[$group] as $module):?>
          <?php $moduleName = zget($lang->dev->tableList, $module, $module);?>
          <?php echo html::a(inlink('extend', "moduleDir=$module"), $moduleName, 'extendWin');?>
          <?php endforeach;?>
          <?php endforeach;?>
        </div>
      </td>
      <td class='w-300px'><iframe frameborder='0' name='extendWin' id='extendWin' width='100%'></iframe></td>
      <td><iframe frameborder='0' name='editWin' id='editWin' width='100%'></iframe></td>
    </tr>
  </table> 

<?php include '../../common/view/footer.html.php';?>
