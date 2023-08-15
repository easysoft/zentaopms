$(function()
{
    if(typeof pivotID == 'undefined' || !pivotID) return;

    expandTree();
    renderFilters(pivot);
    initQueryBtn();
    initOriginQueryBtn();

    $('body').resize(() => {calcPreviewGrowFilter();});

    $('[data-toggle="tooltip"]').tooltip();
});

function expandTree()
{
    $('.pivot-' + groupID + '-' + pivotID).addClass('active');

    var $tree = $('#pivotGroups').tree({name:'tree-group'});
    var tree  = $tree.data('zui.tree');
    tree.expand($('.pivot-' + pivotID).parents('li'));
}

function getQueryInfo()
{
    // if not use JSON.parse(JSON.stringify()), edit pivotInfo also affects pivot. If click btn-query-origin, pivot setting also have summary field.
    var pivotInfo = JSON.parse(JSON.stringify(pivot));
    pivotInfo.searchFilters = JSON.parse(JSON.stringify(pivotInfo.filters));
    pivotInfo.step = 4;

    if(!pivotInfo.searchFilters) return pivotInfo;

    if(isQueryFilter(pivotInfo.filters))
    {
        pivotInfo.filters.forEach(function(filter, index)
        {
            var find = $('#filterItems .filter-items').find('.filter-item-' + index + ' .form-' + filter.type);
            if(find.length != 0) pivotInfo.searchFilters[index].default = find.val();
        });
    }
    else
    {
        pivotInfo.filters.forEach(function(filter, index)
        {
            var filterItem = $('#filterItems .filter-items').find('.filter-item-' + index);
            var type = filter.type;
            if(type == 'input')
            {
                var find = filterItem.find(' .form-' + type);
                if(find.length != 0) pivotInfo.searchFilters[index].default = find.val();
            }
            else if(type == 'select')
            {
                var find = filterItem.find(' .form-' + type);
                if(find.length != 0) pivotInfo.searchFilters[index].default = find.data('zui.picker').getValue();
            }
            else if(type == 'date' || type == 'datetime')
            {
                var begin = filterItem.find('.form-' + type + '.begin');
                var end   = filterItem.find('.form-' + type + '.end');

                pivotInfo.searchFilters[index].default =
                {
                    "begin": begin.length != 0 ? begin.val() : filter.default.begin,
                    "end": end.length != 0 ? end.val() : filter.default.end
                };
            }
        });
    }

    return pivotInfo;
}

/**
 * Init query button.
 *
 * @access public
 * @return void
 */
function initQueryBtn()
{
    $('.btn-query').click(function()
    {
        var pivotInfo = getQueryInfo();
        refreshPivot(pivotInfo);
    });
}

/**
 * Init origin query button.
 *
 * @access public
 * @return void
 */
function initOriginQueryBtn()
{
    $('.btn-query-origin').click(function()
    {
        var pivotInfo = getQueryInfo();
        pivotInfo.settings['summary'] = 'notuse';
        refreshPivot(pivotInfo, true);
    });
}

function refreshPivot(pivotInfo, isOrigin = false)
{
    $.post(createLink('pivot', 'ajaxGetPivot'), pivotInfo,function(resp)
    {
        var myDatagrid = $('#datagirdInfo');
        myDatagrid.find('.reportData').remove();
        myDatagrid.append(resp);

        if(isOrigin)
        {
            $('#filterItems').addClass('hidden');
            $('#filterMargin').removeClass('hidden');

            $('#pivot-query').removeClass('hidden');
            $('#origin-query').addClass('hidden');
        }
        else
        {
            $('#filterItems').removeClass('hidden');
            $('#filterMargin').addClass('hidden');

            $('#pivot-query').addClass('hidden');
            $('#origin-query').removeClass('hidden');

            $('.query-outside').addClass('visibility-hidden');
        }
    });
}

