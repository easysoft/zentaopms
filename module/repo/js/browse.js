/**
 * Get download.
 *
 * @access public
 * @return void
 */
function getDownload()
{
    var content = "<div class='main-col'>";
    content += "<div class='center-block'>";
    content += "<table class='table table-borderless'>";
    if(cloneUrl.svn)  content += getCloneHtml(lang.cloneUrl,  cloneUrl.svn);
    if(cloneUrl.ssh)  content += getCloneHtml(lang.sshClone,  cloneUrl.ssh);
    if(cloneUrl.http) content += getCloneHtml(lang.httpClone, cloneUrl.http);

    content += "<tr>";
    content += "<td><button type='button' class='btn download-btn'><i class='icon-down-circle'></i> <span>" + lang.downloadZip + "</span></button></td>";
    content += "<td>";
    content += "</td>";
    content += "</tr>";
    content += "</table>";
    content += "</div>";
    content += "</div>";
    return content;
}

/**
 * Get html of clone button.
 *
 * @param  cloneLang $cloneLang
 * @param  url $url
 * @access public
 * @return void
 */
function getCloneHtml(cloneLang, url)
{
    var content = '';
    content += "<tr>";
    content += "<th>" + cloneLang + "</th>";
    content += "</tr>";
    content += "<tr>";
    content += "<td><input type='input' class='form-control' value='" + url + "' readonly></td>";
    content += "<td><button type='button' class='btn copy-btn'><i class='icon-common-copy icon-copy' title='" + lang.copy +  "'></i></button></td>";
    content += "</tr>";
    return content;
}

$(function()
{
    /* Init popover. */
    var options = {
        container: 'body',
        content: getDownload(),
        html: true,
        placement: 'bottom',
        template: '<div class="popover"><h3 class="popover-title"></h3><div class="popover-content"></div></div',
        tipClass: 'download-popover',
        trigger: 'manual'
    };
    $('#downloadCode').popover(options);

    $('#downloadCode').click(function()
    {
        if($('.download-popover').css('display') == 'block')
        {
            $('#downloadCode').popover('hide');
        }
        else
        {
            $('#downloadCode').popover('show');

            /* Set popover area. */
            var left = parseFloat($('.download-popover').css('left')) - 155;
            $('.download-popover').css('left', left + 'px')
        }
    })

    $('.copy-btn').live('click', function()
    {
        var copyText = $(this).parent().parent().find('input');
        copyText .select();
        document.execCommand("Copy");
        alert(lang.copied);
    })

    $('.download-btn').live('click', function()
    {
        var link = createLink('repo', 'downloadCode', 'repoID=' + repoID);
        window.open(link);
    })
})
