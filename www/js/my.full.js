(function($)
{
    /* Tab session */
    if(!config.tabSession) return;
    /** Store current tab id */
    var _tid = '';

    /**
     * Get current tab id
     * @returns {string} Tab id
     */
    function getTid(){return _tid;}

    /**
     * Convert url with tab id
     * @param {string}  url
     * @param {string}  [tid]
     * @param {boolean} [force]
     * @returns {string} Tab id
     */
    function convertUrlWithTid(url, tid, force)
    {
        var link = $.parseLink(url);
        if(!link.moduleName) return url;

        tid = tid || _tid;
        if(!force && link.tid === tid) return url;

        link.tid = tid;
        return $.createLink(link);
    }

    /** Init */
    function init()
    {
        /* Check tid */
        if(window.parent !== window)
        {
            if(window.parent.$.tabSession) _tid = window.parent.$.tabSession.getTid();
        }
        else
        {
            var isIndexOrLoginPage = (config.currentModule === 'index' && config.currentMethod === 'index') || (config.currentModule === 'user' && config.currentMethod === 'login');
            var link = $.parseLink(window.location.href);

            _tid = sessionStorage.getItem('TID');
            if(!_tid)
            {
                if(link.tid && isIndexOrLoginPage)
                {
                    _tid = link.tid
                }

                if(!_tid)
                {
                    _tid = $.zui.uuid();
                    _tid = _tid.substr(_tid.length - 8);
                }
            }
            sessionStorage.setItem('TID', _tid);

            if(isIndexOrLoginPage && !link.tid)
            {
                window.location.href = convertUrlWithTid(window.location.href, _tid);
            }
        }

        $.tabSession =
        {
            getTid:            getTid,
            convertUrlWithTid: convertUrlWithTid,
        };

        /* Handle all links in page */
        $('a').each(function()
        {
            var $a         = $(this);
            var url        = $a.attr('href');
            var urlWithTid = convertUrlWithTid(url);

            if(urlWithTid !== url) $a.attr('href', urlWithTid);
        });
        $('[data-url]').each(function()
        {
            var $e         = $(this);
            var url        = $e.attr('data-url');
            var urlWithTid = convertUrlWithTid(url);
            if(urlWithTid !== url) $e.attr('data-url', urlWithTid);
        });

        if(config.debug > 2)
        {
            $(function()
            {
                $('#tid').prepend('<code class="bg-blue">localtid=' + _tid + '</code>');
            });
        }
    }

    init();

    /* Hide context menu when window is scroll. */
    $(window).on('scroll', function()
    {
        $.zui.ContextMenu.hide();
    });
}(jQuery));

/**
 * Set the ping url.
 *
 * @access public
 * @return void
 */
function setPing()
{
    $('#hiddenwin').attr('src', createLink('misc', 'ping'));
}

/**
 * Disable the submit button when submit form.
 *
 * @access public
 * @return void
 */
function setForm()
{
    var formClicked = false;
    $('form').submit(function()
    {
        submitObj   = $(this).find(':submit');
        if($(submitObj).size() >= 1)
        {
            var isBtn = submitObj.prop('tagName') == "BUTTON";
            submitLabel = isBtn ? $(submitObj).html() : $(submitObj).attr('value');
            $(submitObj).attr('disabled', 'disabled');
            var submitting = submitObj.attr('data-submitting') || lang.submitting;
            if(isBtn) submitObj.text(submitting);
            else $(submitObj).attr('value', submitting);
            formClicked = true;
        }
    });

    $("body").click(function()
    {
        if(formClicked)
        {
            $(submitObj).removeAttr('disabled');
            if(submitObj.prop('tagName') == "BUTTON")
            {
                submitObj.text(submitLabel);
            }
            else
            {
                $(submitObj).attr('value', submitLabel);
            }
            $(submitObj).removeClass('button-d');
        }
        formClicked = false;
    });
}

/**
 * Set form action and submit.
 *
 * @param  url    $actionLink
 * @param  string $hiddenwin 'hiddenwin'
 * @access public
 * @return void
 */
function setFormAction(actionLink, hiddenwin, obj)
{
    $form = typeof(obj) == 'undefined' ? $('form') : $(obj).closest('form');
    if(hiddenwin) $form.attr('target', hiddenwin);
    else $form.removeAttr('target');

    $form.attr('action', actionLink);

    // Check safari for bug #1000, see http://pms.zentao.net/bug-view-1000.html
    var userAgent = navigator.userAgent;
    var isSafari = userAgent.indexOf('AppleWebKit') > -1 && userAgent.indexOf('Safari') > -1 && userAgent.indexOf('Chrome') < 0;
    if(isSafari)
    {
        var idPreffix = 'checkbox-fix-' + $.zui.uuid();
        $form.find('[data-fix-checkbox]').remove();
        $form.find('input[type="checkbox"]:not(.rows-selector)').each(function()
        {
            var $checkbox = $(this);
            var checkboxId = idPreffix + $checkbox.val();
            $checkbox.clone().attr('data-fix-checkbox', checkboxId).css('display', 'none').after('<div id="' + checkboxId + '"/>').appendTo($form);
        });
    }
    $form.submit();
}

