$(function()
{
    var $xuanAction = "<div class='xuancard-actions fixed'>";

    if(confirmed != 1) $xuanAction += "<a href='" + confirmLink + "'" + " target='_blank'" + " class='btn btn-link'" + '>' + "<i class='icon-bug-confirmBug icon-search'></i>" + " <span class='text'>" + confirmBug + "</span>" + "</a>"; 
    $xuanAction += "<a href='" + assignLink + "'" + " target='_blank'" + " class='btn btn-link'" + '>' + "<i class='icon-hand-right'></i>" + " <span class='text'>" + assignTo + "</span>" + "</a>"; 
    if(status == 'active') $xuanAction += '<a href="' + resolveLink + '"' + " target='_blank'" + ' class="btn btn-link">' + '<i class="icon-bug-resolve icon-checked"></i>' + ' <span class="text">' + resolve + "</span>" + "</a>"; 
    $xuanAction += "<a href='" + editLink + "'" + " target='_blank' title=" + edit + " class='btn btn-link'" + '>' + "<i class='icon-edit'></i>" + "</a>";

    $xuanAction += '</div>';
    $('#footer').replaceWith($xuanAction);
})
