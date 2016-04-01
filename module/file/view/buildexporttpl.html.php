<?php 
echo html::select('template', $templatePairs, $templateID, "class='form-control chosen' onchange='setTemplate(this.value)'");
foreach($templates as $key => $template) echo "<span id='template$template->id' class='hidden'>$template->content</span>";
