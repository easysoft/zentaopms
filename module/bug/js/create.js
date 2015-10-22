/**
 * Load team members of the project as assignedTo list.
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
  *Load all builds of one project or product.
  *
  * @access public
  * @return void
  */
function loadAllBuilds()
{
    productID = $('#product').val();
    projectID = $('#project').val();
    if(projectID)
    {
        loadAllProjectBuilds(projectID, productID);
    }
    else
    {
        loadAllProductBuilds(productID);
    }
}

/** 
  * Load all builds of the project.
  *
  * @param  int    $projectID
  * @access public
  * @return void
  */
function loadAllProjectBuilds(projectID, productID)
{
    if(page == 'create') oldOpenedBuild = $('#openedBuild').val() ? $('#openedBuild').val() : 0;

    if(page == 'create')
    {
        link = createLink('build', 'ajaxGetAllProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild);
        $('#buildBox').load(link, function(){ notice(); $('#openedBuild').chosen(defaultChosenOptions);});
    }
}

/** 
  * Load all builds of the product.
  *
  * @param  int    $productID
  * @access public
  * @return void
  */
function loadAllProductBuilds(productID)
{
    link = createLink('build', 'ajaxGetAllProductBuilds', 'productID=' + productID + '&varName=openedBuild&build=' + oldOpenedBuild);

    if(page == 'create') $('#buildBox').load(link, function(){ notice(); $('#openedBuild').chosen(defaultChosenOptions);});
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
