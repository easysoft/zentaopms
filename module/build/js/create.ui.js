$().ready(function()
{
    new zui.Tooltip('#tooltipHover', {title: autoRelationTip, trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light'});

    $(document).on('click', '#lastBuildBtn', function()
    {
        $('#name').val($(this).text());
    });

    $(document).off('change', '#product, #branch').on('change', '#product, #branch', function()
    {
        let projectID = $('input[name=project]').val();
        let productID = $('input[name=product]').val();
        $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&letName=builds&build=&branch=all&index=&needCreate=&type=noempty,notrunk,separate,singled&extra=multiple'), function(data)
        {
            if(data)
            {
                data = JSON.parse(data);
                const $buildsPicker = $('select[name^=builds]').zui('picker');
                $buildsPicker.render({items: data, multiple: true});
                $('#builds').attr('data-placeholder', multipleSelect);
            }
        });

        if(productID)
        {
            $.get($.createLink('product', 'ajaxGetProductById', 'produtID=' + productID), function(data)
            {
                $('#branch').prev('.form-label').html(data.branchName);
            }, 'json');
        }
    });

    $(document).on('change', 'input[name=isIntegrated]', function()
    {
        let projectID   = $('input[name=project]').val();
        let executionID = $('input[name=execution]').val();

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
            let productID = $('input[name=product]').val();
            $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&letName=builds&build=&branch=all&index=&needCreate=&type=noempty,notrunk,separate,singled&extra=multiple'), function(data)
            {
                if(data)
                {
                    data = JSON.parse(data);
                    const $buildsPicker = $('select[name^=builds]').zui('picker');
                    $buildsPicker.render({items: data, multiple: true});
                    $('#builds').attr('data-placeholder', multipleSelect);
                }
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
    if(!executionID) executionID = $('input[name=execution]').val();
    $.get($.createLink('product', 'ajaxGetProducts', 'executionID=' + executionID), function(data)
    {
        if(data)
        {
            data = JSON.parse(data);
            const $product       = $('input[name=product]');
            const $productPicker = $product.zui('picker');
            const productID      = data[0].value;
            $productPicker.render({items: data});
            $productPicker.$.setValue(productID);

            $('#builds').attr('data-placeholder', multipleSelect);

            loadBranches(productID);
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
    let isIntegrated = $('input[name=isIntegrated]:checked').val();
    let projectID    = $('input[name=project]').val();
    let executionID  = $('input[name=execution]').val();
    if(isIntegrated == 'yes') executionID = 0;
    $.get($.createLink('build', 'ajaxGetLastBuild', 'projectID=' + projectID + '&executionID=' + executionID), function(data)
    {
        $('#lastBuildBox').html(data);
    });
}
