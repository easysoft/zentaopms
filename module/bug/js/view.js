$(document).ready(function()
{
    limitIframeLevel();
});

$('#tostory').click(function()
{
    if(!confirm(confrimToStory)) return false;
});
