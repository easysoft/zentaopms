$(function()
{
    $('#mainMenu input[name^="involved"]').click(function()
    {
        var involved = $(this).is(':checked') ? 1 : 0;
        $.cookie('involved', involved, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });

    $('#saveButton').on('click', function()
    {
        var projectID = $('#project').val();
        var productID = $('#product').val();
        var branchID  = $('#branch').val();

        $.post(createLink('project', 'manageProducts', 'projectID=' + projectID), {'products[]' : [productID], 'branch[]' : [branchID]});

        $('#link2Project').modal('hide');
        window.location.reload();
    });
});

