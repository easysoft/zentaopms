function deleteBuild(buildID)
{
    if(confirm(confirmDelete))
    {
        url = createLink('build', 'delete','buildID=' + buildID + '&confrim=yes');
        $.ajax(
        {
            type:     'GET', 
            url:      url,
            dataType: 'json', 
            success:  function(data) 
            {
                if(data.result == 'success') 
                {
                    url = createLink('project', 'build', 'projectID=' + projectID);
                    $('.outer').load(url + ' #buildlist', function(){sortTable();});
                }
            }
        });
    }
}
