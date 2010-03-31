<script language='Javascript'>
account='<?php echo $app->user->account;?>';
customHtml = $('#nameBox').html();
function loadList(type)
{
    if(type == 'bug')
    {
        link = createLink('bug', 'ajaxGetUserBugs', 'account=' + account);
    }
    else if(type == 'task')
    {
        link = createLink('task', 'ajaxGetUserTasks', 'account=' + account);
    }
   
    if(type == 'bug' || type == 'task')
    {
        $('#nameBox').load(link);
    }
     else if(type == 'custom')
    {
        $('#nameBox').html(customHtml);
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
