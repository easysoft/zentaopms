<style>
.button-c {padding:1px}
.ke-icon-savetemplate {
    background-image: url(theme/default/images/kindeditor/save.gif);
    background-position: center;
    width: 56px;
    height: 20px;
}
</style>
<script language='Javascript'>
var setTemplateTitle = '<?php echo $lang->bug->setTemplateTitle;?>';

/* 保存模板。*/
KE.lang.savetemplate = '<?php echo $lang->bug->saveTemplate;?>';
KE.plugin.savetemplate = {
    click: function(id) {
        content = KE.html('steps');
        jPrompt(setTemplateTitle, '','', function(r)
        {
            if(!r || !content) return;
            saveTemplateLink = createLink('bug', 'saveTemplate');
            $.post(saveTemplateLink, {title:r, content:content}, function(data)
            {
                $('#tplBox').html(data);
            });
        });
    }
}
/* 设置bug模板。*/
function setTemplate(templateID)
{
    $('#tplTitleBox' + templateID).attr('style', 'text-decoration:underline; color:#8B008B');
    steps = $('#template' + templateID).html();
    KE.html('steps', steps);
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