/**
 * Set the max with of image.
 *
 * @access public
 * @return void
 */
function setImageSize(image, maxWidth, maxHeight)
{
    var $image = $(image);
    if($image.parent().prop('tagName').toLowerCase() == 'a') return;

    /* If not set maxWidth, set it auto. */
    if(!maxWidth)
    {
        bodyWidth = $('body').width();
        maxWidth  = bodyWidth - 470; // The side bar's width is 336, and add some margins.
    }
    if(!maxHeight) maxHeight = $(top.window).height();

    setTimeout(function()
    {
        maxHeightStyle = $image.height() > 0 ? 'max-height:' + maxHeight + 'px' : '';
        if(!document.getElementsByClassName('xxc-embed').length && $image.width() > 0 && $image.width() > maxWidth) $image.attr('width', maxWidth);
        $image.wrap('<a href="' + $image.attr('src') + '" style="display:inline-block;position:relative;overflow:hidden;' + maxHeightStyle + '" target="_blank"></a>');
        if($image.height() > 0 && $image.height() > maxHeight) $image.closest('a').append("<a href='###' class='showMoreImage' onclick='showMoreImage(this)'>" + lang.expand + " <i class='icon-angle-down'></i></a>");
    }, 50);
}

/**
 * Show more image when image is too height.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function showMoreImage(obj)
{
    $(obj).parents('a').css('max-height', 'none');
    $(obj).remove();
}

/**
 * add one option of a select to another select.
 *
 * @param  string $SelectID
 * @param  string $TargetID
 * @access public
 * @return void
 */
function addItem(SelectID,TargetID)
{
    ItemList = document.getElementById(SelectID);
    Target   = document.getElementById(TargetID);
    for(var x = 0; x < ItemList.length; x++)
    {
        var opt = ItemList.options[x];
        if (opt.selected)
        {
            flag = true;
            for (var y=0;y<Target.length;y++)
            {
                var myopt = Target.options[y];
                if (myopt.value == opt.value)
                {
                    flag = false;
                }
            }
            if(flag)
            {
                Target.options[Target.options.length] = new Option(opt.text, opt.value, 0, 0);
            }
        }
    }
}

/**
 * Remove one selected option from a select.
 *
 * @param  string $SelectID
 * @access public
 * @return void
 */
function delItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=ItemList.length-1;x>=0;x--)
    {
        var opt = ItemList.options[x];
        if (opt.selected)
        {
            ItemList.options[x] = null;
        }
    }
}

/**
 * move one selected option up from a select.
 *
 * @param  string $SelectID
 * @access public
 * @return void
 */
function upItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=1;x<ItemList.length;x++)
    {
        var opt = ItemList.options[x];
        if(opt.selected)
        {
            tmpUpValue = ItemList.options[x-1].value;
            tmpUpText  = ItemList.options[x-1].text;
            ItemList.options[x-1].value = opt.value;
            ItemList.options[x-1].text  = opt.text;
            ItemList.options[x].value = tmpUpValue;
            ItemList.options[x].text  = tmpUpText;
            ItemList.options[x-1].selected = true;
            ItemList.options[x].selected = false;
            break;
        }
    }
}

/**
 * move one selected option down from a select.
 *
 * @param  string $SelectID
 * @access public
 * @return void
 */
function downItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=0;x<ItemList.length;x++)
    {
        var opt = ItemList.options[x];
        if(opt.selected)
        {
            tmpUpValue = ItemList.options[x+1].value;
            tmpUpText  = ItemList.options[x+1].text;
            ItemList.options[x+1].value = opt.value;
            ItemList.options[x+1].text  = opt.text;
            ItemList.options[x].value = tmpUpValue;
            ItemList.options[x].text  = tmpUpText;
            ItemList.options[x+1].selected = true;
            ItemList.options[x].selected = false;
            break;
        }
    }
}

/**
 * select all items of a select.
 *
 * @param  string $SelectID
 * @access public
 * @return void
 */
function selectItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=ItemList.length-1;x>=0;x--)
    {
        var opt = ItemList.options[x];
        opt.selected = true;
    }
}

/**
 * Delete item use ajax.
 *
 * @param  string url
 * @param  string replaceID
 * @param  string notice
 * @access public
 * @return void
 */
