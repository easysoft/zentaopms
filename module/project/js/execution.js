$(function()
{
    $('input[name^="showTask"]').click(function()
    {
        var show = $(this).is(':checked') ? 1 : 0;
        $.cookie('showTask', show, {expires:config.cookieLife, path:config.webRoot});
        window.location.reload();
    });
})

window.addEventListener('scroll', this.handleScroll)
function handleScroll(e)
{
    var relative = 500; // 相对距离
    if(getScrollTop() + getWindowHeight() >= getScrollHeight() - relative)
    {
        throttle(loadData(), 250)
    }
}

function loadData()
{
    $('tr.showmore').each(function()
    {
        var offsetTop = $(this)[0].offsetTop;
        if(offsetTop == 0) return true;
        $(this).removeClass('showmore');

        var showmoreTr  = this;
        var executionID = $(this).attr('data-parent');
        var maxTaskID   = $(this).attr('data-id');
        var link = createLink('task', 'ajaxGetTasks', 'executionID=' + executionID + '&maxTaskID=' + maxTaskID);
        $.get(link, function(data)
        {
            $(showmoreTr).before(data);
            $(".iframe").modalTrigger({type:'iframe'});

            $('#executionForm').table('initNestedList');
        })
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

function getScrollHeight()
{
    return scrollHeight = document.documentElement.scrollHeight
}

function getWindowHeight()
{
    return document.compatMode == "CSS1Compat" ? windowHeight = document.documentElement.clientHeight : windowHeight = document.body.clientHeight
}
