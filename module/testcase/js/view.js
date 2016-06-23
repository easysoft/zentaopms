$(document).ready(function() 
{
    if(config.onlybody != 'yes')$(".runCase").modalTrigger({width:900, type:'iframe', afterHide:function(){parent.location.href=parent.location.href;}});
    if(config.onlybody != 'yes')$(".results").modalTrigger({width:900, type:'iframe'});
})
