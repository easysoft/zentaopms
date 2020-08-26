$('#begin, #end, #repo').change(function()
{
    var begin   = $('#begin').val();
    var end     = $('#end').val();
    var repo    = $('#repo').val();

    if(begin.indexOf('-') != -1) 
    {   
        var beginarray = begin.split("-");
        var begin = ''; 
        for(i = 0; i < beginarray.length; i++) begin = begin + beginarray[i];
    }   
    if(end.indexOf('-') != -1) 
    {   
        var endarray = end.split("-");
        var end = ''; 
        for(i = 0 ; i < endarray.length ; i++) end = end + endarray[i];
    }

    location.href = createLink('design', 'commit', "designID=" + designID + '&repoID=' + repo + '&begin=' + begin + '&end=' + end);
})
