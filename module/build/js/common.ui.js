/**
 * Load branches
 *
 * @param  int $productID
 * @access public
 * @return void
 */
window.loadBranches = function(productID)
{
    productID = parseInt(productID);
    if(!productID) productID = $('input[name=product]').val();
    if($('input[name=isIntegrated]:checked').val() == 'yes')
    {
        $('[name^=branch]').closest('.form-row').addClass('hidden');
        return false;
    }

    $.get($.createLink('branch', 'ajaxGetBranches', 'productID=' + productID + '&oldBranch=0&param=active&projectID=' + $('input[name=execution]').val() + '&withMainBranch=true&isSiblings=no&fieldID=0&multiple=multiple'), function(data)
    {
        if(data.length > 0)
        {
            const $branchPicker = $('.form-build [name^=branch]').zui('picker');
            $branchPicker.render({items: data});
            $branchPicker.$.setValue('');
            $('[name^=branch]').closest('.form-row').removeClass('hidden');
        }
        else
        {
            $('[name^=branch]').closest('.form-row').addClass('hidden');
        }
    }, 'json');
}

window.loadSystem = function(productID)
{
    productID = parseInt(productID);
    if(!productID) productID = $('input[name=product]').val();

    $.get($.createLink('build', 'ajaxGetSystemList', 'productID=' + productID), function(data)
    {
        const $systemPicker = $('[name=system]').zui('picker');
        $systemPicker.$.clear();
        $systemPicker.render({items: data});
    }, 'json');
};

window.setSystemBox = function(e)
{
    const newSystem = $(e.target).is(':checked') ? 1 : 0;
    $('#systemBox #systemName').addClass('hidden');
    $('#systemBox .picker-box').addClass('hidden');
    if(newSystem == 1)
    {
        $('#systemBox #systemName').removeClass('hidden');
    }
    else
    {
        $('#systemBox #systemName').val('');
        $('#systemBox .picker-box').removeClass('hidden');
    }
};

window.loadBuilds = function()
{
    let isIntegrated = $('input[name=isIntegrated]:checked').val();
    if(typeof(build) == "undefined" && (isIntegrated == 'no' || typeof(isIntegrated) == "undefined")) return;

    let executionID = $('input[name=execution]').val();
    let systemID    = $('input[name=system]').val();
    let productID   = $('input[name=product]').val();

    $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=all&needCreate=&type=noempty,notrunk,separate,singled&system=' + systemID), function(data)
    {
        data = JSON.parse(data);
        const $buildsPicker = $('select[name^=builds]').zui('picker');
        $buildsPicker.$.clear();
        $buildsPicker.render({items: data, multiple: true});
        $('select[name^=builds]').attr('data-placeholder', multipleSelect);
    });
}
