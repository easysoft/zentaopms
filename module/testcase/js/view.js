$(document).ready(function() 
{
    if(onlybody != 'yes')$(".runCase").colorbox({width:900, height:550, iframe:true, transition:'none', onCleanup:function(){parent.location.href=parent.location.href;}});
    if(onlybody != 'yes')$(".results").colorbox({width:900, height:550, iframe:true, transition:'none'});
})
