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
<div id='featurebar'>
  <ul class='nav'>
    <?php foreach($lang->message->typeList as $type => $typeName):?>
    <?php if(isset($config->message->typeLink[$type])):?>
    <?php list($moduleName, $methodName) = explode('|', $config->message->typeLink[$type]);?>
    <li id='<?php echo $type;?>Tab' <?php if($type == 'webhook') echo "class='active'"?>><?php echo html::a($this->createLink($moduleName, $methodName), $typeName)?></li>
    <?php endif;?>
    <?php endforeach;?>
    <li id='settingTab'><?php echo html::a($this->createLink('message', 'setting'), $lang->message->setting)?></li>
  </ul>
  <div class='actions'>
    <div class='btn-group'>
      <div class='btn-group' id='createActionMenu'>
        <?php common::printIcon('webhook', 'create', '', '', 'button', '', '', 'btn-primary');?>
      </div>
    </div>
  </div>
</div>
