$().ready(function()
{
    new zui.Tooltip('#tooltipHover', {title: autoRelationTip, trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light'});

    $(document).on('click', '#lastBuildBtn', function()
    {
        $('#name').val($(this).text());
    });

    $(document).on('change', '#product, #branch', function()
    {
        var projectID = $('#project').val();
        var productID = $('#product').val();
        $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=all&index=&needCreate=&type=noempty,notrunk,separate,singled&extra=multiple'), function(data)
        {
            $('#builds').replaceWith(data);
            $('#builds').attr('data-placeholder', multipleSelect);
        });

        $.get($.createLink('product', 'ajaxGetProductById', 'produtID=' + productID), function(data)
        {
            $('#branch').closest('.form-label').text(data.branchName);
        }, 'json');
    });

    $(document).on('change', 'input[name=isIntegrated]', function()
    {
        var projectID   = $('#project').val();
        var executionID = $('#execution').val();

        if($(this).val() == 'no')
        {
            $('#execution').closest('.form-row').removeClass('hidden');
            $('#builds').closest('.form-row').addClass('hidden');
            loadProducts(executionID);
        }
        else
        {
            $('#execution').closest('.form-row').addClass('hidden');
            $('#builds').closest('.form-row').removeClass('hidden');

            loadProducts(projectID);
            var productID = $('#product').val();
            $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=all&index=&needCreate=&type=noempty,notrunk,separate,singled&extra=multiple'), function(data)
            {
                $('#builds').replaceWith(data);
                $('#builds').attr('data-placeholder', multipleSelect);
            });
        }
    });
    loadBranches();
});

/**
 * Load products.
 *
 * @param  int $executionID
 * @access public
 * @return void
 */
function loadProducts(executionID)
{
    executionID = parseInt(executionID);
    if(!executionID) executionID = $(this).val();
    $.get($.createLink('product', 'ajaxGetProducts', 'executionID=' + executionID), function(data)
    {
        if(data)
        {
            if(data.indexOf("required") != -1)
            {
                $('#productBox').addClass('required');
            }
            else
            {
                $('#productBox').removeClass('required');
            }

            $('#product').replaceWith(data);

            $.get($.createLink('product', 'ajaxGetProductById', 'produtID=' + $("#product").val()), function(data)
            {
                $('#branch').closest('.form-label').text(data.branchName);
            }, 'json');

            loadBranches($('#product').val());
        }
    });

    loadLastBuild();
}

/**
 * Load last build
 *
 * @access public
 * @return void
 */
function loadLastBuild()
{
    var isIntegrated = $('input[name=isIntegrated]:checked').val();
    var projectID    = $('#project').val();
    var executionID  = $('#execution').val();
    if(isIntegrated == 'yes') executionID = 0;
    $.get($.createLink('build', 'ajaxGetLastBuild', 'projectID=' + projectID + '&executionID=' + executionID), function(data)
    {
        $('#lastBuildBox').html(data);
    });
}
