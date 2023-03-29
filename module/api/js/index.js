$(document).ready(function()
{
    /* Update doc content silently on switch doc version, story #40503 */
    $(document).on('click', '.api-version-menu a, #mainActions .container a', function(event)
    {
        var $tmpDiv = $('<div>');
        $tmpDiv.load($(this).data('url') + ' #mainContent', function()
        {
            $('#content').html($tmpDiv.find('#content').html());
            $('#sidebarContent').html($tmpDiv.find('#sidebarContent').html());
            $('#actionbox .histories-list').html($tmpDiv.find('#actionbox .histories-list').html());
            if($.cookie('isFullScreen') == 1) fullScreen();
            $('#content [data-ride="tree"]').tree();
            $('#outline li.has-list').addClass('open in');
            $('#outline li.has-list>i+ul').prev('i').remove();
        });
    });

    $('#fileTree').tree(
    {
        initialState: 'active',
        data: treeData,
        itemCreator: function($li, item)
        {
            var libClass = item.type == 'api' ? 'lib' : '';
            var hasChild = item.children ? !!item.children.length : false;
            var $item = '<a href="###" style="position: relative" data-has-children="' + hasChild + '" title="' + item.name + '" data-id="' + item.id + '" class="' + libClass + '" data-type="' + item.type + '">';
            $item += '<div class="text h-full w-full flex-start overflow-hidden">';
            if(libClass == 'lib') $item += '<div class="img-lib" style="background-image:url(static/svg/interfacelib.svg)"></div>';
            $item += '<span style="padding-left: 5px;">';
            $item += item.name
            $item += '</span>';
            $item += '<i class="icon icon-drop icon-ellipsis-v hidden tree-icon" data-isCatalogue="' + (item.type ? false : true) + '"></i>';
            $item += '</div>';
            $item += '</a>';

            $li.append($item);
            $li.addClass(libClass);
            if(item.active) $li.addClass('active open in');
        }
    });

    $('li.has-list > ul, #fileTree').addClass("menu-active-primary menu-hover-primary");

    $('#fileTree').on('mousemove', 'a', function()
    {
        if($(this).data('type') == 'annex') return;

        var libClass = '.libDorpdown';
        if(!$(this).hasClass('lib')) libClass = '.moduleDorpdown';
        if($(libClass).find('li').length == 0) return false;

        $(this).find('.icon').removeClass('hidden');
        $(this).addClass('show-icon');
    }).on('mouseout', 'a', function()
    {
        $(this).find('.icon').addClass('hidden');
        $(this).removeClass('show-icon');
    }).on('click', 'a', function(e)
    {
        var isLib    = $(this).hasClass('lib');
        var moduleID = $(this).data('id');
        var libID    = 0;
        var params   = '';

        if(isLib)
        {
            libID    = moduleID;
            moduleID = 0;
        }
        else
        {
            libID = $(this).closest('.lib').data('id');
        }
        var linkParams = 'libID=' + libID + '&moduleID=' + moduleID;
        location.href = createLink('api', 'index', linkParams);
    });
});

/**
 * Ajax delete api doc.
 *
 * @param  string $link
 * @param  int    $replaceID
 * @param  stirng $notice
 * @access public
 * @return void
 */
function ajaxDeleteApi(link, replaceID)
{
    if(confirm(confirmDelete))
    {
        $.get(link, function(data)
        {
            location.href = JSON.parse(data).locate;
        });
    }
}

/**
 * Display the document in full screen.
 *
 * @access public
 * @return void
 */
function fullScreen()
{
    var element       = document.getElementById('content');
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen;
    if(requestMethod)
    {
        var afterEnterFullscreen = function()
        {
            $('#mainActions').removeClass('hidden');
            $('#content').addClass('scrollbar-hover');
            $('#content .actions').addClass('hidden');
            $.cookie('isFullScreen', 1);
        };

        var whenFailEnterFullscreen = function(error)
        {
            $.cookie('isFullScreen', 0);
        };

        try
        {
            var result = requestMethod.call(element);
            if(result && (typeof result.then === 'function' || result instanceof window.Promise))
            {
                result.then(afterEnterFullscreen).catch(whenFailEnterFullscreen);
            }
            else
            {
                afterEnterFullscreen();
            }
        }
        catch (error)
        {
            whenFailEnterFullscreen(error);
        }
    }
}

/**
 * Exit full screen.
 *
 * @access public
 * @return void
 */
function exitFullScreen()
{
    $('#mainActions').addClass('hidden');
    $('#content').removeClass('scrollbar-hover');
    $('#content .actions').removeClass('hidden');
    $.cookie('isFullScreen', 0);
}
document.addEventListener('fullscreenchange', function (e)
{
    if(!document.fullscreenElement) exitFullScreen();
});

document.addEventListener('webkitfullscreenchange', function (e)
{
    if(!document.webkitFullscreenElement) exitFullScreen();
});

document.addEventListener('mozfullscreenchange', function (e)
{
    if(!document.mozFullScreenElement) exitFullScreen();
});

document.addEventListener('msfullscreenChange', function (e)
{
    if(!document.msfullscreenElement) exitFullScreen();
});
