<style>
.button-c {padding:1px}
</style>
<script language='Javascript'>
/* 设置bug模板。*/
function setTemplate(templateID)
{
    $('#tplTitleBox' + templateID).attr('style', 'text-decoration:underline; color:#8B008B');
    steps = $('#template' + templateID).text();
    $("#steps").val(steps);
}

/* 删除bug模板。*/
function deleteTemplate(templateID)
{
    if(!templateID) return;
    hiddenwin.location.href = createLink('bug', 'deleteTemplate', 'templateID=' + templateID);
    $('#tplBox' + templateID).addClass('hidden');
}
</script>
<?php 
foreach($templates as $key => $template)
{
    echo "<span id='tplBox$template->id'>";
    echo $lang->arrow. "<span id='tplTitleBox$template->id' onclick='setTemplate($template->id)' style='text-decoration:underline; color:blue' class='hand'>$template->title</span>";
    echo html::commonButton('x', "onclick=deleteTemplate($template->id)");
    echo "<span id='template$template->id' class='hidden'>$template->content</span>";
    echo '<br /></span>';
}
