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
        var selectProjectID  = $('#project').val();
        var currentProductID = $('#product').val();
        var currentBranchID  = $('#branch').val();

        $.get(createLink('project', 'ajaxGetLinkedProducts', 'projectID=' + selectProjectID), function(product)
        {
            var products = [];
            var branches = [];

            var linkedProducts = JSON.parse(product);
            for(var productID in linkedProducts)
            {
                for(var branchID in linkedProducts[productID])
                {
                    products.push(productID);
                    branches.push(branchID);
                }
            }

            products.push(currentProductID);
            branches.push(currentBranchID);

            $.post(createLink('project', 'manageProducts', 'projectID=' + selectProjectID), {'products' : products, 'branch' : branches}, function()
            {
                $('#link2Project').modal('hide');
                window.location.reload();
            });
        });
    });
});
