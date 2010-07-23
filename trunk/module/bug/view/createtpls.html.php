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

/* 保存bug模板。*/
function saveTemplate()
{
    content = $("#steps").val();
    jPrompt(setTemplateTitle, '','', function(r)
    {
        if(!r || !content) return;
        saveTemplateLink = createLink('bug', 'saveTemplate');
        steps = $("#steps").val();
        $.post(saveTemplateLink, {title:r, content:content}, function(data)
        {
            $('#tplBox').html(data);
        });
    });
}

/* 删除bug模板。*/
function deleteTemplate(templateID)
{
    if(!templateID) return;
    hiddenwin.location.href = createLink('bug', 'deleteTemplate', 'templateID=' + templateID);
    $('#tplBox' + templateID).addClass('hidden');
}

var setTemplateTitle = '<?php echo $lang->bug->setTemplateTitle;?>';
</script>
<?php 
foreach($templates as $key => $template)
{
    echo "<span id='tplBox$template->id'>";
    echo ($key + 1) . ". <span id='tplTitleBox$template->id' onclick='setTemplate($template->id)' style='text-decoration:underline; color:blue' class='hand'>$template->title</span>";
    echo html::commonButton('x', "onclick=deleteTemplate($template->id)");
    echo "<span id='template$template->id' class='hidden'>$template->content</span>";
    echo '<br /></span>';
}
echo html::commonButton($lang->save, 'onclick=saveTemplate()');
