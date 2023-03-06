$(function()
{
    /**
     * Add class active while click item in form.
     *
     * @param  string $id
     * @access public
     * @return void
     */
    function addActive(id)
    {
        $('[data-id=' + id + ']').addClass('active');
    }

    /**
     * Remove class active while blur or click item in form.
     *
     * @param  string $id
     * @access public
     * @return void
     */
    function removeActive(id)
    {
        $('[data-id=' + id + ']').removeClass('active');
    }

    /**
     * Handle function click in label.
     *
     * @param  string $id
     * @access public
     * @return void
     */
    function handleClickItem(clickId)
    {
	    var clearId = $('.form-item.active').attr('data-id');
        if(clearId !== clickId)
        {
            if(clearId) removeActive(clearId);
            if(clickId) addActive(clickId);
        };
    }

    /**
     * Handle function input on search comp and update menu tree.
     *
     * @access public
     * @return void
     */
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
                    $.extend(true, item, menuTree[i]);
                    item.children = filterChildren(item, val);
                    if ((item.children && item.children.length) || item.title.indexOf(val) != -1 || (item.key && item.key.indexOf(val) != -1))
                    {
                        updateData.push(item);
                    }
                }
            }
            $('#menuTree').data('zui.tree').reload(updateData);
        })
    }

    /**
     * Handle function filter in array children in every item in menu tree.
     *
     * @access public
     * @return array
     */
    function filterChildren(item, val)
    {
        var children = null;
        if (item && item.children && item.children.length)
        {
            children = [];
            for (var i = 0; i < item.children.length; i++)
            {
                item.children[i].children = filterChildren(item.children[i], val);
                if (item.children[i].title.indexOf(val) != -1 || (item.children[i].key && item.children[i].key.indexOf(val) != -1) || item.children[i].children)
                {
                    children.push(item.children[i]);
                }
            }
        }
        return children && children.length ? children : null;
    }

    /**
     * Init menu tree by zui.
     *
     * @access public
     * @return void
     */
    function initMenu()
    {
        if (navTypes.indexOf(type) != -1)
        {
            $('#menuTree').tree(
            {
                data: menuTree,
                initialState: 'active',
                itemCreator: function($li, item)
                {
                    $li.append('<a data-module="' + item.module  + '" data-method="' + item.method + '" data-has-children="' + (item.children ? !!item.children.length : false) + '" href=# title="' + item.title + '">' + item.title + '</a>');
                    if (item.active) $li.addClass('active open in');
                }
            });
        }
        $('#menuTree').on('click', 'a', function(e)
        {
            var target = $(e.target);
            if (target.attr('data-has-children') === 'true') return;
            self.location.href = createLink('dev', 'langItem', 'type=' + type + '&module=' + target.attr('data-module') + '&method=' + target.attr('data-method') + '&language=' + language);
        })
    }

    $(".form-item-content").on("click", '.form-item', function(e)
    {
        handleClickItem($(this).data('id'));
    });

    $('.form-item-content > .form-item > .input-group').on('click', function()
    {
        handleClickItem($(this).data('id'));
    });

    $(".form-item-content").on('mouseover', '.form-item', function(e)
    {
        $(this).addClass('hover');
    }).on('mouseout', '.form-item', function()
    {
        $(this).removeClass('hover');
    })

    initMenu();
    handleInputSearch();

    $('li.has-list > ul').addClass("menu-active-primary menu-hover-primary");
})
