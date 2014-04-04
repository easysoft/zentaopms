// if($('a.iframe').size()) $("a.iframe").colorbox({width:800, height:400, iframe:true, transition:'none', scrolling:true});
// if($('a.manual').size()) $("a.manual").colorbox({width:1024, height:600, iframe:true, transition:'none', scrolling:false});
$(function()
{
    $("a.extension").modalTrigger({width:1024, height:600, type:'iframe'});
})
