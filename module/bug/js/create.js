/**
  * Load all users as assignedTo list.
  *
  * @access public
  * @return void
  */
function loadAllUsers()
{
    var link = createLink('bug', 'ajaxLoadAllUsers', 'selectedUser=' + $('#assignedTo').val());
    $.get(link, function(data)
    {
        if(data)
        {
            var moduleID  = $('#module').val();
            var productID = $('#product').val();
            setAssignedTo(moduleID, productID);
            $('#assignedTo').replaceWith(data);
            $('#assignedTo_chosen').remove();
            $('#assignedTo').chosen();
        }
    });
}

/**
  * Load team members of the latest execution of a product as assignedTo list.
  *
  * @param  int    $productID
  * @access public
  * @return void
  */
function loadExecutionTeamMembers(productID)
{
    var link = createLink('bug', 'ajaxLoadExecutionTeamMembers', 'productID=' + productID + '&selectedUser=' + $('#assignedTo').val());
    $.post(link, function(data)
    {
        $('#assignedTo').replaceWith(data);
        $('#assignedTo_chosen').remove();
        $('#assignedTo').chosen();
    })
}

/**
 * Load assignedTo and stories of module.
 *
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    var moduleID  = $('#module').val();
    var productID = $('#product').val();
    var storyID   = $('#story').val();
    setAssignedTo(moduleID, productID);
    setStories(moduleID, productID, storyID);
}

/**
 * Set the assignedTo field.
 *
 * @param  int    $moduleID
 * @param  int    $productID
 * @access public
 * @return void
 */
function setAssignedTo(moduleID, productID)
{
    if(typeof(productID) == 'undefined') productID = $('#product').val();
    if(typeof(moduleID) == 'undefined')  moduleID  = $('#module').val();
    var link = createLink('bug', 'ajaxGetModuleOwner', 'moduleID=' + moduleID + '&productID=' + productID);
    $.get(link, function(owner)
    {
        owner        = JSON.parse(owner);
        var account  = owner[0];
        var realName = owner[1];
        var isExist  = false;
        var count    = $('#assignedTo').find('option').length;
        for(var i=0; i < count; i++)
        {
            if($('#assignedTo').get(0).options[i].value == account)
            {
                isExist = true;
                break;
            }
        }
        if(!isExist && account)
        {
            option = "<option title='" + realName + "' value='" + account + "'>" + realName + "</option>";
            $("#assignedTo").append(option);
        }
        $('#assignedTo').val(account);
        $("#assignedTo").trigger("chosen:updated");
    });
}

$(function()
{
    var productID  = $('#product').val();
    var moduleID   = $('#module').val();
    var assignedto = $('#assignedTo').val();
    changeProductConfirmed = true;
    oldStoryID             = $('#story').val() || 0;
    oldExecutionID         = 0;
    oldOpenedBuild         = '';
    oldTaskID              = $('#oldTaskID').val() || 0;

    if(parseInt($('#execution').val()))
    {
        loadExecutionRelated($('#execution').val());
    }
    else
    {
        if(parseInt($('#project').val())) loadProjectTeamMembers($('#project').val());
    }

    if(!assignedto) setTimeout(function(){setAssignedTo(moduleID, productID)}, 500);
    notice();

    $('[data-toggle=tooltip]').tooltip();

    /* Adjust size of bug type input group. */
    var adjustBugTypeGroup = function()
    {
        var $group = $('#bugTypeInputGroup');
        var width = ($group.parent().width()), addonWidth = 0;
        var $controls = $group.find('.chosen-single');
        $group.children('.input-group-addon').each(function()
        {
            addonWidth += $(this).outerWidth();
        });
        var bestWidth = Math.floor((width - addonWidth)/$controls.length);
        $controls.css('width', bestWidth);
        var lastWidth = width - addonWidth - bestWidth * ($controls.length - 1);
        $controls.last().css('width', lastWidth);
    };
    adjustBugTypeGroup();
    $(window).on('resize', adjustBugTypeGroup);

    /* Init pri and severity selector. */
    $('#severity, #pri').on('change', function()
    {
        var $select = $(this);
        var $selector = $select.closest('.pri-selector');
        var value = $select.val();
        $selector.find('.pri-text').html($selector.data('type') === 'severity' ? '<span class="label-severity" data-severity="' + value + '" title="' + value + '"></span>' : '<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
    });

    /* Get steps template. */
    var stepsTemplate = editor['steps'].html();

    /* Judgment of required items for steps. */
    $('#submit').on('click', function()
    {
        var steps = editor['steps'].html();
        if(stepsRequired !== false && (steps == stepsTemplate || steps == editor.steps.templateHtml) && isStepsTemplate)
        {
            bootbox.alert(stepsNotEmpty);
            return false;
        }
    });

    $('#project').change(function()
    {
        var projectID = parseInt($(this).val());
        projectID ? loadProjectTeamMembers(projectID) : loadExecutionTeamMembers($('#product').val());
    });
});

$(window).unload(function(){
    if(blockID) window.parent.refreshBlock($('#block' + blockID));
});

$(document).ready(function()
{
    $('#gitlab').change(function()
    {
         host = $('#gitlab').val();
         if(host == '') return false;
         projects = '';
         $.each(gitlabProjects[host], function(id, obj){projects = projects + ',' + obj.gitlabProject});
         url = createLink('repo', 'ajaxgetgitlabprojects', "host=" + host + "&projects=" + projects);

         $.get(url, function(response)
         {
             $('#gitlabProject').html('').append(response);
             $('#gitlabProject').chosen().trigger("chosen:updated");
         });
    });
});
