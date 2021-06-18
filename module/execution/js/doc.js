/**
 * Delete doc. 
 * 
 * @param  int    $docID 
 * @access public
 * @return void
 */
function deleteDoc(docID)
{
    if(confirm(confirmDelete))
    {
        url = createLink('doc', 'delete','docID=' + docID + '&confrim=yes');
        $.ajax(
        {
            type:     'GET', 
            url:      url,
            dataType: 'json', 
            success:  function(data) 
            {
                if(data.result == 'success') 
                {
                    url = createLink('project', 'doc', 'projectID=' + projectID);
                    $('.outer').load(url + ' #doclist', function(){sortTable();});
                }
            }
        });
    }
}