function ajaxDelete(url, replaceID, notice)
{
    if(confirm(notice))
    {
        $.ajax(
        {
            type:     'GET',
            url:      url,
            dataType: 'json',
            success:  function(data)
            {
                if(data.result == 'success')
                {
                    var $table = $('#' + replaceID).closest('[data-ride="table"]');
                    if($table.length)
                    {
                        var table = $table.data('zui.table');
                        if(table)
                        {
                            table.options.replaceId = replaceID;
                            return table.reload();
                        }
                    }
                    $.get(document.location.href, function(data)
                    {
                        if(!($(data).find('#' + replaceID).length)) location.reload();
                        $('#' + replaceID).html($(data).find('#' + replaceID).html());
                        if(typeof sortTable == 'function') sortTable();
                        $('#' + replaceID).find('[data-toggle=modal], a.iframe').modalTrigger();
                        if($('#' + replaceID).find('table.datatable').length) $('#' + replaceID).find('table.datatable').datatable();
                        $('.table-footer [data-ride=pager]').pager();
                    });
                }
                else if(data.result == 'fail' && typeof(data.message) == 'string')
                {
                    bootbox.alert(data.message);
                }
            }
        });
    }
}

/**
 * Judge the string is a integer number
 *
 * @access public
 * @return bool
 */
function isNum(s)
{
    if(s!=null)
    {
        var r, re;
        re = /\d*/i;
        r = s.match(re);
        return (r == s) ? true : false;
    }
    return false;
}

/**
 * Start cron.
 *
 * @access public
 * @return void
 */
function startCron(restart)
{
    if(typeof(restart) == 'undefined') restart = 0;
    $.ajax({type:"GET", timeout:100, url:createLink('cron', 'ajaxExec', 'restart=' + restart)});
}

function computePasswordStrength(password)
{
    if(password.length == 0) return 0;

    var strength = 0;
    var length   = password.length;

    var complexity  = new Array();
    for(i = 0; i < length; i++)
    {
        letter = password.charAt(i);
        var asc = letter.charCodeAt();
        if(asc >= 48 && asc <= 57)
        {
            complexity[0] = 1;
        }
        else if((asc >= 65 && asc <= 90))
        {
            complexity[1] = 2;
        }
        else if(asc >= 97 && asc <= 122)
        {
            complexity[2] = 4;
        }
        else
        {
            complexity[3] = 8;
        }
    }

    var sumComplexity = 0;
    for(i in complexity) sumComplexity += complexity[i];

    if((sumComplexity == 7 || sumComplexity == 15) && password.length >= 6) strength = 1;
    if(sumComplexity == 15 && password.length >= 10) strength = 2;

    return strength;
}

/**
 * Check onlybody page when it is not open in modal then location to on onlybody page.
 *
 * @access public
 * @return void
 */
function checkOnlybodyPage()
{
    if(self == parent)
    {
        href = location.href.replace('?onlybody=yes', '');
        location.href = href.replace('&onlybody=yes', '');
    }
}

/**
 * Fixed table head in list when scrolling.
 *
 * @param  string $tableID
 * @access public
 * @return void
 */
function fixedTheadOfList(tableID)
{
    if($(tableID).size() == 0) return false;
    if($(tableID).css('display') == 'none') return false;
    if($(tableID).find('thead').size() == 0) return false;

    fixTheadInit();
    $(window).scroll(fixThead);//Fix table head when scrolling.
    $('.side-handle').click(function(){setTimeout(fixTheadInit, 300);});//Fix table head if module tree is hidden or displayed.

    var tableWidth, theadOffset, fixedThead, $fixedThead;
    function fixThead()
    {
        theadOffset = $(tableID).find('thead').offset().top;
        $fixedThead = $(tableID).parent().find('.fixedTheadOfList');
        if($fixedThead.size() <= 0 &&theadOffset < $(window).scrollTop())
        {
            tableWidth  = $(tableID).width();
            fixedThead  = "<table class='fixedTheadOfList'><thead>" + $(tableID).find('thead').html() + '</thead></table>';
            $(tableID).before(fixedThead);
            $('.fixedTheadOfList').addClass($(tableID).attr('class')).width(tableWidth);
        }
        if($fixedThead.size() > 0 && theadOffset >= $(window).scrollTop()) $fixedThead.remove();
    }
    function fixTheadInit()
    {
        $fixedThead = $(tableID).parent().find('.fixedTheadOfList');
        if($fixedThead.size() > 0) $fixedThead.remove();
        fixThead();
    }
}

/**
 * Apply cs style to page
 * @return void
 */
function applyCssStyle(css, tag)
{
    tag = tag || 'default';
    var name = 'applyStyle-' + tag;
    var $style = $('style#' + name);
    if(!$style.length)
    {
        $style = $('<style id="' + name + '">').appendTo('body');
    }
    var styleTag = $style.get(0);
    if (styleTag.styleSheet) styleTag.styleSheet.cssText = css;
    else styleTag.innerHTML = css;
}

