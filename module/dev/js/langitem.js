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

    function initMenu()
    {
      if (navTypes.includes(type))
      {
        $('#menuTree').tree(
        {
            data: menuTree,
            itemCreator: function($li, item) 
            {
                $li.append($('<a data-module="' + item.module  + '"data-method="' + item.method + '"data-has-children="' + !!item.children + '" />', {href: item.url}).text(item.title));
	    }
        });
      }

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
                  if (item.children.length || item.title.includes(val) ||(item.key && item.key.includes(val)) )
                  {
                      updateData.push(item);
		  }
              }
          }
          $('#menuTree').data('zui.tree').reload(updateData);
      })
      
      $('#menuTree').on('click', 'a', function(e)
      {
          var clickTarget = e.target;
          debugger;
      })
    }
})
