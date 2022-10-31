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

$('.sibling').mouseover(function() {
    $(this).find('.unlink').removeClass('hide');
});
$('.sibling').mouseout(function() {
    $(this).find('.unlink').addClass('hide');
});

$('[data-toggle="popover"]').each(function(item) {
    $index = $(this).attr('data-id');
    $(this).popover({
        placement: 'bottom',
        html: true,
        content: '<div class="popover-icon"><i class="icon-info"></i></div><div class="content">孪生关系解除后无法恢复，需求的内容不再同步，是否解除？</div><div class="popover-custom text-right"><a href="javascript:relieve(' + $index + ')" class="text-active btn-info">解除</a> <a href="javascript:popoverCancel(' + $index + ');" class="text-cancel">取消</a></div>'
    });
})

function relieve(index)
{
    $.post(relieveURL, {storyID:index}, function(data){
        if(data == 'success') $('[data-id="' + index + '"]').popover('hide').parent('li').remove();
    });
}

function popoverCancel(index)
{
    $('[data-id="' + index + '"]').popover('hide').addClass('hide');
}
