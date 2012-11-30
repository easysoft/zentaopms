if($('a.iframe').size()) $("a.iframe").colorbox({width:450, height:220, iframe:true, transition:'elastic', speed:350, scrolling:true});
if($('a.webapp').size()) $("a.webapp").colorbox({width:600, height:400, iframe:true, transition:'elastic', speed:350, scrolling:true});
if($('a.popup').size()) $("a.popup").colorbox({width:900, height:600, iframe:true, transition:'elastic', speed:350, scrolling:true});

function popup(width, height)
{
    if(width == 0 || height == 0) $("a.popup").colorbox({width:width, height:height});
}

var show = false;
var url  = '';
function toggleShowapp(webappID)
{
    height = document.body.clientHeight - 60;
    if(!show)
    {
        if(url == '') url = $('#useapp' + webappID).attr('href');
        $('#useapp' + webappID).attr('href', '#iframe' + webappID);
        var html = "<tr id='iframe" + webappID + "'><td colspan='2'><p align='right'><button class='button-c' onclick='toggleShowapp(" + webappID + ")'>" + packup + "</button></p><iframe src='" + url + "' height='" + height + "' width='100%'></iframe></td></tr>";
        $('#webapp' + webappID).append(html);
        show = true;
    }
    else
    {
        $('#iframe' + webappID).remove();
        show = false;
    }
}

function setSize(target)
{
  $('.size').hide();
  if(target == 'popup') $('.size').show();
}

function addView(webappID)
{
  $.get(createLink('webapp', 'ajaxAddView', 'webappID=' + webappID));
}

$(function(){
    setSize($('#target').val());
    $('#target').change(function(){setSize($(this).val())});
    $('#modulemenu ul li').removeClass('active');
    if(typeof(module) != "undefined") $('#modulemenu ul li #submenu' + module).parent().addClass('active');
})
