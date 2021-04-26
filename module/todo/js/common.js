function switchDateTodo(switcher)
{
    if(switcher.checked)
    {
        $('#date').attr('disabled','disabled');
    }
    else
    {
        $('#date').removeAttr('disabled');
    }
}

function loadList(type, id)
{
    if(id)
    {
        divClass = '.nameBox' + id;
        divID    = '#nameBox' + id;
    }
    else
    {
        divClass   = '.nameBox';
        divID      = '#nameBox';
    }

    var param = 'userID=' + userID;
    if(id) param += '&id=' + id;
    if(type == 'bug')
    {
        link = createLink('bug', 'ajaxGetUserBugs', param);
    }
    else if(type == 'task')
    {
        link = createLink('task', 'ajaxGetUserTasks', param);
    }
    else if(type == 'story')
    {
        link = createLink('story', 'ajaxGetUserStories', param);
    }
    else if(type == 'issue')
    {
        link = createLink('issue', 'ajaxGetUserIssues', param);
    }
    else if(type == 'risk')
    {
        link = createLink('risk', 'ajaxGetUserRisks', param);
    }
    else if(type == 'testtask')
    {
        link = createLink('testtask', 'ajaxGetUserTestTasks', param);
    }
    else if(type == 'review')
    {
        link = createLink('review', 'ajaxGetUserReviews', param);
    }
    else if(type == 'feedback')
    {
        link = createLink('feedback', 'ajaxGetUserFeedback', param);
    }

    if(type == 'bug' || type == 'task' || type == 'story' || type == 'issue' || type == 'risk' || type == 'testtask' || type == 'review' || type == 'feedback')
    {
        $.get(link, function(data, status)
        {
            if(data.length != 0)
            {
                $(divClass).html(data).find('select').chosen();
            }
            else
            {
                $(divClass).html("<select id="+ type +" class='form-control'></select>").find('select').chosen();
            }
        });
    }
    else
    {
        $(divClass).html($(divID).html());
    }
}

function selectNext()
{
    $("#end ")[0].selectedIndex = $("#begin ")[0].selectedIndex + 3;
    $('#end').trigger('chosen:updated');
}

function setBeginsAndEnds(i, beginOrEnd)
{
    if(typeof i == 'undefined')
    {
        for(j = 0; j < batchCreateNum; j++)
        {
            if(j != 0) $("#begins" + j)[0].selectedIndex = $("#ends" + (j - 1))[0].selectedIndex;
            $("#ends" + j)[0].selectedIndex = $("#begins" + j)[0].selectedIndex + 3;
            $("#begins" + j).trigger('chosen:updated');
            $("#ends" + j).trigger('chosen:updated');
        }
    }
    else
    {
        if(beginOrEnd == 'begin')
        {
            $("#ends" + i)[0].selectedIndex = $("#begins" + i)[0].selectedIndex + 3;
            $("#ends" + i).trigger('chosen:updated');
        }

        if(typeof batchCreateNum != 'undefined')
        {
            for(j = i+1; j < batchCreateNum; j++)
            {
                $("#begins" + j)[0].selectedIndex = $("#ends" + (j - 1))[0].selectedIndex;
                $("#ends" + j)[0].selectedIndex = $("#begins" + j)[0].selectedIndex + 3;
                $("#begins" + j).trigger('chosen:updated');
                $("#ends" + j).trigger('chosen:updated');
            }
        }
    }
}

function switchTimeList(number)
{
    if($('#switchTime' + number).prop('checked'))
    {
        $('#begins' + number).attr('disabled', 'disabled').trigger('chosen:updated');
        $('#ends' + number).attr('disabled', 'disabled').trigger('chosen:updated');
    }
    else
    {
        $('#begins' + number).removeAttr('disabled').trigger('chosen:updated');
        $('#ends' + number).removeAttr('disabled').trigger('chosen:updated');
    }
}

function switchDateFeature(switcher)
{
    if(switcher.checked) 
    {
        $('#begin').attr('disabled','disabled').trigger('chosen:updated');
        $('#end').attr('disabled','disabled').trigger('chosen:updated');
    }
    else
    {
        $('#begin').removeAttr('disabled').trigger('chosen:updated');
        $('#end').removeAttr('disabled').trigger('chosen:updated');
    }
}

/**
 * Show specified date.
 *
 * @param  switcher $switcher
 * @access public
 * @return void
 */
function showSpecifiedDate(switcher)
{
    if(switcher.checked)
    {
        $('#everyInput').attr('disabled','disabled');
        $('.specify').removeClass('hidden');
        $('.every').addClass('hidden')
        $('#configEvery').removeAttr('checked');
    }
}

/**
 * Show every.
 *
 * @param  switcher $switcher
 * @access public
 * @return void
 */
function showEvery(switcher)
{
    if(switcher.checked)
    {
        $('#everyInput').removeAttr('disabled');
        $('.specify').addClass('hidden');
        $('.every').removeClass('hidden');
        $('#configSpecify').removeAttr('checked');
        $('#cycleYear').removeAttr('checked');
        $('#configEvery').removeAttr('checked');
    }
}

/**
 * Set days by specified month.
 *
 * @param  int $specifiedMonth
 * @access public
 * @return void
 */
function setDays(specifiedMonth)
{
    /* Get last day in specified month. */
    var date = new Date();
    date.setMonth(specifiedMonth);
    var month = date.getMonth() + 1;
    date.setMonth(month);
    date.setDate(0);
    var specifiedMonthLastDay = date.getDate();

    $('#specifiedDay').empty('');
    for(var i = 1; i <= specifiedMonthLastDay; i++)
    {
        html = "<option value='" + i + "' title='" + i + "' data-keys='" + i + "'>" + i + "</option>";

        $('#specifiedDay').append(html);
    }
}
