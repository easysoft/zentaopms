$(function()
{
    if(!moduleTree || moduleTree.length == 0)
    {
        var contentHeight = $('.no-content').parent().innerHeight();
        var titleHeight   = $('.cell div:nth-child(1)').innerHeight();

        var height = $(document).height() - $('#header').height() - parent.$('#appsBar').height() - (2 * parseInt($('#main').css('padding-top')));
        $('.main-content .cell').height(height);
        $('.no-content').parent().css('padding-top', (height - contentHeight)/2 - titleHeight + 'px');
    }

    $('#main .main-content li.has-list').addClass('open in');

    $('.menu-actions > a').click(function()
    {
        $(this).parent().hasClass('open') ? $(this).css('background', 'none') : $(this).css('background', '#f1f1f1');
    })

    $('.menu-actions > a').blur(function() {$(this).css('background', 'none');})

   /* Make modules tree sortable. */
   $('#modules').sortable(
   {
       trigger: '.module-name>a.sort-module, .tree-actions>.sortModule>.icon-move, a.sortDoc, .tree-actions>.sortDoc>.icon-move',
       dropToClass: 'sort-to',
       stopPropagation: true,
       nested: true,
       selector: 'li',
       dragCssClass: 'drop-here',
       canMoveHere: function($ele, $target)
       {
           var maxTop = $('.side-col > .cell > ul').height() - $ele.height();
           if(parseFloat($('.drag-shadow').css('top')) < 0) $('.drag-shadow').css('top', '0');
           if(parseFloat($('.drag-shadow').css('left')) != 0) $('.drag-shadow').css('left', '0');
           if(parseFloat($('.drag-shadow').css('top')) > maxTop) $('.drag-shadow').css('top', maxTop + 'px');
           return true;
       },
       targetSelector: function($ele, $root)
       {
           var $ul = $ele.closest('ul');
           setTimeout(function()
           {
               if($('#modules').hasClass('sortable-sorting')) $ul.addClass('is-sorting');
           }, 100);

           if($ele.hasClass('sortDoc'))
           {
               return $ul.children('li.sortDoc');
           }
           else
           {
               return $ul.children('li.catalog');
           }
       },
       always: function()
       {
           $('#modules,#modules .is-sorting').removeClass('is-sorting');
       },
       finish: function(e)
       {
           if(!e.changed) return;

           var orders       = {};
           var link         = '';
           var elementClass = e.list.context.className;
           if(elementClass.indexOf('sortDoc') >= 0)
           {
               $('#modules').find('li.sortDoc').each(function()
               {
                   var $li = $(this);

                   var item = $li.data();
                   orders['orders[' + item.id + ']'] = $li.attr('data-order') || item.order;
               });

               link = createLink('doc', 'updateOrder');
           }
           else
           {
               $('#modules').find('li.can-sort').each(function()
               {
                   var $li = $(this);

                   var item = $li.data();
                   '<?php echo $type;?>' == 'book' ? orders['sort[' + item.id + ']'] = $li.attr('data-order') || item.order : orders['orders[' + item.id + ']'] = $li.attr('data-order') || item.order;
               });

               link = createLink('tree', 'updateOrder');
           }

           $.post(link, orders, function(data){}).error(function()
           {
               bootbox.alert(lang.timeout);
           });
       }
   });
})
