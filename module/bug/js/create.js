/**
 * Load all fields. 
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadAll(productID)
{
    $('#taskIdBox').innerHTML = '<select id="task"></select>';  // Reset the task.
    $('#task').chosen({no_results_text: noResultsMatch});
    loadModuleMenu(productID);
    loadProductStories(productID);
    loadProductProjects(productID);
    loadProductBuilds(productID); 
    setAssignedTo(); 
}

/**
 * Load module menu.
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadModuleMenu(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug');
    $('#moduleIdBox').load(link);
}

/**
 * Load product stories 
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadProductStories(productID)
{
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID);
    $('#storyIdBox').load(link, function(){$('#story').chosen({no_results_text:noResultsMatch});});
}

/**
 * Load projects of product. 
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadProductProjects(productID)
{
    link = createLink('product', 'ajaxGetProjects', 'productID=' + productID);
    $('#projectIdBox').load(link);
}

/**
 * Load product builds.
 * 
 * @param  int    $productID 
 * @access public
 * @return void
 */
function loadProductBuilds(productID)
{
    link = createLink('build', 'ajaxGetProductBuilds', 'productID=' + productID + '&varName=openedBuild');
    $('#buildBox').load(link);
}

/**
 * Load project related bugs and tasks.
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadProjectRelated(projectID)
{
    if(projectID)
    {
        loadProjectTasks(projectID);
        loadProjectStories(projectID);
        loadProjectBuilds(projectID);
    }
    else
    {
        $('#taskIdBox').innerHTML = '';
        loadProductStories($('#product').val());
        loadProductBuilds($('#product').val());
    }
}

/**
 * Load project tasks.
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadProjectTasks(projectID)
{
    link = createLink('task', 'ajaxGetProjectTasks', 'projectID=' + projectID);
    $('#taskIdBox').load(link, function(){$('#task').chosen({no_results_text:noResultsMatch});});
}

/**
 * Load project stories.
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadProjectStories(projectID)
{
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=' + $('#product').val());
    $('#storyIdBox').load(link, function(){$('#story').chosen({no_results_text:noResultsMatch});});
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

/**
 * Load project builds.
 * 
 * @param  int $projectID 
 * @access public
 * @return void
 */
function loadProjectBuilds(projectID)
{
    selectedBuilds = $('#openedBuild').val();
    if(!selectedBuilds) selectedBuilds = 0;
    link = createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + $('#product').val() + '&varName=openedBuild&builds=' + selectedBuilds);
    $('#buildBox').load(link);
}

$(function() {
    $("#story").chosen({no_results_text:noResultsMatch});
    $("#task").chosen({no_results_text:noResultsMatch});
    $("#mailto").autocomplete(userList, { multiple: true, mustMatch: true});
    setAssignedTo();
})

/* Save template. */
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
/* Set template. */
function setTemplate(templateID)
{
    $('#tplTitleBox' + templateID).attr('style', 'text-decoration:underline; color:#8B008B');
    steps = $('#template' + templateID).html();
    KE.html('steps', steps);
}

/* Delete template. */
function deleteTemplate(templateID)
{
    if(!templateID) return;
    hiddenwin.location.href = createLink('bug', 'deleteTemplate', 'templateID=' + templateID);
    $('#tplBox' + templateID).addClass('hidden');
}
