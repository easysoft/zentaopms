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

    $('#fileTree').tree(
    {
        initialState: 'active',
        data: treeData,
        itemCreator: function($li, item)
        {
            var libClass = ['lib', 'annex', 'api'].indexOf(item.type) !== -1 ? 'lib' : '';
            var hasChild = item.children ? !!item.children.length : false;
            var $item = '<a href="###" data-has-children="' + hasChild + '" title="' + item.name + '" data-id="' + item.id + '" class="' + libClass + '" data-type="' + item.type + '">';
            $item += '<div class="text h-full w-full flex-center">' + item.name;
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
        $(this).find('.icon').removeClass('hidden');
    }).on('mouseout', 'a', function()
    {
        $(this).find('.icon').addClass('hidden');
    }).on('click', 'a', function(e)
    {
        var isLib    = $(this).hasClass('lib');
        var moduleID = $(this).data('id');

        if(isLib)
        {
            if($(this).data('type') == 'annex')
            {
                linkParams += '&libID=&moduleID=0&browseType=annex';
            }
            else
            {
                linkParams += '&libID=' + moduleID;
            }
        }
        else
        {
            var libID   = $(this).closest('.lib').data('id');
            linkParams += '&libID=' + libID + '&moduleID=' + moduleID;
        }
        location.href = createLink('doc', 'tableContents', linkParams);
    });

    function renderDropdown(option)
    {
        var $liList = (option.id == 'dropDownLibrary') ?
                       ('<li data-method="addCatalogue"><a><i class="icon icon-controls"></i>添加目录</a></li>' +
                       '<li data-method="editLib"><a><i class="icon icon-edit"></i>编辑库</a></li>' +
                       '<li data-method="deleteLib"><a><i class="icon icon-trash"></i>删除库</a></li>')
                       :
                       ('<li data-method="addCata"><a><i class="icon icon-controls"></i>添加同级目录</a></li>' +
                       '<li data-method="addCataChild"><a><i class="icon icon-edit"></i>添加子目录</a></li>' +
                       '<li data-method="editCata"><a><i class="icon icon-edit"></i>编辑目录</a></li>' +
                       '<li data-method="deleteCata"><a><i class="icon icon-trash"></i>删除目录</a></li>')

        var dropdown = '<ul class="dropdown-menu dropdown-in-tree" ' +
                       'id="' + option.id + '"' +
                       'style="display: unset; ' +
                       'left:' + option.left + 'px; ' +
                       'top:' + option.top + 'px;' +
                        '">' + $liList +
                       '</ul>';
        return dropdown;
    };

    function refreshDropdown(option)
    {
        $('#' + option.id).css({
        'display': 'unset',
        'left': option.left,
        'top': option.top
        });
    };

    $('#fileTree').on('click', '.icon-drop', function(e)
    {
        var isCatalogue = $(this).attr('data-isCatalogue') === 'false' ? false : true;
        var dropDownID  = isCatalogue ? 'dropDownCatalogue' : 'dropDownLibrary';
        var option = {
            left: e.pageX,
            top: e.pageY,
            id: dropDownID
        };
        if (!$('#' + dropDownID).length)
        {
            var dropDown = renderDropdown(option);
            $("body").append(dropDown);
        }
        else
        {
            refreshDropdown(option)
        }
        e.stopPropagation();
    });

    $('body').on('click', function(e)
    {
        if(!$.contains(e.target, $('.dropdown-in-tree')))
        {
            $('.dropdown-in-tree').css('display', 'none');
        }
    });

    function initSplitRow()
    {
        /* Init split row. */
        var NAME = 'zui.splitRow';
        /* The SplitRow model class. */
        var SplitRow = function(element, options)
        {
            var that = this;
            that.name = NAME;
            var $element = that.$ = $(element);

            options        = that.options = $.extend({}, SplitRow.DEFAULTS, this.$.data(), options);
            var id         = options.id || $element.attr('id') || $.zui.uuid();
            var $cols      = $element.children('.col');
            var $firstCol  = $cols.first();
            var $firstBar  = $('#leftBar > .btn-group');
            var $secondCol = $cols.eq(1);
            var $spliter   = $firstCol.next('.col-spliter');
            if (!$spliter.length)
            {
                $spliter = $(options.spliter);
                if (!$spliter.parent().length)
                {
                    $spliter.insertAfter($firstCol);
                }
            }
            var spliterWidth      = $spliter.width();
            var minFirstColWidth  = $firstCol.data('min-width');
            var minSecondColWidth = $secondCol.data('min-width');
            var rowWidth          = $element.width();
            var setFirstColWidth  = function(width)
            {
                var maxFirstWidth = 400;
                width = Math.max(minFirstColWidth, Math.min(width, maxFirstWidth));
                $firstCol.width(width);
                $firstBar.width(width);
                $secondCol.width($('#mainContent').width() - width);
                $.zui.store.set('splitRowFirstSize:' + id, width);
            };

            var defaultWidth = $.zui.store.get('splitRowFirstSize:' + id);
            if(typeof(defaultWidth) == 'undefined') defaultWidth = $element.width() * 0.33;
            setFirstColWidth(defaultWidth);

            var documentEventName = '.' + id;

            var mouseDownX, isMouseDown, startFirstWidth, rafID;
            $spliter.on('mousedown', function(e)
            {
                startFirstWidth = $firstCol.width();
                mouseDownX = e.pageX;
                isMouseDown = true;
                $element.addClass('row-spliting');
                e.preventDefault();
                var handleMouseMove = function(e)
                {
                    if(isMouseDown)
                    {
                        var deltaX = e.pageX - mouseDownX;
                        setFirstColWidth(startFirstWidth + deltaX);
                        e.preventDefault();
                    }
                    else
                    {
                        $(document).off(documentEventName);
                        $element.removeClass('row-spliting');
                    }
                };
                $(document).on('mousemove' + documentEventName, function(e)
                {
                    if(rafID) cancelAnimationFrame(rafID);
                    rafID = requestAnimationFrame(function()
                    {
                        handleMouseMove(e);
                        rafID = 0;
                    });
                }).on('mouseup' + documentEventName + ' mouseleave' + documentEventName, function(e)
                {
                    if(rafID) cancelAnimationFrame(rafID);
                    isMouseDown = false;
                    $(document).off(documentEventName);
                    $element.removeClass('row-spliting');
                });
            });
        };

        /* default options. */
        SplitRow.DEFAULTS =
        {
            spliter: '<div class="col-spliter"></div>',
        };

        /* Extense jquery element. */
        $.fn.splitRow = function(option)
        {
            return this.each(function()
            {
                var $this = $(this);
                var data = $this.data(NAME);
                var options = typeof option == 'object' && option;
                if(!data) $this.data(NAME, (data = new SplitRow(this, options)));
            });
        };

        SplitRow.NAME = NAME;

        $.fn.splitRow.Constructor = SplitRow;

        /* Auto call splitRow after document load complete. */
        $(function()
        {
            $('.split-row').splitRow();
        });
    }

    initSplitRow();
})
