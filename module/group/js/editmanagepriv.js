/**
  * Init comp recomend-tree.
  *
  * @param  array  data
  * @access public
  * @return void
  */
function initRecomendTree(data)
{
    $(".menuTree.depend").tree(
    {
        data: data,
        initialState: 'active',
        itemCreator: function($li, item)
        {
            $li.append('<a class="priv-item" data-has-children="' + (item.children ? !!item.children.length : false) + '" href=# title="' + item.title + '">' + item.title + (item.children ? '' : '<i class="icon icon-close hidden"></i>') +  '</a>');
            if(item.active) $li.addClass('active open in');
        }
    });
};

/**
  * Init comp depend-tree.
  *
  * @param  array  data
  * @access public
  * @return void
  */
function initDependTree(data)
{
    $(".menuTree.recommend").tree(
    {
        data: data,
        initialState: 'active',
        itemCreator: function($li, item)
        {
            $li.append('<a class="priv-item" data-has-children="' + (item.children ? !!item.children.length : false) + '" href=# title="' + item.title + '">' + item.title + (item.children ? '' : '<i class="icon icon-close hidden"></i>') + '</a>');
            if(item.active) $li.addClass('active open in');
        }
    });
};

/**
  * update comp tree after click checkbox-label.
  *
  * @param  obj objTree
  * @access public
  * @return void
  */
function updatePrivTree(objTree)
{
    $(".menuTree.depend").data('zui.tree').reload(objTree.dependData || [] );
    if(objTree.dependData && objTree.dependData.length) 
    {
        $(".menuTree.depend + .empty-tip").addClass('hidden');
    }
    else
    {
        $(".menuTree.depend + .empty-tip").removeClass('hidden');
    }
    $(".menuTree.recommend").data('zui.tree').reload(objTree.recommendData || []);
    if(objTree.recommendData && objTree.recommendData.length)
    {
        $(".menuTree.recommend + .empty-tip").addClass('hidden');
    }
    else
    {
        $(".menuTree.recommend + .empty-tip").removeClass('hidden');
    }
};

$(function()
{
    $(".menuTree").on('mouseover', ".priv-item", function()
    {
        $(this).addClass('text-primary');
        $(this).find('.icon').removeClass('hidden');
    }).on('mouseout', '.priv-item', function()
    {
        $(this).removeClass('text-primary');
        $(this).find('.icon').addClass('hidden');
    });

    initRecomendTree([]);
    initDependTree([]);

    $('li.has-list > ul').addClass("menu-active-primary menu-hover-primary");
    $('.sorter-group').sortable(
    {
        'selector': '.group-item'
    });

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
                var objTree = {};
                if(data)
                {
                    var relatedPriv  = JSON.parse(data);
                    objTree          = {
                        dependData: relatedPriv.depend,
                        recommendData: relatedPriv.recommend
                    };
                }
                updatePrivTree(objTree);
            })
        }
    });
});
