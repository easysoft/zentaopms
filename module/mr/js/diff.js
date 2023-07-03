$(document).ready(function()
{
    if(!browser || browser == 'ie')
    {
        $("#inline").click(function(){$('#arrange').val('inline');this.form.submit();});
        $("#appose").click(function(){$('#arrange').val('appose');this.form.submit();});
    }
    $(".label-exchange").click(function(){ $('#exchange').submit();});
});

var distance = 0;

/**
 * Aarrow tabs area.
 *
 * @param  string domID
 * @param  number shift 1|-1
 * @param  bool   hideRightBtn
 * @access public
 * @return void
 */
function arrowTabs(domID, shift, hideRightBtn)
{
    if($('#' + domID).html() == '') return;

    $('.btn-right, .btn-left').show();
    if(hideRightBtn) $('.btn-right').hide();

    var tabItemWidth = $('#' + domID + ' > .tabs-navbar > .nav-tabs')[0].clientWidth;
    var tabsWidth    = $('#' + domID + '')[0].clientWidth;
    if(tabItemWidth < tabsWidth)
    {
        $('.btn-right, .btn-left').hide();
        return;
    }

    distance += tabsWidth * shift * 0.2;
    if(distance > 0) distance = 0;
    if(distance == 0)
    {
        $('.btn-left').hide();
    }

    if((tabItemWidth + distance) < tabsWidth * 0.75)
    {
        $('.btn-right').hide();
        return arrowTabs(domID, 1, true);
    }

    $('#' + domID + ' > .tabs-navbar > .tabs-nav')[0].style.transform = 'translateX('+ distance +'px)';
}

/**
 * Change encoding.
 *
 * @param  string $encoding
 * @access public
 * @return void
 */
function changeEncoding(encoding)
{
    $('#encoding').val(encoding);
    $('#encoding').parents('form').submit();
}

/**
 *  Html code decode.
 *
 * @param  string str
 * @access public
 * @return string
 */
