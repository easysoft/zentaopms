function loadBuilds(event)
{
    let productID = $(event.target).val();
    $.get($.createLink('projectrelease', 'ajaxLoadBuilds', "projectID=" + projectID + "&productID=" + productID), function(data)
    {
        $('#build').replaceWith(data);
    });

}
