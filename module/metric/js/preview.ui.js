window.afterPageUpdate = function($target, info, options)
{
    window.isDropdown  = false;
    window.lineCount   = 1;
    window.checkedList = window.initCheckedList();
    window.chartList   = [];
    for(key in chartTypeList) {
        chartList.push({value: key, text: chartTypeList[key]});
    }
    window.filterChecked = {};

    if(viewType == 'single')
    {
        var metricCurrent = $('.metric-tree .metric-current');
        if(metricCurrent.length) metricCurrent[0].scrollIntoView({block: 'center', inline: 'start' })
    }
    if(viewType == 'multiple')
    {
        window.renderCheckedLabel();
        $(window).on('resize', window.renderCheckedLabel);
    }

    window.addTitle2Star();
    window.addDivider();
    window.initFilterPanel();
}

window.initCheckedList = function()
{
    if(typeof window.checkedList !== 'undefined' && Array.isArray(window.checkedList) && window.checkedList.length > 1)
    {
        return window.checkedList;
    }
    else
    {
        return [{id:current.id + '', name:current.name}];
    }
}

window.addTitle2Star = function()
{
    $('.metric-collect').attr('title', collectStar);
}

window.addDivider = function()
{
    let features = $('#featureBar .nav-feature').find('li.nav-item');
    let lastItem = features[features.length - 1];
    $(lastItem).addClass('divider-before');
}

/* 事件处理函数。 */
window.handleCheckboxChange = function($el)
{
    $el = $($el);
    var isChecked = $el.is(":checked");
    var value = $el.val();
    var text  = $el.next().text();

    if(!isChecked && window.checkedList.length == 1)
    {
        $el.prop('checked', true);
        return;
    }

    if(isChecked && window.checkedList.length >= 10)
    {
        $el.prop('checked', false);
        return messagerWarning(maxSelectMsg.replace('%s', maxSelectNum));
    }

    window.updateCheckAction(value, text, isChecked);
}

window.handleNavMenuClick = function($el)
{
    var scope = $el.attr('id');
    window.ajaxGetMetrics(scope, '', function(total){
        window.deactiveNavMenu();
        window.hideFilterPanel();
        window.resetFilterPanel();
        $el.addClass('active');
        $el.append(`<span class="label size-sm rounded-full white">${total}</span>`);
    })
}

window.handleFilterCheck = function()
{
    window.updateFilterCheck();
}

window.handleFilterToggle = function($el)
{
    toggleSearchBtn();

    $('.filter-panel').toggleClass('hidden');
}

window.handleFilterClearItem = function($el)
{
    $el = $($el);
    if(!$el.length) return;

    var $checkboxList = $el.closest('.panel').find('.panel-body .check-list-inline .checkbox-primary');
    $checkboxList.each(function(index, elem){
        $(elem).find('input').prop('checked', false);
    });

    window.updateFilterCheck();
}

window.handleFilterClearAll = function($el)
{
    $el = $($el);
    if(!$el.length) return;

    var $checkboxList = $el.closest('.panel').find('.panel-body .check-list-inline .checkbox-primary');
    $checkboxList.each(function(index, elem){
        $(elem).find('input').prop('checked', false);
    });

    window.updateFilterCheck();
}

window.handleFilterClick = function()
{
    var filterBase64 = btoa(JSON.stringify(window.filterChecked));

    if(viewType == 'multiple')
    {
        window.ajaxGetMetrics('filter', filterBase64, function(total)
        {
            window.deactiveNavMenu();
            $('.side-title').text(filterLang.filterTotal.replace('%s', total));
        });
        return;
    }
    loadPage($.createLink('metric', 'preview', 'scope=filter&viewType=' + viewType + '&metricID=0&filtersBase64=' + filterBase64));
}

window.handleChartTypeChange = function(metricID, viewType = 'single')
{
    if(viewType == 'single')
    {
        var chartType = $('[name=chartType]').val();
    }
    else
    {
        var chartType = $('#metricBox' + metricID).find('[name=chartType]').val();
    }

    var $form = $('#queryForm' + metricID);
    var formData = window.getFormData($form);

    $.post($.createLink('metric', 'ajaxGetEchartsOptions', 'metricID=' + metricID + '&chartType=' + chartType), formData, function(resp)
    {
        var options = JSON.parse(resp);
        if(!options) return;

        window.renderChart(metricID, viewType, options);
    });
}

window.handleRemoveLabel = function(id)
{
    var checkedItem = window.checkedList.find(function(checked){return checked.id == id});
    if(!checkedItem) return;

    window.updateCheckAction(checkedItem.id, checkedItem.name, false);
}

