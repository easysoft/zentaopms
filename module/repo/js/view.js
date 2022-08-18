$(document).ready(function()
{
    var $pre = $('.repoCode .content pre');
    var rowTip = '';
    if($('#rowTip').length > 0) rowTip = $('#rowTip').html();

    if($pre.length)
    {
        hljs.initHighlightingOnLoad();
        var content = hljs.highlight($pre.attr('class'), $pre.text());
        var code = '', line;
        var arr = content.value.split(/\r\n|[\n\v\f\r\x85\u2028\u2029]/);
        for(var i = 0 ; i < arr.length ; i++)
        {
            line = i + 1;
            code += "<tr data-line='" + line +"'><th id='L" + line + "'><span class='comment-btn view'><span class='icon-wrapper'><i class='icon-bug'></i><span class='label label-light label-badge bug-num'>0</span></span></span><span>" + line + "</span></th><td>" + (arr[i] || '&nbsp;') + rowTip +  "</td></tr>";
        }
        $pre.html("<table><tbody>" + code + "</tbody></table>");
    }
});
