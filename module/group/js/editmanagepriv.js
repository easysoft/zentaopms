$(function()
{
    function initRecomendTree(data)
    {
        $(".menuTree.depend").tree(
        {
            data: data,
            initialState: 'active',
            itemCreator: function($li, item)
            {
                $li.append('<a class="priv-item" data-has-children="' + (item.children ? !!item.children.length : false) + '" href=# title="' + item.title + '">' + item.title + (item.children ? '' : '<i class="icon icon-close hidden"></i>') +  '</a>');
                if (item.active) $li.addClass('active open in');
            }
        });
    };

    function initDependTree(data)
    {
        $(".menuTree.recommend").tree(
        {
            data: data,
            initialState: 'active',
            itemCreator:  function($li, item)
            {
                $li.append('<a class="priv-item" data-has-children="' + (item.children ? !!item.children.length : false) + '" href=# title="' + item.title + '">' + item.title + (item.children ? '' : '<i class="icon icon-close hidden"></i>') + '</a>');
                if (item.active) $li.addClass('active open in');
            }
        });
    };

    function updatePrivTree(objTree)
    {
        $(".menuTree.depend").data('zui.tree').reload(objTree.dependData);
        $(".menuTree.recommend").data('zui.tree').reload(objTree.recommendData);
    };

    $(".menuTree").on('mouseover', ".priv-item", function()
    {
        $(this).addClass('text-primary');
        $(this).find('.icon').removeClass('hidden');
    }).on('mouseout', '.priv-item', function()
    {
        $(this).removeClass('text-primary');
        $(this).find('.icon').addClass('hidden');
    });

    var data = [];
    initRecomendTree(data);
    initDependTree(data);

    $('li.has-list > ul').addClass("menu-active-primary menu-hover-primary");
    $('.sorter-group').sortable();

    $('.btn-switch').on('click', function()
    {
        $.cookie('managePrivEditType', $(this).data('type'), {expires: config.cookieLife, path: config.webRoot});
        location.href = location.href;
    });

    $('.permission-row .checkbox-primary').on('click', 'label', function(e)
    {
        e.stopPropagation();
        if($(e.target).prop('tagName') == 'LABEL')
        {
            var selectedID = $(this).siblings('input:checkbox').data('id');
            $.get(createLink('group', 'ajaxGetPrivRelations', "privID=" + selectedID), function(data)
            {
                if(!data) return;
                var relatedPriv   = JSON.parse(data);
                updatePrivTree(
                {
                    dependData: relatedPriv.depend,
                    recommendData: relatedPriv.recommend
                });
            })
        }
    });
});

