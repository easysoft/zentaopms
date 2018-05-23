<?php
/**
 * The header view file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     webhook
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php foreach($lang->message->typeList as $type => $typeName):?>
    <?php if(isset($config->message->typeLink[$type])):?>
    <?php list($moduleName, $methodName) = explode('|', $config->message->typeLink[$type]);?>
    <?php if(!common::hasPriv($moduleName, $methodName)) continue;?>
    <?php echo html::a($this->createLink($moduleName, $methodName), "<span class='text'>{$typeName}</span>", '', "class='btn btn-link " . ($type == 'webhook' ? 'btn-active-text' : '') . "' id='{$type}Tab'")?>
    <?php endif;?>
    <?php endforeach;?>
    <?php echo html::a($this->createLink('message', 'setting'), "<span class='text'>{$lang->message->setting}</span>", '', "class='btn btn-link' id='settingTab'")?>
  </div>
  <div class='btn-toolbar pull-right'>
    <div class='btn-group'>
      <div class='btn-group' id='createActionMenu'>
        <?php if(common::hasPriv('webhook', 'create')) echo html::a($this->createLink('webhook', 'create'), "<i class='icon-plus'></i> {$lang->webhook->create}", '', "class='btn btn-primary'");?>
      </div>
    </div>
  </div>
</div>
