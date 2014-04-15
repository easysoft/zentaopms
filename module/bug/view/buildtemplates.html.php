<script language='Javascript'>
setTemplateTitle = '<?php echo $lang->bug->setTemplateTitle;?>';
KindEditor.lang({'savetemplate' : '<?php echo $lang->bug->saveTemplate;?>'});
</script>
<?php 
foreach($templates as $key => $template)
{
    echo "<li id='tplBox$template->id' class='nobr list-group-item'>";
    echo "<i class='text-muted icon-angle-left'></i> <a id='tplTitleBox$template->id' href='javascript:setTemplate($template->id)'>$template->title</a>";
    echo "&nbsp; <a href='javascript:void();' onclick='deleteTemplate($template->id)' class='pull-right'><i class='icon-remove'></i></a>";
    echo "<span id='template$template->id' class='hidden'>$template->content</span>";
    echo '</li>';
}
