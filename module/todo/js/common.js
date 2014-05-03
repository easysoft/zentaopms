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
        customHtml = $(divID).html();
    }

    if(type == 'bug')
    {
        if(id)
        {
          link = createLink('bug', 'ajaxGetUserBugs', 'account=' + account + '&id=' + id);
        }
        else
        {
          link = createLink('bug', 'ajaxGetUserBugs', 'account=' + account);
        }
    }
    else if(type == 'task')
    {
        if(id)
        {
          link = createLink('task', 'ajaxGetUserTasks', 'account=' + account + '&id=' + id);
        }
        else
        {
          link = createLink('task', 'ajaxGetUserTasks', 'account=' + account);
        }
    }

    if(type == 'bug' || type == 'task')
    {
        $(divClass).load(link);
    }
    else if(type == 'custom')
    {
        $(divClass).html(customHtml);
    }
}

function selectNext()
{
    $("#end ")[0].selectedIndex = $("#begin ")[0].selectedIndex + 3;
}

function setBeginsAndEnds(i, beginOrEnd)
{
    if(typeof i == 'undefined')
    {
        for(j = 0; j < batchCreateNum; j++)
        {
            if(j != 0) $("#begins" + j)[0].selectedIndex = $("#ends" + (j - 1))[0].selectedIndex;
            $("#ends" + j)[0].selectedIndex = $("#begins" + j)[0].selectedIndex + 3;
        }
    }
    else
    {
        if(beginOrEnd == 'begin')
        {
            $("#ends" + i)[0].selectedIndex = $("#begins" + i)[0].selectedIndex + 3;
        }
        for(j = i+1; j < batchCreateNum; j++)
        {
            $("#begins" + j)[0].selectedIndex = $("#ends" + (j - 1))[0].selectedIndex;
            $("#ends" + j)[0].selectedIndex = $("#begins" + j)[0].selectedIndex + 3;
        }
    }
}

function switchDateFeature(switcher)
{
    if(switcher.checked) 
    {
        $('#begin').attr('disabled','disabled');
        $('#end').attr('disabled','disabled');
    }
    else
    {
        $('#begin').removeAttr('disabled');
        $('#end').removeAttr('disabled');
    }
}
