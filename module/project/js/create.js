function switchCopyProject(switcher)
{
    if($(switcher).attr('checked'))
    {
        $('#copyProjectBox').removeClass('hidden');
    }
    else
    {
        $('#copyProjectBox').addClass('hidden');
    }
}

function setCopyProject(projectID)
{
    location.href = createLink('project', 'create', 'projectID=0&copyProjectID=' + projectID);
}
