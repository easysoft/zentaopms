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
          var myTreeData = [{
	      title: '水果',
	      children: [
	          {title: '橘子', key: 'juzi jz'},
                  {title: '瓜', key: '123'}
              ]
          }, {
              title: '坚果',
              children: [
                  {title: '向日葵'},
                  {title: '瓜子'}
              ]
          }, {
              title: '蔬菜'
	  }];
        $('#menuTree').tree(
        {
            data: myTreeData
        });
      }
      $('.menu-tree .search-input').on('input', function()
      {
          var val = $(this).val();
	  if (!val)
          {
              var updateData = myTreeData;
          }
          else
          {
              var updateData = [];
              for (var i = 0; i < myTreeData.length; i++)
	      {
                  var item = {};
                  $.extend(true, item, myTreeData[i])
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
                  updateData.push(item);
              }
          }
          $('#menuTree').data('zui.tree').reload(updateData);
      })
    }
})
