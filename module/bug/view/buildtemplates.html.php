<?php 
foreach($templates as $key => $template)
{
    echo "<li id='tplBox$template->id' onmouseover='displayXIcon($template->id)' onmouseout='hideXIcon($template->id)'>";
    echo "<a title='{$lang->bug->applyTemplate}' class='tpl-name' id='tplTitleBox$template->id' href='javascript:setTemplate($template->id)'>";
    if($template->public) echo "<span class='label label-info label-badge'>{$lang->public}</span> ";
    echo $template->title . "</a>";
    if(empty($template->public) or $template->account == $app->user->account)echo "<a href='###' onclick='deleteTemplate($template->id)' id='templateID$template->id' class='btn-delete hidden'><i class='icon-remove'></i></a>";
    echo "<span id='template$template->id' class='hidden'>$template->content</span>";
    echo '</li>';
}
