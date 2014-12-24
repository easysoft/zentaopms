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
    $('#assignedToBox').load(link, function(){$('#assignedTo').chosen(defaultChosenOptions);});
}

/**
 * load assignedTo and stories of module.
 * 
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    moduleID  = $('#module').val();
    productID = $('#product').val();
    setAssignedTo(moduleID, productID);
    setStories(moduleID, productID);
}

/**
 * Set the assignedTo field.
 * 
 * @access public
 * @return void
 */
function setAssignedTo(moduleID, productID)
{
    link = createLink('bug', 'ajaxGetModuleOwner', 'moduleID=' + moduleID + '&productID=' + productID);
    $.get(link, function(owner)
    {
        $('#assignedTo').val(owner);
        $("#assignedTo").trigger("chosen:updated");
    });
}

$(function()
{
    if($('#project').val()) loadProjectRelated($('#project').val());
    $('#saveTplBtn').on('click', function()
    {
        var content = $('#steps').val();
        bootbox.prompt(setTemplateTitle, function(r)
        {
            if(!r || !content) return;
            saveTemplateLink = createLink('bug', 'saveTemplate');
            $.post(saveTemplateLink, {title:r, content:content}, function(data)
            {
                $('#tplBox').html(data);
            });
        });
    });
});

// /* Save template. */
// KindEditor.plugin('savetemplate', function(K) 
// {
//     var self = this, name = 'savetemplate';
//     self.plugin.savetemplate = 
//     {
//         click: function(id) 
//         {
//             content = self.html();
//             bootbox.prompt(setTemplateTitle, function(r)
//             {
//                 if(!r || !content) return;
//                 saveTemplateLink = createLink('bug', 'saveTemplate');
//                 $.post(saveTemplateLink, {title:r, content:content}, function(data)
//                 {
//                     $('#tplBox').html(data);
//                 });
//             });
//         }
//     };
//     self.clickToolbar(name, self.plugin.savetemplate.click);
// });

/* Set template. */
function setTemplate(templateID)
{
    $('#tplBox .list-group-item.active').removeClass('active');
    $('#tplTitleBox' + templateID).closest('.list-group-item').addClass('active');
    steps = $('#template' + templateID).html();
    editor['#'].html(steps);
}

/* Delete template. */
function deleteTemplate(templateID)
{
    if(!templateID) return;
    hiddenwin.location.href = createLink('bug', 'deleteTemplate', 'templateID=' + templateID);
    $('#tplBox' + templateID).addClass('hidden');
}
