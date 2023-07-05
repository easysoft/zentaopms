$(function()
{
    $(document).on('click', '#involved', function()
    {
        var involved = $(this).prop('checked') ? 1 : 0;
        $.cookie.set('involved', involved, {expires:config.cookieLife, path:config.webRoot});
        loadCurrentPage();
    });

    $(document).on('click', '#link2Project #saveButton', function()
    {
        var $link2Project    = $(this).closest('#link2Project');
        var selectProjectID  = $link2Project.find('#project').val();
        var currentProductID = $link2Project.find('#product').val();
        var currentBranchID  = $link2Project.find('#branch').val();

        $.get($.createLink('project', 'ajaxGetLinkedProducts', 'projectID=' + selectProjectID), function(product)
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

            $.ajaxSubmit({
                url:  $.createLink('project', 'manageProducts', 'projectID=' + selectProjectID), 
                data: {'products' : products, 'branch' : branches},
                load: true
            });
        });
    });
});

window.renderCustomCell = function(result, {col, row})
{
    if(!col || !row) return result;

    if(col.name == 'end')    result = [row.data.end];
    if(col.name == 'status') result = [row.data.statusTitle];
    if(col.name == 'PM')     result = ["<a href='" + col.setting.link.replace('{PMUserID}', row.data.PMUserID) + "' data-toggle='modal'>" + row.data.PM + "</a>"];
    return result;
}
