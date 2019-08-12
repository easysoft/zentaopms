function loadModules(libID)
{
    link = createLink('doc', 'ajaxGetModules', 'libID=' + libID);
    $('#moduleBox').load(link, function(){$('#moduleBox').find('select').chosen()});
}

function toggleAcl(acl, type)
{
    if(acl == 'custom')
    {
        $('#whiteListBox').removeClass('hidden');
    }
    else
    {
        $('#whiteListBox').addClass('hidden');
    }
    if(type == 'lib')
    {
        var libType = $('input[name="type"]:checked').val();
        var notice  = typeof(noticeAcl[libType][acl]) != 'undefined' ? noticeAcl[libType][acl] : '';
        $('#noticeAcl').html(notice);
    }
    else
    {
        var notice  = typeof(noticeAcl[acl]) != 'undefined' ? noticeAcl[acl] : '';
        $('#noticeAcl').html(notice);
    }
}

function loadDocModule(libID)
{
    link = createLink('doc', 'ajaxGetChild', 'libID=' + libID);
    $.post(link, function(data)
    {
        $('#module').replaceWith(data);
        $('#module_chosen').remove();
        $('#module').chosen();
    });
}

function setBrowseType(type)
{
    $.cookie('browseType', type, {expires:config.cookieLife, path:config.webRoot});
    location.href = location.href;
}

$(document).ready(function()
{
    // hide #module chosen dropdown on #lib dropdown show
    $('#lib').on('chosen:showing_dropdown', function()
    {
        $('#module').trigger('chosen:close');
    });

    $('.libs-group.sort').sortable(
    {
        trigger:  '.lib',
        selector: '.lib',
        finish:   function()
        {
            var orders = {};
            var orderNext = 1;

            $('.libs-group .lib').not('.files').not('.addbtn').each(function()
            {
                orders[$(this).data('id')] = orderNext ++;
            })

            $.post(createLink('doc', 'sort'), orders, function(data)
            {
                if(data.result == 'success')
                {
                    return location.reload();
                }
                else
                {
                    alert(data.message);
                    return location.reload();
                }
            }, 'json');
        }
    });

    'use strict';

    var NAME = 'zui.splitRow'; // model name

    // File input list
    // The SplitRow model class
    var SplitRow = function(element, options)
    {
        var that = this;
        that.name = NAME;
        var $element = that.$ = $(element);

        options = that.options = $.extend({}, SplitRow.DEFAULTS, this.$.data(), options);
        var id = options.id || $element.attr('id') || $.zui.uuid();
        var $cols = $element.children('.side-col,.main-col');
        var $firstCol = $cols.first();
        var $secondCol = $cols.eq(1);
        var $spliter = $firstCol.next('.col-spliter');
        if (!$spliter.length)
        {
            $spliter = $(options.spliter);
            if (!$spliter.parent().length)
            {
                $spliter.insertAfter($firstCol);
            }
        }
        var spliterWidth = $spliter.width();
        var minFirstColWidth = $firstCol.data('minWidth');
        var minSecondColWidth = $secondCol.data('minWidth');
        var setFirstColWidth = function(width)
        {
            var rowWidth = $element.width();
            var maxFirstWidth = rowWidth - minSecondColWidth - spliterWidth;
            width = Math.max(minFirstColWidth, Math.min(width, maxFirstWidth));
            $firstCol.width(width);
            $.zui.store.set('splitRowFirstSize:' + id, width);
        };

        var defaultWidth = $.zui.store.get('splitRowFirstSize:' + id);
        if(typeof(defaultWidth) == 'undefined')
        {
            defaultWidth = 0;
            $firstCol.find('.tabs ul.nav-tabs li').each(function(){defaultWidth += $(this).outerWidth()});
            defaultWidth += ($firstCol.find('.tabs ul.nav-tabs li').length - 1) * 10;
            defaultWidth += 30;
        }
        setFirstColWidth(defaultWidth);

        var documentEventName = '.' + id;

        var mouseDownX, isMouseDown, startFirstWidth;
        $spliter.on('mousedown', function(e)
        {
            startFirstWidth = $firstCol.width();
            mouseDownX = e.pageX;
            isMouseDown = true;
            $element.addClass('row-spliting');
            e.preventDefault();
            $(document).on('mousemove' + documentEventName, function(e)
            {
                if (isMouseDown)
                {
                    var deltaX = e.pageX - mouseDownX;
                    setFirstColWidth(startFirstWidth + deltaX);
                    e.preventDefault();
                } else {
                    $(document).off(documentEventName);
                    $element.removeClass('row-spliting');
                }
            }).on('mouseup' + documentEventName + ' mouseleave' + documentEventName, function(e)
            {
                isMouseDown = false;
                $(document).off(documentEventName);
                $element.removeClass('row-spliting');
            });
        });

        var fixColClass = function($col)
        {
            if (options.smallSize) $col.toggleClass('col-sm-size', $col.width() < options.smallSize);
            if (options.middleSize) $col.toggleClass('col-md-size', $col.width() < options.middleSize);
        };

        var resizeCols = function() {
            var cellHeight = $(window).height() - $('#footer').outerHeight() - $('#header').outerHeight() - 42;
            $cols.children('.panel').height(cellHeight).css('maxHeight', cellHeight).find('.panel-body').css('position', 'absolute');
            var sideHeight = cellHeight - $cols.find('.nav-tabs').height() - $cols.find('.side-footer').height() - 35;
            $cols.find('.tab-content').height(sideHeight).css('maxHeight', sideHeight).css('overflow-y', 'auto');
        };

        $(window).on('resize', resizeCols);
        $firstCol.on('resize', function(e) {fixColClass($firstCol);});
        $secondCol.on('resize', function(e) {fixColClass($secondCol);});
        fixColClass($firstCol);
        fixColClass($secondCol);
        resizeCols();
    };

    // default options
    SplitRow.DEFAULTS =
    {
        spliter: '<div class="col-spliter"></div>',
        smallSize: 700,
        middleSize: 850
    };

    // Extense jquery element
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

    // Auto call splitRow after document load complete
    $(function()
    {
        $('.split-row').splitRow();
    });

    var $pageSetting = $('#pageSetting');
    if($pageSetting.length)
    {
        $pageSetting.on('click', '.close-dropdown', function()
        {
            $pageSetting.parent().removeClass('open');
        }).on('click', function(e){e.stopPropagation()});
    }

    $('.ajaxCollect').mousedown(function (event) {
        var obj = $(this);
        var url = obj.data('url');
        $.get(url, function(response)
        {
          if(response.status == 'yes')
          {
            obj.children('i').removeClass().addClass('icon icon-star text-yellow');
            obj.parent().prev().children('.file-name').children('i').remove('.icon');
            obj.parent().prev().children('.file-name').prepend('<i class="icon icon-star text-yellow"></i> ');
          }
          else
          {
            obj.children('i').removeClass().addClass('icon icon-star-empty');
            obj.parent().prev().children('.file-name').children('i').remove(".icon");
          }
        }, 'json');
        return false;
    });
});