/**
 * Remove cookie by key
 *
 * @param  cookieKey $cookieKey
 * @access public
 * @return void
 */
function removeCookieByKey(cookieKey)
{
    $.cookie(cookieKey, '', {expires:config.cookieLife, path:config.webRoot});
    location.href = location.href;
}

/**
 * Set homepage.
 *
 * @param  string $module
 * @param  string $page
 * @access public
 * @return void
 */
function setHomepage(module, page)
{
    $.get(createLink('custom', 'ajaxSetHomepage', 'module=' + module + '&page=' + page), function(){location.reload(true)});
}

/**
 * Reload page when tutorial mode setted in this session but not load in iframe
 *
 * @access public
 * @return void
 */
function checkTutorial()
{
    if(config.currentModule != 'tutorial' && window.TUTORIAL && (!frameElement || frameElement.tagName != 'IFRAME'))
    {
        if(confirm(window.TUTORIAL.tip))
        {
            $.getJSON(createLink('tutorial', 'ajaxQuit'), function()
            {
                window.location.reload();
            }).error(function(){alert(lang.timeout)});
        }
        else
        {
            window.location.href = createLink('tutorial', 'index');
        }
    }
}

/* Remove 'ditto' in first row when batch create or edit. */
function removeDitto()
{
    $firstTr = $('.table-form').find('tbody tr:first');
    $firstTr.find('td select').each(function()
    {
        $(this).find("option[value='ditto']").remove();
        $(this).trigger("chosen:updated");
    });
}

/**
 * Revert module cookie.
 *
 * @access public
 * @return void
 */
function revertModuleCookie()
{
    if($('#mainmenu .nav li[data-id="project"]').hasClass('active'))
    {
        $('#modulemenu .nav li[data-id="task"] a').click(function()
        {
            $.cookie('moduleBrowseParam', 0, {expires:config.cookieLife, path:config.webRoot});
        });
    }
    if($('#mainmenu .nav li[data-id="product"]').hasClass('active'))
    {
        $('#modulemenu .nav li[data-id="story"] a').click(function()
        {
            $.cookie('storyModule', 0, {expires:config.cookieLife, path:config.webRoot});
        });
    }
    if($('#mainmenu .nav li[data-id="qa"]').hasClass('active'))
    {
        $('#modulemenu .nav li[data-id="bug"] a').click(function()
        {
            $.cookie('bugModule', 0, {expires:config.cookieLife, path:config.webRoot});
        });
        $('#modulemenu .nav li[data-id="testcase"] a').click(function()
        {
            $.cookie('caseModule', 0, {expires:config.cookieLife, path:config.webRoot});
        });
    }
}

/**
 * Focus move up or down for input.
 *
 * @param direction up|down
 */
function inputFocusJump(direction, type){
    var $input = $('#mainContent table').find(type || 'input').filter(':focus').first();
    if(!$input.length) return;

    var $row         = $input.closest('tr');
    var $nextRow     = $row[direction === 'up' ? 'prev' : 'next']('tr');
    if(!$nextRow.length) $nextRow = $row.parent().children('tr')[direction === 'up' ? 'last' : 'first']();
    if(!$nextRow.length) return;

    var datetimepicker = $input.data('datetimepicker');
    if(datetimepicker) datetimepicker.hide();

    return $nextRow.find(':input[name^=' + ($input.attr('name').split('[')[0]) + ']:text:not(:disabled):not([name*="%"])').focus();
}

/**
 * Focus move up or down for select.
 *
 * @param direction
 */
function selectFocusJump(direction)
{
    return inputFocusJump(direction, 'select');
}

function adjustNoticePosition()
{
    var bottom = 25;
    $('#noticeBox').find('.alert').each(function()
    {
        $(this).css('bottom',  bottom + 'px');
        bottom += $(this).outerHeight(true) - 10;
    });
}

function notifyMessage(data)
{
    if(window.Notification)
    {
        var notify = null;

        message = data;
        if(typeof data.message == 'string') message = data.message;
        if(Notification.permission == "granted")
        {
            notify = new Notification("", {body:message, tag:'zentao', data:data});
        }
        else if(Notification.permission != "denied")
        {
            Notification.requestPermission().then(function(permission)
            {
                notify = new Notification("", {body:message, tag:'zentao', data:data});
            });
        }

        if(notify)
        {
            notify.onclick = function()
            {
                window.focus();
                if(typeof notify.data.url == 'string' && notify.data.url) window.location.href = notify.data.url;
                notify.close();
            }
            setTimeout(function()
            {
                notify.close();
            }, 3000);
        }
    }
}

/**
 * Get fingerprint.
 *
 * @access public
 * @return void
 */
function getFingerprint()
{
    if(typeof(Fingerprint) == 'function') return new Fingerprint().get();

    fingerprint = '';
    $.each(navigator, function(key, value)
    {
        if(typeof(value) == 'string') fingerprint += value.length;
    })
    return fingerprint;
}