window.handleDateLabelClick = function(evt)
{
    $form = $(evt).closest('form');
    $form.find('.query-date button.btn').removeClass('selected');
    $form.find('.query-date-picker input[name="dateBegin"]').zui('datepicker').$.setValue('');
    $form.find('.query-date-picker input[name="dateEnd"]').zui('datepicker').$.setValue('');
    $(evt).addClass('selected');
}

window.handleCalcDateClick = function(evt)
{
    $form = $(evt).closest('form');
    $form.find('.query-calc-date button.btn').removeClass('selected');
    $(evt).addClass('selected');
}

window.handleDatePickerChange = function(evt)
{
    $form = $(evt).closest('form');
    var dateBegin = $form.find('.query-date-picker input[name="dateBegin"]').zui('datepicker').$.value;
    var dateEnd   = $form.find('.query-date-picker input[name="dateEnd"]').zui('datepicker').$.value;
    if(dateBegin.length && dateEnd.length)
    {
        $form.find('.query-date button.btn').removeClass('selected');
    }
}
/* 事件处理函数结束。 */

window.isMetricChecked = function(id)
{
    return window.checkedList.filter(function(metric){return metric.id == id}).length != 0;
}

window.updateCheckList = function(id, name, isChecked)
{
    if(isChecked)
    {
        return window.checkedList.push({id: id, name: name});
    }

    window.checkedList = window.checkedList.filter(function(item){return item.id != id});
}

window.updateCheckAction = function(id, name, isChecked)
{
    window.updateCheckList(id, name, isChecked);
    window.updateCheckbox(id, isChecked);
    window.renderCheckedLabel();
    window.updateMetricBoxs(id, isChecked);
}

window.ajaxGetMetrics = function(scope, filters = '', callback)
{
    $.get($.createLink('metric', 'ajaxGetMetrics', 'scope=' + scope + '&filters=' + filters), function(resp){
        var metrics = JSON.parse(resp);
        var total   = metrics.length;

        var checked = window.checkedList.map(obj => obj.id).join(',');
        var url     = $.createLink('metric', 'ajaxGetMetricSideTree', 'scope=' + scope + '&checkedList=' + checked + '&filters=' + filters);

        $('.side .metric-tree').empty();
        loadTarget(url, '.side .metric-tree');

        if(typeof callback == 'function') callback(total);
    });
}

window.updateFilterCheck = function()
{
    var selector = '.filter-panel .panel-body .check-list-inline .checkbox-primary input:checked';
    window.filterChecked = {'scope': [], 'object': [], 'purpose': []};
    var hasChecked = false;
    $(selector).each(function(index, elem){
        var name = $(elem).attr('name');
        var value = $(elem).val();

        window.filterChecked[name].push(value);
        hasChecked = true;
    });

    if(!hasChecked)
    {
        $('.filter-btn .common').removeClass('hidden');
        $('.filter-btn .checked').addClass('hidden');
        return;
    }

    $('.filter-btn .common').addClass('hidden');
    $('.filter-btn .checked').removeClass('hidden');
    var checkedInfo = filterLang.checkedInfo;
    checkedInfo = checkedInfo.replace('%s', window.filterChecked.scope.length);
    checkedInfo = checkedInfo.replace('%s', window.filterChecked.object.length);
    checkedInfo = checkedInfo.replace('%s', window.filterChecked.purpose.length);
    $('.filter-btn .checked').text(checkedInfo);
}

window.resetFilterPanel = function()
{
    var $checkboxList = $('.filter-panel .panel').find('.panel-body .check-list-inline .checkbox-primary');
    $checkboxList.each(function(index, elem){
        $(elem).find('input').prop('checked', false);
    });

    window.updateFilterCheck();

    $('.side-title').text(metricListLang);
}

window.initFilterPanel = function()
{
    if(!$('.filter-panel').length) return;

    $('#mainMenu').after($('.filter-panel'));

    toggleSearchBtn(true);
    if(scope == 'filter')
    {
        toggleSearchBtn(false);

        $('.filter-panel').removeClass('hidden');
        window.updateFilterCheck();
    }
    else
    {
        window.hideFilterPanel();
    }
}

window.getMetricRecordType = function(recordRow)
{
    if(!recordRow) return false;
    var type = [];
    if(recordRow.scope) type.push('scope');
    if(recordRow.date) type.push('date');
    if(type.length == 0) type.push('system');
    return type.join('-');
}

