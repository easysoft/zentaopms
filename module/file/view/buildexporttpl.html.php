<style>
.chosen-container-single {max-width: 100px;}
.chosen-container[id^="template"] .chosen-drop {min-width: 180px;!important}
</style>
<?php 
echo html::select('template', $templatePairs, $templateID, "class='form-control chosen' onchange='setTemplate(this.value)'");
foreach($templates as $key => $template) echo "<span id='template$template->id' class='hidden'>$template->content</span>";
