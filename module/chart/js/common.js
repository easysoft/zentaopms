/**
 * Ajax get chart data.
 *
 * @access public
 * @return bool
 */
function ajaxGetChart(check = true, chart = DataStorage.chart, echart = window.echart)
{
    var chartParams = JSON.parse(JSON.stringify(chart));
    if(typeof DataStorage != 'undefined') chartParams.fieldSettings = JSON.parse(JSON.stringify(DataStorage.fieldSettings));
    if(typeof DataStorage != 'undefined') chartParams.langs         = JSON.parse(JSON.stringify(DataStorage.langs));

    /* Redraw echart. */
    /* 拿数据并重绘图表。*/
    $.post(createLink('chart', 'ajaxGetChart'), chartParams, function(resp)
    {
        var data = JSON.parse(resp);
        if(echart)
        {
            echart.resize();
            echart.clear();
            echart.setOption(data, true);
            $('.btn-export').removeClass('hidden');
        }
    });
}

function resizeChart()
{
    var filterHeight = $('.main-col .cell #filterContent').height();
    $('.main-col .cell #draw').css('height', 'calc(100% - ' + (filterHeight + 16) + 'px)')

    if(echart)
    {
        echart.resize();
    }
}

function waitForRepaint(callback)
{
    window.requestAnimationFrame(function()
    {
        window.requestAnimationFrame(callback);
    });
}

/**
 * Init picker.
 *
 * @access public
 * @return void
 */
function initPicker($row, pickerName = 'picker-select', onready = false)
{
    $row.find('.' + pickerName).picker(
    {
        maxDropHeight: pickerHeight,
        onReady: function()
        {
            if(!onready) return;
            if(!$row.find('.picker')) return;
            if(window.getComputedStyle($row.find('.picker').find('.picker-selections')[0]).getPropertyValue('width') !== 'auto')
            {
                var pickerWidth = $row.find('.picker')[0].getBoundingClientRect().width;
                $row.find('.picker').find('.picker-selections').css('width', pickerWidth);
            }
        }
    });
    $row.find("." + pickerName).each(function(index)
    {
       if($(this).hasClass('required')) $(this).siblings("div .picker").addClass('required');
    });
}

/**
 * Init datepicker.
 *
 * @param  object   $obj
 * @param  function callback
 * @access public
 * @return void
 */
function initDatepicker($obj, callback)
{
    $obj.find('.form-date').datepicker();
    $obj.find('.form-datetime').datetimepicker();

    if(typeof callback == 'function') callback($obj);
}

/**
 * Attr date check.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function attrDateCheck($obj)
{
    $obj.find('.form-date').attr('onchange', 'checkDate(this, this.value)');
    $obj.find('.form-datetime').attr('onchange', 'checkDate(this, this.value)');
}