function htmlspecialchars_decode(str)
{
    str = str.replace(/&amp;/g, '&');
    str = str.replace(/&lt;/g, '<');
    str = str.replace(/&gt;/g, '>');
    str = str.replace(/&quot;/g, '"');
    str = str.replace(/&#039;/g, "'");
    return str;
}

/**
 * Get diffs by file name.
 *
 * @param  string fileName
 * @access public
 * @return object
 */
function getDiffs(fileName)
{
    if(fileName.indexOf('./') === 0) fileName = fileName.substring(2);

    var result = {
        'code': {'new': '', 'old': ''},
        'line': {'new': [], 'old': []}
    };
    $.each(diffs, function(i, diff)
    {
        if(diff.fileName == fileName)
        {
            $.each(diff.contents, function(c, content)
            {
                if(typeof content.lines != 'object') return result;

                var lines = content.lines;
                $.each(lines, function(l, code)
                {
                    var subIndex = code.type == 'all' ? 1 : 2;
                    var lineCode = htmlspecialchars_decode(code.line.substring(subIndex)) + "\n";

                    if(code.type == 'new')
                    {
                        result.code.new += lineCode;
                        result.line.new.push(code.newlc);
                    }
                    else if(code.type == 'old')
                    {
                        result.code.old += lineCode;
                        result.line.old.push(code.oldlc);
                    }
                    else
                    {
                        result.code.new += lineCode;
                        result.code.old += lineCode;
                        result.line.new.push(code.newlc);
                        result.line.old.push(code.oldlc);
                    }
                });
            });
            return result;
        }
    });
    return result;
}

/**
 * Create file tab.
 *
 * @param  string filename
 * @param  string filepath
 * @access public
 * @return object
 */
function createTab(filename, filepath)
{
    $('[data-path="' + decodeURIComponent(filepath) + '"]').closest('li').addClass('selected');
    var tabID = Base64.encode(filepath).replace(/=/g, '-');
    return {
        title: filename,
        id:    tabID,
        type:  'iframe',
        url:   createLink('repo', 'ajaxGetDiffEditorContent', urlParams.replace('%s', Base64.encode(encodeURIComponent(filepath))))
    };
}

/**
 * Load link object page.
 *
 * @param  string $link
 * @access public
 * @return void
 */
function loadLinkPage(link)
{
    $('#linkObject').attr('href', link);
    $('#linkObject').click()
}

$(document).ready(function()
{
    if(browser != 'ie')
    {
        var diffAppose = false;
        $('.dropdown #inline').hide();

        if(!browser || browser == 'ie')
        {
            $("#inline").click(function(){$('#arrange').val('inline');this.form.submit();});
            $("#appose").click(function(){$('#arrange').val('appose');this.form.submit();});
        }
        $(".label-exchange").click(function(){ $('#exchange').submit();});

        $('.btn-left').click(function()  {arrowTabs('fileTabs', 1);});
        $('.btn-right').click(function() {arrowTabs('fileTabs', -2);});
        if(file) $('#fileTabs').tabs({tabs: [createTab(file['basename'], entry)]});

        /**
         * Set pane height.
         *
         * @access public
         * @return void
         */
        function setHeight()
        {
            var paneHeight = $(window).height() - 120;
            $('#fileTabs .tab-pane').css('height', paneHeight + 'px')
            $('#filesTree').css('height', paneHeight + 45)
        }
        setHeight();

        $(document).on('click', '.repoFileName', function()
        {
            var path  = $(this).data('path');
            var name  = $(this).text();
            var $tabs = $('#fileTabs').data('zui.tabs');

            $tabs.open(createTab(name, path));
            setHeight();
            arrowTabs('fileTabs', -2);
        });

        /* Remove file path for opened files. */
        $('#fileTabs').on('onClose', function(event, tab) {
            var filepath = decodeURIComponent(Base64.decode(tab.id.replace(/-/g, '=')));
            var index    = openedFiles.indexOf(filepath);
            if(index > -1)
            {
                openedFiles.splice(index, 1)
                $('[data-path="' + filepath + '"]').closest('li').removeClass('selected');
            }

            if(index == openedFiles.length) arrowTabs('fileTabs', -2);
        });

        /* Append file path into the title. */
        $('#fileTabs').on('onLoad', function(event, tab) {
            var filepath = decodeURIComponent(Base64.decode(tab.id.replace(/-/g, '=')));
            $('#tab-nav-item-' + tab.id).attr('title', filepath);
            document.getElementById('tab-iframe-' + tab.id).contentWindow.updateEditorInline(diffAppose);

            if(openedFiles.indexOf(filepath) == -1) openedFiles.push(filepath);
        });

        $('#fileTabs').on('onOpen', function(event, tab) {
            var filepath = decodeURIComponent(Base64.decode(tab.id.replace(/-/g, '=')));
            var index    = openedFiles.indexOf(filepath);
            if(index > -1) document.getElementById('tab-iframe-' + tab.id).contentWindow.updateEditorInline(diffAppose);
        });

        $('.inline-appose').on('click', function()
        {
            $('.inline-appose').hide();
            diffAppose = !diffAppose;
            if(diffAppose)
            {
                $('.dropdown #inline').show();
            }
            else
            {
                $('.dropdown #appose').show();
            }
            var type   = $(this).attr('id');
            var tabID  = $('.tab-nav-item.active').data('id');
            document.getElementById('tab-iframe-' + tabID).contentWindow.updateEditorInline(diffAppose);
            return;
        });
    }
    else
    {
        var $diffCode = $('.diff');
        var $rows     = $diffCode.find('tr');
        var rowTip    = $('#rowTip').html();
        var lastLine;
        $rows.each(function()
        {
            var $row = $(this);
            if(!$row.hasClass('empty'))
            {
                $row.children('th').first().prepend("<div class='comment-btn diff'><span class='icon-wrapper'><i class='icon-plus'></i></span></div>");
                $row.children('td').first().append(rowTip);
            }

            if(lastLine && !$row.data('line'))
            {
                $row.attr('data-line', lastLine);
            }
            else
            {
                lastLine = $row.data('line');
            }
        }).hover(function()
        {
            var $this = $(this);
            if($this.hasClass('empty')) return;
            $this.addClass("over");
        },
        function()
        {
            $(this).removeClass("over");
        });

        var isInline       = $.cookie('arrange') == 'inline';
        var $reviewFormRow = $('<tr class="action-row"><th></th>' + (isInline ? '<th></th><td class="action-cell"></td>' : '<td colspan="3" class="action-cell"></td>') + '</tr>');
        var $reviewForm    = $('#reviewForm');
        var $reviewPanel   = $('#reviewPanel');
        $reviewFormRow.find('td').append($reviewForm.removeClass('hide'));

        var highlight = function($e)
        {
            $('.highlight').removeClass('highlight');
            $e.addClass('highlight');
        };

        var createReview = function(review, line, show)
        {
            var $review = $reviewPanel.clone().removeClass('hide').attr('id', review.objectType + '-' + review.id);
            $review.find('.realname').text(review.realname);
            $review.find('.openedDate').text(review.openedDate);
            $review.find('.title').text(review.title);
            $review.find('.content').toggle(review.content != '').html(review.content);
            $review.find('.code-lines').text(review.lines);
            $review.find('input[name="objectID"]').val(review.id);
            $review.data(review);

            id  = review.objectType == 'bug' ? 'Bug' : 'Task';
            id += '#' + review.id;
            if(review.view) id = "<a href='" + createLink(review.objectType, 'view', 'id=' + review.id) +  "'>" + id + "</a>";
            $review.find('.title').closest('.panel-heading').find('.panel-actions').prepend(id);
            if(!review.delete) $review.find('.title').closest('.panel-heading').find('.panel-actions .reviewDelete').hide();

            $review.toggleClass('show', show > 1);
            if(show > 2) highlight($review);

            if(!line && review.line) line = review.line;
            if(line)
            {
                var $row = $rows.filter('[data-line="' + line + '"]').last();
                if(review.entry) $row = $('#diff[data-entry="' + review.entry + '"]').find('tr').filter('[data-line="' + line + '"]');
                if($row.length == 0) return false;

                $commentRow = $row.next('tr');
                if($row.hasClass('commented')) $commentRow = $row.nextAll('tr.comment-row').first();
                if(!$commentRow.hasClass('comment-row'))
                {
                    $commentRow = $('<tr class="comment-row"><th></th>' + (isInline ? '<th></th><td class="comment-cell"><div class="comment-list"></div></td>' : '<td colspan="3" class="comment-cell"><div class="comment-list"></div></td>') + '</tr>');
                    $row.addClass('commented').after($commentRow);
                }

                $commentRow.find('.comment-list').append($review);

                if(show && $commentRow.hasClass('comment-row')) $commentRow.addClass('show');
            }

            return $review;
        };

        var toggleComment = function($row, show)
        {
            var $commentRow;
            if($row.hasClass('comment-row'))
            {
                $commentRow = $row;
                $row = $commentRow.prev('tr');
                if($row.hasClass('action-row'))
                {
                    $row = $row.prev('tr');
                }
            }
            else
            {
                $commentRow = $row.next('tr');
                if($commentRow.hasClass('action-row'))
                {
                    $commentRow = $commentRow.next('tr');
                }
            }
            if(show === undefined)
            {
                show = !$row.hasClass('open');
            }
            if($row.hasClass('commented') && $commentRow.hasClass('comment-row'))
            {
                $commentRow.toggleClass('show', show);
                $row.toggleClass('open', show);
            }
        };

        $diffCode.on('click', '.comment-btn', function(e)
        {
            $rows.removeClass('selected');
            var $row  = $(this).closest('tr');
            if($diffCode.hasClass('with-action-row') && $row.hasClass('with-action-row'))
            {
                $diffCode.removeClass('with-action-row');
            }
            else
            {
                $diffCode.addClass('with-action-row');
                var line = $row.attr('data-line');
                if(!$row.hasClass('with-action-row'))
                {
                    $rows.removeClass('with-action-row')
                    $row.addClass('with-action-row');

                    $reviewForm.find('input[name="begin"]').val(line);
                    $reviewForm.find('input[name="end"]').attr('min', line).val(line);
                    $reviewForm.find('select#assignedTo').trigger("chosen:updated");
                    $reviewForm.find('input#entry').val($row.closest('table#diff').data('entry'));

                    $row.after($reviewFormRow);

                    KindEditor.remove('#commentText');
                    $('#commentText').kindeditor();
                }
                highlight($reviewForm);
                $reviewForm.find('input[name="title"]').focus();
                $row.addClass('selected');
            }
            e.stopPropagation();
        }).on('click', '.reviewDelete', function(e)
        {
            var $review = $(this).closest('.panel-review');
            if(!$review.length) return;

            if(confirm(confirmDelete))
            {
                var link = createLink($review.data('objectType'), 'delete', 'id=' + $review.data('id') + '&confirm=yes');
                $.get(link, function(data)
                {
                    var $commentRow = $review.closest('.comment-row');
                    if($commentRow.find('.panel-review').length === 1)
                    {
                        $commentRow.removeClass('show').prev('tr').removeClass('commented');
                    }
                    $review.remove();
                });
            }
            e.stopPropagation();
            return false;
        }).on('click', 'tr.commented', function()
        {
            toggleComment($(this));
        }).on('click', '.panel-review > .panel-heading', function()
        {
            $(this).closest('.panel-review').toggleClass('show');
        });

        $reviewForm.submit(function()
        {
            $(this).ajaxSubmit(
            {
                success:function(json)
                {
                    json = $.parseJSON(json);
                    if(json.result == 'fail')
                    {
                        var message = '';
                        if(typeof(json.message) != 'string')
                        {
                            for(i in json.message)
                            {
                                if(typeof(json.message[i]) == 'string')
                                {
                                    message += json.message[i] + '\n';
                                }
                                else
                                {
                                    for(j in json.message[i])
                                    {
                                        message += json.message[i][j] + '\n';
                                    }
                                }
                            }
                        }
                        else
                        {
                            message += json.message + '\n';
                        }
                        alert(message);
                        return false;
                    }

                    createReview(json, json.line, 3);
                    $diffCode.removeClass('with-action-row');
                    $diffCode.find('tr.with-action-row.selected').removeClass('selected');
                    $reviewForm.find('#title').val('');
                    KindEditor.html('#commentText', '');
                },
                beforeSubmit:function(formData, jqForm)
                {
                    var form = jqForm[0];
                    if(!form.product.value)
                    {
                        alert(productError);
                        return false;
                    }
                    if(!form.title.value)
                    {
                        alert(titleError);
                        $reviewForm.find('input[name="title"]').focus();
                        return false;
                    }
                }
            });
            return false;
        }).on('change', 'input[name="begin"]', function()
        {
            var begin = $(this).val();
            var $end = $reviewForm.find('input[name="end"]').attr('min', begin);
            if(parseInt($end.val()) < parseInt(begin))
            {
                $end.val(begin);
            }
        });

        if(reviews)
        {
            var lineReviews;
            for(var line in reviews)
            {
                if(line)
                {
                    lineReviews = reviews[line];
                    for(var i in lineReviews) createReview(lineReviews[i], line);
                }
            }
        }

        setTimeout(anchor, 200);

        $(document).on('click', function()
        {
            $('.highlight').removeClass('highlight');
        });

        /**
         * Anchor
         *
         * @access public
         * @return void
         */
        function anchor()
        {
            var hash = window.location.hash;
            if(!hash) return false;

            var line = hash.substr(1).replace('L', '');
            var $row = $('.diff tr[data-line="' + line +'"]').first();
            if($row.length) return false;

            var anchor = $row.offset().top;
            $('body,html').animate({scrollTop:anchor - 50}, 500);

            $row.addClass('highlight');
            if($row.hasClass('commented'))
            {
                toggleComment($row, true);
                var $commentRow = $row.next('tr');
                if($commentRow.hasClass('comment-row')) $commentRow.addClass('highlight');
            }
        }
    }
});
