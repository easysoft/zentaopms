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

    var param = 'account=' + account;
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
        link = createLink('story', 'ajaxGetUserStorys', param);
    }

    if(type == 'bug' || type == 'task' || type == 'story')
    {
        $.get(link, function(data, status)
        {
            if(data != ' ')
            {
                $(divClass).html(data);
            }
            else
            {
                $("#type").val("custom");
                $(divClass).html("<select id='bugs' class='form-control'></select>");
            }
        });
    }
    else if(type == 'custom')
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
            $("#ends" + j).trigger('chosen:updated');
        }
    }
    else
    {
        if(beginOrEnd == 'begin')
        {
            $("#ends" + i)[0].selectedIndex = $("#begins" + i)[0].selectedIndex + 3;
            $("#ends" + j).trigger('chosen:updated');
        }
        for(j = i+1; j < batchCreateNum; j++)
        {
            $("#begins" + j)[0].selectedIndex = $("#ends" + (j - 1))[0].selectedIndex;
            $("#ends" + j)[0].selectedIndex = $("#begins" + j)[0].selectedIndex + 3;
            $("#begins" + j).trigger('chosen:updated');
            $("#ends" + j).trigger('chosen:updated');
        }
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
