/**
  * Load team members of the latest execution of a product as assignedTo list.
  *
  * @param  int    $productID
  * @param  bool   $changeProduct
  * @access public
  * @return void
  */
function loadExecutionTeamMembers(productID, changeProduct)
{
    var link = createLink('bug', 'ajaxLoadExecutionTeamMembers', 'productID=' + productID + '&selectedUser=' + $('#assignedTo').val());
    $.post(link, function(data)
    {
        $('#assignedTo').replaceWith(data);
        $('#assignedTo_chosen').remove();
        $('#assignedTo').chosen();
        if(typeof(changeProduct) != undefined && changeProduct) setAssignedTo();
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
 * Set lane.
 *
 * @param  int $regionID
 * @access public
 * @return void
 */
function setLane(regionID)
{
    laneLink = createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=bug&field=lane');
    $.get(laneLink, function(lane)
    {
        if(!lane) lane = "<select id='lane' name='lane' class='form-control'></select>";
        $('#lane').replaceWith(lane);
        $("#lane" + "_chosen").remove();
        $("#lane").next('.picker').remove();
        $("#lane").chosen();
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
    else if(parseInt($('#project').val()))
    {
        loadProjectBuilds($('#project').val());
        loadProjectTeamMembers($('#project').val());
    }
    else
    {
        if(!assignedto) setTimeout(function(){setAssignedTo(moduleID, productID)}, 500);
    }

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
});

$(window).unload(function()
{
    if(blockID) window.parent.refreshBlock($('#block' + blockID));
});