/**
 * Alert message with bootbox.
 *
 * @param  message $message
 * @access public
 * @return bool
 */
function bootAlert(message)
{
    bootbox.alert(message);
    return false;
}

/**
 * Toggle fold or unfold for parent.
 *
 * @param  string $form
 * @param  array  $unfoldIdList
 * @param  int    $objectID
 * @param  string $objectType
 * @access public
 * @return void
 */
function toggleFold(form, unfoldIdList, objectID, objectType)
{
    $form     = $(form);
    $parentTd = $form.find('td.has-child');
    if($parentTd.length == 0) return false;

    var toggleClass = ['product', 'requirement', 'story'].indexOf(objectType) !== -1 ? 'story-toggle' : 'task-toggle';
    var nameClass   = ['product', 'productplan'].indexOf(objectType) !== -1 ? 'c-title' : 'c-name';

    if(objectType == 'demand')
    {
      toggleClass = 'demand-toggle';
      nameClass   = 'c-title';
    }

    $form.find('th.' + nameClass).addClass('clearfix').append("<span id='toggleFold' class='collapsed'><i  class='icon icon-angle-double-right'></i></span>");

    var allUnfold = true;
    $parentTd.each(function()
    {
        var dataID = $(this).closest('tr').attr('data-id');
        if(typeof(unfoldIdList[dataID]) != 'undefined') return true;

        allUnfold = false;
        $form.find('tr.parent-' + dataID).hide();
        $(this).find('a.' + toggleClass).addClass('collapsed')
    })

    $form.find('th.' + nameClass + ' #toggleFold').toggleClass('collapsed', !allUnfold);

    $(document).on('click', '#toggleFold', function()
    {
        var newUnfoldID = [];
        var url         = '';
        var collapsed   = $(this).hasClass('collapsed');
        $parentTd.each(function()
        {
            var dataID = $(this).closest('tr').attr('data-id');
            $form.find('tr.parent-' + dataID).toggle(collapsed);
            $(this).find('a.' + toggleClass).toggleClass('collapsed', !collapsed)
            newUnfoldID.push(dataID);
        })

        $(this).toggleClass('collapsed', !collapsed);
        url = createLink('misc', 'ajaxSetUnfoldID', 'objectID=' + objectID + '&objectType=' + objectType + '&action=' + (collapsed ? 'add' : 'delete'));
        $.post(url, {'newUnfoldID': JSON.stringify(newUnfoldID)});
    });

    $parentTd.find('a.' + toggleClass).click(function()
    {
        var newUnfoldID = [];
        var url         = '';
        var collapsed   = $(this).hasClass('collapsed');
        var dataID      = $(this).closest('tr').attr('data-id');

        $form.find('tr.parent-' + dataID).toggle(!collapsed);
        newUnfoldID.push(dataID);
        url = createLink('misc', 'ajaxSetUnfoldID', 'objectID=' + objectID + '&objectType=' + objectType + '&action=' + (collapsed ? 'add' : 'delete'));

        $table = $(this).closest('table');
        setTimeout(function()
        {
            hasCollapsed = $table.find('td.has-child a.' + toggleClass + '.collapsed').length != 0;
            $('#toggleFold').toggleClass('collapsed', hasCollapsed);
        }, 100);

        $.post(url, {'newUnfoldID': JSON.stringify(newUnfoldID)});
    });
}

/**
 * Adjust menu width.
 *
 * @access public
 * @return void
 */
function adjustMenuWidth()
{
    if(window.navigator.userAgent.indexOf('xuanxuan') > 0) return;

    var $mainHeader = $('#mainHeader .container');
    if($mainHeader.length == 0) return false;

    var $navbar = $mainHeader.find('#navbar .nav');

    var mainHeaderWidth = $mainHeader.width() - 10;
    var headingWidth    = $mainHeader.find('#heading').width() + 30;
    var navbarWidth     = $navbar.width();
    var toolbarWidth    = $mainHeader.find('#toolbar').width() + 20;

    if(mainHeaderWidth < headingWidth + navbarWidth + toolbarWidth)
    {
        var delta = (headingWidth + navbarWidth + toolbarWidth) - mainHeaderWidth;
        delta = Math.ceil(delta / $navbar.children('li').length / 2);

        var aTagPadding   = $navbar.find('a:first').css('padding-left').replace('px', '');
        var dividerMargin = $navbar.find('.divider').css('margin-left').replace('px', '');

        var newPadding = aTagPadding - delta;
        var newMargin  = dividerMargin - delta - 1;
        if(newPadding < 0) newPadding = 0;
        if(newMargin < 0)  newMargin  = 0;

        $navbar.children('li').find('a').css('padding-left', newPadding).css('padding-right', newPadding);
        $navbar.find('.divider').css('margin-left', newMargin).css('margin-right', newMargin);
    }
}

