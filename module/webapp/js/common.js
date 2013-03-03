if($('a.iframe').size()) $("a.iframe").colorbox({width:450, height:190, iframe:true, transition:'none', scrolling:true});
if($('a.webapp').size()) $("a.webapp").colorbox({width:700, height:400, iframe:true, transition:'none', scrolling:true});
if($('a.apiapp').size()) $("a.apiapp").colorbox({width:700, height:330, iframe:true, transition:'none', scrolling:true});
if($('a.popup').size()) $("a.popup").colorbox({width:900, height:600, iframe:true, transition:'none', scrolling:true});

function popup(width, height)
{
    if(width != 0 && height != 0) $("a.popup").colorbox({width:width, height:height});
}

var show = new Array();
var url  = new Array();
function toggleShowapp(webappID, webappName)
{
    if(show[webappID] == undefined)
    {
        height = document.documentElement.clientHeight - 110;
        if(url[webappID] == undefined) url[webappID] = $('#useapp' + webappID).attr('href');
        $('#useapp' + webappID).attr('href', '#iframe' + webappID);
        var html = "<tr id='iframe" + webappID + "'><td><p>" + webappName + "<span class='f-right'><button class='button-c' onclick='toggleShowapp(" + webappID + ", \" " + webappName + "\")'>" + packup + "</button></sapn></p><iframe src='" + url[webappID] + "' height='" + height + "' width='100%' style='border:1px solid #999;'></iframe></td></tr>";
        $('#webapps').parent().parent().after(html);
        show[webappID] = true;
    }
    else if(show[webappID])
    {
        $('#iframe' + webappID).hide();
        show[webappID] = false;
    }
    else
    {
        $('#iframe' + webappID).show();
        show[webappID] = true;
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
