$(function()
{
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
                   var item = $(this).data();
                   '<?php echo $type;?>' == 'book' ? orders['sort[' + item.id + ']'] = item.order || item.order : orders['orders[' + item.id + ']'] = item.order || item.order;
               });

               link = createLink('tree', 'updateOrder');
           }

           $.post(link, orders, function(data){}).error(function()
           {
               bootbox.alert(lang.timeout);
           });
       }
   });

    $('#fileTree').tree(
    {
        initialState: 'active',
        data: treeData,
        itemCreator: function($li, item)
        {
            var libClass = ['lib', 'annex', 'api', 'execution'].indexOf(item.type) !== -1 ? 'lib' : '';
            var hasChild = item.children ? !!item.children.length : false;
            var $item = '<a href="###" data-has-children="' + hasChild + '" title="' + item.name + '" data-id="' + item.id + '" class="' + libClass + '" data-type="' + item.type + '">';
            $item += '<div class="text h-full w-full flex-between">' + item.name;
            $item += '<i class="icon icon-drop icon-ellipsis-v float-r hidden" data-isCatalogue="' + (item.type ? false : true) + '"></i>';
            $item += '</div>';
            $item += '</a>';
            $li.append($item);
            $li.addClass(libClass);

            if (item.active) $li.addClass('active open in');
        }
    });
    $('li.has-list > ul').addClass("menu-active-primary menu-hover-primary");

    $('#fileTree').on('mousemove', 'a', function()
    {
        if($(this).data('type') == 'annex') return;
        var libClass = '.libDorpdown';
        if(!$(this).hasClass('lib')) libClass = '.moduleDorpdown';
        if($(libClass).find('li').length == 0) return false;

        $(this).find('.icon').removeClass('hidden');
    }).on('mouseout', 'a', function()
    {
        $(this).find('.icon').addClass('hidden');
    }).on('click', 'a', function(e)
    {
        var isLib    = $(this).hasClass('lib');
        var moduleID = $(this).data('id');
        var libID    = 0;
        var params   = '';

        if(isLib)
        {
            if($(this).data('type') == 'annex') return false;

            libID     = moduleID;
            moduleID  = 0;
        }
        else
        {
            libID   = $(this).closest('.lib').data('id');
        }
        linkParams = linkParams.replace('%s', '&libID=' + libID + '&moduleID=' + moduleID);
        location.href = createLink('doc', 'tableContents', linkParams);
    });

    function renderDropdown(option)
    {
        var libClass = '.libDorpdown';
        if(option.type != 'dropDownLibrary') libClass = '.moduleDorpdown';
        if($(libClass).find('li').length == 0) return '';

        var dropdown = '<ul class="dropdown-menu dropdown-in-tree" id="' + option.type + '" style="display: unset; left:' + option.left + 'px; top:' + option.top + 'px;">';
        dropdown += $(libClass).html().replace(/%libID%/g, option.libID).replace(/%moduleID%/g, option.moduleID).replace(/%hasChildren%/g, option.hasChildren);
        dropdown += '</ul>';
        return dropdown;
    };

    var moduleData = {
        "name": "",
        "createType": "",
        "libID": '',
        "parentID": '',
        "objectID": '',
        "moduleType": '',
        "order": ""
    };
    $('#fileTree').on('click', '.icon-drop', function(e)
    {
        $('.dropdown-in-tree').css('display', 'none');
        var isCatalogue = $(this).attr('data-isCatalogue') === 'false' ? false : true;
        var dropDownID  = isCatalogue ? 'dropDownCatalogue' : 'dropDownLibrary';
        var libID       = 0;
        var moduleID    = 0;
        var parentID    = 0;
        var $module     = $(this).closest('a');
        var hasChildren = $module.data('has-children');
        var moduleType  = '';
        if($module.hasClass('lib'))
        {
            libID      = $module.data('id');
            moduleType = $module.data('type');
            parentID   = libID;
        }
        else
        {
            moduleID   = $module.data('id');
            libID      = $module.closest('.lib').data('id');
            moduleType = $module.closest('.lib').data('type');
            parentID   = $module.closest('ul').closest('.lib').data('id');
        }
        moduleData = {
            "createType": "",
            "libID": libID,
            "parentID": parentID,
            "objectID": moduleID,
            "moduleType": moduleType == 'lib' ? 'doc' : moduleType,
        };
        console.log(moduleData);
        var option = {
            left        : e.pageX,
            top         : e.pageY,
            type        : dropDownID,
            libID       : libID,
            moduleID    : moduleID,
            hasChildren : hasChildren
        };
        var dropDown = renderDropdown(option);
        $(".m-doc-tablecontents").append(dropDown);
        e.stopPropagation();
    });

    $('body').on('click', function(e)
    {
        if(!$.contains(e.target, $('.dropdown-in-tree'))) $('.dropdown-in-tree').remove();
    }).on('click', '.dropdown-in-tree li', function(e)
    {
        var item = $(this).data();
        if($(this).hasClass('edit-module'))
        {
            new $.zui.ModalTrigger({
                type: 'ajax',
                url: $(this).find('a').data('href'),
                keyboard: true
            }).show();
        }
        if(item.type !== 'add') return;
        var $item = $(this);
        switch(item.method)
        {
            case 'addCataLib' :
                moduleData.createType = 'child';
                moduleData.parentID = 0;
                var $input = '';
                if(item.hasChildren)
                {
                    var $rootDom = $('[data-id=' + item.libid + ']a').parent().find('ul');
                    $input += $('[data-id=liTreeModal]').html();
                }
                else
                {
                    var $rootDom = $('[data-id=' + item.libid + ']a').parent();
                    $rootDom.addClass('open in has-list');
                    $input += $('[data-id=ulTreeModal]').html();
                }
                $rootDom.prepend($input);
                $rootDom.find('input').focus();
                break;
            case 'addCata' :
                break;
            case 'addCataChild' :
                break;
        }
    }).on('blur', '.file-tree input.input-tree', function()
    {
        var $this = $(this);
        var value = $this.val();
        if(!value)
        {
            $this.closest('[data-id=insert]').remove();
            return;
        }

        moduleData.name = value;
        $.post(createLink('tree', 'ajaxCreateModule'), moduleData, function(result)
        {
            result = JSON.parse(result);
            if(result.result == 'fail')
            {
                bootbox.alert(
                result.message[0],
                function()
                {
                    setTimeout(function()
                    {
                        $('.file-tree .input-tree').focus()
                    }, 10)
                });
                return false;
            }
            var module = result.module;
            var resultDom = $('[data-id=aTreeModal]').html().replace(/%name%/g, module.name).replace(/%id%/g, module.id)
            $this.parent().append(resultDom);
            $this.remove();
        });
    });
});