/**
 * Scroll to selected item in drop menu.
 *
 * @param  string $id
 * @access public
 * @return void
 */
function scrollToSelected()
{
    setTimeout(function()
    {
        $selected = $('#dropMenu .selected');
        if($selected.length == 0) return;

        $id = $selected.closest('.list-group');
        $id.mouseout(function(){$(this).find('a.active:not(.not-list-item)').removeClass('active')});

        var fixOffset = 160;
        offsetTop = $selected.offset().top;
        if(offsetTop < fixOffset) return;
        $id.scrollTop(offsetTop - fixOffset);
    }, 100);
}

/**
 * Limit iframe levels up to 3.
 *
 * @access public
 * @return void
 */
function limitIframeLevel()
{
    /* Fix bug #15325. */
    if(window.parent != window.top)
    {
        $('body').find('a.iframe').each(function()
        {
            $(this).replaceWith($(this).clone().removeClass('iframe'));
        });
    }
}

/**
 * Remove html tag.
 *
 * @param  str $str
 * @access public
 * @return void
 */
function removeHtmlTag(str)
{
    return str.replace(/<[^>]+>/g,"");
}

/* Ping the server every some minutes to keep the session. */
needPing = true;

/* When body's ready, execute these. */
$(document).ready(function()
{
    if(needPing) setTimeout('setPing()', 1000 * 60 * 10);  // After 10 minutes, begin ping.

    checkTutorial();
    revertModuleCookie();

    $(document).on('click', '#helpMenuItem .close-help-tab', function(){$('#helpMenuItem').prev().remove();$('#helpMenuItem').remove();});

    /* Open link in new tab when pressed ctrl key in windows */
    if(window.navigator.userAgent.match(/Windows/i))
    {
        $(document).on('mousedown', 'a', function(e)
        {
            var $a = $(this);
            if(!e.ctrlKey || $a.attr('target')) return;
            $a.attr('target', '_blank');
            clearTimeout($a.data('ctrlTimer'));
            $a.data('ctrlTimer', setTimeout(function(){$a.attr('target', null).data('ctrlTimer', 0)}, 100));
            e.preventDefault();
        });
    }

    /* Hide the global create drop-down when hovering over the avatar. */
    $('.has-avatar').hover(function()
    {
        $(this).next().removeClass('open');
        $(this).prev().removeClass('open');
    });

    /* Hide the avatar drop-down when hovering over the global create button. */
    $('#globalCreate').hover(function()
    {
        $(this).next().removeClass('open');
        $(this).addClass('dropdown-hover');
    });

    /* Hide create button when global create menu is clicked. */
    $('#globalCreate').click(function()
    {
        $(this).removeClass('dropdown-hover');
    });
});

/**
 * Make the selected product non clickable.
 *
 * @return void
 */
function disableSelectedProduct()
{
    $("select[id^='products'] option[disabled='disabled']").removeAttr('disabled');

    var selectedVal = [];
    $("select[id^='products']").each(function()
    {
        var selectedProduct = $(this).val();
        if(selectedProduct != 0 && $.inArray(selectedProduct, selectedVal) < 0) selectedVal.push(selectedProduct);
    })

    $("select[id^='products']").each(function()
    {
        var selectedProduct = $(this).val();
        $(this).find('option').each(function()
        {
            var optionVal = $(this).attr('value');
            if(optionVal != selectedProduct && $.inArray(optionVal, selectedVal) >= 0) $(this).attr('disabled', 'disabled');
        })
    })

    $("select[id^=products]").trigger('chosen:updated');
}

/**
 * Make the selected branch non clickable.
 *
 * @return void
 */
function disableSelectedBranch()
{
    var relatedProduct = $(this).siblings("select[id^='products']").val();

    /* Get the products control of the same value and their branch control. */
    var sameProductControl       = [];
    var sameProductBranchControl = [];
    $("select[id^='products']").each(function()
    {
        if($(this).val() == relatedProduct)
        {
            $(this).siblings("select[id^='branch']").find("option[disabled='disabled']").removeAttr('disabled');

            sameProductControl.push(this);
            sameProductBranchControl.push($(this).siblings("select[id^='branch']"));
        }
    });

    /* Get the selected branch of the related product. */
    var preSelectedVal = [];
    $.each(sameProductControl, function()
    {
        var selectedBranch = $(this).siblings("select[id^='branch']").val();
        if($.inArray(selectedBranch, preSelectedVal) < 0) preSelectedVal.push(selectedBranch);
    });

    var selectedVal = [];
    $.each(sameProductControl, function()
    {
        var selectedBranch = $(this).siblings("select[id^='branch']").val();
        if($.inArray(selectedBranch, selectedVal) >= 0)
        {
            $(this).siblings("select[id^='branch']").find('option').removeAttr('selected');
            for(i in preSelectedVal) $(this).siblings("select[id^='branch']").find('option[value=' + preSelectedVal[i] + ']').attr('disabled', 'disabled');

            $(this).siblings("select[id^='branch']").find('option').not('[disabled=disabled]').eq(0).attr('selected', 'selected');
            var selectedBranch = $(this).siblings("select[id^='branch']").val();
        }
        if($.inArray(selectedBranch, selectedVal) < 0) selectedVal.push(selectedBranch);
    });

    /* Make the selected value disabled. */
    $.each(sameProductBranchControl, function()
    {
        var selectedBranch = $(this).val();
        $(this).find('option').each(function()
        {
            var optionVal = $(this).attr('value');

            if(optionVal != selectedBranch && $.inArray(optionVal, selectedVal) >= 0) $(this).attr('disabled', 'disabled');
        })
    })

    $("select[id^=branch]").trigger('chosen:updated');
}

