/* Copy story title as task title. */
function copyStoryTitle()
{
    var storyTitle = $('#story option:selected').text();
    startPosition = storyTitle.indexOf(':') + 1;
    endPosition   = storyTitle.lastIndexOf('(');
    storyTitle = storyTitle.substr(startPosition, endPosition - startPosition);

    $('#name').attr('value', storyTitle);
    $('#estimate').val($('#storyEstimate').val());
    $('#desc').val($('#storyDesc').val());

    $('.pri-text span:first').removeClass().addClass('pri' + $('#storyPri').val()).text($('#storyPri').val());
    $('select#pri').val($('#storyPri').val());

    $(window.editor.desc.edit.doc).find('span.kindeditor-ph').remove();
    window.editor.desc.html($('#storyDesc').val());
}

/* Set the assignedTos field. */
function setOwners(result)
{
    $("#multipleBox").removeAttr("checked");
    $('.team-group').addClass('hidden');
    $('#assignedTo, #assignedTo_chosen').removeClass('hidden');
    if(result == 'affair')
    {
        $('#assignedTo').attr('multiple', 'multiple');
        $('#assignedTo').chosen('destroy');
        $('#assignedTo').chosen(defaultChosenOptions);
        $('.affair').hide();
        $('.team-group').addClass('hidden');
        $('#selectAllUser').removeClass('hidden');
    }
    else if($('#assignedTo').attr('multiple') == 'multiple')
    {
        $('#assignedTo').removeAttr('multiple');
        $('#assignedTo').chosen('destroy');
        $('#assignedTo').chosen(defaultChosenOptions);
        $('.affair').show();
        $('#selectAllUser').addClass('hidden');
    }
}

/* Set preview and module of story. */
function setStoryRelated()
{
    setPreview();
    setStoryModule();
}

/* Set the story module. */
function setStoryModule()
{
    var storyID = $('#story').val();
    if(storyID)
    {
        var link = createLink('story', 'ajaxGetInfo', 'storyID=' + storyID);
        $.getJSON(link, function(storyInfo)
        {
            $('#module').val(storyInfo.moduleID);
            $("#module").trigger("chosen:updated");

            $('#storyEstimate').val(storyInfo.estimate);
            $('#storyPri'     ).val(storyInfo.pri);
            $('#storyDesc'    ).val(storyInfo.spec);
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
        $('input[value="toStoryList"]').attr('disabled', 'disabled');
    }
    else
    {
        if(!toTaskList) $('input[value="continueAdding"]').attr('checked', 'checked');
        $('input[value="continueAdding"]').attr('disabled', false);
        $('input[value="toStoryList"]').attr('disabled', false);
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
    setStoryRelated();

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

    /* Adjust form controls layout */
    var ajustFormControls = function()
    {
        /* Adjust style for file box */
        applyCssStyle('.fileBox > tbody > tr > td:first-child {transition: none; width: ' + ($('#dataPlanGroup').width() - 1) + 'px}', 'filebox');

        /* Adjust #priRowCol and #estRowCol size */
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

/* show team menu. */
$('[name^=multiple]').change(function()
{
    if($(this).prop('checked'))
    {
        $('#assignedTo, #assignedTo_chosen').addClass('hidden');
        $('.team-group').removeClass('hidden');
        $('#estimate').attr('readonly', true);
    }
    else
    {
        $('#assignedTo, #assignedTo_chosen').removeClass('hidden');
        $('.team-group').addClass('hidden');
        $('#estimate').attr('readonly', false);
    }
});
$(".team-group[data-toggle='modalTeam']").css('cursor', 'pointer');
$(".team-group[data-toggle='modalTeam']").click(function()
{
    $('#modalTeam').modal('show');
    adjustSortBtn();
});
