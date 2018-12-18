$(function()
{
    var $xuanAction = "<div class='xuancard-actions fixed'>";

    if(status != 'closed') $xuanAction += "<a href='" + changeLink + "'" + " target='_blank'" + " class='btn btn-link'" + '>' + "<i class='icon-story-change icon-fork'></i>" + " <span class='text'>" + change + "</span>" + "</a>";
    if(status == 'draft')  $xuanAction += "<a href='" + reviewLink + "'" + " target='_blank'" + " class='btn btn-link'" + '>' + "<i class='icon-glasses'></i>" + " <span class='text'>" + review + "</span>" + "</a>";
    $xuanAction += "<a href='" + editLink + "'" + " target='_blank' title=" + edit + " class='btn btn-link'" + '>' + "<i class='icon-edit'></i>" + "</a>";
    if(status != 'closed') $xuanAction += '<a href="' + closeLink  + '"' + " target='_blank'" + ' class="btn btn-link">' + '<i class="icon-story-close icon-off"></i>' + ' <span class="text">' + close + "</span>" + "</a>";
    if(status == 'closed') $xuanAction += '<a href="' + activateLink  + '"' + " target='_blank'" + ' class="btn btn-link">' + '<i class="icon-story-activate icon-magic"></i>' + ' <span class="text">' + activate + "</span>" + "</a>";

    $xuanAction += '</div>';
    $('#footer').replaceWith($xuanAction);
})
