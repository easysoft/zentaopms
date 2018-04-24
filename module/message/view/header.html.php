<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->message->typeList as $type => $typeName):?>
    <?php if(isset($config->message->typeLink[$type])):?>
    <?php list($moduleName, $methodName) = explode('|', $config->message->typeLink[$type]);?>
    <?php if(!common::hasPriv($moduleName, $methodName)) continue;?>
    <?php echo html::a($this->createLink($moduleName, $methodName), "<span class='text'>$typeName</span>", '', "class='btn btn-link' id='{$type}Tab'")?>
    <?php endif;?>
     <?php endforeach;?>
    <?php if(common::hasPriv('message', 'setting')) echo html::a($this->createLink('message', 'setting'), "<span class='text'>{$lang->message->setting}</span>", '', "class='btn btn-link' id='settingTab'")?>
  </div>
</div>
