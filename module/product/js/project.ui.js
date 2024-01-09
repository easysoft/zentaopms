$(document).off('click', '#involved').on('click', '#involved', function()
{
    var involved = $(this).prop('checked') ? 1 : 0;
    $.cookie.set('involved', involved, {expires:config.cookieLife, path:config.webRoot});
    loadCurrentPage();
});

window.renderCustomCell = function(result, {col, row})
{
    if(!col || !row) return result;

    if(col.name == 'end')    result = [row.data.end];
    if(col.name == 'status') result = [row.data.statusTitle];
    return result;
}

window.link2Project = function(e)
{
    let $link2Project    = $(e.target).closest('#link2Project');
    let selectProjectID  = $link2Project.find('[name="project"]').val();
    let currentProductID = $link2Project.find('#product').val();
    let currentBranchID  = $link2Project.find('#branch').val();

    $.get($.createLink('project', 'ajaxGetLinkedProducts', 'projectID=' + selectProjectID), function(product)
    {
        let   products = [];
        let   branches = [];
        const formData = new FormData();

        var linkedProducts = JSON.parse(product);
        for(var productID in linkedProducts)
        {
            for(var branchID in linkedProducts[productID])
            {
                formData.append('products[]', productID);
                formData.append('branch[' + productID + '][]', branchID);
            }
        }

        formData.append('products[]', currentProductID);
        formData.append('branch[' + currentProductID + '][]', currentBranchID);

        $.ajaxSubmit({
            url:  $.createLink('project', 'manageProducts', 'projectID=' + selectProjectID),
            data: formData,
            load: true
        });
    });
}
