function loadBuilds(event)
{
    let productID = $(event.target).val();
    $.get($.createLink('projectrelease', 'ajaxLoadBuilds', "projectID=" + projectID + "&productID=" + productID), function(data)
    {
        if(data)
        {
            data = JSON.parse(data);
            $('[name*="build"]').zui('picker').render({items: data});
        }
    });

}
