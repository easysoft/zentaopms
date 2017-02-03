/* Copy story title as task title. */
function copyStoryTitle()
{
    var storyTitle = $('#story option:selected').text();
    startPosition = storyTitle.indexOf(':') + 1;
    endPosition   = storyTitle.lastIndexOf('(');
    storyTitle = storyTitle.substr(startPosition, endPosition - startPosition);
    $('#name').attr('value', storyTitle);
}

/* Set the assignedTos field. */
function setOwners(result)
{
    if(result == 'affair')
    {
        $('#assignedTo').attr('multiple', 'multiple');
        $('#assignedTo').chosen('destroy');
        $('#assignedTo').chosen(defaultChosenOptions);
        $('#selectAllUser').removeClass('hidden');
    }
    else if($('#assignedTo').attr('multiple') == 'multiple')
    {
        $('#assignedTo').removeAttr('multiple');
        $('#assignedTo').chosen('destroy');
        $('#assignedTo').chosen(defaultChosenOptions);
        $('#selectAllUser').addClass('hidden');
    }
}

/* Set preview and module of story. */
function setStoryRelated()
{
    setPreview();
    if($('#module').val() == 0) setStoryModule();
}

/* Set the story module. */
function setStoryModule()
{
    var storyID = $('#story').val();
    if(storyID)
    {
        var link = createLink('story', 'ajaxGetModule', 'storyID=' + storyID);
        $.get(link, function(moduleID)
        {
            $('#module').val(moduleID);
            $("#module").trigger("chosen:updated");
        });
    }
}

/* Set the story priview link. */
function setPreview()
{
    if(!$('#story').val())
    {
        $('#preview').addClass('hidden');
        $('#copyButton').parent().addClass('hidden');
    }
    else
    {
        storyLink  = createLink('story', 'view', "storyID=" + $('#story').val());
        var concat = config.requestType != 'GET' ? '?'  : '&';
        storyLink  = storyLink + concat + 'onlybody=yes';
        $('#preview').removeClass('hidden');
        $('#preview a').attr('href', storyLink);
        $('#copyButton').parent().removeClass('hidden');
    }

    setAfter();
}

/**
 * Set after locate. 
 * 
 * @access public
 * @return void
 */
function setAfter()
{
    if($("#story").length == 0 || $("#story").select().val() == '') 
    {
        if($('input[value="continueAdding"]').attr('checked') == 'checked') 
        {
            $('input[value="toTaskList"]').attr('checked', 'checked');
        }
        $('input[value="continueAdding"]').attr('disabled', 'disabled');
    }
    else
    {
        $('input[value="continueAdding"]').attr('checked', 'checked');
        $('input[value="continueAdding"]').attr('disabled', false);
    }
}

/**
 * Load stories.
 * 
 * @param  int    $projectID 
 * @access public
 * @return void
 */
function loadStories(projectID)
{
    moduleID  = $('#module').val();
    setStories(moduleID, projectID);
}

/**
 * load stories of module.
 * 
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    moduleID  = $('#module').val();
    projectID = $('#project').val();
    setStories(moduleID, projectID);
}

/* Get select of stories.*/
function setStories(moduleID, projectID)
{
    link = createLink('story', 'ajaxGetProjectStories', 'projectID=' + projectID + '&productID=0&branch=0&moduleID=' + moduleID);
    $.get(link, function(stories)
    {
        var storyID = $('#story').val();
        if(!stories) stories = '<select id="story" name="story" class="form-control"></select>';
        $('#story').replaceWith(stories);
        $('#story').val(storyID);
        setPreview();
        $('#story_chosen').remove();
        $("#story").chosen(defaultChosenOptions);
    });
}

$(document).ready(function()
{
    setPreview();

    $('#selectAllUser').on('click', function()
    {
        var $assignedTo = $('#assignedTo');
        if($assignedTo.attr('multiple')) 
        {
            $assignedTo.children('option').attr('selected', 'selected');
            $assignedTo.trigger('chosen:updated');
        }
    });

    $('[data-toggle=tooltip]').tooltip();

    // adjust form controls layout
    var ajustFormControls = function()
    {
        // adjust style for file box
        applyCssStyle('.fileBox > tbody > tr > td:first-child {transition: none; width: ' + ($('#dataPlanGroup').width() - 1) + 'px}', 'filebox');

        // adjust #priRowCol and #estRowCol size
        var $priRowCol = $('#priRowCol'),
            $estRowCol = $('#estRowCol');
        $priRowCol.css('width', 54 + $priRowCol.find('.input-group-addon').outerWidth());
        $estRowCol.css('width', 55 + $estRowCol.find('.input-group-addon').outerWidth());
    };
    ajustFormControls();
    $(window).resize(ajustFormControls);

    /* First unbind ajaxForm for form.*/
    $("form[data-type='ajax']").unbind('submit');
    setForm();

    /* Bind ajaxForm for form again. */
    $.ajaxForm("form[data-type='ajax']", function(response)
    {
        if(response.message) alert(response.message);
        if(response.locate)
        {
            if(response.locate == 'reload' && response.target == 'parent')
            {
                parent.$.cookie('selfClose', 1);
                parent.$.closeModal(null, 'this');
            }
            else
            {
                location.href = response.locate;
            }
        }
        return false;
    });
});
