$(function()
{

});

window.changeAllLines = function()
{
    $('input[name^=productLines]').prop('checked', $('#checkAllLines').prop('checked'));
    $('#checkAllProducts').prop('checked', $('#checkAllLines').prop('checked'));
    changeAllProducts();
    $('#checkAllSprints').prop('checked', $('#checkAllLines').prop('checked'));
    changeAllSprints();
}

window.changeAllProducts = function()
{
    $('input[name^=products]').prop('checked', $('#checkAllProducts').prop('checked'));

    $('input[name^=products]').each(function()
    {
        const lineID = $(this).data('line');
        if(lineID) checkGroupLines(lineID);
    });

    $('#checkAllSprints').prop('checked', $('#checkAllProducts').prop('checked'));
    changeAllSprints();
}

window.changeAllSprints = function()
{
    $('input[name^=sprints]').prop('checked', $('#checkAllSprints').prop('checked'));

    $('input[name^=sprints]').each(function()
    {
        const productID = $(this).data('product');
        const lineID    = $(this).data('line');
        checkGroupProducts(lineID, productID);
        if(lineID) checkGroupLines(lineID);
    });
}

window.changeLines = function(event)
{
    checkAllLines();

    const lineID = $(event.target).val();
    $('input[id^=products-' + lineID + '-]').prop('checked', $(event.target).prop('checked'));
    checkAllProducts();
    $('input[id^=sprints-' + lineID + '-]').prop('checked', $(event.target).prop('checked'));
    checkAllSprints();
}

window.changeProducts = function(event)
{
    checkAllProducts();

    const productID = $(event.target).val();
    const lineID    = $(event.target).data('line');
    if(lineID)
    {
        $('input[id^=sprints-' + lineID + '-' + productID + '-]').prop('checked', $(event.target).prop('checked'));
        checkGroupLines(lineID);
    }
    else
    {
        $('input[id^=sprints-' + productID + '-]').prop('checked', $(event.target).prop('checked'));
    }
    checkAllSprints();
}

window.changeSprints = function(event)
{
    checkAllSprints();

    const productID = $(event.target).data('product');
    const lineID    = $(event.target).data('line');
    checkGroupProducts(lineID, productID);
    if(lineID) checkGroupLines(lineID);
}

window.checkAllLines = function()
{
    let allChecked = true;
    $('input[name^=productLines]').each(function()
    {
        if(!$(this).prop('checked')) allChecked = false;
    });
    $('#checkAllLines').prop('checked', allChecked);
}

window.checkAllProducts = function()
{
    let allChecked = true;
    $('input[name^=products]').each(function()
    {
        if(!$(this).prop('checked')) allChecked = false;
    });
    $('#checkAllProducts').prop('checked', allChecked);
}

window.checkAllSprints = function()
{
    let allChecked = true;
    $('input[name^=sprints]').each(function()
    {
        if(!$(this).prop('checked')) allChecked = false;
    });
    $('#checkAllSprints').prop('checked', allChecked);
}

window.checkGroupLines = function(lineID)
{
    let allChecked = true;
    $('input[id^=products-' + lineID + '-]').each(function()
    {
        if(!$(this).prop('checked')) allChecked = false;
    });
    $('#productLines' + lineID).prop('checked', allChecked);
}

window.checkGroupProducts = function(lineID, productID)
{
    let allChecked = true;
    if(lineID)
    {
        $('input[id^=sprints-'+ lineID +'-' + productID + '-]').each(function()
        {
            if(!$(this).prop('checked')) allChecked = false;
        });
        $('#products-'+ lineID +'-' + productID).prop('checked', allChecked);
    }
    else
    {
        $('input[id^=sprints-' + productID + '-]').each(function()
        {
            if(!$(this).prop('checked')) allChecked = false;
        });
        $('#products' + productID).prop('checked', allChecked);
    }
}
