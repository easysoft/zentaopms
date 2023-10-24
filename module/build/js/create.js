$().ready(function()
{
    $(document).on('click', '#lastBuildBtn', function()
    {
        $('#name').val($(this).text()).focus();
    });

    $(document).on('change', '#product, #branch', function()
    {
        var projectID = $('#project').val();
        var productID = $('#product').val();
        $.get(createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=all&index=&needCreate=&type=noempty,notrunk,separate,singled&extra=multiple'), function(data)
        {
            if(data) $('#buildBox').html(data);
            $('#builds').attr('data-placeholder', multipleSelect).chosen();
        });

        $.get(createLink('product', 'ajaxGetProductById', 'produtID=' + productID), function(data)
        {
            $('#branchBox').closest('tr').find('th').text(data.branchName);
        }, 'json');
    });

    $('input[name=isIntegrated]').change(function()
    {
        var projectID   = $('#project').val();
        var executionID = $('#execution').val();

        if($(this).val() == 'no')
        {
            $('#execution').closest('tr').show();
            $('#buildBox').closest('tr').hide();
            loadProducts(executionID);
        }
        else
        {
            $('#execution').closest('tr').hide();
            $('#buildBox').closest('tr').show();

            $.ajaxSettings.async = false;
            loadProducts(projectID);
            var productID = $('#product').val();
            $.get(createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=all&index=&needCreate=&type=noempty,notrunk,separate,singled&extra=multiple'), function(data)
            {
                if(data) $('#buildBox').html(data);
                $('#builds').attr('data-placeholder', multipleSelect).chosen();
            });
            $.ajaxSettings.async = true;
        }
    });
    $('#product').change();

    $('[data-toggle="popover"]').popover();
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
    $('#product').remove();
    $('#product_chosen').remove();
    $('#branch').remove();
    $('#branch_chosen').remove();
    $('#noProduct').remove();
    executionID = executionID ? executionID : 0;
    $.get(createLink('product', 'ajaxGetProducts', 'executionID=' + executionID), function(data)
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

            $('#productBox').append(data);
            $('#product').chosen();

            $.get(createLink('product', 'ajaxGetProductById', 'produtID=' + $("#product").val()), function(data)
            {
                $('#branchBox').closest('tr').find('th').text(data.branchName);
            }, 'json');

            loadBranches($("#product").val());
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
    $.get(createLink('build', 'ajaxGetLastBuild', 'projectID=' + projectID + '&executionID=' + executionID), function(data)
    {
        $('#lastBuildBox').html(data);
    });
}
