$(document).ready(function()
{
    var $bugFormRow = $('<tr class="action-row"><th></th><td class="action-cell"></td></tr>');
    var $bugForm = $('#bugForm');
    $bugFormRow.find('td').append($bugForm.removeClass('hide'));
    var $bugPanel = $('#bugPanel');
    var $commentCell = $('#commentCell');
    var $pre = $('.repoCode .content pre');
    var $bugsPreview = $('#bugsPreview');
    var hidePreview;
    var $bugsPreviewMenu = $('#bugsPreview').children('.dropdown-menu');
    var rowTip = $('#rowTip').html();

    if($pre.length)
    {
        hljs.initHighlightingOnLoad();
        var content = hljs.highlight($pre.attr('class'), $pre.text());
        var code = '', line;
        var arr = content.value.split(/\r\n|[\n\v\f\r\x85\u2028\u2029]/);
        for(var i = 0 ; i < arr.length ; i++)
        {
            line = i + 1;
            code += "<tr data-line='" + line +"'><th id='L" + line + "'><div class='comment-btn view'><span class='icon-wrapper'><i class='icon-plus'></i></span></div>" + line + "</th><td>" + (arr[i] || '&nbsp;') + rowTip + "</td></tr>";
        }
        $bugForm.find('input[name="begin"], input[name="end"]').attr('max', line);
        $pre.html("<table><tbody>" + code + "</tbody></table>");
    }

    var $rows = $pre.find('tr');
    var highlight = function($e)
    {
        $('.highlight').removeClass('highlight');
        $e.addClass('highlight');
    };

    var createComment = function(comment, $comments)
    {
        var $comment = $commentCell.clone()
            .removeClass('hide')
            .attr('id', 'comment-' + comment.id)
            .attr('data-comment', comment.id);
        $comment.find('.realname').text(comment.realname);
        $comment.find('.comment-content').text(comment.comment);
        $comment.find('.date').text(comment.date);
        $comment.find('.edit').toggle(comment.edit);
        $comment.find('.comment-edit-form').attr('action', createLink('repo', 'editComment', 'commentID=' + comment.id));

        if($comments)
        {
            if(typeof $comments !== 'object') $comments = $('#bug-' + $comments + ' .comments');
            ($comments.hasClass('comments') ? $comments : $comments.find('.comments')).append($comment);
        }

        return $comment;
    };

    var createBug = function(bug, line, $commentRow, show)
    {
        var commentCount, j;
        var $bug = $bugPanel.clone().removeClass('hide').attr('id', 'bug-' + bug.id).attr('data-bug', bug.id);
        $bug.find('.bugid').text(bug.id);
        $bug.find('.realname').text(bug.realname);
        $bug.find('.openedDate').text(bug.openedDate);
        $bug.find('.title').text(bug.title);
        $bug.find('.steps').toggle(bug.steps != '').html(bug.steps);
        $bug.find('.edit').toggle(bug.edit);
        $bug.find('.code-lines').text(bug.lines);
        $bug.find('.delete').toggle(bug.delete);
        $bug.find('input[name="objectID"]').val(bug.id);
        $bug.find('.bug-edit-form').attr('action', createLink('repo', 'editBug', 'bugID=' + bug.id));
        $bug.find('a.view-bug').attr('href', createLink('bug', 'view', "bugID=" + bug.id));
        $bug.data('data', bug);
        $bug.toggleClass('show', show > 1);
        if(show > 2) highlight($bug);
        
        if(bug.comments)
        {
            commentCount = bug.comments.length;
            $bugComments = $bug.find('.comments');
            for(j = 0; j < commentCount; j++)
            {
                createComment(bug.comments[j], $bugComments);
            }
        }

        if(!line && bug.line) line = bug.line;
        if(line)
        {
            var $row = $rows.filter('[data-line="' + line + '"]').first();
            if(!$commentRow)
            {
                $commentRow = $row.next('tr');
                if(!$commentRow.hasClass('comment-row'))
                {
                    $commentRow = $('<tr class="comment-row"><th></th><td class="comment-cell"><div class="comment-list"></div></td></tr>');
                    $row.after($commentRow);
                }
            }
            $row.addClass('commented');
            ($commentRow.hasClass('comment-list') ? $commentRow : $commentRow.find('.comment-list')).append($bug);

            if(show && $commentRow.hasClass('comment-row')) $commentRow.addClass('show');
        }

        return $bug;
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

    $rows.hover(function()
    {
        var $this = $(this);
        $this.addClass("over");
    },
    function()
    {
        $(this).removeClass("over");
    });

    $pre.on('click', '.comment-btn', function(e)
    {
        $rows.removeClass('selected');
        var $row  = $(this).closest('tr');
        if($pre.hasClass('with-action-row') && $row.hasClass('with-action-row'))
        {
            $pre.removeClass('with-action-row');
        }
        else
        {
            $pre.addClass('with-action-row');
            var line = $row.data('line');
            if(!$row.hasClass('with-action-row'))
            {
                $rows.removeClass('with-action-row')
                $row.addClass('with-action-row');

                $bugForm.find('input[name="begin"]').val(line);
                $bugForm.find('input[name="end"]').attr('min', line).val(line);
                $bugForm.find('select#assignedTo').val(blamePairs[line]);
                $bugForm.find('select#assignedTo').trigger("chosen:updated");

                var $nextRow = $row.next('tr');
                ($nextRow.hasClass('comment-row') ? $nextRow : $row).after($bugFormRow);

                KindEditor.remove('#commentText');
                $('#commentText').kindeditor();

                var getCommiterLink = createLink('repo', 'ajaxgetcommitter', 'repoID=' + repoID + "&entry=&revision=" + revision + "&line=" + line);
                var connector = getCommiterLink.indexOf('&') >= 0 ? '&' : '?';
                getCommiterLink = getCommiterLink + connector + 'entry=' + file;
                $.ajax({url: getCommiterLink}).done(function(responseText)
                {
                    $bugForm.find('#assignedTo').val(responseText).trigger("chosen:updated");
                });
            }
            $row.addClass('selected');

            highlight($bugForm);
            $bugForm.find('input[name="title"]').focus();
        }

        e.stopPropagation();
    }).on('click', '.bugCancel', function()
    {
        $rows.removeClass('selected');
        $pre.removeClass('with-action-row');
    }).on('click', '.bugEdit', function(e)
    {
        var $panelBug = $(this).closest('.panel-bug');

        if($panelBug.hasClass('show-edit-form'))
        {
            $panelBug.removeClass('show-edit-form');
            e.stopPropagation();
            return;
        }

        $panelBug.addClass('show show-edit-form').find('input[name="commentText"]').val($panelBug.find('.title').first().text()).focus();
        e.stopPropagation();
        return false;
    }).on('submit', '.bug-edit-form', function()
    {
        var $form = $(this);
        $(this).ajaxSubmit(
        {
            success:function(text)
            {
                var $bug = $form.closest('.panel-bug');
                $bug.find('.title').text(text);
                $bug.removeClass('show-edit-form');
            },
            beforeSubmit:function(formData, jqForm)
            {
                var form = jqForm[0];
                if(!form.commentText.value)
                {
                    alert(contentError);
                    return false;
                }
            }
        });
        return false;
    }).on('click', '.bugEditCancel', function()
    {
        $(this).closest('.panel-bug').removeClass('show-edit-form');
    }).on('click', '.bugDelete', function(e)
    {
        var $bug = $(this).closest('.panel-bug');
        if(!$bug.length) return;

        if(confirm(confirmDelete)) 
        {
            var link  = createLink('repo', 'deleteBug', 'bugID=' + $bug.data('bug') + '&confirm=yes');
            $.get(link, function(data)
            {
                if(data == 'deleted') 
                {
                    var $commentRow = $bug.closest('.comment-row');
                    if($commentRow.find('.panel-bug').length === 1)
                    {
                        $commentRow.removeClass('show').prev('tr').removeClass('commented');
                    }
                    $bug.remove();
                }
            });
        }
        e.stopPropagation();
        return false;
    }).on('click', '.addComment', function()
    {
        $(this).closest('.panel-bug').addClass('show-form').find('.commentForm textarea').focus();
    }).on('click', '.commentCancel', function()
    {
        $(this).closest('.panel-bug').removeClass('show-form');
    }).on('submit', '.commentForm', function()
    {
        var $form = $(this);
        $form.ajaxSubmit(
        {
            success:function(json)
            {
                var $panelBug = $form.closest('.panel-bug');
                $form.find('textarea').val('');
                $panelBug.removeClass('show-form');
                createComment($.parseJSON(json), $panelBug.data('bug'));
            },
            beforeSubmit:function(formData, jqForm)
            {
                var form = jqForm[0];
                if(!form.comment.value)
                {
                    alert(commentError);
                    return false;
                }
            }
        });
        return false;
    }).on('click', '.commentEdit', function()
    {
        var $comment = $(this).closest('.comment');

        if($comment.hasClass('show-form'))
        {
            $comment.removeClass('show-form');
            return;
        }
        $comment.addClass('show-form').find('textarea').val($comment.find('.comment-content').text()).focus();
    }).on('click', '.commentEditCancel', function()
    {
        $(this).closest('.comment').removeClass('show-form');
    }).on('submit', '.comment-edit-form', function()
    {
        var $form = $(this);
        $form.ajaxSubmit(
        {
            success:function(html)
            {
                var $comment = $form.closest('.comment');
                $comment.find('.comment-content').html(html);
                $comment.removeClass('show-form');
            },
            beforeSubmit:function(formData, jqForm)
            {
                var form = jqForm[0];
                if(!form.commentText.value)
                {
                    alert(contentError);
                    return false;
                }
            }
        });
        return false;
    }).on('click', '.commentDelete', function()
    {
        var $container = $(this).closest('.commentContainer');
        if(!$container.length) return;

        if(confirm(confirmDeleteComment)) 
        {
            var commentID = $container.data('comment');
            var link      = createLink('repo', 'deleteComment', 'commentID=' + commentID + '&confirm=yes');

            $.get(link, function(data)
            {
                if(data == 'deleted')
                {
                    var $commentRow = $container.closest('.comment-row');
                    if($commentRow.find('.bugContainer, .commentContainer').length === 1)
                    {
                        $commentRow.removeClass('show').prev('tr').removeClass('commented');
                    }
                    $container.remove();
                }
            });
        }
        return false;
    }).on('click', 'tr.commented', function()
    {
        toggleComment($(this));
    }).on('click', '.panel-bug > .panel-heading', function()
    {
        $(this).closest('.panel-bug').toggleClass('show');
    }).on('mouseenter', 'tr.commented td .preview-icon', function(e)
    {
        var $cell = $(this).closest('td');
        var $row = $cell.closest('tr');
        var $commentRow = $row.next('tr');

        var $bugs = $commentRow.find('.panel-bug'), line = '?';
        $bugsPreviewMenu.children('li:not(.dropdown-header)').remove();
        $bugsPreviewMenu.find('.bug-count').text($bugs.length);
        $bugsPreviewMenu.find('.comment-count').text($commentRow.find('.comment').length);
        $bugs.each(function()
        {
            var bug = $(this).data('data');
            line = bug.line;
            $bugsPreviewMenu.append('<li><a href="javascript:;" data-id="#bug-' + bug.id + '"><small class="text-muted">#' + bug.id + '</small> ' + bug.title + '</a></li>');
        });
        $bugsPreviewMenu.find('.code-line').text(line);

        $bugsPreview.prependTo($cell);
        clearTimeout(hidePreview);
        $bugsPreviewMenu.css({top: 0-$bugsPreviewMenu.outerHeight(), left: Math.max(0, e.offsetX-$bugsPreviewMenu.outerWidth())}).addClass('show');
        setTimeout(function(){$bugsPreviewMenu.addClass('in');}, 50);
    }).on('mouseleave', 'tr.commented td', function()
    {
        $bugsPreviewMenu.removeClass('in');
        hidePreview = setTimeout(function(){$bugsPreviewMenu.removeClass('show');}, 200);
    });

    $bugsPreviewMenu.on('click', 'li', function(e)
    {
        var $bug = $($(this).find('a').data('id'));
        if($bug.length)
        {

            $bug.addClass('show');
            toggleComment($bug.closest('tr.comment-row'), true);
            highlight($bug);

            $bugsPreviewMenu.removeClass('in');
            hidePreview = setTimeout(function(){$bugsPreviewMenu.removeClass('show');}, 200);
        }
        e.stopPropagation();
    });

    $bugForm.submit(function()
    {
        $(this).ajaxSubmit(
        {
            success:function(json)
            {
                json = $.parseJSON(json);
                if(json.result == 'fail')
                {
                   alert(json.message);
                   return false;
                }

                createBug(json, null, null, 3);
                $pre.removeClass('with-action-row');
                $pre.find('tr.with-action-row.selected').removeClass('selected');
                $bugForm.find('#title').val('');
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
                    $bugForm.find('input[name="title"]').focus();
                    return false;
                }
            }
        });
        return false;
    }).on('change', 'input[name="begin"]', function()
    {
        var begin = $(this).val();
        var $end = $bugForm.find('input[name="end"]').attr('min', begin);
        if(parseInt($end.val()) < parseInt(begin)) $end.val(begin);
        $bugForm.find('select#assignedTo').val(blamePairs[begin]);
        $bugForm.find('select#assignedTo').trigger("chosen:updated");
    });

    if(bugs)
    {
        var lineBugs, bugsCount, i;
        for(var line in bugs)
        {
            if(line)
            { 
                lineBugs = bugs[line];
                bugsCount = lineBugs.length;

                for(i = 0; i < bugsCount; i++)
                {
                    createBug(lineBugs[i], line);
                }
            }
        }
    }

    setTimeout(anchor, 200);

    $(document).on('click', function()
    {
        $('.highlight').removeClass('highlight');
    });

    function anchor()
    {
        var hash  = window.location.hash;
        if(hash)
        {
            var $row = $(hash).closest('tr');
            if($row.length)
            {
                var anchor = $row.offset().top;

                $('body,html').animate({scrollTop:anchor - 50}, 500);

                $row.addClass('highlight');
                if($row.hasClass('commented'))
                {
                    var $commentRow = $row.next('tr');
                    if($commentRow.hasClass('comment-row'))
                    {
                        toggleComment($row, true);
                        $commentRow.addClass('highlight').find('.panel-bug').first().addClass('show');
                    }
                }
            }
        }
    }
});
