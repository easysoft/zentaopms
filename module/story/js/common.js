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

    if($('#navbar .nav>li[data-id=story] .dropdown-menu').length)
    {
        $('#navbar .nav li[data-id=story]').addClass('active');
        $("#navbar>ul>li[data-id='story']>ul>li[data-id!='" + storyType + "']").removeClass('active');
        $("#navbar>ul>li[data-id='story']>ul>li[data-id='" + storyType + "']").addClass('active');
        $('#navbar .nav>li[data-id=story]>a').html($('#navbar .nav li.active [data-id=' + storyType + ']').text() + '<span class="caret"></span>');
    }

    $('#saveButton').on('click', function()
    {
        $('#saveButton').attr('type', 'submit').attr('disabled', true);
        $('#saveDraftButton').attr('disabled', true);

        var storyStatus = !$('#reviewer').val() || $('#needNotReview').is(':checked') ? 'active' : 'reviewing';
        $('<input />').attr('type', 'hidden').attr('name', 'status').attr('value', storyStatus).appendTo('#dataform');
        $('#dataform').submit();

        setTimeout(function()
        {
            $('#saveButton').attr('type', 'button').removeAttr('disabled');
            $('#saveDraftButton').removeAttr('disabled');
        }, 1000);
    });

    $('#saveDraftButton').on('click', function()
    {
        $('#saveButton').attr('disabled', true);
        $('#saveDraftButton').attr('type', 'submit').attr('disabled', true);

        storyStatus = 'draft';
        if(typeof(page) != 'undefined' && page == 'change') storyStatus = 'changing';
        if(typeof(page) !== 'undefined' && page == 'edit' && $('#status').val() == 'changing') storyStatus = 'changing';
        $('<input />').attr('type', 'hidden').attr('name', 'status').attr('value', storyStatus).appendTo('#dataform');
        $('#dataform').submit();

        setTimeout(function()
        {
            $('#saveButton').removeAttr('disabled');
            $('#saveDraftButton').attr('type', 'button').removeAttr('disabled');
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
function loadURS(allURS)
{
    var productID       = $('#product').val();
    var branchID        = $('#branch').val();
    var moduleID        = typeof(allURS) == 'undefined' ? $('#module').val() : 0;
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

$('.twins').mouseover(function() {
    $(this).parent('ul').find('a.unlink').addClass('hide');
    $(this).find('.unlink').removeClass('hide');
});
$('.twins').mouseenter(function() {
    $('[data-toggle="popover"]').popover('hide');
});
$('.twins').mouseout(function() {
    $(this).find('.unlink').addClass('hide');
});

$('[data-toggle="popover"]').each(function(item) {
    $index = $(this).attr('data-id');
    $(this).popover({
        placement: 'bottom',
        html: true,
        content: '<div class="popover-icon"><i class="icon-info"></i></div><div class="content">' + relievedTip + '</div><div class="popover-custom text-right"><a href="javascript:relieve(' + $index + ')" class="text-active btn-info">' + relieved + '</a> <a href="javascript:popoverCancel(' + $index + ');" class="text-cancel">' + cancel + '</a></div>'
    });
})

function relieve(index)
{
    $.post(relieveURL, {twinID:index}, function(data){
        $('[data-id="' + index + '"]').popover('hide');

        if(data.result == 'success')
        {
            if(data.silbingsCount != 0) $('[data-id="' + index + '"]').parent('li').remove();
            if(data.silbingsCount == 0 || index == storyID)
            {
                $('[href="#legendTwins"]').parent('li').next('li').addClass('active');;
                $('[href="#legendTwins"]').parent('li').remove();
                $('#legendTwins').next('div').addClass('active');
                $('#legendTwins').remove();
                $('#twinsTitle').remove();
                $('#twinsList').remove();
            }
        }
    }, 'json');
}

function popoverCancel(index)
{
    $('[data-id="' + index + '"]').popover('hide').addClass('hide');
}
