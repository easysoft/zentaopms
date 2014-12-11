<script language='Javascript'>
setTemplateTitle = '<?php echo $lang->bug->setTemplateTitle;?>';
</script>
<?php 
foreach($templates as $key => $template)
{
    echo "<li id='tplBox$template->id'>";
    echo "<a title='{$lang->bug->applyTemplate}' class='tpl-name' id='tplTitleBox$template->id' href='javascript:setTemplate($template->id)'>$template->title</a>";
    echo "<a href='javascript:void();' onclick='deleteTemplate($template->id)' class='btn-delete'><i class='icon-remove'></i></a>";
    echo "<span id='template$template->id' class='hidden'>$template->content</span>";
    echo '</li>';
}
