$(function() 
{ 
    $("#projectbox").tabs("#projectbox div.pane", {tabs: 'h2', effect: 'fade', initialIndex: 0});
    $("#productbox").tabs("#productbox div.pane", {tabs: 'h2', effect: 'fade', initialIndex: 0});

    /* Set the heights of every block to keep them same height. */
    row1Height = $('#row1').height() - 10;
    row2Height = $('#row2').height() - 10;
    row2Height = row2Height > 200 ? row2Height : 200;   // Min height is 200px.
    $('#row1 .block').each(function(){$(this).css('height', row1Height);})
    $('#row2 .block').each(function(){$(this).css('height', row2Height);})

    /* Set the height of charts. */
    var tabTitleHeight = 29;    // The tab-title height.
    var linkHeight     = 10     // The product or project link height.
    $('#projectbox .chartDiv').each(function()
    {
        chartHeight = row1Height - tabTitleHeight * projectCounts - linkHeight;
        $(this).css('height', chartHeight);
    })

    $('#productbox .chartDiv').each(function()
    {
        chartHeight = row1Height - tabTitleHeight * productCounts - linkHeight;
        $(this).css('height', chartHeight);
    })
});
