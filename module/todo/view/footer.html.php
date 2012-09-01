<script language='Javascript'>
account='<?php echo $app->user->account;?>';
function loadList(type, id)
{
    if(id)
    {
        divClass = '.nameBox' + id;
        divID    = '#nameBox' + id;
    }
    else
    {
        divClass = '.nameBox';
        divID    = '#nameBox';
    }
    customHtml = $(divID).html();

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
    $("#end ").get(0).selectedIndex = $("#begin ").get(0).selectedIndex + 3;
}

function setBeginsAndEnds(i, beginOrEnd)
{
    if(typeof i == 'undefined')
    {
        for(j = 0; j < batchCreateNum; j++)
        {
            if(j != 0) $("#begins" + j).get(0).selectedIndex = $("#ends" + (j - 1)).get(0).selectedIndex;
            $("#ends" + j).get(0).selectedIndex = $("#begins" + j).get(0).selectedIndex + 3;
        }
    }
    else
    {
        if(beginOrEnd == 'begin')
        {
            $("#ends" + i).get(0).selectedIndex = $("#begins" + i).get(0).selectedIndex + 3;
        }
        for(j = i+1; j < batchCreateNum; j++)
        {
            $("#begins" + j).get(0).selectedIndex = $("#ends" + (j - 1)).get(0).selectedIndex;
            $("#ends" + j).get(0).selectedIndex = $("#begins" + j).get(0).selectedIndex + 3;
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
</script>
<?php include '../../common/view/footer.html.php';?>