/**
 * Determine whether multi-branch products should be disabled.
 *
 * @param  object  product
 * @return bool
 */
function checkMultiProducts(product)
{
    var disabledBranchList = [];
    var optionLength       = $(product).siblings("select[id^='branch']").find('option').length;
    $(product).siblings("select[id^='branch']").find("option[disabled='disabled']").each(function()
    {
        disabledBranchList.push($(this).attr('value'));
    });

    if(optionLength - disabledBranchList.length == 1) return true;

    return false;
}

/**
 * Add row.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function addRow(obj)
{
    var row = $('#addRow').html().replace(/%i%/g, rowIndex + 1);
    $('<tr class="addedRow">' + row  + '</tr>').insertAfter($(obj).closest('tr'));

    var $row = $(obj).closest('tr').next();

    $row.find(".form-date").datepicker();
    $row.find("input[name^=color]").colorPicker();
    $row.find('div[id$=_chosen]').remove();
    $row.find('.picker').remove();
    $row.find('.chosen').chosen();
    $row.find('.picker-select').picker();

    rowIndex ++;
}

/**
 * Delete row.
 *
 * @param  object $obj
 * @access public
 * @return void
 */
function deleteRow(obj)
{
    $(obj).closest('tr').remove();
}

/**
 * Show checked fields.
 *
 * @param  string fields
 * @access public
 * @return void
 */
function showCheckedFields(fields)
{
    var fieldList = ',' + fields + ',';
    $('#formSettingForm > .checkboxes > .checkbox-primary > input').each(function()
    {
        var field     = ',' + $(this).val() + ',';
        var $field    = config.currentMethod == 'create' ? $('#' + $(this).val()) : $('[name^=' + $(this).val() + ']');
        var $fieldBox = $('.' + $(this).val() + 'Box' );

        var required  = '';
        if(typeof requiredFields != 'undefined') var required = ',' + requiredFields + ',';
        if(fieldList.indexOf(field) >= 0 || (required && required.indexOf(field) >= 0))
        {
            $fieldBox.removeClass('hidden');
            $field.removeAttr('disabled');
        }
        else if(!$fieldBox.hasClass('hidden'))
        {
            $fieldBox.addClass('hidden');
            if($(this).val() != 'branch') $field.attr('disabled', true);
        }

        if(config.currentModule == 'story' && $(this).val() == 'source')
        {
            var $sourceNote = config.currentMethod == 'create' ? $('#sourceNote') : $('[name^=sourceNote]');
            $sourceNote.attr('disabled', $fieldBox.hasClass('hidden'));
        }
    });


    if(config.currentModule == 'task' && config.currentMethod == 'create');
    {
        if(fieldList.indexOf(',estStarted,') >= 0 && fieldList.indexOf(',deadline,') >= 0)
        {
            $('.borderBox').removeClass('hidden');
        }
        else if(fieldList.indexOf(',estStarted,') >= 0 || fieldList.indexOf(',deadline,') >= 0)
        {
            $('.datePlanBox').removeClass('hidden');
            if(!$('.borderBox').hasClass('hidden')) $('.borderBox').addClass('hidden');
        }
        else
        {
            if(!$('.borderBox').hasClass('hidden')) $('.borderBox').addClass('hidden');
            if(!$('.datePlanBox').hasClass('hidden')) $('.datePlanBox').addClass('hidden');
        }

        if(typeof lifetime != 'undefined' && lifetime == 'ops') $('.storyBox').addClass('hidden');
    }
}

/**
 * Hidden require field.
 *
 * @access public
 * @return void
 */
function hiddenRequireFields()
{
    $('#formSettingForm > .checkboxes > .checkbox-primary > input').each(function()
    {
        var field    = ',' + $(this).val() + ',';
        var required = ',' + requiredFields + ',';
        if(required.indexOf(field) >= 0) $(this).closest('div').addClass('hidden');
    });
}

