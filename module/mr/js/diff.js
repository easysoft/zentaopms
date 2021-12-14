$(document).ready(function()
{
    $("#inline").click(function(){$('#arrange').val('inline');this.form.submit();});
    $("#appose").click(function(){$('#arrange').val('appose');this.form.submit();});
    $(".label-exchange").click(function(){ $('#exchange').submit();});
});

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

$(document).ready(function()
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
});