window.renderChart = function(metricID, viewType, options)
{
    var $currentBox = $('#metricBox' + metricID);
    if(viewType == 'single') $currentBox = $('.table-and-chart-single');
    if(!$currentBox.find('.chart').length) return;

    var classes = viewType == 'single' ? 'chart chart-single' : 'chart chart-multiple';
    $currentBox.find('.chart').remove();
    $currentBox.find('.chart-side').append('<div class="' + classes + '"></div>');

    window.renderEchart($currentBox.find('.chart')[0], options);
}

window.renderEchart = function($obj, option)
{
    $.getLib(config.webRoot + 'js/echarts/echarts.common.min.js', {root: false}, function() {
        var myChart = echarts.init($obj);
        option && myChart.setOption(option);
    });
}

window.setDropDown = function()
{
    var $drop = $('.dropdown-icon');
    $drop.toggleClass('rotate');
    if($drop.hasClass('rotate'))
    {
        window.isDropdown = true;
        window.unfoldContent();
    }
    else
    {
        window.isDropdown = false;
        window.foldContent();
    }
}

window.unfoldContent = function()
{
    var $content = $('.checked-content');
    var contentHeight = 48 + (window.lineCount - 1) * 40;
    $content.height(contentHeight);
    window.setMultiTableHeight(contentHeight);

    setTimeout(function(){
        $content.find('.gray-next').addClass('gray-visible');
        $content.find('.gray-next').removeClass('gray-hidden');
    }, 300);
}

window.foldContent = function()
{
    var $content = $('.checked-content');
    var contentHeight = 48;
    $content.height(contentHeight);
    window.setMultiTableHeight(contentHeight);

    $content.find('.gray-next').addClass('gray-hidden');
    $content.find('.gray-next').removeClass('gray-visible');
}

window.updateMetricBoxs = function(id, isChecked)
{
    if(!isChecked)
    {
        $('#metricBox' + id).remove();
        $('.table-and-charts').first().find('.metric-name').removeClass('metric-name-notfirst');
    }
    else
    {
        window.appendMetricBox(id);
    }
}

/**
 * 多选添加一个度量项到展示区。
 * Add a metricBox.
 *
 * @access public
 * @return void
 */
window.appendMetricBox = function(id, mode = 'add')
{
    var url = $.createLink('metric', 'ajaxGetMultipleMetricBox', 'metricID=' + id);

    var metricDom = $('<div>');
    var metricID  = 'metricBox' + id;
    metricDom.attr('id', metricID);
    metricDom.attr('metric-id', id);
    metricDom.addClass('metricBox');

    $('.table-and-charts').append(metricDom);
    loadTarget(url, metricID);
}

window.handleQueryClick = function(id, viewType = 'single')
{
    var $form = $('#queryForm' + id);
    var check = window.validateForm($form);
    if(!check) return;

    var formData = window.getFormData($form);
    var scopeValue = formData.get('scope') === null     ? '' : formData.get('scope');
    var dateLabel  = formData.get('dateLabel') === null ? '' : formData.get('dateLabel');
    var dateBegin  = formData.get('dateBegin') === null ? '' : formData.get('dateBegin').replace(/-/g, '_');
    var dateEnd    = formData.get('dateEnd') === null   ? '' : formData.get('dateEnd').replace(/-/g, '_');

    $.post($.createLink('metric', 'ajaxGetTableAndCharts', 'metricID=' + id + '&scope=' + scopeValue + '&dateLabel=' + dateLabel + '&dateBegin=' + dateBegin + '&dateEnd=' + dateEnd + '&viewType=' + viewType), formData, function(resp)
    {
        if(viewType == 'multiple')
        {
            $('#metricBox' + id).find('.table-and-chart').replaceWith(resp);
        }
        else
        {
            $('.table-and-chart-single').replaceWith(resp);
        }
    });
}

window.validateForm = function($form)
{
    var formSerialize = $form.serialize();
    var formObj = window.parseSerialize(formSerialize);

    var dateBegin = formObj.dateBegin?.length ? new Date(formObj.dateBegin) : false;
    var dateEnd   = formObj.dateEnd?.length ? new Date(formObj.dateEnd) : false;

    if(dateBegin && dateEnd && dateBegin > dateEnd)
    {
        zui.Messager.show(errorDateRange, {type: 'danger', time: 2000});
        return false;
    }
    return true;
}