/**
 * Save custom fields.
 *
 * @param  stirng $key
 * @param  int    $maxFieldCount
 * @param  object $name
 * @param  int    $nameMinWidth
 * @access public
 * @return void
 */
function saveCustomFields(key, maxFieldCount, $name, nameMinWidth)
{
    var fields = '';
    $('#formSettingForm > .checkboxes > .checkbox-primary > input:checked').each(function()
    {
        fields += ',' + $(this).val();
    });

    var module = config.currentModule;
    var link   = createLink('custom', 'ajaxSaveCustomFields', 'module=' + module + '&section=custom&key=' + key);
    $.post(link, {'fields' : fields}, function()
    {
        showFields = fields;

        showCheckedFields(fields);
        $('#formSetting').parent().removeClass('open');

        if(key == 'batchCreateFields') setCustomFieldsStyle(maxFieldCount, $name, nameMinWidth);
    });
}

/**
 * Set custom fields style.
 *
 * @param  int    $maxFieldCount
 * @param  object $name
 * @param  int    $nameMinWidth
 * @access public
 * @return void
 */
function setCustomFieldsStyle(maxFieldCount, $name, nameMinWidth)
{
    var fieldCount = $('#batchCreateForm .table thead>tr>th:visible').length;
    $('.form-actions').attr('colspan', fieldCount);

    var $table = $('#batchCreateForm > .table-responsive');
    if(fieldCount > maxFieldCount)
    {
        $table.removeClass('scroll-none');
        $table.css('overflow', 'auto');
    }
    else
    {
        $table.addClass('scroll-none');
        $table.css('overflow', 'visible');
    }

    if($name.width() < nameMinWidth) $name.width(200);
}

/**
 * Refresh budget units of the project.
 *
 * @param  object $data
 * @access public
 * @return void
 */
function refreshBudgetUnit(data)
{
    $('#budgetUnit').val(data.budgetUnit).trigger('chosen:updated');
    if(typeof(data.availableBudget) == 'undefined')
    {
        $('#budget').removeAttr('placeholder').attr('disabled', true);
        $('#future').prop('checked', true);
    }
    else
    {
        $('#budget').removeAttr('disabled');
        $('#future').prop('checked', false);
    }
}

/**
 * Handle radio logic of Kanban column width setting.
 *
 * @access public
 * @return void
 */
function handleKanbanWidthAttr ()
{
    $('#colWidth, #minColWidth, #maxColWidth').attr('onkeyup', 'value=value.match(/^\\d+$/) ? value : ""');
    $('#colWidth, #minColWidth, #maxColWidth').attr('maxlength', '3');
    var fluidBoard = $("#mainContent input[name='fluidBoard'][checked='checked']").val() || 0;
    var addAttrEle = fluidBoard == 0 ? '#colWidth' : '#minColWidth, #maxColWidth';
    var $fixedTip  = $('#colWidth + .fixedTip');
    var $autoTip   = $('#maxColWidth + .autoTip');
    $(addAttrEle).closest('.width-radio-row').addClass('required');
    $('#colWidth').attr('disabled',fluidBoard == 1);
    $('#minColWidth, #maxColWidth').attr('disabled',fluidBoard == 0);
    $("#minColWidth, #maxColWidth").on('input', function()
    {
        $('#minColWidthLabel, #maxColWidthLabel').remove();
        $('#minColWidth, #maxColWidth').removeClass('has-error');
    });

    if(fluidBoard == 1)
    {
        $fixedTip.addClass('hidden');
        $autoTip.removeClass('hidden');
    }
    else
    {
        $fixedTip.removeClass('hidden');
        $autoTip.addClass('hidden');
    }

    $(document).on('change', "#mainContent input[name='fluidBoard']", function(e)
    {
        $('#colWidth').attr('disabled', e.target.value == 1);
        $('#minColWidth, #maxColWidth').attr('disabled', e.target.value == 0);
        if(e.target.value == 0 && $('#minColWidthLabel, #maxColWidthLabel'))
        {
            $('#colWidth').closest('.width-radio-row').addClass('required');
            $('#minColWidth, #maxColWidth').closest('.width-radio-row').removeClass('required');
            $('#minColWidthLabel, #maxColWidthLabel').remove();
            $('#minColWidth, #maxColWidth').removeClass('has-error');
            $fixedTip.removeClass('hidden');
            $autoTip.addClass('hidden');
        }
        else if(e.target.value == 1 && $('#colWidthLabel'))
        {
            $('#minColWidth, #maxColWidth').closest('.width-radio-row').addClass('required');
            $('#colWidth').closest('.width-radio-row').removeClass('required');
            $('#colWidthLabel').remove();
            $('#colWidth').removeClass('has-error');
            $fixedTip.addClass('hidden');
            $autoTip.removeClass('hidden');
        }
    });
}
