<script>
$('#showData > tbody').addClass('load-indicator loading');
$.get(createLink('transfer', 'ajaxGetTbody','model=<?php echo $model;?>&lastID=0&pagerID=<?php echo $pagerID;?>'), function(data)
{
    $('#showData > tbody').append(data);
    if($('#showData tbody').find('tr').hasClass('showmore') === false) $('#showData tfoot').removeClass('hidden');
    $('#showData tbody').find('.picker-select').picker({chosenMode: true});
    $('.form-date').datetimepicker({minView: 2, format: "yyyy-mm-dd"});
    $('.form-datetime').datetimepicker('update');
    $('#showData > tbody').removeClass('load-indicator loading');

    if(typeof(getTbodyLoaded) == 'function') getTbodyLoaded();
})

window.addEventListener('scroll', this.handleScroll);
function handleScroll(e)
{
    var relative = 500; // 相对距离
    $('tr.showmore').each(function()
    {
        var $showmore = $(this);
        var offsetTop = $showmore[0].offsetTop;
        if(offsetTop == 0) return true;

        if(getScrollTop() + getWindowHeight() >= offsetTop - relative)
        {
            throttle(loadData($showmore), 250)
        }
    })
}

function loadData($showmore)
{
    $showmore.removeClass('showmore');
    var lastID = $showmore.attr('data-id');
    var url    = createLink('transfer', 'ajaxGetTbody','model=<?php echo $model;?>&lastID=' + lastID + '&pagerID=<?php echo $pagerID;?>');
    $.get(url, function(data)
    {
        $showmore.after(data);
        if($('#showData tbody').find('tr').hasClass('showmore') === false) $('#showData tfoot').removeClass('hidden');
        $('#showData tbody').find('.picker-select').picker({chosenMode: true}).removeClass('nopicker');
        $('.form-date').datetimepicker({minView: 2, format: "yyyy-mm-dd"});
        $('.form-datetime').datetimepicker('update');
    })
}

function throttle(fn, threshhold)
{
    var last;
    var timer;
    threshhold || (threshhold = 250);

    return function()
    {
        var context = this;
        var args = arguments;

        var now = +new Date()

        if (last && now < last + threshhold)
        {
            clearTimeout(timer);
            timer = setTimeout(function ()
            {
                last = now
                fn.apply(context, args)
            }, threshhold)
        }
        else
        {
            last = now
            fn.apply(context, args)
        }
    }
}

function getScrollTop()
{
    return scrollTop = document.body.scrollTop + document.documentElement.scrollTop
}

function getWindowHeight()
{
    return document.compatMode == "CSS1Compat" ? windowHeight = document.documentElement.clientHeight : windowHeight = document.body.clientHeight
}

$('#showData').on('mouseenter', '.picker', function(e){
    var myPicker = $(this);
    var field    = myPicker.prev().attr('data-field');
    var id       = myPicker.prev().attr('id');
    var name     = myPicker.prev().attr('name');
    var index    = Number(name.replace(/[^\d]/g, " "));
    var value    = myPicker.prev().val();

    if($('#' + id).attr('isInit')) return;

    $.ajaxSettings.async = false;
    $.get(createLink('transfer', 'ajaxGetOptions', 'model=<?php echo $model;?>&field=' + field + '&value=' + value + '&index=' + index), function(data)
    {
        $('#' + id).parent().html(data);
        $('#' + id).picker({chosenMode: true});
        $('#' + id).attr('isInit', true);
        $('#' + id).attr('data-field', field);
    });
    $.ajaxSettings.async = true;
})

$(function()
{
    $.fixedTableHead('#showData');
    $("#showData th").each(function()
    {
        if(requiredFields.indexOf(this.id) !== -1) $("#" + this.id).addClass('required');
    });
});

function delItem(val)
{
    $(val).parents('tr').remove();
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
