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
    endIndex = $("#begin ").get(0).selectedIndex + 2;
    $("#end ").get(0).selectedIndex = endIndex;
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
