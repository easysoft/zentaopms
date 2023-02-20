$(function()
{
    $(".input-list").on("click", 'input', function(e)
    {
        handleClickItem(e.target.id);
    });
    $(".input-list").on("blur", 'input', function(e)
    {
        removeActive(e.target.id);
    });

    $('.label-list > .input-label').on('click', function(e)
    {
        handleClickItem($(this).attr('labelid'));
    });

    initMenu();

    $('li.has-list > ul').addClass("menu-active-primary menu-hover-primary")

    handleInputSearch();

    function addActive(id)
    {
        $('[labelid=' + id + ']').addClass('text-primary');
        $('[iconid=' + id + ']').removeClass('hidden');
    }

    function removeActive(id)
    {
        $('[labelid=' + id + ']').removeClass('text-primary');
        $('[iconid=' + id + ']').addClass('hidden');
    }

    function handleClickItem(clickId)
    {
	var clearId = $('.label-list > .text-primary').attr('labelid');
        if(clearId && clearId !== clickId)
	{
	    removeActive(clearId);
	};

        if(clickId && clickId != clearId)
        {
            addActive(clickId);
        };
    }

    function handleInputSearch()
    {
        $('.menu-tree .search-input').on('input', function()
        {
            var val = $(this).val();
            if (!val)
            {
                var updateData = menuTree;
            }
            else
            {
                var updateData = [];
                for (var i = 0; i < menuTree.length; i++)
                {
                    var item = {};
                    $.extend(true, item, menuTree[i])
                    if (item.children)
                    {
                        var children = [];
                        for (var j = 0; j < item.children.length; j++)
                        {
                            if (item.children[j].title.includes(val) || (item.children[j].key && item.children[j].key.includes(val)))
                            {
                                children.push(item.children[j]);
              	            }
                        }
                        item.children = children;
                    }
                    if (item.children.length || item.title.includes(val) ||(item.key && item.key.includes(val)))
                    {
                        updateData.push(item);
                    }
                }
            }
            $('#menuTree').data('zui.tree').reload(updateData);
        })
    }

    function initMenu()
    {
        if (navTypes.includes(type))
        {
            $('#menuTree').tree(
            {
                data: menuTree,
                initialState: 'active',
                itemCreator: function($li, item)
                {
                    $li.append('<a data-module="' + item.module  + '" data-method="' + item.method + '" data-has-children="' + (item.children ? !!item.children.length : false) + '" href=# >' + item.title + '</a>');
                    if (item.active) $li.addClass('active open in');
                }
            });
        }

        $('#menuTree').on('click', 'a', function(e)
        {
            var target = $(e.target);
            if (target.attr('data-has-children') === 'true') return

            self.location.href = createLink('dev', 'langItem', 'type=' + type + '&module=' + target.attr('data-module') + '&method=' + target.attr('data-method'));
        })
    }
})
