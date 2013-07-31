function deletePlan(planID)
{
    if(confirm(confirmDelete))
    {
        url = createLink('productplan', 'delete','planID=' + planID + '&confrim=yes');
        $.ajax(
        {
            type:     'GET', 
            url:      url,
            dataType: 'json', 
            success:  function(data) 
            {
                if(data.result == 'success') 
                {
                    url = createLink('productplan', 'browse', 'productID=' + productID);
                    $('.outer').load(url + ' #productplan');
                }
            }
        });
    }
}
