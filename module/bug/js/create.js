/**
  * Load all users as assignedTo list.
  *
  * @access public
  * @return void
  */
function loadAllUsers()
{
    link = createLink('bug', 'ajaxLoadAllUsers', 'selectedUser=' + $('#assignedTo').val());
    $('#assignedToBox').load(link, function(){$('#assignedTo').chosen(defaultChosenOptions);});
}

/**
  * Load team members of the latest project of a product as assignedTo list.
  *
  * @param  $productID
  * @access public
  * @return void
  */
function loadProjectTeamMembers(productID)
{
    link = createLink('bug', 'ajaxLoadProjectTeamMembers', 'productID=' + productID + '&selectedUser=' + $('#assignedTo').val());
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
    if(typeof(productID) == 'undefined') productID = $('#product').val();
    if(typeof(moduleID) == 'undefined')  moduleID  = $('#module').val();
    link = createLink('bug', 'ajaxGetModuleOwner', 'moduleID=' + moduleID + '&productID=' + productID);
    $.get(link, function(owner)
    {
        $('#assignedTo').val(owner);
        $("#assignedTo").trigger("chosen:updated");
    });
}

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

    $('[data-toggle=tooltip]').tooltip();

    // ajust style for file box
    var ajustFilebox = function()
    {
        applyCssStyle('.fileBox > tbody > tr > td:first-child {transition: none; width: ' + ($('#contactListGroup').width() - 2) + 'px}', 'filebox')
    };
    ajustFilebox();
    $(window).resize(ajustFilebox);
});