window.getFormData = function($form)
{
    var $dateBegin    = $form.find('.query-date-picker input[name="dateBegin"]');
    var $dateEnd      = $form.find('.query-date-picker input[name="dateEnd"]');
    var $selectedDate = $form.find('.query-date button.selected');

    var dateBeginValue = $dateBegin.length ? $dateBegin.zui('datepicker').$.value : '';
    var dateEndValue   = $dateEnd.length ? $dateEnd.zui('datepicker').$.value : '';

    if(!$selectedDate.length && $dateBegin.length && $dateEnd.length && (!dateBeginValue.length || !dateEndValue.length))
    {
        var buttons = $form.find('.query-date button');
        $(buttons[0]).addClass('selected');
        $form.find('.query-date-picker input[name="dateBegin"]').zui('datepicker').$.setValue('');
        $form.find('.query-date-picker input[name="dateEnd"]').zui('datepicker').$.setValue('');
    }

    var formSerialize = $form.serialize();
    var formObj = window.parseSerialize(formSerialize);

    var formData = new FormData();
    for(var key in formObj)
    {
        var value = formObj[key];
        if(key.startsWith('scope')) key = 'scope';
        formData.append(key, value);
    }

    if($selectedDate.length)
    {
        formData.append('dateLabel', $selectedDate.attr('key'));
        formData.delete('dateBegin');
        formData.delete('dateEnd');
    }

    var $calcDate = $form.find('.query-calc-date button.selected');
    if($calcDate.length)
    {
        formData.append('calcDate', $calcDate.attr('key'));
    }

    return formData;
}

window.collectMetric = function(id)
{
    $.get($.createLink('metric', 'ajaxCollectMetric', 'metricID=' + id), function(resp)
    {
        var result = JSON.parse(resp);
        $('.metric-collect-' + id).find('i').attr('class', result.collect ? 'icon icon-star star' : 'icon icon-star-empty star-empty');
    });
}

window.handleSidebarToggle = function(status)
{
    setTimeout(function(){
        $('.chart-side .chart').children().each(function(index, elem){
            var chart = $(elem).zui().chart;
            if(!chart) return;

            chart.resize();
        });
    }, 300);
}

window.renderCheckedLabel = function()
{
    var $content =  $('.picker-multi-selections');
    if(!$content.length) return;
    $content.empty();

    var tpl    = $('#item-tpl').html();
    var labels = JSON.parse(JSON.stringify(window.checkedList));
    var multi  = labels.length > 1;
    var width  = Math.floor($content.width());
    var left   = width;

    // 当nextLine 在left <= 0 中置true后，代表接下来的元素都是换行的
    var nextLine  = false;
    var lineCount = 1;

    for(var i = 0; i < labels.length; i++)
    {
        var classes = 'picker-multi-selection';
        if(nextLine) classes += ' gray-next';

        var label = labels[i];
        var data  = {
            id: label.id,
            name: label.name,
            spanClass: classes,
            multiple: multi ? '' : 'hidden',
        };
        var html = $(zui.formatString(tpl, data));

        $content.append(html);

        var $label     = $content.find('[metric-id="' + label.id + '"]');
        var labelWidth = $label.width();
        var labelLeft  = parseInt($label.css('padding-left')) + parseInt($label.css('margin-left'));
        var labelRight = parseInt($label.css('padding-right')) + parseInt($label.css('margin-right'));
        var labelWidth = Math.ceil(labelWidth + labelLeft + labelRight + 2);

        left = left - labelWidth;
        if(left <= 0)
        {
            var $div     = $label.find('.text');
            var divWidth = $div.width();

            if(divWidth < -left)
            {
                // 如果剩下的空间一点字都显示不下了，就换行
                left = width - labelWidth;
                // 如果列表是折叠的还没判定换行但是这一行一点字都显示不下，从这里加next类
                if(!nextLine) $label.addClass('gray-next');
                // 此时已经换行了，行数加1
                lineCount ++;
            }
            else
            {
                // 通过设置div的宽度，减少文字的内容
                $div.width(Math.floor($div.width()) - Math.ceil(-left) - 1);
                // 换行了，重置left
                left = width;
                // 如果这个元素后面还有元素，说明行数要+1
                if(i + 1 < labels.length) lineCount ++;
            }

            nextLine = true;
        }
    }

    $(".metric-remove").addClass('hidden');
    $('.details').removeClass('details-after');
    if(!multi)
    {
        var maxSelectTipText = maxSelectTip.replace('%s', maxSelectNum);
        $content.append(`<span class="label ghost">${maxSelectTipText}</span>`);
    }
    else
    {
        $(".metric-remove").removeClass('hidden');
        $('.details').addClass('details-after');
    }

    if(!window.isDropdown) $content.find('.gray-next').addClass('gray-hidden');

    $('.dropdown-icon').addClass('visibility-hidden');
    if(lineCount >= 2) $('.dropdown-icon').removeClass('visibility-hidden');

    var contentHeight = 48 + (lineCount - 1) * 40;
    if(window.lineCount != lineCount && window.isDropdown)
    {
        // 在展开状态缩放到只有一行了
        if(lineCount == 1)
        {
            $('.dropdown-icon').toggleClass('rotate');
            window.isDropdown = false;
            window.foldContent();
        }
        // 在展开状态多了若干行, 或者少了若干行
        else
        {
            $('.checked-content').height(contentHeight);
            window.setMultiTableHeight(contentHeight);
        }
    }

    window.lineCount = lineCount;

    $('.checked-tip').html(selectCount.replace('%s', labels.length));
}