function calcPreviewGrowFilter()
{
    if(pivot.filters.length <= 0) return;
    var $filterItems = $('#filterItems .filter-items');
    /* When refreshing this page, ZenTao load this page twice, when the first load isn't complete, jump back to index and load the second time,
       the first load can't calc filter width, can't get the element using jQuery at first load. */
    var hasInit = true;
    pivot.filters.forEach(function(filter, index)
    {
        var nowItem    = '.filter-item-' + index;
        var $titleSpan = $filterItems.find(nowItem).find('.input-group-addon').first();
        if(!$titleSpan.length) hasInit = false;
    });
    if(!hasInit) return;

    var domWidth     = $filterItems[0].getBoundingClientRect().width;
    var nowWidth     = domWidth;
    var lineWrap     = false;
    var nowCount     = 0;
    var canGrowTotal = 0;

    $('.query-inside').addClass('hidden');
    $('.query-outside').addClass('visibility-hidden');
    pivot.filters.forEach(function(filter, index)
    {
        var nowItem      = '.filter-item-' + index;
        var $nowDom      = $filterItems.find(nowItem);
        var leftPadding  = parseInt($nowDom.css('padding-left'));
        var rightPadding = parseInt($nowDom.css('padding-right'));
        var spanWidth    = $nowDom.find('.input-group-addon').first()[0].getBoundingClientRect().width;
        var filterWidth  = ((filter.type == 'input' || filter.type == 'select') ? WIDTH_INPUT : WIDTH_DATE) + (spanWidth + leftPadding + rightPadding);

        /* Clear the flex-basis and set flex-basic again. */
        $nowDom.css('flex-basis', '');
        $nowDom.css('flex-basis', filterWidth);
        if(nowWidth - filterWidth >= 0)
        {
            nowWidth -= filterWidth;
            nowCount ++;
        }
        else
        {
            canGrowTotal += nowCount;
            nowWidth = domWidth - filterWidth;
            nowCount = 1;
            lineWrap = true;
        }
    });

    /* Clear all filter filter-item-grow and add class to support grow element. */
    $filterItems.children().removeClass('filter-item-grow');
    pivot.filters.forEach(function(filter, index)
    {
        var $nowDom = $filterItems.find('.filter-item-' + index);
        if(canGrowTotal >= index + 1) $nowDom.addClass('filter-item-grow');
    });

    if(!lineWrap && nowWidth >= 60) $('.query-inside').removeClass('hidden');
    else $('.query-outside').removeClass('visibility-hidden');
}

function renderFilters(pivot)
{
    if(pivot.filters.length == 0) return;
    renderFilterItem(pivot.filters);
    calcPreviewGrowFilter();
}

function renderFilterItem(filters)
{
    $('#filterItems .filter-items').empty();
    var tpl = isQueryFilter(filters) ? $('#queryFilterItemTpl').html() : $('#resultFilterItemTpl').html();

    filters.forEach(function(filter, index)
    {
        var data =
        {
            index: index,
            name: filter.name,
            defaultValue: filter.default
        };
        var html = $($.zui.formatString(tpl, data))
        $('#filterItems .filter-items').append(html);

        isQueryFilter(filters) ? initQueryControl(html, filter) : initResultControl(html, filter);
    });
    $('#filterItems .filter-items').append(queryDom);
}

function initQueryControl(container, filter, object, field)
{
    var type = filter.type;
    var options = filter.typeOption;
    var value = filter.default;

    container.find('.default-block').addClass('hidden');
    container.find('.default-block input[name="default"]').val('');

    var control = container.find('.form-' + type);
    control.val(filter.default);
    control.parent('.default-block').removeClass('hidden');

    if(type == 'date')     setDateField('.form-date');
    if(type == 'datetime') setDateField('.form-datetime');
    if(type == 'select')
    {
        var picker = control.data('zui.picker');
        if(picker) picker.destroy();

        $.post(createLink('pivot', 'ajaxGetSysOptions', 'type=' + options + '&object=' + object + '&field=' + field), function(resp)
        {
            control.html($(resp).html());
            control.picker();
            control.data('zui.picker').setValue(value);
        });
    }
}

function initResultControl(container, filter, object, field)
{
    var type = filter.type;
    var value = filter.default;

    container.find('.default-block').addClass('hidden');
    container.find('.default-block input[name^="default"]').val('');

    var control = container.find('.form-' + type);
    if(type == 'date' || type == 'datetime')
    {
        container.find('.form-' + type + '.begin').val(filter.default.begin);
        container.find('.form-' + type + '.end').val(filter.default.end);
    }
    else
    {
        control.val(filter.default);
    }
    control.parents('.default-block').removeClass('hidden');

    if(type == 'date') control.datepicker();
    if(type == 'datetime') control.datetimepicker();
    if(type == 'select')
    {
        var picker = control.data('zui.picker');
        if(picker) picker.destroy();

        var fieldSetting = pivot.fieldSettings[filter.field];
        var options = fieldSetting.type;
        var object = fieldSetting.object;
        var field = fieldSetting.field;

        var pickerOptions =
        {
            multi: true,
            maxDropHeight: pickerHeight,
            onReady: function()
            {
                if(!control.parent().find('.picker')) return;
                if(window.getComputedStyle(control.parent().find('.picker').find('.picker-selections')[0]).getPropertyValue('width') !== 'auto')
                {
                    var pickerWidth = control.parent().find('.picker')[0].getBoundingClientRect().width;
                    control.parent().find('.picker').find('.picker-selections').css('width', pickerWidth);
                }
            }
        };
        if(value !== '') pickerOptions.defaultValue = value;

        var pivotParams = {sql: pivot.sql, filters: getQueryFilters(pivot)};
        $.post(createLink('pivot', 'ajaxGetSysOptions', 'type=' + options + '&object=' + object + '&field=' + field), pivotParams, function(resp)
        {
            control.html($(resp).html());
            control.picker(pickerOptions);
        });
    }
}

function exportData()
{
    const $domObj = $(".table-condensed")[0];
    exportFile($domObj);
}

function isQueryFilter(filters)
{
    return filters.length > 0 && filters[0].from == 'query';
}

function locate(module, method, params)
{
    var link = createLink(module, method, params);
    window.location.href = link;
}
