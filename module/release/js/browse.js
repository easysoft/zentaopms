/**
 * Delete release. 
 * 
 * @param  int    releaseID 
 * @access public
 * @return void
 */
function deleteRelease(releaseID)
{
    if(confirm(confirmDelete))
    {
        url = createLink('release', 'delete','releaseID=' + releaseID + '&confrim=yes');
        $.ajax(
        {
            type:     'GET', 
            url:      url,
            dataType: 'json', 
            success:  function(data) 
            {
                if(data.result == 'success') 
                {
                    url = createLink('release', 'browse', 'productID=' + productID);
                    $('.outer').load(url + ' #releaselist', function(){sortTable();});
                }
            }
        });
    }
}
