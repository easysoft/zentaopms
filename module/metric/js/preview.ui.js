window.renderHeight = function()
{
    return $('.table-side').height();
}

window.parseSerialize = function(serialize)
{
    var result = {};
    var items = serialize.split('&');
    for(var i = 0; i < items.length; i++)
    {
        var item = items[i].split('=');
        result[item[0]] = item[1];
    }
    return result;
}

window.generateCheckItem = function(text, value, isChecked)
{
    var checked = isChecked ? 'checked=""' : '';
    var currentClass = isChecked ? 'metric-current' : '';
    return `<div class="font-medium checkbox-primary ${currentClass}">
            <input type="checkbox" id="metric${value}" name="metric" ${checked} value="${value}" onchange="window.handleCheckboxChange(this)">
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

window.isMetricChecked = function(id)
{
    return window.checkedList.filter(function(metric){return metric.id == id}).length != 0;
}

window.renderCheckList = function(metrics)
{
    $('.side .check-list-metric').empty();

    var metricsHtml = metrics.map(function(metric){
        var isChecked = window.isMetricChecked(metric.id);
        return window.generateCheckItem(metric.name, metric.id, isChecked);
    }).join('');

    $('.side .check-list-metric').html(metricsHtml);
}

window.updateCheckList = function(id, name, isChecked)
{
    if(isChecked)
    {
        return window.checkedList.push({id: id, name: name});
    }

    window.checkedList = window.checkedList.filter(function(item){return item.id != id});
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

window.updateCheckAction = function(id, name, isChecked)
{
    window.updateCheckList(id, name, isChecked);
    window.updateCheckbox(id, isChecked);
    window.renderCheckedLabel();
    window.updateMetricBoxs(id, isChecked);
}

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
    window.ajaxGetMetrics(scope, '', function(metrics, total){
        window.deactiveNavMenu();
        window.hideFilterPanel();
        window.resetFilterPanel();
        $el.addClass('active');
        $el.append(`<span class="label size-sm rounded-full white">${total}</span>`);
    })
}

window.handleQueryClick = function()
{
    window.ajaxGetRecords(current.id);
}

window.deactiveNavMenu = function()
{
    var itemSelector = 'menu.nav-ajax .nav-item a';
    $(itemSelector).removeClass('active');
    $(itemSelector).find('span.label').remove();
}

window.ajaxGetMetrics = function(scope, filters = '', callback)
{
    $.get($.createLink('metric', 'ajaxGetMetrics', 'scope=' + scope + '&filters=' + filters), function(resp){
        var metrics = JSON.parse(resp);
        var total = metrics.length;

        window.renderCheckList(metrics);

        if(typeof callback == 'function') callback(metrics, total);
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

window.handleFilterCheck = function()
{
    window.updateFilterCheck();
}

window.handleFilterToggle = function($el)
{
    $el.toggleClass('primary-600');
    $('.filter-panel').toggleClass('hidden');
}

window.hideFilterPanel = function()
{
    $('.filter-btn').removeClass('primary-600');
    $('.filter-panel').addClass('hidden');
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
        window.ajaxGetMetrics('filter', filterBase64, function(_, total)
        {
            window.deactiveNavMenu();
            $('.side-title').text(filterLang.filterTotal.replace('%s', total));
        });
        return;
    }
    loadPage($.createLink('metric', 'preview', 'scope=filter&viewType=' + viewType + '&metricID=0&filters=' + filterBase64));
}

window.afterPageUpdate = function($target, info, options)
{
    window.isDropdown  = false;
    window.lineCount   = 1;
    window.checkedList = [{id:current.id + '', name:current.name}];
    window.chartList   = [];
    for(key in chartTypeList) {
        chartList.push({value: key, text: chartTypeList[key]});
    }
    window.filterChecked = {};
    window.renderDTable(current.id, resultHeader, resultData);
    window.renderChart();
    if(viewType == 'multiple') window.renderCheckedLabel();
    $(window).on('resize', window.renderCheckedLabel);
    window.initFilterPanel();
}

window.initFilterPanel = function()
{
    if(!$('.filter-panel').length) return;

    $('#mainMenu').after($('.filter-panel'));

    $('.filter-btn').removeClass('primary-600');
    if(scope == 'filter')
    {
        $('.filter-btn').addClass('primary-600');
        $('.filter-panel').removeClass('hidden');
        window.updateFilterCheck();
    }
    else
    {
        window.hideFilterPanel();
    }
}

window.renderDTable = function(metricID = current.id, header = resultHeader, data = resultData)
{
    var $currentBox = $('#metricBox' + metricID);
    if(viewType == 'single') $currentBox = $('.table-and-chart-single');

    if(!$currentBox.find('.dtable').length) return;
    $currentBox.find('.dtable').remove();
    $currentBox.find('.table-side').append('<div class="dtable"></div>');

    window.initDTable($currentBox.find('.dtable'), header, data);
}

window.renderChart = function(metricID = current.id, header = resultHeader, data = resultData)
{
    var $currentBox = $('#metricBox' + metricID);
    if(viewType == 'single') $currentBox = $('.table-and-chart-single');
    if(!$currentBox.find('.chart').length) return;
    $currentBox.find('.chart').remove();
    $currentBox.find('.chart-side').append('<div class="chart chart-container"></div>');

    window.initChart($currentBox.find('.chart')[0], header, data);
    window.initPicker($currentBox.find('.chart-type'), window.chartList, header.length);
}

window.initDTable = function($obj, head, data)
{
    var height = 328;
    if(viewType == 'single') height = $('.table-side').height();
    if(!head || !data) return;
    new zui.DTable($obj,{
        responsive: true,
        bordered: true,
        scrollbarHover: true,
        height: height,
        cols: head,
        data: data,
        onRenderCell: function(result, {row, col})
        {
            if(col.name == 'scope')
            {
                var scope = `<span class="scope-ellipsis" title="${row.data.scope}">${row.data.scope}</span>`;
                result[0] = {html: scope};
            }
            return result;
        }
    });
}

window.initChart = function($obj, head, data, chartType = 'line')
{
    if(chartType == 'pie') return window.initPieChart($obj, head, data);
    if(head.length == 2) {
        var x = head[1].name;
        var y = head[0].name;
    }
    else if(head.length == 3) {
        var x = head[0].name;
        var y = head[1].name;
    }
    else if(head.length == 4) {
        var x = head[1].name;
        var y = head[2].name;
    }
    if(!x || !y) return;

    var type  = (chartType == 'barX' || chartType == 'barY') ? 'bar' : chartType;

    if(head.length <= 3) {
        var xAxis = {
            type: 'category',
            data: data.map(item => item[x])
        };
        if(head.length == 2) xAxis.data = xAxis.data.map(item => item.slice(0, 10));
        var yAxis = {
            type: 'value'
        };
        var series = [{
            data: data.map(item => item[y]),
            type: type
        }];
    }
    else if(head.length == 4) {
        var xAxis = {
            type: 'category',
            data: data.map(item => item[x]),
        };
        xAxis.data = xAxis.data.filter((value, index, self) => {
            return self.indexOf(value) === index;
        });
        var yAxis = {
            type: 'value'
        };
        var series = [];

        var groupedData = data.reduce((accumulator, currentValue) => {
            var scope = currentValue.scope;
            var date  = currentValue.date;
            var value = currentValue.value;

            var group = accumulator.find(item => item.scope === scope);
            if (group) {
                group.date.push({date: date, value: value});
            } else {
                accumulator.push({ scope, date: [{date: date, value: value}] });
            }
            return accumulator;
        }, []);

        for(key in groupedData) {
            var scope = groupedData[key].scope;
            var dates = groupedData[key].date;

            var seriesData = [];
            xAxis.data.forEach(function(date) {
                seriesData.push(dates.find(item => item.date === date) ? dates.find(item => item.date === date).value : null);
            });

            series.push({data: seriesData, type: type, name: scope});
        }
    }

    $.getLib(config.webRoot + 'js/echarts/echarts.common.min.js', {root: false}, function() {

        var myChart = echarts.init($obj);
        var option = {
            tooltip: {
                trigger: 'axis'
            },
            xAxis: chartType == 'barY' ? yAxis : xAxis,
            yAxis: chartType == 'barY' ? xAxis : yAxis,
            series: series,
        };

        option && myChart.setOption(option);
    });
}

window.initPieChart = function($obj, head, data)
{
    var x = head[0].name;
    var y = head[1].name;
    var datas = [];
    data.forEach(function(item) {
        datas.push({name: item[x], value: item[y]});
    });

    $.getLib(config.webRoot + 'js/echarts/echarts.common.min.js', {root: false}, function() {

        var myChart = echarts.init($obj);
        option = {
            tooltip: {
                trigger: 'item'
            },
            legend: {
                orient: 'vertical',
                left: 'left'
            },
            series: [
                {
                    type: 'pie',
                    radius: '50%',
                    data: datas,
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };

        option && myChart.setOption(option);
    });
}

window.initPicker = function($obj, items, headLength = 3)
{
    if(headLength != 3) {
        items = items.filter(item => item.value != 'pie');
    }
    if($obj.zui('picker')) return;
    new zui.Picker($obj, {
        items,
        defaultValue: 'line',
        name: 'chartType',
        required: true,
        onChange: () => window.handleChartTypeChange(this),
    });
}

window.handleChartTypeChange = function($el)
{
    if(viewType == 'single')
    {
        var chartType = $('[name=chartType]').val();
        var $currentBox = $('.table-and-chart-single');
        $currentBox.find('.chart').remove();
        $currentBox.find('.chart-side').append('<div class="chart chart-container"></div>');

        window.initChart($currentBox.find('.chart')[0], resultHeader, resultData, chartType);
    }
    else
    {
        var $metricBox = $($el.closest('.metricBox'));
        var metricID   = $metricBox.attr('metric-id');
        var chartType  = $metricBox.find('[name=chartType]').val();

        $.get($.createLink('metric', 'ajaxGetTableData', 'metricID=' + metricID), function(resp)
        {
            var data = JSON.parse(resp);
            if(data)
            {
                $currentBox.find('.chart').remove();
                $currentBox.find('.chart-side').append('<div class="chart chart-container"></div>');
                window.initChart($metricBox.find('.chart')[0], data.header, data.data, chartType);
            }
        });
    }
}

window.handleRemoveLabel = function(id)
{
    var checkedItem = window.checkedList.find(function(checked){return checked.id == id});
    if(!checkedItem) return;

    window.updateCheckAction(checkedItem.id, checkedItem.name, false);
}

window.setDropDown = function()
{
    var $drop    = $('.dropdown-icon');
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
        var label = window.checkedList.find(function(checked){return checked.id == id});
        var tpl   = $("#metricBox-tpl").html();
        var data  = {
            id: label.id,
            name: label.name,
        };
        var html = $(zui.formatString(tpl, data));

        $('.table-and-charts').append(html);

        window.ajaxGetRecords(id);
    }
}

window.ajaxGetRecords = function(id)
{
    var formData = window.getFormData($('#queryForm'));

    $.post($.createLink('metric', 'ajaxGetTableData', 'metricID=' + id),formData, function(resp)
    {
        var data = JSON.parse(resp);
        if(data)
        {
            if(viewType == 'multiple')
            {
                window.initDTable($('#metricBox' + id).find('.dtable'), data.header, data.data);
            }
            else
            {
                window.renderDTable(id, data.header, data.data);
                window.renderChart(id, data.header, data.data);
            }
        }
    });
}

window.getFormData = function($form)
{
    var formSerialize = $form.serialize();
    var formObj = window.parseSerialize(formSerialize);

    var formData = new FormData();
    for(var key in formObj)
    {
        formData.append(key, formObj[key]);
    }

    return formData;
}

window.setMultiTableHeight = function(contentHeight)
{
    $('.table-and-charts').css('max-height', 'calc(100% - ' + contentHeight + 'px)');
}

window.collectMetric = function(id)
{
    $.get($.createLink('metric', 'ajaxCollectMetric', 'metricID=' + id), function(resp)
    {
        var result = JSON.parse(resp);
        if(result.collect)
        {
            $('.metric-collect').find('img').attr('src', 'static/svg/star.svg');
        }
        else
        {
            $('.metric-collect').find('img').attr('src', 'static/svg/star-empty.svg');
        }
    });
}

window.renderCheckedLabel = function()
{
    var $content =  $('.checked-label-content');
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

    var labelClass = 'label circle gray-pale';
    if(multi) labelClass += ' gray-pale-withdelete';

    for(var i = 0; i < labels.length; i++)
    {
        var classes = labelClass;
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
        var labelWidth = Math.ceil($label.width() + parseInt($label.css('padding-left')) + parseInt($label.css('padding-right')) + parseInt($label.css('margin-left')) + parseInt($label.css('margin-right')));

        left = left - labelWidth;
        if(left <= 0)
        {
            var $div     = $label.find('.gray-pale-div');
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
        $content.append(`<span class="label ghost gray-pale">${maxSelectTipText}</span>`);
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
