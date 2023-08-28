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

    var $saveButton      = $('#saveButton');
    var $saveDraftButton = $('#saveDraftButton');
    $saveButton.on('click', function(e)
    {
        $saveButton.attr('type', 'submit').attr('disabled', true);
        $saveDraftButton.attr('disabled', true);

        var storyStatus = !$('#reviewer').val() || $('#needNotReview').is(':checked') ? 'active' : 'reviewing';
        $('<input />').attr('type', 'hidden').attr('name', 'status').attr('value', storyStatus).appendTo('#dataform');
        $('#dataform').submit();
        e.preventDefault();

        setTimeout(function()
        {
            if($saveButton.attr('disabled') == 'disabled')
            {
                setTimeout(function()
                {
                    $saveButton.attr('type', 'button').removeAttr('disabled');
                    $saveDraftButton.removeAttr('disabled');
                }, 10000);
            }
            else
            {
                $saveDraftButton.removeAttr('disabled');
            }
        }, 100);
    });

    $saveDraftButton.on('click', function(e)
    {
        $saveButton.attr('disabled', true);
        $saveDraftButton.attr('type', 'submit').attr('disabled', true);

        storyStatus = 'draft';
        if(typeof(page) != 'undefined' && page == 'change') storyStatus = 'changing';
        if(typeof(page) !== 'undefined' && page == 'edit' && $('#status').val() == 'changing') storyStatus = 'changing';
        $('<input />').attr('type', 'hidden').attr('name', 'status').attr('value', storyStatus).appendTo('#dataform');
        $('#dataform').submit();
        e.preventDefault();

        setTimeout(function()
        {
            if($saveDraftButton.attr('disabled') == 'disabled')
            {
                setTimeout(function()
                {
                    $saveButton.removeAttr('disabled');
                    $saveDraftButton.attr('type', 'button').removeAttr('disabled');
                }, 10000);
            }
            else
            {
                $saveButton.removeAttr('disabled');
            }
        }, 100);
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
 * Load product user stories.
 *
 * @access public
 * @return void
 */
function loadURS()
{
    var $URS = $('#URS');
    if($URS.length == 0) return;

    var productID       = $('#product').val();
    var branchID        = $('#branch').val() ? $('#branch').val() : 0;
    var requirementList = $URS.val();
    requirementList     = requirementList ? requirementList.join(',') : '';
    var $branches       = $('#dataform #branchBox [id^=branches]');
    if($branches.length > 0)
    {
        branchIdList = [];
        $branches.each(function()
        {
            var currentBranch = $(this).val();
            if(currentBranch == '') currentBranch = 0;
            if(!branchIdList.includes(currentBranch)) branchIdList.push(currentBranch);
        })
        branchID = branchIdList.join(',');
    }

    var link = createLink('story', 'ajaxGetProductUserStories', 'productID=' + productID + '&branchID=' + branchID + '&requirementList=' + requirementList);
    $.post(link, function(data)
    {
        $('.URSBox').html(data);
        $('.URSBox #URS').picker();
    });
}

$('.twins').mouseover(function() {
    if(page == 'edit') return;
    $(this).parent('ul').find('a.unlink').addClass('hide');
    $(this).find('.unlink').removeClass('hide');
});

$('.twins').mouseenter(function() {
    $('[data-toggle="popover"]').popover('hide');
});

$('.twins').mouseout(function() {
    if(page == 'edit') return;
    $(this).find('.unlink').addClass('hide');
});

if(typeof(relievedTip) != 'undefined')
{
    $('[data-toggle="popover"]').each(function(item) {
        $index = $(this).attr('data-id');
        $(this).popover({
            placement: 'bottom',
            html: true,
            content: '<div class="popover-icon"><i class="icon-info"></i></div><div class="content">' + relievedTip + '</div><div class="popover-custom text-right"><a href="javascript:relieve(' + $index + ')" class="text-active btn-info">' + relieved + '</a> <a href="javascript:popoverCancel(' + $index + ');" class="text-cancel">' + cancel + '</a></div>'
        });
    })
}


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
    $('[data-id="' + index + '"]').popover('hide');
    if(page == 'edit') return;

    $('[data-id="' + index + '"]').addClass('hide');
}

/**
 * Reload parent window When operating in a pop-up window.
 *
 * @access public
 * @return void
 */
function reloadByAjaxForm()
{
    parent.location.reload();
}
