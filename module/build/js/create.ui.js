$().ready(function()
{
    new zui.Tooltip('#tooltipHover', {title: autoRelationTip, trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light'});

    $(document).on('click', '#lastBuildBtn', function()
    {
        $('#name').val($(this).text());
    });

    $(document).off('change', '[name=product], [name^=branch]').on('change', '[name=product], [name^=branch]', function()
    {
        let projectID = $('#createBuildForm input[name=project]').val();
        let productID = $('#createBuildForm input[name=product]').val();
        let systemID  = $('input[name=system]').val();
        $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=all&&needCreate=&type=noempty,notrunk,separate,singled&systemID=' + systemID), function(data)
        {
            if(data)
            {
                data = JSON.parse(data);
                const $buildsPicker = $('#createBuildForm select[name^=builds]').zui('picker');
                $buildsPicker.render({items: data, multiple: true});
                $buildsPicker.$.setValue('');
                $('select[name^=builds]').attr('data-placeholder', multipleSelect);
            }
        });

        if(productID)
        {
            $.get($.createLink('product', 'ajaxGetProductById', 'produtID=' + productID), function(data)
            {
                $('#branch').prev('.form-label').html(data.branchName);
            }, 'json');

            loadSystem(productID);
        }
    });

    $(document).on('change', 'input[name=isIntegrated]', function()
    {
        let projectID   = $('input[name=project]').val();
        let executionID = $('input[name=execution]').val();
        let systemID    = $('input[name=system]').val();

        if($(this).val() == 'no')
        {
            $('[name=newSystem]').closest('.input-group-addon').removeClass('hidden');
            $('input[name=execution]').closest('.form-row').removeClass('hidden');
            $('select[name^=builds]').closest('.form-row').addClass('hidden');
            loadProducts(executionID);
        }
        else
        {
            $('[name=newSystem]').closest('.input-group-addon').addClass('hidden');
            $('input[name=execution]').closest('.form-row').addClass('hidden');
            $('select[name^=builds]').closest('.form-row').removeClass('hidden');

            loadProducts(projectID);
            let productID = $('input[name=product]').val();
            $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=all&needCreate=&type=noempty,notrunk,separate,singled&system=' + systemID), function(data)
            {
                if(data)
                {
                    data = JSON.parse(data);
                    const $buildsPicker = $('select[name^=builds]').zui('picker');
                    $buildsPicker.render({items: data, multiple: true});
                    $('select[name^=builds]').attr('data-placeholder', multipleSelect);
                }
            });
        }
    });

    loadBranches();
    if(multipleProject)
    {
        window.waitDom('[name=execution]', function()
        {
            loadProducts();
        })
    }

    if(hidden == 'hide')
    {
        loadSystem(currentProduct);
    }
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
    if(!executionID) executionID = $(document).find('[name=execution]').val();

    $.getJSON($.createLink('product', 'ajaxGetProducts', 'executionID=' + executionID), function(data)
    {
        if(data.length > 0 || executionID == 0 || projectModel == 'waterfall' || projectModel == 'waterfallplus')
        {
            $('#noProductRow').addClass('hidden');
            $('#productRow').removeClass('hidden');

            const $product       = $('#createBuildForm input[name=product]');
            const $productPicker = $product.zui('picker');
            const productID      = data.length ? data[0].value : 0;
            $productPicker.render({items: data});
            $productPicker.$.setValue(productID);

            $('select[name^=builds]').attr('data-placeholder', multipleSelect);
            loadBranches(productID);
        }
        else
        {
            $('#noProductRow').find('a').attr('data-url', $.createLink('execution', 'manageProducts', 'executionID=' + executionID));
            $('#noProductRow').removeClass('hidden');
            $('#productRow').addClass('hidden');
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
