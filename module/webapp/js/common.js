
function popup(width, height)
{
    if(width != 0 && height != 0) $("a.popup").modalTrigger({width:width, height:height});
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
        var html = "<tr id='iframe" + webappID + "'><td><p>" + webappName + "<span class='text-right'><button class='btn' onclick='toggleShowapp(" + webappID + ", \" " + webappName + "\")'>" + packup + "</button></span></p><iframe src='" + url[webappID] + "' height='" + height + "' width='100%' style='border:1px solid #999;'></iframe></td></tr>";
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
  $('.customSize').hide();
  if(target == 'popup')
  {
    $('.size').show();
    customSize($('#size').val());
  }
}

function addView(webappID)
{
  $.get(createLink('webapp', 'ajaxAddView', 'webappID=' + webappID));
}

function customSize(value)
{
    $('.customSize').hide();
    if(value == 'custom') $('.customSize').removeClass('hidden').show();
}

$(function()
{
    if($('a.iframe').length)
    {
        $("a.iframe").modalTrigger({width:450, type:'iframe', afterHide:function()
        {
            var selfClose = $.cookie('selfClose');
            if(selfClose != 1) return;
            $(this).replaceWith("<input type='button' value='" + installed + "' disabled='disabled' style='color:gray' class='btn'>");
            $.cookie('selfClose', 0);
        }});
    }

    if($('a.webapp').length) $("a.webapp").modalTrigger({width:700, type:'iframe'});
    if($('a.apiapp').length) $("a.apiapp").modalTrigger({width:700, type:'iframe'});
    $('.runapp.popup').modalTrigger({width:700, type:'iframe'});
    $('.runapp:not(.popup)').modalTrigger({size:'fullscreen', type:'iframe', cssClass:'app-inside'});

    setSize($('#target').val());
    $('#target').change(function(){setSize($(this).val())});
    $('#size').change(function(){customSize($(this).val())});
    $('#modulemenu ul li').removeClass('active');
    if(typeof(module) == "string") $('#modulemenu ul li #submenu' + module).parent().addClass('active');
})