/* 纯函数 */
window.renderHeight = function()
{
    return $('.table-side').height();
}

window.parseSerialize = function(serialize)
{
    var result = {};
    serialize = serialize.replaceAll('%5B%5D', '');
    var items = serialize.split('&');
    for(var i = 0; i < items.length; i++)
    {
        var item = items[i].split('=');
        var key   = item[0];
        var value = item[1];
        if(!result[key]) result[key] = [];
        if(key.startsWith('scope'))
        {
            result[key].push(value);
        }
        else
        {
            result[key] = value;
        }
    }
    return result;
}

window.generateCheckItem = function(text, value, scope, isChecked)
{
    var checked = isChecked ? 'checked=""' : '';
    var currentClass = isChecked ? 'metric-current' : '';
    return `<div class="font-medium checkbox-primary ${currentClass}">
              <input type="checkbox" id="metric${value}" name="metric" ${checked} value="${value}" scope="${scope}" onchange="window.handleCheckboxChange(this)">
              <label for="metric${value}">${text}</label>
            </div>`;
}

window.messagerWarning = function(message)
{
    return zui.Messager.show({
        content: message,
        icon: 'icon-exclamation-pure',
        iconClass: 'center w-6 h-6 rounded-full m-0 warning',
        contentClass: 'text-lg font-bold',
        close: false,
        className: 'p-6 bg-white text-black gap-2 messager-fail',
    });
}

window.getTableHeight = function(actual)
{
    return Math.min(800, $('.table-side').height());
}

window.setMultiTableHeight = function(contentHeight)
{
    $('.table-and-charts').css('max-height', 'calc(100% - ' + contentHeight + 'px)');
}

window.updateCheckbox = function(id, isChecked)
{
    var $el = $('.side .metric-tree .check-list input#metric' + id);
    $el.prop('checked', isChecked);
    if(isChecked)
    {
        return $el.closest('.checkbox-primary').addClass('metric-current');
    }

    $el.closest('.checkbox-primary').removeClass('metric-current');
}

window.genDataZoom = function(dataLength, initZoom = 10, axis = 'x')
{
    var percent = initZoom / dataLength * 100;
    percent = percent > 100 ? 100 : percent;
    var dataZoom = {
        start: 0,
        end: percent,
    };
    if(axis == 'x') dataZoom.xAxisIndex = [0];
    if(axis == 'y') dataZoom.yAxisIndex = [0];
    return [dataZoom];
}

window.hideFilterPanel = function()
{
    toggleSearchBtn(false);

    $('.filter-panel').addClass('hidden');
}

window.deactiveNavMenu = function()
{
    var itemSelector = 'menu.nav-ajax .nav-item a';
    $(itemSelector).removeClass('active');
    $(itemSelector).find('span.label').remove();
}

window.toggleSearchBtn = function(active = null)
{
    if(active === null)
    {
        $('.filter-btn').toggleClass('ring-primary');
        $('.icon-search').toggleClass('text-primary');
        $('.filter-btn-text').toggleClass('text-primary');
    }
    else
    {
        if(active)
        {
            $('.filter-btn').addClass('ring-primary');
            $('.icon-search').addClass('text-primary');
            $('.filter-btn-text').addClass('text-primary');
        }
        else
        {
            $('.filter-btn').removeClass('ring-primary');
            $('.icon-search').removeClass('text-primary');
            $('.filter-btn-text').removeClass('text-primary');
        }
    }
}

window.collapseFilters = function()
{
    $('.collapse-icon').toggleClass('icon-chevron-double-up');
    $('.collapse-icon').toggleClass('icon-chevron-double-down');

    $('[data-collapse=true]').parent('.checkbox-primary').toggleClass('hidden');
}

$(document).ready(function()
{
    $('[data-collapse=true]').parent('.checkbox-primary').addClass('hidden');
})
