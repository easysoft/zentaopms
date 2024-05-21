window.renderStoryCell = function(result, info)
{
    const story = info.row.data;
    if(info.col.name == 'title' && result)
    {
        let html = '';
        let gradeLabel = (showGrade || story.grade >= 2) ? grades[story.grade] : '';
        if(gradeLabel) html += "<span class='label gray-pale rounded-xl'>" + gradeLabel + "</span>";
        if(html) result.unshift({html});
    }

    return result;
};
