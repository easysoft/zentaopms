$(function()
{
    if(typeof(resetActive) != 'undefined') return false;
    if(typeof(storyType) == 'undefined') storyType = '';
    if(typeof(rawModule) == 'undefined') rawModule = 'product';
    if(typeof(app)       == 'undefined') app       = '';
    if(typeof(execution) != 'undefined') rawModule = 'projectstory';
    if(['project', 'projectstory'].indexOf(rawModule) === -1 && app != 'qa')
    {
        if(app != 'my') $('#navbar .nav li').removeClass('active');
        $("#navbar .nav li[data-id=" + storyType + ']').addClass('active');
        $('#subNavbar li[data-id="' + storyType + '"]').addClass('active');
    }

    $('#saveButton').on('click', function()
    {
        $('#saveButton').attr('disabled', true);
        $('#saveDraftButton').attr('disabled', true);

        var storyStatus = !$('#reviewer').val() || $('#needNotReview').is(':checked') ? 'active' : 'reviewing';
        $('<input />').attr('type', 'hidden').attr('name', 'status').attr('value', storyStatus).appendTo('#dataform');
        $('#dataform').submit();

        setTimeout(function()
        {
            $('#saveButton').removeAttr('disabled');
            $('#saveDraftButton').removeAttr('disabled');
        }, 1000);
    });

    $('#saveDraftButton').on('click', function()
    {
        $('#saveButton').attr('disabled', true);
        $('#saveDraftButton').attr('disabled', true);

        storyStatus = 'draft';
        if(typeof(page) != 'undefined' && page == 'change') storyStatus = 'changing';
        if(typeof(page) !== 'undefined' && page == 'edit' && $('#status').val() == 'changing') storyStatus = 'changing';
        $('<input />').attr('type', 'hidden').attr('name', 'status').attr('value', storyStatus).appendTo('#dataform');
        $('#dataform').submit();

        setTimeout(function()
        {
            $('#saveButton').removeAttr('disabled');
            $('#saveDraftButton').removeAttr('disabled');
        }, 1000);
    });
})

/**
 * Get status.
 *
 * @param  method $method
 * @param  params $params
 * @access public
 * @return void
 */
function getStatus(method, params)
{
    $.get(createLink('story', 'ajaxGetStatus', "method=" + method + '&params=' + params), function(status)
    {
        $('form #status').val(status).change();
    });
}

/**
 * Load URS.
 *
 * @access public
 * @return void
 */
function loadURS()
{
    var productID       = $('#product').val();
    var branchID        = $('#branch').val();
    var moduleID        = $('#module').val();
    var requirementList = $('#URS').val();
    requirementList     = requirementList ? requirementList.join(',') : '';

    var link = createLink('story', 'ajaxGetURS', 'productID=' + productID + '&branchID=' + branchID + '&moduleID=' + moduleID + '&requirementList=' + requirementList);

    $.post(link, function(data)
    {
        $('#URS').replaceWith(data);
        $('#URS_chosen').remove();
        $('#URS').chosen();
    });
}
