$(function() 
{ 
    /* Set the heights of every block to keep them same height. */
    projectBoxHeight = $('#projectbox').height();
    productBoxHeight = $('#productbox').height();
    if(projectBoxHeight < 180) $('#projectbox').css('height', 180);
    if(productBoxHeight < 180) $('#productbox').css('height', 180);

    row2Height = $('#row2').height() - 10;
    row2Height = row2Height > 200 ? row2Height : 200;
    $('#row2 .block').each(function(){$(this).css('height', row2Height);})

    $('.projectline').each(function()
    {
        $(this).sparkline('html', {height:'25px'});
    })
});
