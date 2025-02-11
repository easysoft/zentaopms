 window.renderCell = function(result, info)
  {
      if(info.col.name == 'title' && result)
      {
          let html = '';
          let gradeLabel = '';

          const story    = info.row.data;
          const gradeMap = gradeGroup[story.type] || {};

          gradeLabel = gradeMap[story.grade];
          html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";
          if(html) result.unshift({html});
      }

      return result;
  }
