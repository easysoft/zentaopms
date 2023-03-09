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

    $(".menuTree").on('mouseover', ".priv-item", function()
    {
        $(this).addClass('text-primary');
        $(this).find('.icon').removeClass('hidden');
    }).on('mouseout', '.priv-item', function()
    {
        $(this).removeClass('text-primary');
        $(this).find('.icon').addClass('hidden');
    });

    var data = [
      {
          title: '地盘',
          children: [
              {title: '地盘仪表盘'},
              {title: '我的项目'},
              {title: '个性化设置'},
              {title: '维护联系人'},
              {title: '我的日程'},
              {title: '地盘仪表盘'},
              {title: '我的项目'},
              {title: '个性化设置'},
              {title: '维护联系人'},
              {title: '我的日程'},
              {title: '地盘仪表盘'},
              {title: '我的项目'},
              {title: '个性化设置'},
              {title: '维护联系人'},
              {title: '我的日程'},
          ]
      }
    ];
    initRecomendTree(data);
    initDependTree(data);
    $('li.has-list > ul').addClass("menu-active-primary menu-hover-primary");
    $('.sorter-group').sortable();

    $('.btn-switch').on('click', function()
    {
        $.cookie('managePrivEditType', $(this).data('type'), {expires: config.cookieLife, path: config.webRoot});
        location.href = location.href;
    });
    $('.permission-row .checkbox-primary').on('click', 'input', function(e)
    {
        console.log($(e.target).attr('type'));
    }).on('click', 'label', function(e)
    {
        if($(e.target).prop('tagName') == 'LABEL');
        console.log('label');
    });
});

