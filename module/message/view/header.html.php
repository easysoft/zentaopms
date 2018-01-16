<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <ul class='nav'>
    <?php foreach($lang->message->typeList as $type => $typeName):?>
    <?php if(isset($config->message->typeLink[$type])):?>
    <?php list($moduleName, $methodName) = explode('|', $config->message->typeLink[$type]);?>
    <li id='<?php echo $type?>Tab'><?php echo html::a($this->createLink($moduleName, $methodName), $typeName)?></li>
    <?php endif;?>
     <?php endforeach;?>
    <li id='settingTab'><?php if(common::hasPriv('message', 'setting')) echo html::a($this->createLink('message', 'setting'), $lang->message->setting)?></li>
  </ul>
</div>
