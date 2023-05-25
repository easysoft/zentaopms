<?php 
$this->app->loadLang('measurement');
js::set('methodName', $this->app->methodName);
?>
<?php if(common::hasPriv('measurement', 'settips')) echo html::a($this->createLink('measurement', 'settips'), '<span class="text">' . $lang->measurement->setTips . '</span>', '', "class='btn btn-link settipsTab'");?>
<?php if(common::hasPriv('measurement', 'browse')) echo html::a($this->createLink('measurement', 'browse'), '<span class="text">' . $lang->measurement->definition . '</span>', '', "class='btn btn-link browseTab'");?>
<?php if(common::hasPriv('sqlbuilder', 'browseSqlView')) echo html::a($this->createLink('sqlbuilder', 'browsesqlview'), '<span class="text">' . $lang->measurement->sqlBuilder. '</span>', '', "class='btn btn-link browsesqlviewTab'");?>
<?php if(common::hasPriv('measurement', 'template')) echo html::a($this->createLink('measurement', 'template'), '<span class="text">' . $lang->measurement->template . '</span>', '', "class='btn btn-link templateTab'");?>
<script>
$('#mainMenu .' + methodName + 'Tab').addClass('btn-active-text');
</script>
