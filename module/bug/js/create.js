/**
 * Load assginedTo list, make use the members of this project at first.
 * 
 * @param  int     $projectID 
 * @access public
 * @return void
 */
function loadAssignedTo(projectID)
{
    link = createLink('bug', 'ajaxLoadAssignedTo', 'projectID=' + projectID + '&selectedUser=' + $('#assignedTo').val());
    $('#assignedToBox').load(link)       
}

/**
 * Set the assignedTo field.
 * 
 * @access public
 * @return void
 */
function setAssignedTo()
{
    link = createLink('bug', 'ajaxGetModuleOwner', 'moduleID=' + $('#module').val() + '&productID=' + $('#product').val());
    $.get(link, function(owner)
    {
        $('#assignedTo').val(owner);
    });
}

/* Save template. */
KindEditor.plugin('savetemplate', function(K) 
{
    var self = this, name = 'savetemplate';
    self.plugin.savetemplate = 
    {
        click: function(id) 
        {
            content = self.html();
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
    };
    self.clickToolbar(name, self.plugin.savetemplate.click);
});

/* Set template. */
function setTemplate(templateID)
{
    $('#tplTitleBox' + templateID).attr('style', 'text-decoration:underline; color:#8B008B');
    steps = $('#template' + templateID).html();
    editor.html(steps);
}

/* Delete template. */
function deleteTemplate(templateID)
{
    if(!templateID) return;
    hiddenwin.location.href = createLink('bug', 'deleteTemplate', 'templateID=' + templateID);
    $('#tplBox' + templateID).addClass('hidden');
}
