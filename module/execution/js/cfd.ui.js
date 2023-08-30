/**
 * Change display type.
 *
 * @access public
 * @return void
 */
function changeType()
{
    loadPage($.createLink('execution', 'cfd', 'executionID=' + executionID + '&type=' + $('input[name=type]').val()));
}

window.randTipInfo = function(params)
{
    var newParams     = [];
    var tooltipString = [];
    newParams = params.reverse();
    newParams.forEach((p) => {
        const cont = p.marker + " " + p.seriesName + ": " + p.value + "<br/>";
        tooltipString.push(cont);
    });
    return tooltipString.join("");
}

$(function()
{
    if($('#cfdHover').length) new zui.Tooltip('#cfdHover', {title: {html: cfdTip}, trigger: 'hover', placement: 'bottom'});
})
