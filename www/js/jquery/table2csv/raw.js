/**
 *  see http://www.kunalbabre.com/projects/table2CSV.php;
 */
jQuery.fn.table2CSV = function(options) {
    var options = jQuery.extend({
        separator: ',',
        header: [],
        delivery: 'popup', // popup, value
        title: 'Please input a file name:' 
    },
    options);

    var csvData = [];
    var headerArr = [];
    var el = this;

    //header
    var numCols = options.header.length;
    var tmpRow = []; // construct header avalible array
    var column2Skip = -1;    // the column to skip.

    if (numCols > 0) {
        for (var i = 0; i < numCols; i++) {
            tmpRow[tmpRow.length] = formatData(options.header[i]);
        }
    } else {
        var i = 0;
        $(el).filter(':visible').find('th').each(function() {
            if ($(this).css('display') != 'none' && $(this).attr('class').indexOf("sorter:false") == -1 )
            {
                tmpRow[tmpRow.length] = formatData($(this).html());
            }
            if($(this).attr('class').indexOf("sorter:false") > 0)
            {
                column2Skip = i;
            }
            i ++;

        });
    }

    row2CSV(tmpRow);

    // actual data
    $(el).find('tr').each(function() {
        var tmpRow = [];
        var i = 0;
        $(this).filter(':visible').find('td').each(function() {
            if(i == column2Skip) return;
            if ($(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData($(this).html());
            i ++;
        });
        row2CSV(tmpRow);
    });
    if (options.delivery == 'popup') {
        var mydata = csvData.join('\n');
        return popup(mydata);
    } else {
        var mydata = csvData.join('\n');
        return mydata;
    }

    function row2CSV(tmpRow) {
        var tmp = tmpRow.join('') // to remove any blank rows
        // alert(tmp);
        if (tmpRow.length > 0 && tmp != '') {
            var mystr = tmpRow.join(options.separator);
            csvData[csvData.length] = mystr;
        }
    }
    function formatData(input) {
        // replace " with “
        var regexp = new RegExp(/["]/g);
        var output = input.replace(regexp, "“");
        //HTML
        var regexp = new RegExp(/\<[^\<]+\>/g);
        var output = output.replace(regexp, "");
        // space
        var regexp = new RegExp(/[ ]/g);
        var output = output.replace(regexp, "");
        // break
        var regexp = new RegExp(/[\r\n]/g);
        var output = output.replace(regexp, "");
        if (output == "") return '';
        return '"' + output + '"';
    }

    function popup(data) {
      jPrompt(options.title, '', '', function(r) 
      {
          if(!r) return;
          fileName = r;
          agent      = $.browser.msie ? 'ie' : 'notie';
          exportLink = createLink('file', 'export2csv', 'agent=' + agent);
          $("#exporter").html('<form id="exportform" action="' + exportLink + '" method="post" target="hiddenwin"><input type="hidden" name="fileName" value="' + fileName + '" /><input type="hidden" id="csvData" name="csvData" /></form>');
          $("#csvData").val(data);
          $("#exportform").submit().remove();
          $("#exporter").html('');
          return true; 
      });
    }
};
