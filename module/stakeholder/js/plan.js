$(function()
{
    $('#planList').datatable(
    {
        customizable  : false,
        sortable      : false,
        tableClass    : 'tablesorter',
        storage       : false,
        fixCellHeight : false,
        selectable    : false,
        fixedHeader   : true,
        ready:function()
        {
            setTimeout(function()
            {
                var rowspan    = 1;
                var compareVal = '';
                var mergeIndex = 0;
                $('.datatable-rows .datatable-rows-span:first table tr').each(function()
                {
                    var $firstTd  = $(this).find('td:first');
                    var dataIndex = $(this).data('index');
                    if(dataIndex == 0)
                    {
                        compareVal = $firstTd.html();
                        mergeIndex = dataIndex;
                    }

                    if(mergeIndex != dataIndex)
                    {
                        if(compareVal == $firstTd.html())
                        {
                            rowspan += 1;
                            $(this).parent().find('tr').eq(mergeIndex).find('td:first').attr('rowspan', rowspan);
                            $firstTd.remove();
                        }
                        else
                        {
                            rowspan    = 1;
                            compareVal = $firstTd.html();
                            mergeIndex = dataIndex;
                        }
                    }
                })
            }, 100);
        }
    })

    setTimeout(function()
    {
        fixScroll();
        $('.datatable .iframe').initIframeModal() 
    }, 500);

    $('.datatable-head .datatable-row-left').height($('.datatable-head .flexarea table thead')[0].getBoundingClientRect().height * 2);
    $('.datatable-head .datatable-row-right').height($('.datatable-head .flexarea table thead')[0].getBoundingClientRect().height * 2);
    $('.datatable-head table').addClass('text-center');
    $('.datatable-rows .fixed-right .process').html("<td></td>");
    $('.datatable-rows .flexarea .table tr').html('');
    $('.datatable-rows .flexarea .process').html("<td class='text-center' colspan=" + (insideColspan + outsideColspan) + "></td>");
    $('.datatable-rows .flexarea tbody tr').each(function(index){$(this).css('height', $('.fixed-left > .datatable-wrapper table  tr[data-index=' + index + ']')[0].getBoundingClientRect().height);})
    $('.datatable-rows .fixed-right tbody tr').each(function(index){$(this).css('height', $('.fixed-left > .datatable-wrapper table  tr[data-index=' + index + ']')[0].getBoundingClientRect().height);})
    $('.datatable-head-span.flexarea .datatable-wrapper > table tr:first th:first').attr('colspan', insideColspan);
    $('.datatable-head-span.flexarea .datatable-wrapper > table tr:first th:last').attr('colspan', outsideColspan);
    $('.datatable-head-span.flexarea .datatable-wrapper > table .datatable-row-flex').after('<tr>' + insideList + outsideList + '</tr>');
    $('.main-table .table-footer').css({'bottom': '40px', 'width': $('.main-table').width()})
    $('.fixed-left').css('width', '45%');
    $('.fixed-right').css('width', '100px');
    $('#planList tbody tr').each(function(index)
    {
        if($(this).hasClass('process')) return;        
        var $inside  = $(this).find('.inside').html().replace(/span/g, 'td');
        var $outside = $(this).find('.outside').html().replace(/span/g, 'td');
        if($inside.trim().length == '') $inside   = '<td></td>';
        if($outside.trim().length == '') $outside = '<td></td>';
        var $html = $inside + $outside;
        $('.datatable-rows .flexarea tbody tr[data-index=' + index + ']').html($html);
    })

    $('.edit-btn').click(function()
    {
        var $index     = $(this).closest('tr').attr('data-index');
        var activityID = $(this).attr('activity');
        var $leftTr    = $('.fixed-left .table tr[data-index=' + $index + ']');
        var $rightTr   = $('.fixed-right .table tr[data-index=' + $index + ']');
        var $centerTr  = $('.flexarea .table tr[data-index=' + $index + ']');
        var link       = createLink('stakeholder', 'ajaxGetControl', 'activityID=' + activityID);
        $.post(link, function(data)
        {
            $centerTr.html('');
            data = JSON.parse(data);
            Object.keys(data.partakeList).forEach(function(key)
            {
                $centerTr.append(data.partakeList[key]);
            });
            $leftTr.find('td[data-index=1]').html(data.begin);
            $leftTr.find('td[data-index=2]').html(data.realBegin);
            $leftTr.find('td[data-index=3]').html(data.status);
            $leftTr.find('td[data-index=4]').html(data.situation);
            $('.form-date').datepicker();
        })

        $(this).closest('td').html('<button type="submit" id="submit" class="btn btn-primary" style="background: #16a8f8; color: #fff; width: 50px">保存</button>');
    })
})

function fixScroll()
{
    var $scrollwrapper = $('div.datatable').first().find('.scroll-wrapper:first');
    if($scrollwrapper.size() == 0)return;

    var $tfoot       = $('div.datatable').first().find('table tfoot:last');
    var scrollOffset = $scrollwrapper.offset().top + $scrollwrapper.find('.scroll-slide').height();
    if($tfoot.size() > 0) scrollOffset += $tfoot.height();
    if($('div.datatable.head-fixed').size() == 0) scrollOffset -= '29';
    var windowH = $(window).height();
    var bottom  = $tfoot.hasClass('fixedTfootAction') ? 80 + $tfoot.height() : 80;
    if(typeof(ssoRedirect) != "undefined") bottom = 80;
    if(scrollOffset > windowH + $(window).scrollTop()) $scrollwrapper.css({'position': 'fixed', 'bottom': bottom + 'px'});
    $(window).scroll(function()
    {
          newBottom = $tfoot.hasClass('fixedTfootAction') ? 80 + $tfoot.height() : 80;
          if(typeof(ssoRedirect) != "undefined") newBottom = 80;
          if(scrollOffset <= windowH + $(window).scrollTop()) 
          {    
              $scrollwrapper.css({'position':'relative', 'bottom': '0px'});
          }    
          else if($scrollwrapper.css('position') != 'fixed' || bottom != newBottom)
          {    
              $scrollwrapper.css({'position': 'fixed', 'bottom': newBottom + 'px'});
              bottom = newBottom;
          }
    });
}
