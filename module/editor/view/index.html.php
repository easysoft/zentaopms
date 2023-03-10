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
<?php include $app->getModuleRoot() . 'dev/view/header.html.php';?>
<?php if(common::hasPriv('editor', 'turnon')):?>
<div id='mainMenu' class='clearfix menu-secondary'>
  <div class="pull-left">
    <?php
    echo $lang->editor->turnOff;
    echo html::a($this->createLink('editor', 'turnon', 'status=0'), $lang->dev->switchList['0'], '', "class='btn btn-sm'");
    ?>
  </div>
</div>
<?php endif;?>
<div id='mainContent' class='main-row'>
  <div class='side-col' id='sidebar'>
    <div class='cell moduleTree'>
      <div class='panel panel-sm with-list'>
        <div class='panel-heading'><i class='icon-list'></i> <strong><?php echo $lang->editor->moduleList?></strong></div>
        <?php foreach($lang->dev->groupList as $group => $groupName):?>
        <?php if(!empty($modules[$group])):?>
        <div class='modulegroup'><?php echo $groupName?></div>
        <?php foreach($modules[$group] as $module):?>
        <?php $moduleName = zget($lang->dev->tableList, $module, $module);?>
        <?php echo html::a(inlink('extend', "moduleDir=$module"), $moduleName, 'extendWin');?>
        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
        <?php foreach($lang->dev->endGroupList as $group => $groupName):?>
        <?php if(!empty($modules[$group])):?>
        <div class='modulegroup'><?php echo $groupName?></div>
        <?php foreach($modules[$group] as $module):?>
        <?php
        $moduleName = $module;
        if(isset($lang->dev->tableList[$module]))
        {
            $moduleName = $lang->dev->tableList[$module];
        }
        else
        {
            if(!isset($lang->{$module}->common)) $app->loadLang($module);
            $moduleName = isset($lang->{$module}->common) ? $lang->{$module}->common : $module;
        }
        ?>
        <?php echo html::a(inlink('extend', "moduleDir=$module"), $moduleName, 'extendWin');?>
        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
      </div>
    </div>
  </div>
  <div class='side-col w-350px'>
    <div class='cell module-col'>
      <iframe frameborder='0' name='extendWin' id='extendWin' width='100%'></iframe>
    </div>
  </div>
  <div class='main-col main-content module-content'>
    <iframe frameborder='0' name='editWin' id='editWin' width='100%'></iframe>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
