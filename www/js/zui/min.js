/*!
 * ZUI: Zentao template - v1.8.1 - 2018-08-14
 * http://zui.sexy
 * GitHub: https://github.com/easysoft/zui.git 
 * Copyright (c) 2018 cnezsoft.com; Licensed MIT
 */

/* ========================================================================
 * ZUI: jquery.extensions.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014-2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($, window, undefined) {
    'use strict';

    /* Check jquery */
    if(typeof($) === 'undefined') throw new Error('ZUI requires jQuery');

    // ZUI shared object
    if(!$.zui) $.zui = function(obj) {
        if($.isPlainObject(obj)) {
            $.extend($.zui, obj);
        }
    };

    var MOUSE_BUTTON_CODES = {
        all: -1,
        left: 0,
        middle: 1,
        right: 2
    };

    var lastUuidAmend = 0;
    $.zui({
        uuid: function(asNumber) {
            var uuidNumber = (new Date()).getTime() * 1000 + (lastUuidAmend++) % 1000;
            return asNumber ? uuidNumber : uuidNumber.toString(36);
        },

        callEvent: function(func, event, proxy) {
            if($.isFunction(func)) {
                if(proxy !== undefined) {
                    func = $.proxy(func, proxy);
                }
                var result = func(event);
                if(event) event.result = result;
                return !(result !== undefined && (!result));
            }
            return 1;
        },

        clientLang: function() {
            var lang;
            var config = window.config;
            if(typeof(config) != 'undefined' && config.clientLang) {
                lang = config.clientLang;
            }
            if(!lang) {
                var hl = $('html').attr('lang');
                lang = hl ? hl : (navigator.userLanguage || navigator.userLanguage || 'zh_cn');
            }
            return lang.replace('-', '_').toLowerCase();
        },

        strCode: function(str) {
            var code = 0;
            if(str && str.length) {
                for(var i = 0; i < str.length; ++i) {
                    code += i * str.charCodeAt(i);
                }
            }
            return code;
        },

        getMouseButtonCode: function(mouseButton) {
            if(typeof mouseButton !== 'number') {
                mouseButton = MOUSE_BUTTON_CODES[mouseButton];
            }
            if(mouseButton === undefined || mouseButton === null) mouseButton = -1;
            return mouseButton;
        }
    });

    $.fn.callEvent = function(name, event, model) {
        var $this = $(this);
        var dotIndex = name.indexOf('.zui.');
        var shortName = dotIndex < 0 ? name : name.substring(0, dotIndex);
        var e = $.Event(shortName, event);

        if((model === undefined) && dotIndex > 0) {
            model = $this.data(name.substring(dotIndex + 1));
        }

        if(model && model.options) {
            var func = model.options[shortName];
            if($.isFunction(func)) {
                e.result = $.zui.callEvent(func, e, model);
            }
        }
        $this.trigger(e);
        return e;
    };

    $.fn.callComEvent = function(component, eventName, params) {
        if (params !== undefined && !$.isArray(params)) {
            params = [params];
        }
        var $this = this;
        var result = $this.triggerHandler(eventName, params);

        var eventCallback = component.options[eventName];
        if (eventCallback) {
            result = eventCallback.apply(component, params);
        }
        return result;
    };
}(jQuery, window, undefined));


/* ========================================================================
 * ZUI: typography.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014-2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($) {
    'use strict';

    $.fn.fixOlPd = function(pd) {
        pd = pd || 10;
        return this.each(function() {
            var $ol = $(this);
            $ol.css('paddingLeft', Math.ceil(Math.log10($ol.children().length)) * pd + 10);
        });
    };

    $(function() {
        $('.ol-pd-fix,.article ol').fixOlPd();
    });
}(jQuery));


/* ========================================================================
 * Bootstrap: alert.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#alerts
 * ========================================================================
 * Copyright 2013 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * Bootsrap version in the future.
 * http://zui.sexy
 * ======================================================================== */


+ function($) {
    'use strict';

    // ALERT CLASS DEFINITION
    // ======================

    var dismiss = '[data-dismiss="alert"]'
    var zuiname = 'zui.alert';

    var Alert = function(el) {
        $(el).on('click', dismiss, this.close)
    }

    Alert.prototype.close = function(e) {
        var $this = $(this)
        var selector = $this.attr('data-target')

        if(!selector) {
            selector = $this.attr('href')
            selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
        }

        var $parent = $(selector)

        if(e) e.preventDefault()

        if(!$parent.length) {
            $parent = $this.hasClass('alert') ? $this : $this.parent()
        }

        $parent.trigger(e = $.Event('close.' + zuiname))

        if(e.isDefaultPrevented()) return

        $parent.removeClass('in')

        function removeElement() {
            $parent.trigger('closed.' + zuiname).remove()
        }

        $.support.transition && $parent.hasClass('fade') ?
            $parent
            .one($.support.transition.end, removeElement)
            .emulateTransitionEnd(150) :
            removeElement()
    }


    // ALERT PLUGIN DEFINITION
    // =======================

    var old = $.fn.alert

    $.fn.alert = function(option) {
        return this.each(function() {
            var $this = $(this)
            var data = $this.data(zuiname)

            if(!data) $this.data(zuiname, (data = new Alert(this)))
            if(typeof option == 'string') data[option].call($this)
        })
    }

    $.fn.alert.Constructor = Alert


    // ALERT NO CONFLICT
    // =================

    $.fn.alert.noConflict = function() {
        $.fn.alert = old
        return this
    }


    // ALERT DATA-API
    // ==============

    $(document).on('click.' + zuiname + '.data-api', dismiss, Alert.prototype.close)

}(window.jQuery);

/* ========================================================================
 * ZUI: pager.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2017-2018 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($, undefined) {
    'use strict';

    var NAME = 'zui.pager'; // model name

    var DEFAULT_PAGER = {
        page: 1,        // current page index
        recTotal: 0,    // records total count
        recPerPage: 10, // records count per page
    };

    var LANG = {
        zh_cn: {
            pageOfText: '第 {0} 页',
            prev: '上一页',
            next: '下一页',
            first: '第一页',
            last: '最后一页',
            goto: '跳转',
            pageOf: '第 <strong>{page}</strong> 页',
            totalPage: '共 <strong>{totalPage}</strong> 页',
            totalCount: '共 <strong>{recTotal}</strong> 项',
            pageSize: '每页 <strong>{recPerPage}</strong> 项',
            itemsRange: '第 <strong>{start}</strong> ~ <strong>{end}</strong> 项',
            pageOfTotal: '第 <strong>{page}</strong>/<strong>{totalPage}</strong> 页'
        },
        zh_tw: {
            pageOfText: '第 {0} 頁',
            prev: '上一頁',
            next: '下一頁',
            first: '第一頁',
            last: '最後一頁',
            goto: '跳轉',
            pageOf: '第 <strong>{page}</strong> 頁',
            totalPage: '共 <strong>{totalPage}</strong> 頁',
            totalCount: '共 <strong>{recTotal}</strong> 項',
            pageSize: '每頁 <strong>{recPerPage}</strong> 項',
            itemsRange: '第 <strong>{start}</strong> ~ <strong>{end}</strong> 項',
            pageOfTotal: '第 <strong>{page}</strong>/<strong>{totalPage}</strong> 頁'
        },
        en: {
            pageOfText: 'Page {0}',
            prev: 'Prev',
            next: 'Next',
            first: 'First',
            last: 'Last',
            goto: 'Goto',
            pageOf: 'Page <strong>{page}</strong>',
            totalPage: '<strong>{totalPage}</strong> pages',
            totalCount: '<strong>{recTotal}</strong> in total',
            pageSize: '<strong>{recPerPage}</strong> per page',
            itemsRange: 'From <strong>{start}</strong> to <strong>{end}</strong>',
            pageOfTotal: 'Page <strong>{page}</strong> of <strong>{totalPage}</strong>'
        }
    };

    // The pager model class
    var Pager = function(element, options) {
        var that = this;
        that.name = NAME;
        that.$ = $(element);

        options = that.options = $.extend({}, Pager.DEFAULTS, this.$.data(), options);

        var lang   = options.lang || $.zui.clientLang();
        that.lang  = $.isPlainObject(lang) ? ($.extend(true, {}, LANG[lang.lang || $.zui.clientLang()], lang)) : LANG[lang];

        that.state = {};

        that.set(options.page, options.recTotal, options.recPerPage, true);

        that.$.on('click', '.pager-goto-btn', function() {
            var $goto = $(this).closest('.pager-goto');
            var page = parseInt($goto.find('.pager-goto-input').val());
            if (page !== NaN) {
                that.set(page);
            }
        }).on('click', '.pager-item', function() {
            var page = $(this).data('page');
            if (typeof page === 'number' && page > 0) {
                that.set(page);
            }
        }).on('click', '.pager-size-menu [data-size]', function() {
            var size = $(this).data('size');
            if (typeof size === 'number' && size > 0) {
                that.set(-1, -1, size);
            }
        });
    };

    Pager.prototype.set = function(page, recTotal, recPerPage, notTiggerChange) {
        var that = this;
        if (typeof page === 'object' && page !== null) {
            recPerPage = page.recPerPage;
            recTotal = page.recTotal;
            page = page.page;
        }
        var state = that.state;
        if (!state) {
            state = $.extend({}, DEFAULT_PAGER);
        }
        var oldState = $.extend({}, state);
        if (typeof recPerPage === 'number' && recPerPage > 0) {
            state.recPerPage = recPerPage;
        }
        if (typeof recTotal === 'number' && recTotal >= 0) {
            state.recTotal = recTotal;
        }
        if (typeof page === 'number' && page >= 0) {
            state.page = page;
        }
        state.totalPage = (state.recTotal && state.recPerPage) ? (Math.ceil(state.recTotal / state.recPerPage)) : 1;
        state.page = Math.max(0, Math.min(state.page, state.totalPage));
        // stateRecCount is items count in current page
        state.pageRecCount = state.recTotal;
        if (state.page && state.recTotal) {
            if (state.page < state.totalPage) {
                state.pageRecCount = state.recPerPage;
            } else if (state.page > 1) {
                state.pageRecCount = state.recTotal - (state.recPerPage * (state.page - 1));
            }
        }
        state.skip  = state.page > 1 ? ((state.page - 1) * state.recPerPage) : 0;
        state.start = state.skip + 1;
        state.end   = state.skip + state.pageRecCount;
        state.prev  = state.page > 1 ? (state.page - 1) : 0;
        state.next  = state.page < state.totalPage ? (state.page + 1) : 0;
        that.state  = state;
        if (!notTiggerChange && (oldState.page !== state.page || oldState.recTotal !== state.recTotal || oldState.recPerPage !== state.recPerPage)) {
            that.$.callComEvent(that, 'onPageChange', [state, oldState]);
        }
        return that.render();
    };

    Pager.prototype.createLinkItem = function(page, text, asAElement) {
        var that = this;
        if (text === undefined) {
            text = page;
        }
        var $ele = $('<a title="' + that.lang.pageOfText.format(page) + '" class="pager-item" data-page="' + page + '"/>').attr('href', page ? that.createLink(page, that.state) : '###').html(text);
        if (!asAElement) {
            $ele = $('<li />').append($ele).toggleClass('active', page === that.state.page).toggleClass('disabled', !page || page === that.state.page);
        }
        return $ele;
    };

    Pager.prototype.createNavItems = function(maxCount) {
        var that = this;
        var $nav = that.$;
        var pager = that.state;
        var totalPage = pager.totalPage;
        var page = pager.page;
        var appendItem = function(p, to) {
            if(p === false) {
                $nav.append(that.createLinkItem(0, to || that.options.navEllipsisItem));
                return;
            }
            if(to === undefined) to = p;
            for(var i = p; i <= to; ++i) {
                $nav.append(that.createLinkItem(i));
            }
        };
        if (maxCount === undefined) {
            maxCount = that.options.maxNavCount || 10;
        }
        appendItem(1);
        if(totalPage > 1) {
            if(totalPage <= maxCount) {
                appendItem(2, totalPage);
            }
            else if(page < (maxCount - 2)) {
                appendItem(2, maxCount - 2);
                appendItem(false);
                appendItem(totalPage);
            }
            else if(page > (totalPage - maxCount + 2)) {
                appendItem(false);
                appendItem((totalPage - maxCount + 2), totalPage);
            }
            else {
                appendItem(false);
                appendItem(page - Math.ceil((maxCount-4)/2), page + Math.floor((maxCount-4)/2));
                appendItem(false);
                appendItem(totalPage);
            }
        }
    };

    Pager.prototype.createGoto = function() {
        var that = this;
        var pager = this.state;
        var $goto = $('<div class="input-group pager-goto" style="width: ' + (35 + (pager.page + '').length * 9 + 25 + that.lang.goto.length*12) + 'px"><input value="' + pager.page + '" type="number" min="1" max="' + pager.totalPage + '" placeholder="' + pager.page + '" class="form-control pager-goto-input"><span class="input-group-btn"><button class="btn pager-goto-btn" type="button">' + that.lang.goto + '</button></span></div>');
        return $goto;
    };

    Pager.prototype.createSizeMenu = function() {
        var that = this;
        var pager = this.state;
        var $menu = $('<ul class="dropdown-menu"></ul>');
        var options = that.options.pageSizeOptions;
        if (typeof options === 'string') {
            options = options.split(',');
        }
        for (var i = 0; i < options.length; ++i) {
            var size = options[i];
            if (typeof size === 'string') {
                size = parseInt(size);
            }
            var $li = $('<li><a href="###" data-size="' + size + '">' + size + '</a></li>').toggleClass('active', size === pager.recPerPage);
            $menu.append($li);
        }
        return $('<div class="btn-group pager-size-menu"><button type="button" class="btn dropdown-toggle" data-toggle="dropdown">' + that.lang.pageSize.format(pager) + ' <span class="caret"></span></button></div>').addClass(that.options.menuDirection).append($menu);
    };

    Pager.prototype.createElement = function(element, $pager, pager) {
        var that = this;
        var createLinkItem= $.proxy(that.createLinkItem, that);
        var lang = that.lang;
        switch (element) {
            case 'prev':
                return createLinkItem(pager.prev, lang.prev);
            case 'prev_icon':
                return createLinkItem(pager.prev, '<i class="icon ' + that.options.prevIcon + '"></i>');
            case 'next':
                return createLinkItem(pager.next, lang.next);
            case 'next_icon':
                return createLinkItem(pager.next, '<i class="icon ' + that.options.nextIcon + '"></i>');
            case 'first':
                return createLinkItem(1, lang.first);
            case 'first_icon':
                return createLinkItem(1, '<i class="icon ' + that.options.firstIcon + '"></i>');
            case 'last':
                return createLinkItem(pager.totalPage, lang.last);
            case 'last_icon':
                return createLinkItem(pager.totalPage, '<i class="icon ' + that.options.lastIcon + '"></i>');
            case 'space':
            case '|':
                return $('<li class="space" />');
            case 'nav':
            case 'pages':
                that.createNavItems();
                return;
            case 'total_text':
                return $(('<div class="pager-label">' + lang.totalCount + '</div>').format(pager));
            case 'page_text':
                return $(('<div class="pager-label">' + lang.pageOf + '</div>').format(pager));
            case 'total_page_text':
                return $(('<div class="pager-label">' + lang.totalPage + '</div>').format(pager));
            case 'page_of_total_text':
                return $(('<div class="pager-label">' + lang.pageOfTotal + '</div>').format(pager));
            case 'page_size_text':
                return $(('<div class="pager-label">' + lang.pageSize + '</div>').format(pager));
            case 'items_range_text':
                return $(('<div class="pager-label">' + lang.itemsRange + '</div>').format(pager));
            case 'goto':
                return that.createGoto();
            case 'size_menu':
                return that.createSizeMenu();
            default:
                return $('<li/>').html(element.format(pager));
        }
    };

    Pager.prototype.createLink = function(page, pager) {
        if (page === undefined) {
            page = this.state.page;
        }
        if (pager === undefined) {
            pager = this.state;
        }
        var linkCreator = this.options.linkCreator;
        if (typeof linkCreator === 'string') {
            return linkCreator.format($.extend({}, pager, {page: page}));
        } else if ($.isFunction(linkCreator)) {
            return linkCreator(page, pager);
        }
        return '#page=' + page;
    };

    Pager.prototype.render = function(elements) {
        var that = this;
        var state = that.state;
        var createElement = that.options.elementCreator || that.createElement;
        var isMapperCreator = $.isPlainObject(createElement);

        elements = elements || that.elements || that.options.elements;
        if (typeof elements == 'string') {
            elements = elements.split(',');
        }
        that.elements = elements;

        that.$.empty();

        for(var i = 0; i < elements.length; ++i) {
            var element  = $.trim(elements[i]);
            var creator = isMapperCreator ? (createElement[element] || createElement) : createElement;
            var $element = creator.call(that, element, that.$, state);
            if ($element === false) {
                $element = that.createElement(element, that.$, state);
            }
            if ($element instanceof $) {
                if ($element[0].tagName !== 'LI') {
                    $element = $('<li/>').append($element);
                }
                that.$.append($element);
            }
        }

        // Fix page item border
        var $lastItem = null;
        that.$.children('li').each(function() {
            var $li = $(this);
            var isItem = !!$li.children('.pager-item').length;
            if ($lastItem) {
                $lastItem.toggleClass('pager-item-right', !isItem);
            } else {
                if (isItem) {
                    $li.addClass('pager-item-left');
                }
            }
            $lastItem = isItem ? $li : null;
        });
        if ($lastItem) {
            $lastItem.addClass('pager-item-right');
        }

        that.$.callComEvent(that, 'onRender', [state]);
        return that;
    };

    // default options
    Pager.DEFAULTS = $.extend({
        elements: ['first_icon', 'prev_icon', 'pages', 'next_icon', 'last_icon', 'page_of_total_text', 'items_range_text', 'total_text'],
        prevIcon: 'icon-double-angle-left',
        nextIcon: 'icon-double-angle-right',
        firstIcon: 'icon-step-backward',
        lastIcon: 'icon-step-forward',
        navEllipsisItem: '<i class="icon icon-ellipsis-h"></i>',
        maxNavCount: 10,
        menuDirection: 'dropdown', // or dropup
        pageSizeOptions: [10, 20, 30, 50, 100],
        // onPageChange: null
    }, DEFAULT_PAGER);

    // Extense jquery element
    $.fn.pager = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(NAME);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(NAME, (data = new Pager(this, options)));

            if(typeof option == 'string') data[option]();
        });
    };

    Pager.NAME = NAME;

    $.fn.pager.Constructor = Pager;

    // Auto call pager after document load complete
    $(function() {
        $('[data-ride="pager"]').pager();
    });
}(jQuery, undefined));


/* ========================================================================
 * Bootstrap: tab.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#tabs
 *  
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * Bootsrap version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */


+ function($) {
    'use strict';

    // TAB CLASS DEFINITION
    // ====================

    var zuiname = 'zui.tab'
    var Tab = function(element) {
        this.element = $(element)
    }

    Tab.prototype.show = function() {
        var $this = this.element
        var $ul = $this.closest('ul:not(.dropdown-menu)')
        var selector = $this.attr('data-target') || $this.attr('data-tab')

        if(!selector) {
            selector = $this.attr('href')
            selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
        }

        if($this.parent('li').hasClass('active')) return

        var previous = $ul.find('.active:last a')[0]
        var e = $.Event('show.' + zuiname, {
            relatedTarget: previous
        })

        $this.trigger(e)

        if(e.isDefaultPrevented()) return

        var $target = $(selector)

        this.activate($this.parent('li'), $ul)
        this.activate($target, $target.parent(), function() {
            $this.trigger({
                type: 'shown.' + zuiname,
                relatedTarget: previous
            })
        })
    }

    Tab.prototype.activate = function(element, container, callback) {
        var $active = container.find('> .active')
        var transition = callback && $.support.transition && $active.hasClass('fade')

        function next() {
            $active
                .removeClass('active')
                .find('> .dropdown-menu > .active')
                .removeClass('active')

            element.addClass('active')

            if(transition) {
                element[0].offsetWidth // reflow for transition
                element.addClass('in')
            } else {
                element.removeClass('fade')
            }

            if(element.parent('.dropdown-menu')) {
                element.closest('li.dropdown').addClass('active')
            }

            callback && callback()
        }

        transition ?
            $active
            .one($.support.transition.end, next)
            .emulateTransitionEnd(150) :
            next()

        $active.removeClass('in')
    }


    // TAB PLUGIN DEFINITION
    // =====================

    var old = $.fn.tab

    $.fn.tab = function(option) {
        return this.each(function() {
            var $this = $(this)
            var data = $this.data(zuiname)

            if(!data) $this.data(zuiname, (data = new Tab(this)))
            if(typeof option == 'string') data[option]()
        })
    }

    $.fn.tab.Constructor = Tab


    // TAB NO CONFLICT
    // ===============

    $.fn.tab.noConflict = function() {
        $.fn.tab = old
        return this
    }


    // TAB DATA-API
    // ============

    $(document).on('click.zui.tab.data-api', '[data-toggle="tab"], [data-tab]', function(e) {
        e.preventDefault()
        $(this).tab('show')
    })

}(window.jQuery);


/* ========================================================================
 * Bootstrap: transition.js v3.2.0
 * http://getbootstrap.com/javascript/#transitions
 *  
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * Bootsrap version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+ function($) {
    'use strict';

    // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
    // ============================================================

    function transitionEnd() {
        var el = document.createElement('bootstrap')

        var transEndEventNames = {
            WebkitTransition: 'webkitTransitionEnd',
            MozTransition: 'transitionend',
            OTransition: 'oTransitionEnd otransitionend',
            transition: 'transitionend'
        }

        for(var name in transEndEventNames) {
            if(el.style[name] !== undefined) {
                return {
                    end: transEndEventNames[name]
                }
            }
        }

        return false // explicit for ie8 (  ._.)
    }

    // http://blog.alexmaccaw.com/css-transitions
    $.fn.emulateTransitionEnd = function(duration) {
        var called = false
        var $el = this
        $(this).one('bsTransitionEnd', function() {
            called = true
        })
        var callback = function() {
            if(!called) $($el).trigger($.support.transition.end)
        }
        setTimeout(callback, duration)
        return this
    }

    $(function() {
        $.support.transition = transitionEnd()

        if(!$.support.transition) return

        $.event.special.bsTransitionEnd = {
            bindType: $.support.transition.end,
            delegateType: $.support.transition.end,
            handle: function(e) {
                if($(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
            }
        }
    })

}(jQuery);


/* ========================================================================
 * Bootstrap: collapse.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#collapse
 * 
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * Bootsrap version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */


+ function($) {
    'use strict';

    var zuiname = 'zui.collapse'

    // COLLAPSE PUBLIC CLASS DEFINITION
    // ================================

    var Collapse = function(element, options) {
        this.$element = $(element)
        this.options = $.extend({}, Collapse.DEFAULTS, options)
        this.transitioning = null

        if(this.options.parent) this.$parent = $(this.options.parent)
        if(this.options.toggle) this.toggle()
    }

    Collapse.DEFAULTS = {
        toggle: true
    }

    Collapse.prototype.dimension = function() {
        var hasWidth = this.$element.hasClass('width')
        return hasWidth ? 'width' : 'height'
    }

    Collapse.prototype.show = function() {
        if(this.transitioning || this.$element.hasClass('in')) return

        var startEvent = $.Event('show.' + zuiname)
        this.$element.trigger(startEvent)
        if(startEvent.isDefaultPrevented()) return

        var actives = this.$parent && this.$parent.find('.in')

        if(actives && actives.length) {
            var hasData = actives.data(zuiname)
            if(hasData && hasData.transitioning) return
            actives.collapse('hide')
            hasData || actives.data(zuiname, null)
        }

        var dimension = this.dimension()

        this.$element
            .removeClass('collapse')
            .addClass('collapsing')[dimension](0)

        this.transitioning = 1

        var complete = function() {
            this.$element
                .removeClass('collapsing')
                .addClass('in')[dimension]('auto')
            this.transitioning = 0
            this.$element.trigger('shown.' + zuiname)
        }

        if(!$.support.transition) return complete.call(this)

        var scrollSize = $.camelCase(['scroll', dimension].join('-'))

        this.$element
            .one($.support.transition.end, $.proxy(complete, this))
            .emulateTransitionEnd(350)[dimension](this.$element[0][scrollSize])
    }

    Collapse.prototype.hide = function() {
        if(this.transitioning || !this.$element.hasClass('in')) return

        var startEvent = $.Event('hide.' + zuiname)
        this.$element.trigger(startEvent)
        if(startEvent.isDefaultPrevented()) return

        var dimension = this.dimension()

        this.$element[dimension](this.$element[dimension]())[0].offsetHeight

        this.$element
            .addClass('collapsing')
            .removeClass('collapse')
            .removeClass('in')

        this.transitioning = 1

        var complete = function() {
            this.transitioning = 0
            this.$element
                .trigger('hidden.' + zuiname)
                .removeClass('collapsing')
                .addClass('collapse')
        }

        if(!$.support.transition) return complete.call(this)

        this.$element[dimension](0)
            .one($.support.transition.end, $.proxy(complete, this))
            .emulateTransitionEnd(350)
    }

    Collapse.prototype.toggle = function() {
        this[this.$element.hasClass('in') ? 'hide' : 'show']()
    }


    // COLLAPSE PLUGIN DEFINITION
    // ==========================

    var old = $.fn.collapse

    $.fn.collapse = function(option) {
        return this.each(function() {
            var $this = $(this)
            var data = $this.data(zuiname)
            var options = $.extend({}, Collapse.DEFAULTS, $this.data(), typeof option == 'object' && option)

            if(!data) $this.data(zuiname, (data = new Collapse(this, options)))
            if(typeof option == 'string') data[option]()
        })
    }

    $.fn.collapse.Constructor = Collapse


    // COLLAPSE NO CONFLICT
    // ====================

    $.fn.collapse.noConflict = function() {
        $.fn.collapse = old
        return this
    }


    // COLLAPSE DATA-API
    // =================

    $(document).on('click.' + zuiname + '.data-api', '[data-toggle=collapse]', function(e) {
        var $this = $(this),
            href
        var target = $this.attr('data-target') || e.preventDefault() || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '') //strip for ie7
        var $target = $(target)
        var data = $target.data(zuiname)
        var option = data ? 'toggle' : $this.data()
        var parent = $this.attr('data-parent')
        var $parent = parent && $(parent)

        if(!data || !data.transitioning) {
            if($parent) $parent.find('[data-toggle=collapse][data-parent="' + parent + '"]').not($this).addClass('collapsed')
            $this[$target.hasClass('in') ? 'addClass' : 'removeClass']('collapsed')
        }

        $target.collapse(option)
    })

}(window.jQuery);


/* ========================================================================
 * ZUI: device.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014-2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function(window, $) {
    'use strict';
    var desktopLg = 1200,
        desktop = 992,
        tablet = 768;

    var $window = $(window);

    var resetCssClass = function() {
        var width = $window.width();
        $('html').toggleClass('screen-desktop', width >= desktop && width < desktopLg)
            .toggleClass('screen-desktop-wide', width >= desktopLg)
            .toggleClass('screen-tablet', width >= tablet && width < desktop)
            .toggleClass('screen-phone', width < tablet)
            .toggleClass('device-mobile', width < desktop)
            .toggleClass('device-desktop', width >= desktop);
    };

    var classNames = '';
    var userAgent = navigator.userAgent;
    if (userAgent.match(/(iPad|iPhone|iPod)/i)) {
        classNames += ' os-ios';
    } else if (userAgent.match(/android/i)) {
        classNames += ' os-android';
    } else if (userAgent.match(/Win/i)) {
        classNames += ' os-windows';
    } else if (userAgent.match(/Mac/i)) {
        classNames += ' os-mac';
    } else if (userAgent.match(/Linux/i)) {
        classNames += ' os-linux';
    } else if (userAgent.match(/X11/i)) {
        classNames += ' os-unix';
    }
    if ('ontouchstart' in document.documentElement) {
        classNames += ' is-touchable';
    }
    $('html').addClass(classNames);

    $window.resize(resetCssClass);
    resetCssClass();
}(window, jQuery));


/* ========================================================================
 * ZUI: browser.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function ($) {
    'use strict';

    var browseHappyTip = {
        'zh_cn': '您的浏览器版本过低，无法体验所有功能，建议升级或者更换浏览器。 <a href="http://browsehappy.com/" target="_blank" class="alert-link">了解更多...</a>',
        'zh_tw': '您的瀏覽器版本過低，無法體驗所有功能，建議升級或者更换瀏覽器。<a href="http://browsehappy.com/" target="_blank" class="alert-link">了解更多...</a>',
        'en': 'Your browser is too old, it has been unable to experience the colorful internet. We strongly recommend that you upgrade a better one. <a href="http://browsehappy.com/" target="_blank" class="alert-link">Learn more...</a>'
    };

    // The browser modal class
    var Browser = function () {
        var ie = this.isIE() || this.isIE10() || false;
        if (ie) {
            for (var i = 10; i > 5; i--) {
                if (this.isIE(i)) {
                    ie = i;
                    break;
                }
            }
        }

        this.ie = ie;

        this.cssHelper();
    };

    // Append CSS class to html tag
    Browser.prototype.cssHelper = function () {
        var ie = this.ie,
            $html = $('html');
        $html.toggleClass('ie', ie)
            .removeClass('ie-6 ie-7 ie-8 ie-9 ie-10');
        if (ie) {
            $html.addClass('ie-' + ie)
                .toggleClass('gt-ie-7 gte-ie-8 support-ie', ie >= 8)
                .toggleClass('lte-ie-7 lt-ie-8 outdated-ie', ie < 8)
                .toggleClass('gt-ie-8 gte-ie-9', ie >= 9)
                .toggleClass('lte-ie-8 lt-ie-9', ie < 9)
                .toggleClass('gt-ie-9 gte-ie-10', ie >= 10)
                .toggleClass('lte-ie-9 lt-ie-10', ie < 10);
        }
    };

    // Show browse happy tip
    Browser.prototype.tip = function (showCoontent) {
        var $browseHappy = $('#browseHappyTip');
        if (!$browseHappy.length) {
            $browseHappy = $('<div id="browseHappyTip" class="alert alert-dismissable alert-danger-inverse alert-block" style="position: relative; z-index: 99999"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><div class="container"><div class="content text-center"></div></div></div>');
            $browseHappy.prependTo('body');
        }

        $browseHappy.find('.content').html(showCoontent || this.browseHappyTip || browseHappyTip[$.zui.clientLang() || 'zh_cn']);
    };

    // Detect it is IE, can given a version
    Browser.prototype.isIE = function (version) {
        if (version === 10) return this.isIE10();
        var b = document.createElement('b');
        b.innerHTML = '<!--[if IE ' + (version || '') + ']><i></i><![endif]-->';
        return b.getElementsByTagName('i').length === 1;
    };

    // Detect ie 10 with hack
    Browser.prototype.isIE10 = function () {
        return (/*@cc_on!@*/false);
    };

    $.zui({
        browser: new Browser()
    });

    $(function () {
        if (!$('body').hasClass('disabled-browser-tip')) {
            if ($.zui.browser.ie && $.zui.browser.ie < 8) {
                $.zui.browser.tip();
            }
        }
    });
}(jQuery));


/* ========================================================================
 * ZUI: date.js
 * Date polyfills
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function() {
    'use strict';

    /**
     * Ticks of a whole day
     * @type {number}
     */
    Date.ONEDAY_TICKS = 24 * 3600 * 1000;

    /**
     * Format date to a string
     *
     * @param  string   format
     * @return string
     */
    if(!Date.prototype.format) {
        Date.prototype.format = function(format) {
            var date = {
                'M+': this.getMonth() + 1,
                'd+': this.getDate(),
                'h+': this.getHours(),
                'm+': this.getMinutes(),
                's+': this.getSeconds(),
                'q+': Math.floor((this.getMonth() + 3) / 3),
                'S+': this.getMilliseconds()
            };
            if(/(y+)/i.test(format)) {
                format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
            }
            for(var k in date) {
                if(new RegExp('(' + k + ')').test(format)) {
                    format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? date[k] : ('00' + date[k]).substr(('' + date[k]).length));
                }
            }
            return format;
        };
    }

    /**
     * Add milliseconds to the date
     * @param {number} value
     */
    if(!Date.prototype.addMilliseconds) {
        Date.prototype.addMilliseconds = function(value) {
            this.setTime(this.getTime() + value);
            return this;
        };
    }


    /**
     * Add days to the date
     * @param {number} days
     */
    if(!Date.prototype.addDays) {
        Date.prototype.addDays = function(days) {
            this.addMilliseconds(days * Date.ONEDAY_TICKS);
            return this;
        };
    }


    /**
     * Clone a new date instane from the date
     * @return {Date}
     */
    if(!Date.prototype.clone) {
        Date.prototype.clone = function() {
            var date = new Date();
            date.setTime(this.getTime());
            return date;
        };
    }


    /**
     * Judge the year is in a leap year
     * @param  {integer}  year
     * @return {Boolean}
     */
    if(!Date.isLeapYear) {
        Date.isLeapYear = function(year) {
            return(((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0));
        };
    }

    if(!Date.getDaysInMonth) {
        /**
         * Get days number of the date
         * @param  {integer} year
         * @param  {integer} month
         * @return {integer}
         */
        Date.getDaysInMonth = function(year, month) {
            return [31, (Date.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
        };
    }


    /**
     * Judge the date is in a leap year
     * @return {Boolean}
     */
    if(!Date.prototype.isLeapYear) {
        Date.prototype.isLeapYear = function() {
            return Date.isLeapYear(this.getFullYear());
        };
    }


    /**
     * Clear time part of the date
     * @return {date}
     */
    if(!Date.prototype.clearTime) {
        Date.prototype.clearTime = function() {
            this.setHours(0);
            this.setMinutes(0);
            this.setSeconds(0);
            this.setMilliseconds(0);
            return this;
        };
    }


    /**
     * Get days of this month of the date
     * @return {integer}
     */
    if(!Date.prototype.getDaysInMonth) {
        Date.prototype.getDaysInMonth = function() {
            return Date.getDaysInMonth(this.getFullYear(), this.getMonth());
        };
    }


    /**
     * Add months to the date
     * @param {date} value
     */
    if(!Date.prototype.addMonths) {
        Date.prototype.addMonths = function(value) {
            var n = this.getDate();
            this.setDate(1);
            this.setMonth(this.getMonth() + value);
            this.setDate(Math.min(n, this.getDaysInMonth()));
            return this;
        };
    }


    /**
     * Get last week day of the date
     * @param  {integer} day
     * @return {date}
     */
    if(!Date.prototype.getLastWeekday) {
        Date.prototype.getLastWeekday = function(day) {
            day = day || 1;

            var d = this.clone();
            while(d.getDay() != day) {
                d.addDays(-1);
            }
            d.clearTime();
            return d;
        };
    }


    /**
     * Judge the date is same day as another date
     * @param  {date}  date
     * @return {Boolean}
     */
    if(!Date.prototype.isSameDay) {
        Date.prototype.isSameDay = function(date) {
            return date.toDateString() === this.toDateString();
        };
    }


    /**
     * Judge the date is in same week as another date
     * @param  {date}  date
     * @return {Boolean}
     */
    if(!Date.prototype.isSameWeek) {
        Date.prototype.isSameWeek = function(date) {
            var weekStart = this.getLastWeekday();
            var weekEnd = weekStart.clone().addDays(7);
            return date >= weekStart && date < weekEnd;
        };
    }


    /**
     * Judge the date is in same year as another date
     * @param  {date}  date
     * @return {Boolean}
     */
    if(!Date.prototype.isSameYear) {
        Date.prototype.isSameYear = function(date) {
            return this.getFullYear() === date.getFullYear();
        };
    }

    /**
     * Create an date instance with string, timestamp or date instance
     * @param  {Date|String|Number}  date
     * @return {Date}
     */
    if (!Date.create) {
        Date.create = function(date) {
            if (!(date instanceof Date)) {
                if (typeof date === 'number' && date < 10000000000) {
                    date *= 1000;
                }
                date = new Date(date);
            }
            return date;
        };
    }

    if (!Date.timestamp) {
        Date.timestamp = function(date) {
            if (typeof date === 'number') {
                if (date < 10000000000) {
                    date *= 1000;
                }
            } else {
                date = Date.create(date).getTime();
            }
            return date;
        };
    }
}());


/* ========================================================================
 * ZUI: string.js
 * String Polyfill.
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014-2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function() {
    'use strict';

    /**
     * Format string with argument list or object
     * @param  {object | arguments} args
     * @return {String}
     */
    if(!String.prototype.format) {
        String.prototype.format = function(args) {
            var result = this;
            if(arguments.length > 0) {
                var reg;
                if(arguments.length <= 2 && typeof(args) == 'object') {
                    for(var key in args) {
                        if(args[key] !== undefined) {
                            reg = new RegExp('(' + (arguments[1] ? arguments[1].replace('0', key) : '{' + key + '}') + ')', 'g');
                            result = result.replace(reg, args[key]);
                        }
                    }
                } else {
                    for(var i = 0; i < arguments.length; i++) {
                        if(arguments[i] !== undefined) {
                            reg = new RegExp('({[' + i + ']})', 'g');
                            result = result.replace(reg, arguments[i]);
                        }
                    }
                }
            }
            return result;
        };
    }

    /**
     * Judge the string is a integer number
     *
     * @access public
     * @return bool
     */
    if(!String.prototype.isNum) {
        String.prototype.isNum = function(s) {
            if(s !== null) {
                var r, re;
                re = /\d*/i;
                r = s.match(re);
                return(r == s) ? true : false;
            }
            return false;
        };
    }

    if(!String.prototype.endsWith) {
        String.prototype.endsWith = function(searchString, position) {
            var subjectString = this.toString();
            if(position === undefined || position > subjectString.length) {
                position = subjectString.length;
            }
            position -= searchString.length;
            var lastIndex = subjectString.indexOf(searchString, position);
            return lastIndex !== -1 && lastIndex === position;
        };
    }

    if(!String.prototype.startsWith) {
        String.prototype.startsWith = function(searchString, position) {
            position = position || 0;
            return this.lastIndexOf(searchString, position) === position;
        };
    }

    if(!String.prototype.includes) {
        String.prototype.includes = function() {
            return String.prototype.indexOf.apply(this, arguments) !== -1;
        };
    }

})();


/* ========================================================================
 * Resize: resize.js [Version: 1.1]
 * http://benalman.com/projects/jquery-resize-plugin/
 *  
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * official version in the future.
 * http://zui.sexy
 * ========================================================================
 * opyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 * ======================================================================== */


/*!
 * jQuery resize event - v1.1
 * http://benalman.com/projects/jquery-resize-plugin/
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * MIT & GPL http://benalman.com/about/license/
 */

// Script: jQuery resize event
//
// *Version: 1.1, Last updated: 3/14/2010*
//
// Project Home - http://benalman.com/projects/jquery-resize-plugin/
// GitHub       - http://github.com/cowboy/jquery-resize/
// Source       - http://github.com/cowboy/jquery-resize/raw/master/jquery.ba-resize.js
// (Minified)   - http://github.com/cowboy/jquery-resize/raw/master/jquery.ba-resize.min.js (1.0kb)
//
// About: License
//
// Copyright (c) 2010 "Cowboy" Ben Alman,
// Dual licensed under the MIT and GPL licenses.
// http://benalman.com/about/license/
//
// About: Examples
//
// This working example, complete with fully commented code, illustrates a few
// ways in which this plugin can be used.
//
// resize event - http://benalman.com/code/projects/jquery-resize/examples/resize/
//
// About: Support and Testing
//
// Information about what version or versions of jQuery this plugin has been
// tested with, what browsers it has been tested in, and where the unit tests
// reside (so you can test it yourself).
//
// jQuery Versions - 1.3.2, 1.4.1, 1.4.2
// Browsers Tested - Internet Explorer 6-8, Firefox 2-3.6, Safari 3-4, Chrome, Opera 9.6-10.1.
// Unit Tests      - http://benalman.com/code/projects/jquery-resize/unit/
//
// About: Release History
//
// 1.1 - (3/14/2010) Fixed a minor bug that was causing the event to trigger
//       immediately after bind in some circumstances. Also changed $.fn.data
//       to $.data to improve performance.
// 1.0 - (2/10/2010) Initial release

(function($, window, undefined) {
    '$:nomunge'; // Used by YUI compressor.

    // A jQuery object containing all non-window elements to which the resize
    // event is bound.
    var elems = $([]),

        // Extend $.resize if it already exists, otherwise create it.
        jq_resize = $.resize = $.extend($.resize, {}),

        timeout_id,

        // Reused strings.
        str_setTimeout = 'setTimeout',
        str_resize = 'resize',
        str_data = str_resize + '-special-event',
        str_delay = 'delay',
        str_throttle = 'throttleWindow';

    // Property: jQuery.resize.delay
    //
    // The numeric interval (in milliseconds) at which the resize event polling
    // loop executes. Defaults to 250.

    jq_resize[str_delay] = 250;

    // Property: jQuery.resize.throttleWindow
    //
    // Throttle the native window object resize event to fire no more than once
    // every <jQuery.resize.delay> milliseconds. Defaults to true.
    //
    // Because the window object has its own resize event, it doesn't need to be
    // provided by this plugin, and its execution can be left entirely up to the
    // browser. However, since certain browsers fire the resize event continuously
    // while others do not, enabling this will throttle the window resize event,
    // making event behavior consistent across all elements in all browsers.
    //
    // While setting this property to false will disable window object resize
    // event throttling, please note that this property must be changed before any
    // window object resize event callbacks are bound.

    jq_resize[str_throttle] = true;

    // Event: resize event
    //
    // Fired when an element's width or height changes. Because browsers only
    // provide this event for the window element, for other elements a polling
    // loop is initialized, running every <jQuery.resize.delay> milliseconds
    // to see if elements' dimensions have changed. You may bind with either
    // .resize( fn ) or .bind( "resize", fn ), and unbind with .unbind( "resize" ).
    //
    // Usage:
    //
    // > jQuery('selector').bind( 'resize', function(e) {
    // >   // element's width or height has changed!
    // >   ...
    // > });
    //
    // Additional Notes:
    //
    // * The polling loop is not created until at least one callback is actually
    //   bound to the 'resize' event, and this single polling loop is shared
    //   across all elements.
    //
    // Double firing issue in jQuery 1.3.2:
    //
    // While this plugin works in jQuery 1.3.2, if an element's event callbacks
    // are manually triggered via .trigger( 'resize' ) or .resize() those
    // callbacks may double-fire, due to limitations in the jQuery 1.3.2 special
    // events system. This is not an issue when using jQuery 1.4+.
    //
    // > // While this works in jQuery 1.4+
    // > $(elem).css({ width: new_w, height: new_h }).resize();
    // >
    // > // In jQuery 1.3.2, you need to do this:
    // > var elem = $(elem);
    // > elem.css({ width: new_w, height: new_h });
    // > elem.data( 'resize-special-event', { width: elem.width(), height: elem.height() } );
    // > elem.resize();

    $.event.special[str_resize] = {

        // Called only when the first 'resize' event callback is bound per element.
        setup: function() {
            // Since window has its own native 'resize' event, return false so that
            // jQuery will bind the event using DOM methods. Since only 'window'
            // objects have a .setTimeout method, this should be a sufficient test.
            // Unless, of course, we're throttling the 'resize' event for window.
            if(!jq_resize[str_throttle] && this[str_setTimeout]) {
                return false;
            }

            var elem = $(this);

            // Add this element to the list of internal elements to monitor.
            elems = elems.add(elem);

            // Initialize data store on the element.
            $.data(this, str_data, {
                w: elem.width(),
                h: elem.height()
            });

            // If this is the first element added, start the polling loop.
            if(elems.length === 1) {
                loopy();
            }
        },

        // Called only when the last 'resize' event callback is unbound per element.
        teardown: function() {
            // Since window has its own native 'resize' event, return false so that
            // jQuery will unbind the event using DOM methods. Since only 'window'
            // objects have a .setTimeout method, this should be a sufficient test.
            // Unless, of course, we're throttling the 'resize' event for window.
            if(!jq_resize[str_throttle] && this[str_setTimeout]) {
                return false;
            }

            var elem = $(this);

            // Remove this element from the list of internal elements to monitor.
            elems = elems.not(elem);

            // Remove any data stored on the element.
            elem.removeData(str_data);

            // If this is the last element removed, stop the polling loop.
            if(!elems.length) {
                clearTimeout(timeout_id);
            }
        },

        // Called every time a 'resize' event callback is bound per element (new in
        // jQuery 1.4).
        add: function(handleObj) {
            // Since window has its own native 'resize' event, return false so that
            // jQuery doesn't modify the event object. Unless, of course, we're
            // throttling the 'resize' event for window.
            if(!jq_resize[str_throttle] && this[str_setTimeout]) {
                return false;
            }

            var old_handler;

            // The new_handler function is executed every time the event is triggered.
            // This is used to update the internal element data store with the width
            // and height when the event is triggered manually, to avoid double-firing
            // of the event callback. See the "Double firing issue in jQuery 1.3.2"
            // comments above for more information.

            function new_handler(e, w, h) {
                var elem = $(this),
                    data = $.data(this, str_data) || {};

                // If called from the polling loop, w and h will be passed in as
                // arguments. If called manually, via .trigger( 'resize' ) or .resize(),
                // those values will need to be computed.
                data.w = w !== undefined ? w : elem.width();
                data.h = h !== undefined ? h : elem.height();

                old_handler.apply(this, arguments);
            };

            // This may seem a little complicated, but it normalizes the special event
            // .add method between jQuery 1.4/1.4.1 and 1.4.2+
            if($.isFunction(handleObj)) {
                // 1.4, 1.4.1
                old_handler = handleObj;
                return new_handler;
            } else {
                // 1.4.2+
                old_handler = handleObj.handler;
                handleObj.handler = new_handler;
            }
        }

    };

    function loopy() {

        // Start the polling loop, asynchronously.
        timeout_id = window[str_setTimeout](function() {

            // Iterate over all elements to which the 'resize' event is bound.
            elems.each(function() {
                var elem = $(this),
                    width = elem.width(),
                    height = elem.height(),
                    data = $.data(this, str_data);

                // If element size has changed since the last time, update the element
                // data store and trigger the 'resize' event.
                if(width !== data.w || height !== data.h) {
                    elem.trigger(str_resize, [data.w = width, data.h = height]);
                }

            });

            // Loop.
            loopy();

        }, jq_resize[str_delay]);

    };

})(jQuery, this);


/* ========================================================================
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 * 
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * official version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 * ======================================================================== */


/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 * Copyright 2006, 2014 Klaus Hartl
 * Released under the MIT license
 */

(function(factory) {
    if(typeof define === 'function' && define.amd) {
        // AMD
        define(['jquery'], factory);
    } else if(typeof exports === 'object') {
        // CommonJS
        factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function($) {

    var pluses = /\+/g;

    function encode(s) {
        return config.raw ? s : encodeURIComponent(s);
    }

    function decode(s) {
        return config.raw ? s : decodeURIComponent(s);
    }

    function stringifyCookieValue(value) {
        return encode(config.json ? JSON.stringify(value) : String(value));
    }

    function parseCookieValue(s) {
        if(s.indexOf('"') === 0) {
            // This is a quoted cookie as according to RFC2068, unescape...
            s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
        }

        try {
            // Replace server-side written pluses with spaces.
            // If we can't decode the cookie, ignore it, it's unusable.
            // If we can't parse the cookie, ignore it, it's unusable.
            s = decodeURIComponent(s.replace(pluses, ' '));
            return config.json ? JSON.parse(s) : s;
        } catch(e) {}
    }

    function read(s, converter) {
        var value = config.raw ? s : parseCookieValue(s);
        return $.isFunction(converter) ? converter(value) : value;
    }

    var config = $.cookie = function(key, value, options) {

        // Write

        if(value !== undefined && !$.isFunction(value)) {
            options = $.extend({}, config.defaults, options);

            if(typeof options.expires === 'number') {
                var days = options.expires,
                    t = options.expires = new Date();
                t.setTime(+t + days * 864e+5);
            }

            return(document.cookie = [
                encode(key), '=', stringifyCookieValue(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
            ].join(''));
        }

        // Read

        var result = key ? undefined : {};

        // To prevent the for loop in the first place assign an empty array
        // in case there are no cookies at all. Also prevents odd result when
        // calling $.cookie().
        var cookies = document.cookie ? document.cookie.split('; ') : [];

        for(var i = 0, l = cookies.length; i < l; i++) {
            var parts = cookies[i].split('=');
            var name = decode(parts.shift());
            var cookie = parts.join('=');

            if(key && key === name) {
                // If second argument (value) is a function it's a converter...
                result = read(cookie, value);
                break;
            }

            // Prevent storing a cookie that we couldn't decode.
            if(!key && (cookie = read(cookie)) !== undefined) {
                result[name] = cookie;
            }
        }

        return result;
    };

    config.defaults = {};

    $.removeCookie = function(key, options) {
        if($.cookie(key) === undefined) {
            return false;
        }

        // Must not alter options, thus extending a fresh object...
        $.cookie(key, '', $.extend({}, options, {
            expires: -1
        }));
        return !$.cookie(key);
    };
}));

/* ========================================================================
 * ZUI: storeb.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014-2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function(window, $) {
    'use strict';

    var lsName = 'localStorage';
    var storage,
        dataset,
        pageName = 'page_' + window.location.pathname + window.location.search;

    /* The Store object */
    var Store = function() {
        this.slience = true;
        try {
            if((lsName in window) && window[lsName] && window[lsName].setItem) {
                this.enable = true;
                storage = window[lsName];
            }
        } catch(e){}
        if(!this.enable) {
            dataset = {};
            storage = {
                getLength: function() {
                    var length = 0;
                    $.each(dataset, function() {
                        length++;
                    });
                    return length;
                },
                key: function(index) {
                    var key, i = 0;
                    $.each(dataset, function(k) {
                        if(i === index) {
                            key = k;
                            return false;
                        }
                        i++;
                    });
                    return key;
                },
                removeItem: function(key) {
                    delete dataset[key];
                },
                getItem: function(key) {
                    return dataset[key];
                },
                setItem: function(key, val) {
                    dataset[key] = val;
                },
                clear: function() {
                    dataset = {};
                }
            };
        }
        this.storage = storage;
        this.page = this.get(pageName, {});
    };

    /* Save page data */
    Store.prototype.pageSave = function() {
        if($.isEmptyObject(this.page)) {
            this.remove(pageName);
        } else {
            var forDeletes = [],
                i;
            for(i in this.page) {
                var val = this.page[i];
                if(val === null)
                    forDeletes.push(i);
            }
            for(i = forDeletes.length - 1; i >= 0; i--) {
                delete this.page[forDeletes[i]];
            }
            this.set(pageName, this.page);
        }
    };

    /* Remove page data item */
    Store.prototype.pageRemove = function(key) {
        if(typeof this.page[key] != 'undefined') {
            this.page[key] = null;
            this.pageSave();
        }
    };

    /* Clear page data */
    Store.prototype.pageClear = function() {
        this.page = {};
        this.pageSave();
    };

    /* Get page data */
    Store.prototype.pageGet = function(key, defaultValue) {
        var val = this.page[key];
        return(defaultValue !== undefined && (val === null || val === undefined)) ? defaultValue : val;
    };

    /* Set page data */
    Store.prototype.pageSet = function(objOrKey, val) {
        if($.isPlainObject(objOrKey)) {
            $.extend(true, this.page, objOrKey);
        } else {
            this.page[this.serialize(objOrKey)] = val;
        }
        this.pageSave();
    };

    /* Check enable status */
    Store.prototype.check = function() {
        if(!this.enable) {
            if(!this.slience) throw new Error('Browser not support localStorage or enable status been set true.');
        }
        return this.enable;
    };

    /* Get length */
    Store.prototype.length = function() {
        if(this.check()) {
            return storage.getLength ? storage.getLength() : storage.length;
        }
        return 0;
    };

    /* Remove item with browser localstorage native method */
    Store.prototype.removeItem = function(key) {
        storage.removeItem(key);
        return this;
    };

    /* Remove item with browser localstorage native method, same as removeItem */
    Store.prototype.remove = function(key) {
        return this.removeItem(key);
    };

    /* Get item value with browser localstorage native method, and without deserialize */
    Store.prototype.getItem = function(key) {
        return storage.getItem(key);
    };

    /* Get item value and deserialize it, if value is null and defaultValue been given then return defaultValue */
    Store.prototype.get = function(key, defaultValue) {
        var val = this.deserialize(this.getItem(key));
        if(typeof val === 'undefined' || val === null) {
            if(typeof defaultValue !== 'undefined') {
                return defaultValue;
            }
        }
        return val;
    };

    /* Get item key by index and deserialize it */
    Store.prototype.key = function(index) {
        return storage.key(index);
    };

    /* Set item value with browser localstorage native method, and without serialize filter */
    Store.prototype.setItem = function(key, val) {
        storage.setItem(key, val);
        return this;
    };

    /* Set item value, serialize it if the given value is not an string */
    Store.prototype.set = function(key, val) {
        if(val === undefined) return this.remove(key);
        this.setItem(key, this.serialize(val));
        return this;
    };

    /* Clear all items with browser localstorage native method */
    Store.prototype.clear = function() {
        storage.clear();
        return this;
    };

    /* Iterate all items with callback */
    Store.prototype.forEach = function(callback) {
        var length = this.length();
        for(var i = length - 1; i >= 0; i--) {
            var key = storage.key(i);
            callback(key, this.get(key));
        }
        return this;
    };

    /* Get all items and set value in an object. */
    Store.prototype.getAll = function() {
        var all = {};
        this.forEach(function(key, val) {
            all[key] = val;
        });

        return all;
    };

    /* Serialize value with JSON.stringify */
    Store.prototype.serialize = function(value) {
        if(typeof value === 'string') return value;
        return JSON.stringify(value);
    };

    /* Deserialize value, with JSON.parse if the given value is not a string */
    Store.prototype.deserialize = function(value) {
        if(typeof value !== 'string') return undefined;
        try {
            return JSON.parse(value);
        } catch(e) {
            return value || undefined;
        }
    };

    $.zui({
        store: new Store()
    });
}(window, jQuery));


/* ========================================================================
 * ZUI: searchbox.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014-2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($) {
    'use strict';

    var NAME = 'zui.searchBox'; // modal name

    // The searchbox modal class
    var SearchBox = function(element, options) {
        var that = this;
        that.name = name;
        that.$ = $(element);

        that.options = options = $.extend({}, SearchBox.DEFAULTS, that.$.data(), options);

        // Initialize here
        var $input = that.$.is(options.inputSelector) ? that.$ : that.$.find(options.inputSelector);
        if ($input.length) {
            var clearChangeTimer = function() {
                if (that.changeTimer) {
                    clearTimeout(that.changeTimer);
                    that.changeTimer = null;
                }
            };

            var handleChange = function() {
                clearChangeTimer();
                var value = that.getSearch();
                if (value !== that.lastValue) {
                    var isEmpty = value === '';
                    $input.toggleClass('empty', isEmpty);
                    that.$.callComEvent(that, 'onSearchChange', [value, isEmpty]);
                    that.lastValue = value;
                }
            };

            that.$input = $input = $input.first();

            $input.on(options.listenEvent, function(params) {
                that.changeTimer = setTimeout(function() {
                    handleChange();
                }, options.changeDelay);
            }).on('focus', function(e) {
                $input.addClass('focus');
                that.$.callComEvent(that, 'onFocus', [e]);
            }).on('blur', function(e) {
                $input.removeClass('focus');
                that.$.callComEvent(that, 'onBlur', [e]);
            }).on('keydown', function(e) {
                var handled = 0;
                var keyCode = e.which;
                if (keyCode === 27 && options.escToClear) { // esc
                    this.setSearch('', true);
                    handleChange();
                    handled = 1;
                } else if (keyCode === 13 && options.onPressEnter) {
                    handleChange();
                    that.$.callComEvent(that, 'onPressEnter', [e]);
                }
                var onKeyDownResult = that.$.callComEvent(that, 'onKeyDown', [e]);
                if (onKeyDownResult === false) {
                    handled = 1;
                }
                if (handled) {
                    e.preventDefault();
                }
            });

            that.$.on('click', '.search-clear-btn', function(e) {
                that.setSearch('', true);
                handleChange();
                that.focus();
                e.preventDefault();
            });

            handleChange();
        } else {
            console.error('ZUI: search box init error, cannot find search box input element.');
        }
    };

    // default options
    SearchBox.DEFAULTS = {
        inputSelector: 'input[type="search"],input[type="text"]',
        listenEvent: 'change input paste',
        changeDelay: 500,

        // onKeyDown: null,
        // onFocus: null,
        // onBlur: null,
        // onSearchChange: null,
        // onPressEnter: null,
        // escToClear: true
    };

    // Get current search string
    SearchBox.prototype.getSearch = function() {
        return this.$input && $.trim(this.$input.val());
    };

    // Set current search string
    SearchBox.prototype.setSearch = function(value, notTriggerChange) {
        var $input = this.$input;
        if ($input) {
            $input.val(value);
            if (!notTriggerChange) {
                $input.trigger('change');
            }
        }
    };

    // Focus input element
    SearchBox.prototype.focus = function() {
        this.$input && this.$input.focus();
    };

    // Extense jquery element
    $.fn.searchBox = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(NAME);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(NAME, (data = new SearchBox(this, options)));

            if(typeof option == 'string') data[option]();
        });
    };

    SearchBox.NAME = NAME;

    $.fn.searchBox.Constructor = SearchBox;
}(jQuery));


/* ========================================================================
 * ZUI: draggable.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014-2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($, document) {
    'use strict';

    var NAME     = 'zui.draggable',
        DEFAULTS = {
        // selector: '',
        container: 'body',
        move: true
        // mouseButton: -1 // 0, 1, 2, -1, all, left,  right, middle
    };
    var idIncrementer = 0;

    var Draggable = function(element, options) {
        var that     = this;
        that.$       = $(element);
        that.id      = idIncrementer++;
        that.options = $.extend({}, DEFAULTS, that.$.data(), options);
        that.init();
    };

    Draggable.DEFAULTS = DEFAULTS;
    Draggable.NAME     = NAME;

    Draggable.prototype.init = function() {
        var that           = this,
            $root          = that.$,
            BEFORE         = 'before',
            DRAG           = 'drag',
            FINISH         = 'finish',
            eventSuffix    = '.' + NAME + '.' + that.id,
            mouseDownEvent = 'mousedown' + eventSuffix,
            mouseUpEvent   = 'mouseup' + eventSuffix,
            mouseMoveEvent = 'mousemove' + eventSuffix,
            setting        = that.options,
            selector       = setting.selector,
            handle         = setting.handle,
            $ele           = $root,
            isMoveFunc     = $.isFunction(setting.move),
            startPos,
            cPos,
            startOffset,
            mousePos,
            moved;

        var mouseMove = function(event) {
            var mX      = event.pageX,
                mY      = event.pageY;
                moved   = true;
            var dragPos = {
                left: mX - startOffset.x,
                top: mY - startOffset.y
            };

            $ele.removeClass('drag-ready').addClass('dragging');
            if(setting.move) {
                if (isMoveFunc) {
                    setting.move(dragPos, $ele);
                } else {
                    $ele.css(dragPos);
                }
            }

            setting[DRAG] && setting[DRAG]({
                event: event,
                element: $ele,
                startOffset: startOffset,
                pos: dragPos,
                offset: {
                    x: mX - startPos.x,
                    y: mY - startPos.y
                },
                smallOffset: {
                    x: mX - mousePos.x,
                    y: mY - mousePos.y
                }
            });
            mousePos.x = mX;
            mousePos.y = mY;

            if(setting.stopPropagation) {
                event.stopPropagation();
            }
        };

        var mouseUp = function(event) {
            $(document).off(eventSuffix);
            if(!moved) {
                $ele.removeClass('drag-ready');
                return;
            }
            var endPos = {
                left: event.pageX - startOffset.x,
                top: event.pageY - startOffset.y
            };
            $ele.removeClass('drag-ready dragging');
            if(setting.move) {
                if (isMoveFunc) {
                    setting.move(endPos, $ele);
                } else {
                    $ele.css(endPos);
                }
            }

            setting[FINISH] && setting[FINISH]({
                event: event,
                element: $ele,
                startOffset: startOffset,
                pos: endPos,
                offset: {
                    x: event.pageX - startPos.x,
                    y: event.pageY - startPos.y
                },
                smallOffset: {
                    x: event.pageX - mousePos.x,
                    y: event.pageY - mousePos.y
                }
            });
            event.preventDefault();
            if(setting.stopPropagation) {
                event.stopPropagation();
            }
        };

        var mouseDown = function(event) {
            var mouseButton = $.zui.getMouseButtonCode(setting.mouseButton);
            if(mouseButton > -1 && event.button !== mouseButton) {
                return;
            }

            var $mouseDownEle = $(this);
            if(selector) {
                $ele = handle ? $mouseDownEle.closest(selector) : $mouseDownEle;
            }

            if(setting[BEFORE]) {
                var isSure = setting[BEFORE]({
                    event: event,
                    element: $ele
                });
                if(isSure === false) return;
            }

            var $container = $(setting.container),
                pos        = $ele.offset();
                cPos       = $container.offset();
                startPos   = {
                    x: event.pageX,
                    y: event.pageY
                };
                startOffset = {
                    x: event.pageX - pos.left + cPos.left,
                    y: event.pageY - pos.top + cPos.top
                };
                mousePos    = $.extend({}, startPos);
                moved       = false;

            $ele.addClass('drag-ready');
            event.preventDefault();

            if(setting.stopPropagation) {
                event.stopPropagation();
            }

            $(document).on(mouseMoveEvent, mouseMove).on(mouseUpEvent, mouseUp);
        };

        if(handle) {
            $root.on(mouseDownEvent, handle, mouseDown);
        } else if(selector) {
            $root.on(mouseDownEvent, selector, mouseDown);
        } else {
            $root.on(mouseDownEvent, mouseDown);
        }
    };

    Draggable.prototype.destroy = function() {
        var eventSuffix = '.' + NAME + '.' + this.id;
        this.$.off(eventSuffix);
        $(document).off(eventSuffix);
        this.$.data(NAME, null);
    };

    $.fn.draggable = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(NAME);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(NAME, (data = new Draggable(this, options)));
            if(typeof option == 'string') data[option]();
        });
    };

    $.fn.draggable.Constructor = Draggable;
}(jQuery, document));


/* ========================================================================
 * ZUI: droppable.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014-2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($, document, Math) {
    'use strict';

    var NAME     = 'zui.droppable',
        DEFAULTS = {
        // container: '',
        // selector: '',
        // handle: '',
        // flex: false,
        // nested: false,
        target: '.droppable-target',
        deviation: 5,
        sensorOffsetX: 0,
        sensorOffsetY: 0,
        dropToClass: 'drop-to',
         // mouseButton: -1 // 0, 1, 2, -1, all, left,  right, middle
    };
    var idIncrementer = 0;

    var Droppable = function(element, options) {
        var that     = this;
        that.id      = idIncrementer++;
        that.$       = $(element);
        that.options = $.extend({}, DEFAULTS, that.$.data(), options);
        that.init();
    };

    Droppable.DEFAULTS = DEFAULTS;
    Droppable.NAME     = NAME;

    Droppable.prototype.trigger = function(name, params) {
        return $.zui.callEvent(this.options[name], params, this);
    };

    Droppable.prototype.init = function() {
        var that           = this,
            $root          = that.$,
            setting        = that.options,
            deviation      = setting.deviation,
            eventSuffix    = '.' + NAME + '.' + that.id,
            mouseDownEvent = 'mousedown' + eventSuffix,
            mouseUpEvent   = 'mouseup' + eventSuffix,
            mouseMoveEvent = 'mousemove' + eventSuffix,
            selector       = setting.selector,
            handle         = setting.handle,
            flex           = setting.flex,
            container      = setting.container,
            canMoveHere    = setting.canMoveHere,
            dropToClass    = setting.dropToClass,
            $ele           = $root,
            isMouseDown    = false,
            $container     = container ? $(setting.container).first() : (selector ? $root : $('body')),
            $targets,
            $target,
            $shadow,
            isIn,
            isSelf,
            oldCssPosition,
            startOffset,
            startMouseOffset,
            containerOffset,
            clickOffset,
            mouseOffset,
            lastMouseOffset,
            mouseDownBackEventCall;

        var mouseMove = function(event) {
            if(!isMouseDown) return;

            mouseOffset = {left: event.pageX, top: event.pageY};

            // ignore small move
            if(Math.abs(mouseOffset.left - startMouseOffset.left) < deviation && Math.abs(mouseOffset.top - startMouseOffset.top) < deviation) return;

            if($shadow === null) // create shadow
            {
                var cssPosition = $container.css('position');
                if(cssPosition != 'absolute' && cssPosition != 'relative' && cssPosition != 'fixed') {
                    oldCssPosition = cssPosition;
                    $container.css('position', 'relative');
                }

                $shadow = $ele.clone().removeClass('drag-from').addClass('drag-shadow').css({
                    position:   'absolute',
                    width:      $ele.outerWidth(),
                    transition: 'none'
                }).appendTo($container);
                $ele.addClass('dragging');

                that.trigger('start', {
                    event:   event,
                    element: $ele,
                    targets: $targets
                });
            }

            var offset = {
                left: mouseOffset.left - clickOffset.left,
                top:  mouseOffset.top - clickOffset.top
            };
            var position = {
                left: offset.left - containerOffset.left,
                top:  offset.top - containerOffset.top
            };
            $shadow.css(position);
            $.extend(lastMouseOffset, mouseOffset);

            var isNew = false;
                isIn = false;

            if(!flex) {
                $targets.removeClass(dropToClass);
            }

            var $newTarget = null;
            $targets.each(function() {
                var t    = $(this),
                    tPos = t.offset(),
                    tW   = t.outerWidth(),
                    tH   = t.outerHeight(),
                    tX   = tPos.left + setting.sensorOffsetX,
                    tY   = tPos.top + setting.sensorOffsetY;

                if(mouseOffset.left > tX && mouseOffset.top > tY && mouseOffset.left < (tX + tW) && mouseOffset.top < (tY + tH)) {
                    if($newTarget) $newTarget.removeClass(dropToClass);
                    $newTarget = t;
                    if(!setting.nested) return false;
                }
            });

            if($newTarget) {
                isIn = true;
                var id = $newTarget.data('id');
                if($ele.data('id') != id) isSelf = false;
                if($target === null || ($target.data('id') !== id && (!isSelf))) isNew = true;
                $target = $newTarget;
                if(flex) {
                    $targets.removeClass(dropToClass);
                }
                $target.addClass(dropToClass);
            }


            if(!flex) {
                $ele.toggleClass('drop-in', isIn);
                $shadow.toggleClass('drop-in', isIn);
            } else if($target !== null && $target.length) {
                isIn = true;
            }

            if(!canMoveHere || canMoveHere($ele, $target) !== false) {
                that.trigger('drag', {
                    event: event,
                    isIn: isIn,
                    target: $target,
                    element: $ele,
                    isNew: isNew,
                    selfTarget: isSelf,
                    clickOffset: clickOffset,
                    offset: offset,
                    position: {
                        left: offset.left - containerOffset.left,
                        top: offset.top - containerOffset.top
                    },
                    mouseOffset: mouseOffset
                });
            }

            event.preventDefault();
        };

        var mouseUp = function(event) {
            $(document).off(eventSuffix);
            clearTimeout(mouseDownBackEventCall);
            if(!isMouseDown) return;

            isMouseDown = false;

            if(oldCssPosition) {
                $container.css('position', oldCssPosition);
            }

            if($shadow === null) {
                $ele.removeClass('drag-from');
                that.trigger('always', {
                    event: event,
                    cancel: true
                });
                return;
            }

            if(!isIn) $target = null;
            var isSure = true;
            mouseOffset = event ? {
                left: event.pageX,
                top: event.pageY
            } : lastMouseOffset;
            var offset = {
                left: mouseOffset.left - clickOffset.left,
                top: mouseOffset.top - clickOffset.top
            };
            var moveOffset = {
                left: mouseOffset.left - lastMouseOffset.left,
                top: mouseOffset.top - lastMouseOffset.top
            };
            lastMouseOffset.left = mouseOffset.left;
            lastMouseOffset.top = mouseOffset.top;
            var eventOptions = {
                event: event,
                isIn: isIn,
                target: $target,
                element: $ele,
                isNew: (!isSelf) && $target !== null,
                selfTarget: isSelf,
                offset: offset,
                mouseOffset: mouseOffset,
                position: {
                    left: offset.left - containerOffset.left,
                    top: offset.top - containerOffset.top
                },
                lastMouseOffset: lastMouseOffset,
                moveOffset: moveOffset
            };

            isSure = that.trigger('beforeDrop', eventOptions);

            if(isSure && isIn) {
                that.trigger('drop', eventOptions);
            }

            $targets.removeClass(dropToClass);
            $ele.removeClass('dragging').removeClass('drag-from');
            $shadow.remove();
            $shadow = null;

            that.trigger('finish', eventOptions);
            that.trigger('always', eventOptions);

            if(event) event.preventDefault();
        };

        var mouseDown = function(event) {
            var mouseButton = $.zui.getMouseButtonCode(setting.mouseButton);
            if(mouseButton > -1 && event.button !== mouseButton) {
                return;
            }

            var $mouseDownEle = $(this);
            if(selector) {
                $ele = handle ? $mouseDownEle.closest(selector) : $mouseDownEle;
            }

            if($ele.hasClass('drag-shadow')) {
                return;
            }

            if(setting['before']) {
                if(setting['before']({
                    event: event,
                    element: $ele
                }) === false) return;
            }

            isMouseDown = true;
            $targets         = $.isFunction(setting.target) ? setting.target($ele, $root) : $container.find(setting.target),
            $target          = null,
            $shadow          = null,
            isIn             = false,
            isSelf           = true,
            oldCssPosition   = null,
            startOffset      = $ele.offset(),
            containerOffset  = $container.offset();
            containerOffset.top = containerOffset.top - $container.scrollTop();
            containerOffset.left = containerOffset.left - $container.scrollLeft();
            startMouseOffset = {left: event.pageX, top: event.pageY};
            lastMouseOffset  = $.extend({}, startMouseOffset);
            clickOffset      = {
                left: startMouseOffset.left - startOffset.left,
                top: startMouseOffset.top - startOffset.top
            };

            $ele.addClass('drag-from');
            $(document).on(mouseMoveEvent, mouseMove).on(mouseUpEvent, mouseUp);
            mouseDownBackEventCall = setTimeout(function() {
                $(document).on(mouseDownEvent, mouseUp);
            }, 10);
            event.preventDefault();
            if(setting.stopPropagation) {
                event.stopPropagation();
            }
        };

        if(handle) {
            $root.on(mouseDownEvent, handle, mouseDown);
        } else if(selector) {
            $root.on(mouseDownEvent, selector, mouseDown);
        } else {
            $root.on(mouseDownEvent, mouseDown);
        }
    };

    Droppable.prototype.destroy = function() {
        var eventSuffix = '.' + NAME + '.' + this.id;
        this.$.off(eventSuffix);
        $(document).off(eventSuffix);
        this.$.data(NAME, null);
    };

    Droppable.prototype.reset = function() {
        this.destroy();
        this.init();
    };

    $.fn.droppable = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(NAME);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(NAME, (data = new Droppable(this, options)));

            if(typeof option == 'string') data[option]();
        });
    };

    $.fn.droppable.Constructor = Droppable;
}(jQuery, document, Math));

/* ========================================================================
 * Bootstrap: modal.js v3.2.0
 * http://getbootstrap.com/javascript/#modals
 *
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * Bootsrap version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2011-2014 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ========================================================================
 * Updates in ZUI：
 * 1. changed event namespace to *.zui.modal
 * 2. added position option to ajust poisition of modal
 * 3. added event 'escaping.zui.modal' with an param 'esc' to judge the esc
 *    key down
 * 4. get moveable options value from '.modal-moveable' on '.modal-dialog'
 * 5. add setMoveable method to make modal dialog moveable
 * ======================================================================== */

+ function($, undefined) {
    'use strict';

    // MODAL CLASS DEFINITION
    // ======================

    var zuiname = 'zui.modal'
    var Modal = function(element, options) {
        var that = this;
        that.options = options
        that.$body = $(document.body)
        that.$element = $(element)
        that.$backdrop =
            that.isShown = null
        that.scrollbarWidth = 0

        if(options.moveable === undefined) {
            that.options.moveable = that.$element.hasClass('modal-moveable');
        }

        if(options.remote) {
            that.$element
                .find('.modal-content')
                .load(options.remote, function() {
                    that.$element.trigger('loaded.' + zuiname)
                })
        }
    }

    Modal.VERSION = '3.2.0'

    Modal.TRANSITION_DURATION = 300
    Modal.BACKDROP_TRANSITION_DURATION = 150

    Modal.DEFAULTS = {
        backdrop: true,
        keyboard: true,
        show: true,
        // rememberPos: false,
        // moveable: false,
        position: 'fit' // 'center' or '40px' or '10%',
    };

    var setDialogPos = function($dialog, pos) {
        var $window = $(window);
        pos.left = Math.max(0, Math.min(pos.left, $window.width() - $dialog.outerWidth()));
        pos.top = Math.max(0, Math.min(pos.top, $window.height() - $dialog.outerHeight()));
        $dialog.css(pos);
    };

    Modal.prototype.toggle = function(_relatedTarget, position) {
        return this.isShown ? this.hide() : this.show(_relatedTarget, position)
    }

    Modal.prototype.ajustPosition = function(position) {
        var that = this;
        var options = that.options;
        if(position === undefined) position = options.position;
        if(position === undefined) return;
        if ($.isFunction(position)) {
            position = position(that);
        }
        var $dialog = that.$element.find('.modal-dialog');

        var half = Math.max(0, ($(window).height() - $dialog.outerHeight()) / 2);
        if (position === 'fit') {
            position = {top: Math.floor(half * 2 / 3)};
        } else if (position === 'center') {
            position = {top: half};
        } else if (!$.isPlainObject(position)) {
            position = {top: position};
        }
        if($dialog.hasClass('modal-moveable')) {
            var pos = null;
            var rememberPos = options.rememberPos;
            if(rememberPos) {
                if(rememberPos === true) {
                    pos = that.$element.data('modal-pos');
                } else if($.zui.store) {
                    pos = $.zui.store.pageGet(zuiname + '.rememberPos.' + rememberPos);
                }
            }
            position = $.extend(position, {left: Math.max(0, ($(window).width() - $dialog.outerWidth()) / 2)}, pos);
            if (options.moveable === 'inside') {
                setDialogPos($dialog, position);
            } else {
                $dialog.css(position);
            }
        } else {
            $dialog.css(position);
        }
    }

    Modal.prototype.setMoveale = function() {
        if(!$.fn.draggable) console.error('Moveable modal requires draggable.js.');
        var that = this;
        var options = that.options;
        var $dialog = that.$element.find('.modal-dialog').removeClass('modal-dragged');
        $dialog.toggleClass('modal-moveable', !!options.moveable);

        if(!that.$element.data('modal-moveable-setup')) {
            $dialog.draggable({
                container: that.$element,
                handle: '.modal-header',
                before: function() {
                    var marginTop = $dialog.css('margin-top');
                    if (marginTop && marginTop !== '0px') {
                        $dialog.css('top', marginTop).css('margin-top', '').addClass('modal-dragged');
                    }
                },
                finish: function(e) {
                    var rememberPos = options.rememberPos;
                    if(rememberPos) {
                        that.$element.data('modal-pos', e.pos);
                        if($.zui.store && rememberPos !== true) {
                            $.zui.store.pageSet(zuiname + '.rememberPos.' + rememberPos, e.pos);
                        }
                    }
                },
                move: options.moveable === 'inside' ? function (dragPos) {
                    setDialogPos($dialog, dragPos);
                } : true
            });
        }
    }

    Modal.prototype.show = function(_relatedTarget, position) {
        var that = this
        var e = $.Event('show.' + zuiname, {
            relatedTarget: _relatedTarget
        })

        that.$element.trigger(e)

        if(that.isShown || e.isDefaultPrevented()) return

        that.isShown = true

        if(that.options.moveable) that.setMoveale();

        that.checkScrollbar()
        if (that.options.backdrop !== false) {
            that.$body.addClass('modal-open')
            that.setScrollbar()
        }

        that.escape()

        that.$element.on('click.dismiss.' + zuiname, '[data-dismiss="modal"]',function(e) {
            that.hide();
            e.stopPropagation();
        })

        that.backdrop(function() {
            var transition = $.support.transition && that.$element.hasClass('fade')

            if(!that.$element.parent().length) {
                that.$element.appendTo(that.$body) // don't move modals dom position
            }

            that.$element
                .show()
                .scrollTop(0)

            if(transition) {
                that.$element[0].offsetWidth // force reflow
            }

            that.$element
                .addClass('in')
                .attr('aria-hidden', false)

            that.ajustPosition(position);

            that.enforceFocus()

            var e = $.Event('shown.' + zuiname, {
                relatedTarget: _relatedTarget
            })

            transition ?
                that.$element.find('.modal-dialog') // wait for modal to slide in
                .one('bsTransitionEnd', function() {
                    that.$element.trigger('focus').trigger(e)
                })
                .emulateTransitionEnd(Modal.TRANSITION_DURATION) :
                that.$element.trigger('focus').trigger(e)
        })
    }

    Modal.prototype.hide = function(e) {
        if(e) e.preventDefault()

        var that = this;

        e = $.Event('hide.' + zuiname)

        that.$element.trigger(e)

        if(!that.isShown || e.isDefaultPrevented()) return

        that.isShown = false

        if (that.options.backdrop !== false) {
            that.$body.removeClass('modal-open')
            that.resetScrollbar()
        }

        that.escape()

        $(document).off('focusin.' + zuiname)

        that.$element
            .removeClass('in')
            .attr('aria-hidden', true)
            .off('click.dismiss.' + zuiname)

        $.support.transition && that.$element.hasClass('fade') ?
            that.$element
            .one('bsTransitionEnd', $.proxy(that.hideModal, that))
            .emulateTransitionEnd(Modal.TRANSITION_DURATION) :
            that.hideModal()
    }

    Modal.prototype.enforceFocus = function() {
        $(document)
            .off('focusin.' + zuiname) // guard against infinite focus loop
            .on('focusin.' + zuiname, $.proxy(function(e) {
                if(this.$element[0] !== e.target && !this.$element.has(e.target).length) {
                    this.$element.trigger('focus')
                }
            }, this))
    }

    Modal.prototype.escape = function() {
        if(this.isShown && this.options.keyboard) {
            $(document).on('keydown.dismiss.' + zuiname, $.proxy(function(e) {
                if(e.which == 27) {
                    var et = $.Event('escaping.' + zuiname)
                    var result = this.$element.triggerHandler(et, 'esc')
                    if(result != undefined && (!result)) return
                    this.hide()
                }
            }, this))
        } else if(!this.isShown) {
            $(document).off('keydown.dismiss.' + zuiname)
        }
    }

    Modal.prototype.hideModal = function() {
        var that = this
        this.$element.hide()
        this.backdrop(function() {
            that.$element.trigger('hidden.' + zuiname)
        })
    }

    Modal.prototype.removeBackdrop = function() {
        this.$backdrop && this.$backdrop.remove()
        this.$backdrop = null
    }

    Modal.prototype.backdrop = function(callback) {
        var that = this
        var animate = this.$element.hasClass('fade') ? 'fade' : ''

        if(this.isShown && this.options.backdrop) {
            var doAnimate = $.support.transition && animate

            this.$backdrop = $('<div class="modal-backdrop ' + animate + '" />')
                .appendTo(this.$body)

            this.$element.on('mousedown.dismiss.' + zuiname, $.proxy(function(e) {
                if(e.target !== e.currentTarget) return
                this.options.backdrop == 'static' ? this.$element[0].focus.call(this.$element[0]) : this.hide.call(this)
            }, this))

            if(doAnimate) this.$backdrop[0].offsetWidth // force reflow

            this.$backdrop.addClass('in')

            if(!callback) return

            doAnimate ?
                this.$backdrop
                .one('bsTransitionEnd', callback)
                .emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) :
                callback()

        } else if(!this.isShown && this.$backdrop) {
            this.$backdrop.removeClass('in')

            var callbackRemove = function() {
                that.removeBackdrop()
                callback && callback()
            }
            $.support.transition && this.$element.hasClass('fade') ?
                this.$backdrop
                .one('bsTransitionEnd', callbackRemove)
                .emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION) :
                callbackRemove()

        } else if(callback) {
            callback()
        }
    }

    Modal.prototype.checkScrollbar = function() {
        if(document.body.clientWidth >= window.innerWidth) return
        this.scrollbarWidth = this.scrollbarWidth || this.measureScrollbar()
    }

    Modal.prototype.setScrollbar = function() {
        var bodyPad = parseInt((this.$body.css('padding-right') || 0), 10)
        if(this.scrollbarWidth) this.$body.css('padding-right', bodyPad + this.scrollbarWidth)
    }

    Modal.prototype.resetScrollbar = function() {
        this.$body.css('padding-right', '')
    }

    Modal.prototype.measureScrollbar = function() { // thx walsh
        var scrollDiv = document.createElement('div')
        scrollDiv.className = 'modal-scrollbar-measure'
        this.$body.append(scrollDiv)
        var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
        this.$body[0].removeChild(scrollDiv)
        return scrollbarWidth
    }


    // MODAL PLUGIN DEFINITION
    // =======================

    function Plugin(option, _relatedTarget, position) {
        return this.each(function() {
            var $this = $(this)
            var data = $this.data(zuiname)
            var options = $.extend({}, Modal.DEFAULTS, $this.data(), typeof option == 'object' && option)

            if(!data) $this.data(zuiname, (data = new Modal(this, options)))
            if(typeof option == 'string') data[option](_relatedTarget, position)
            else if(options.show) data.show(_relatedTarget, position)
        })
    }

    var old = $.fn.modal

    $.fn.modal = Plugin
    $.fn.modal.Constructor = Modal


    // MODAL NO CONFLICT
    // =================

    $.fn.modal.noConflict = function() {
        $.fn.modal = old
        return this
    }


    // MODAL DATA-API
    // ==============

    $(document).on('click.' + zuiname + '.data-api', '[data-toggle="modal"]', function(e) {
        var $this = $(this)
        var href = $this.attr('href')
        var $target = null
        try {
            // strip for ie7
            $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
        } catch(ex) {
            return
        }
        if(!$target.length) return;
        var option = $target.data(zuiname) ? 'toggle' : $.extend({
            remote: !/#/.test(href) && href
        }, $target.data(), $this.data())

        if($this.is('a')) e.preventDefault()

        $target.one('show.' + zuiname, function(showEvent) {
            // only register focus restorer if modal will actually get shown
            if(showEvent.isDefaultPrevented()) return
            $target.one('hidden.' + zuiname, function() {
                $this.is(':visible') && $this.trigger('focus')
            })
        })
        Plugin.call($target, option, this, $this.data('position'))
    })

}(jQuery, undefined);


/* ========================================================================
 * ZUI: modal.trigger.js [1.2.0+]
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014-2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($, window, undefined) {
    'use strict';

    if(!$.fn.modal) throw new Error('Modal trigger requires modal.js');

    var NAME = 'zui.modaltrigger',
        STR_AJAX = 'ajax',
        ZUI_MODAL = '.zui.modal',
        STR_STRING = 'string';

    // MODAL TRIGGER CLASS DEFINITION
    // ======================
    var ModalTrigger = function(options, $trigger) {
        options = $.extend({}, ModalTrigger.DEFAULTS, $.ModalTriggerDefaults, $trigger ? $trigger.data() : null, options);
        this.isShown;
        this.$trigger = $trigger;
        this.options = options;
        this.id = $.zui.uuid();
    };

    ModalTrigger.DEFAULTS = {
        type: 'custom',
        // width: null, // number, css definition
        // size: null, // 'md', 'sm', 'lg', 'fullscreen'
        height: 'auto',
        // icon: null,
        name: 'triggerModal',
        // className: '',
        fade: true,
        position: 'fit',
        showHeader: true,
        delay: 0,
        // iframeBodyClass: '',
        // onlyIncreaseHeight: false,
        // moveable: false,
        // rememberPos: false,
        backdrop: true,
        keyboard: true,
        waittime: 0,
        loadingIcon: 'icon-spinner-indicator',
        scrollInside: false
    };

    ModalTrigger.prototype.init = function(options) {
        var that = this;
        if(options.url) {
            if(!options.type || (options.type != STR_AJAX && options.type != 'iframe')) {
                options.type = STR_AJAX;
            }
        }
        if(options.remote) {
            options.type = STR_AJAX;
            if(typeof options.remote === STR_STRING) options.url = options.remote;
        } else if(options.iframe) {
            options.type = 'iframe';
            if(typeof options.iframe === STR_STRING) options.url = options.iframe;
        } else if(options.custom) {
            options.type = 'custom';
            if(typeof options.custom === STR_STRING) {
                var $doms;
                try {
                    $doms = $(options.custom);
                } catch(e) {}

                if($doms && $doms.length) {
                    options.custom = $doms;
                } else if($.isFunction(window[options.custom])) {
                    options.custom = window[options.custom];
                }
            }
        }

        var $modal = $('#' + options.name);
        if($modal.length) {
            if(!that.isShown) $modal.off(ZUI_MODAL);
            $modal.remove();
        }
        $modal = $('<div id="' + options.name + '" class="modal modal-trigger ' + (options.className || '') + '">' + (typeof options.loadingIcon === 'string' && options.loadingIcon.indexOf('icon-') === 0 ? ('<div class="icon icon-spin loader ' + options.loadingIcon + '"></div>') : options.loadingIcon) + '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button class="close" data-dismiss="modal">×</button><h4 class="modal-title"><i class="modal-icon"></i> <span class="modal-title-name"></span></h4></div><div class="modal-body"></div></div></div></div>').appendTo('body').data(NAME, that);

        var bindEvent = function(optonName, eventName) {
            var handleFunc = options[optonName];
            if($.isFunction(handleFunc)) $modal.on(eventName + ZUI_MODAL, handleFunc);
        };
        bindEvent('onShow', 'show');
        bindEvent('shown',  'shown');
        bindEvent('onHide', 'hide');
        bindEvent('hidden', 'hidden');
        bindEvent('loaded', 'loaded');

        $modal.on('shown' + ZUI_MODAL, function() {
            that.isShown = true;
        }).on('hidden' + ZUI_MODAL, function() {
            that.isShown = false;
        });

        this.$modal = $modal;
        this.$dialog = $modal.find('.modal-dialog');

        if(options.mergeOptions) this.options = options;
    };

    ModalTrigger.prototype.show = function(option) {
        var options = $.extend({}, this.options, {
            url: this.$trigger ? (this.$trigger.attr('href') || this.$trigger.attr('data-url') || this.$trigger.data('url')) : this.options.url
        }, option);

        this.init(options);
        var that = this,
            $modal = this.$modal,
            $dialog = this.$dialog,
            custom = options.custom;
        var $body = $dialog.find('.modal-body').css('padding', ''),
            $header = $dialog.find('.modal-header'),
            $content = $dialog.find('.modal-content');

        $modal.toggleClass('fade', options.fade)
            .addClass(options.className)
            .toggleClass('modal-loading', !this.isShown)
            .toggleClass('modal-scroll-inside', !!options.scrollInside);

        $dialog.toggleClass('modal-md', options.size === 'md')
            .toggleClass('modal-sm', options.size === 'sm')
            .toggleClass('modal-lg', options.size === 'lg')
            .toggleClass('modal-fullscreen', options.size === 'fullscreen');

        $header.toggle(options.showHeader);
        $header.find('.modal-icon').attr('class', 'modal-icon icon-' + options.icon);
        $header.find('.modal-title-name').text(options.title || '');
        if(options.size && options.size === 'fullscreen') {
            options.width = '';
            options.height = '';
        }

        var resizeDialog = function() {
            clearTimeout(this.resizeTask);
            this.resizeTask = setTimeout(function() {
                that.ajustPosition(options.position);
            }, 100);
        };

        var readyToShow = function(delay, callback) {
            if(typeof delay === 'undefined') delay = options.delay;
            return setTimeout(function() {
                $dialog = $modal.find('.modal-dialog');
                if(options.width && options.width != 'auto') {
                    $dialog.css('width', options.width);
                }
                if(options.height && options.height != 'auto') {
                    $dialog.css('height', options.height);
                    if(options.type === 'iframe') $body.css('height', $dialog.height() - $header.outerHeight());
                }
                that.ajustPosition(options.position);
                $modal.removeClass('modal-loading');

                if(options.type != 'iframe') {
                    $dialog.off('resize.' + NAME).on('resize.' + NAME, resizeDialog);
                }

                callback && callback();
            }, delay);
        };

        if(options.type === 'custom' && custom) {
            if($.isFunction(custom)) {
                var customContent = custom({
                    modal: $modal,
                    options: options,
                    modalTrigger: that,
                    ready: readyToShow
                });
                if(typeof customContent === STR_STRING) {
                    $body.html(customContent);
                    readyToShow();
                }
            } else if(custom instanceof $) {
                $body.html($('<div>').append(custom.clone()).html());
                readyToShow();
            } else {
                $body.html(custom);
                readyToShow();
            }
        } else if(options.url) {
            var onLoadBroken = function() {
                var brokenContent = $modal.callComEvent(that, 'broken');
                if(brokenContent) {
                    $body.html(brokenContent);
                    readyToShow();
                }
            };

            $modal.attr('ref', options.url);
            if(options.type === 'iframe') {
                $modal.addClass('modal-iframe');
                this.firstLoad = true;
                var iframeName = 'iframe-' + options.name;
                $header.detach();
                $body.detach();
                $content.empty().append($header).append($body);
                $body.css('padding', 0)
                    .html('<iframe id="' + iframeName + '" name="' + iframeName + '" src="' + options.url + '" frameborder="no"  allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"  allowtransparency="true" scrolling="auto" style="width: 100%; height: 100%; left: 0px;"></iframe>');

                if(options.waittime > 0) {
                    that.waitTimeout = readyToShow(options.waittime, onLoadBroken);
                }

                var frame = document.getElementById(iframeName);
                frame.onload = frame.onreadystatechange = function() {
                    var scrollInside = !!options.scrollInside;
                    if(that.firstLoad) $modal.addClass('modal-loading');
                    if(this.readyState && this.readyState != 'complete') return;
                    that.firstLoad = false;

                    if(options.waittime > 0) {
                        clearTimeout(that.waitTimeout);
                    }
                    try {
                        $modal.attr('ref', frame.contentWindow.location.href);
                        var frame$ = window.frames[iframeName].$;
                        if(frame$ && options.height === 'auto' && options.size != 'fullscreen') {
                            // todo: update iframe url to ref attribute

                            var $framebody = frame$('body').addClass('body-modal').toggleClass('body-modal-scroll-inside', scrollInside);
                            if(options.iframeBodyClass) $framebody.addClass(options.iframeBodyClass);
                            var ajustFrameSize = function(check) {
                                $modal.removeClass('fade');
                                var height = $framebody.outerHeight();
                                if(check === true && options.onlyIncreaseHeight) {
                                    height = Math.max(height, $body.data('minModalHeight') || 0);
                                    $body.data('minModalHeight', height);
                                }
                                if (scrollInside)
                                {
                                    var winHeight = $(window).height();
                                    height = Math.min(height, winHeight - $header.height());
                                }
                                $body.css('height', height);
                                if(options.fade) $modal.addClass('fade');
                                readyToShow();
                            };

                            $modal.callComEvent(that, 'loaded', {
                                modalType: 'iframe',
                                jQuery: frame$
                            });

                            setTimeout(ajustFrameSize, 100);

                            $framebody.off('resize.' + NAME).on('resize.' + NAME, ajustFrameSize);
                            if (scrollInside) {
                                $(window).off('resize.' + NAME).on('resize.' + NAME, ajustFrameSize);
                            }
                        } else {
                            readyToShow();
                        }
                    } catch(e) {
                        readyToShow();
                    }
                };
            } else {
                $.ajax($.extend({
                    url: options.url,
                    success: function(data) {
                        try {
                            var $data = $(data);
                            if($data.filter('.modal-dialog').length) {
                                $dialog.replaceWith($data);
                            } else if($data.filter('.modal-content').length) {
                                $dialog.find('.modal-content').replaceWith($data);
                            } else {
                                $body.wrapInner($data);
                            }
                        } catch(e) {
                            if (window.console && window.console.warn) {
                                console.warn('ZUI: Cannot recogernize remote content.', {error: e, data: data});
                            }
                            $modal.html(data);
                        }
                        $modal.callComEvent(that, 'loaded', {
                            modalType: STR_AJAX
                        });
                        readyToShow();
                    },
                    error: onLoadBroken
                }, options.ajaxOptions));
            }
        }

        $modal.modal({
            show       : 'show',
            backdrop   : options.backdrop,
            moveable   : options.moveable,
            rememberPos: options.rememberPos,
            keyboard   : options.keyboard
        });
    };

    ModalTrigger.prototype.close = function(callback, redirect) {
        var that = this;
        if(callback || redirect) {
            that.$modal.on('hidden' + ZUI_MODAL, function() {
                if($.isFunction(callback)) callback();

                if(typeof redirect === STR_STRING && redirect.length && !that.$modal.data('cancel-reload')) {
                    if(redirect === 'this') window.location.reload();
                    else window.location = redirect;
                }
            });
        }
        that.$modal.modal('hide');
    };

    ModalTrigger.prototype.toggle = function(options) {
        if(this.isShown) this.close();
        else this.show(options);
    };

    ModalTrigger.prototype.ajustPosition = function(position) {
        position = position === undefined ? this.options.position : position;
        if ($.isFunction(position)) {
            position = position(this);
        }
        this.$modal.modal('ajustPosition', position);
    };

    $.zui({
        ModalTrigger: ModalTrigger,
        modalTrigger: new ModalTrigger()
    });

    $.fn.modalTrigger = function(option, settings) {
        return $(this).each(function() {
            var $this = $(this);
            var data = $this.data(NAME),
                options = $.extend({
                    title: $this.attr('title') || $this.text(),
                    url: $this.attr('href'),
                    type: $this.hasClass('iframe') ? 'iframe' : ''
                }, $this.data(), $.isPlainObject(option) && option);
            if(!data) $this.data(NAME, (data = new ModalTrigger(options, $this)));
            if(typeof option == STR_STRING) data[option](settings);
            else if(options.show) data.show(settings);

            $this.on((options.trigger || 'click') + '.toggle.' + NAME, function(e) {
                options = $.extend(options, {
                    url: $this.attr('href') || $this.attr('data-url') || $this.data('url') || options.url
                });
                data.toggle(options);
                if($this.is('a')) e.preventDefault();
            });
        });
    };

    var old = $.fn.modal;
    $.fn.modal = function(option, settings) {
        return $(this).each(function() {
            var $this = $(this);
            if($this.hasClass('modal')) old.call($this, option, settings);
            else $this.modalTrigger(option, settings);
        });
    };

    var getModal = function(modal) {
        if (!modal) {
            modal = $('.modal.modal-trigger');
        } else {
            modal = $(modal);
        }
        if(modal && (modal instanceof $)) return modal;
        return null;
    };

    // callback, redirect, modal
    var closeModal = function(modal, callback, redirect) {
        var originModal = modal;
        if($.isFunction(modal)) {
            var oldModal = redirect;
            redirect = callback;
            callback = modal;
            modal = oldModal;
        }
        modal = getModal(modal);
        if(modal && modal.length) {
            modal.each(function() {
                $(this).data(NAME).close(callback, redirect);
            });
        } else if(!$('body').hasClass('modal-open') && !$('.modal.in').length) {
            // check if current page is as modal iframe
            if ($('body').hasClass('body-modal')) {
                window.parent.$.zui.closeModal(originModal, callback, redirect);
            }
        }
    };

    var ajustModalPosition = function(position, modal) {
        modal = getModal(modal);
        if(modal && modal.length) {
            modal.modal('ajustPosition', position);
        }
    };

    $.zui({
        closeModal: closeModal,
        ajustModalPosition: ajustModalPosition
    });

    $(document).on('click.' + NAME + '.data-api', '[data-toggle="modal"]', function(e) {
        var $this = $(this);
        var href = $this.attr('href');
        var $target = null;
        try {
            $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, '')));
        } catch(ex) {}
        if(!$target || !$target.length) {
            if(!$this.data(NAME)) {
                $this.modalTrigger({
                    show: true,
                });
            } else {
                $this.trigger('.toggle.' + NAME);
            }
        }
        if($this.is('a')) {
            e.preventDefault();
        }
    }).on('click.' + NAME + '.data-api', '[data-dismiss="modal"]', function() {
        $.zui.closeModal();
    });
}(window.jQuery, window, undefined));


/* ========================================================================
 * Bootstrap: tooltip.js v3.0.0
 * http://twzui.github.com/bootstrap/javascript.html#tooltip
 * Inspired by the original jQuery.tipsy by Jason Frame
 *  
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * Bootsrap version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */


+ function($) {
    'use strict';

    // TOOLTIP PUBLIC CLASS DEFINITION
    // ===============================

    var Tooltip = function(element, options) {
        this.type = null
        this.options = null
        this.enabled = null
        this.timeout = null
        this.hoverState = null
        this.$element = null

        this.init('tooltip', element, options)
    } 

    Tooltip.DEFAULTS = {
        animation: true,
        placement: 'top',
        selector: false,
        template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        trigger: 'hover focus',
        title: '',
        delay: 0,
        html: false,
        container: false
    }

    Tooltip.prototype.init = function(type, element, options) {
        this.enabled = true
        this.type = type
        this.$element = $(element)
        this.options = this.getOptions(options)

        var triggers = this.options.trigger.split(' ')

        for(var i = triggers.length; i--;) {
            var trigger = triggers[i]

            if(trigger == 'click') {
                this.$element.on('click.' + this.type, this.options.selector, $.proxy(this.toggle, this))
            } else if(trigger != 'manual') {
                var eventIn = trigger == 'hover' ? 'mouseenter' : 'focus'
                var eventOut = trigger == 'hover' ? 'mouseleave' : 'blur'

                this.$element.on(eventIn + '.' + this.type, this.options.selector, $.proxy(this.enter, this))
                this.$element.on(eventOut + '.' + this.type, this.options.selector, $.proxy(this.leave, this))
            }
        }

        this.options.selector ?
            (this._options = $.extend({}, this.options, {
                trigger: 'manual',
                selector: ''
            })) :
            this.fixTitle()
    }

    Tooltip.prototype.getDefaults = function() {
        return Tooltip.DEFAULTS
    }

    Tooltip.prototype.getOptions = function(options) {
        options = $.extend({}, this.getDefaults(), this.$element.data(), options)

        if(options.delay && typeof options.delay == 'number') {
            options.delay = {
                show: options.delay,
                hide: options.delay
            }
        }

        return options
    }

    Tooltip.prototype.getDelegateOptions = function() {
        var options = {}
        var defaults = this.getDefaults()

        this._options && $.each(this._options, function(key, value) {
            if(defaults[key] != value) options[key] = value
        })

        return options
    }

    Tooltip.prototype.enter = function(obj) {
        var self = obj instanceof this.constructor ?
            obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('zui.' + this.type)

        clearTimeout(self.timeout)

        self.hoverState = 'in'

        if(!self.options.delay || !self.options.delay.show) return self.show()

        self.timeout = setTimeout(function() {
            if(self.hoverState == 'in') self.show()
        }, self.options.delay.show)
    }

    Tooltip.prototype.leave = function(obj) {
        var self = obj instanceof this.constructor ?
            obj : $(obj.currentTarget)[this.type](this.getDelegateOptions()).data('zui.' + this.type)

        clearTimeout(self.timeout)

        self.hoverState = 'out'

        if(!self.options.delay || !self.options.delay.hide) return self.hide()

        self.timeout = setTimeout(function() {
            if(self.hoverState == 'out') self.hide()
        }, self.options.delay.hide)
    }

    Tooltip.prototype.show = function(content) {
        var e = $.Event('show.zui.' + this.type)

        if((content || this.hasContent()) && this.enabled) {
            var that = this
            that.$element.trigger(e)

            if(e.isDefaultPrevented()) return

            var $tip = that.tip()

            that.setContent(content)

            if(that.options.animation) $tip.addClass('fade')

            var placement = typeof that.options.placement == 'function' ?
                that.options.placement.call(that, $tip[0], that.$element[0]) :
                that.options.placement

            var autoToken = /\s?auto?\s?/i
            var autoPlace = autoToken.test(placement)
            if(autoPlace) placement = placement.replace(autoToken, '') || 'top'

            $tip
                .detach()
                .css({
                    top: 0,
                    left: 0,
                    display: 'block'
                })
                .addClass(placement)

            that.options.container ? $tip.appendTo(that.options.container) : $tip.insertAfter(that.$element)

            var pos = that.getPosition()
            var actualWidth = $tip[0].offsetWidth
            var actualHeight = $tip[0].offsetHeight

            if(autoPlace) {
                var $parent = that.$element.parent()

                var orgPlacement = placement
                var docScroll = document.documentElement.scrollTop || document.body.scrollTop
                var parentWidth = that.options.container == 'body' ? window.innerWidth : $parent.outerWidth()
                var parentHeight = that.options.container == 'body' ? window.innerHeight : $parent.outerHeight()
                var parentLeft = that.options.container == 'body' ? 0 : $parent.offset().left

                placement = placement == 'bottom' && pos.top + pos.height + actualHeight - docScroll > parentHeight ? 'top' :
                    placement == 'top' && pos.top - docScroll - actualHeight < 0 ? 'bottom' :
                    placement == 'right' && pos.right + actualWidth > parentWidth ? 'left' :
                    placement == 'left' && pos.left - actualWidth < parentLeft ? 'right' :
                    placement

                $tip
                    .removeClass(orgPlacement)
                    .addClass(placement)
            }

            var calculatedOffset = that.getCalculatedOffset(placement, pos, actualWidth, actualHeight)

            that.applyPlacement(calculatedOffset, placement)
            var complete = function () {
                var prevHoverState = that.hoverState
                that.$element.trigger('shown.zui.' + that.type)
                that.hoverState = null

                if (prevHoverState == 'out') that.leave(that)
            }

            $.support.transition && that.$tip.hasClass('fade') ?
                $tip
                  .one('bsTransitionEnd', complete)
                  .emulateTransitionEnd(150) :
                complete()
        }
    }

    Tooltip.prototype.applyPlacement = function(offset, placement) {
        var replace
        var $tip = this.tip()
        var width = $tip[0].offsetWidth
        var height = $tip[0].offsetHeight

        // manually read margins because getBoundingClientRect includes difference
        var marginTop = parseInt($tip.css('margin-top'), 10)
        var marginLeft = parseInt($tip.css('margin-left'), 10)

        // we must check for NaN for ie 8/9
        if(isNaN(marginTop)) marginTop = 0
        if(isNaN(marginLeft)) marginLeft = 0

        offset.top = offset.top + marginTop
        offset.left = offset.left + marginLeft

        $tip
            .offset(offset)
            .addClass('in')

        // check to see if placing tip in new offset caused the tip to resize itself
        var actualWidth = $tip[0].offsetWidth
        var actualHeight = $tip[0].offsetHeight

        if(placement == 'top' && actualHeight != height) {
            replace = true
            offset.top = offset.top + height - actualHeight
        }

        if(/bottom|top/.test(placement)) {
            var delta = 0

            if(offset.left < 0) {
                delta = offset.left * -2
                offset.left = 0

                $tip.offset(offset)

                actualWidth = $tip[0].offsetWidth
                actualHeight = $tip[0].offsetHeight
            }

            this.replaceArrow(delta - width + actualWidth, actualWidth, 'left')
        } else {
            this.replaceArrow(actualHeight - height, actualHeight, 'top')
        }

        if(replace) $tip.offset(offset)
    }

    Tooltip.prototype.replaceArrow = function(delta, dimension, position) {
        this.arrow().css(position, delta ? (50 * (1 - delta / dimension) + "%") : '')
    }

    Tooltip.prototype.setContent = function(content) {
        var $tip = this.tip()
        var title = content || this.getTitle()

        if(this.options.tipId) $tip.attr('id', this.options.tipId)
        if(this.options.tipClass) $tip.addClass(this.options.tipClass)

        $tip.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](title)
        $tip.removeClass('fade in top bottom left right')
    }

    Tooltip.prototype.hide = function() {
        var that = this
        var $tip = this.tip()
        var e = $.Event('hide.zui.' + this.type)

        function complete() {
            if(that.hoverState != 'in') $tip.detach()
        }

        this.$element.trigger(e)

        if(e.isDefaultPrevented()) return

        $tip.removeClass('in')

        $.support.transition && this.$tip.hasClass('fade') ?
            $tip
            .one($.support.transition.end, complete)
            .emulateTransitionEnd(150) :
            complete()

        this.$element.trigger('hidden.zui.' + this.type)

        return this
    }

    Tooltip.prototype.fixTitle = function() {
        var $e = this.$element
        if($e.attr('title') || typeof($e.attr('data-original-title')) != 'string') {
            $e.attr('data-original-title', $e.attr('title') || '').attr('title', '')
        }
    }

    Tooltip.prototype.hasContent = function() {
        return this.getTitle()
    }

    Tooltip.prototype.getPosition = function() {
        var el = this.$element[0]
        return $.extend({}, (typeof el.getBoundingClientRect == 'function') ? el.getBoundingClientRect() : {
            width: el.offsetWidth,
            height: el.offsetHeight
        }, this.$element.offset())
    }

    Tooltip.prototype.getCalculatedOffset = function(placement, pos, actualWidth, actualHeight) {
        return placement == 'bottom' ? {
                top: pos.top + pos.height,
                left: pos.left + pos.width / 2 - actualWidth / 2
            } :
            placement == 'top' ? {
                top: pos.top - actualHeight,
                left: pos.left + pos.width / 2 - actualWidth / 2
            } :
            placement == 'left' ? {
                top: pos.top + pos.height / 2 - actualHeight / 2,
                left: pos.left - actualWidth
            } :
            /* placement == 'right' */
            {
                top: pos.top + pos.height / 2 - actualHeight / 2,
                left: pos.left + pos.width
            }
    }

    Tooltip.prototype.getTitle = function() {
        var title
        var $e = this.$element
        var o = this.options

        title = $e.attr('data-original-title') || (typeof o.title == 'function' ? o.title.call($e[0]) : o.title)

        return title
    }

    Tooltip.prototype.tip = function() {
        return this.$tip = this.$tip || $(this.options.template)
    }

    Tooltip.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find('.tooltip-arrow')
    }

    Tooltip.prototype.validate = function() {
        if(!this.$element[0].parentNode) {
            this.hide()
            this.$element = null
            this.options = null
        }
    }

    Tooltip.prototype.enable = function() {
        this.enabled = true
    }

    Tooltip.prototype.disable = function() {
        this.enabled = false
    }

    Tooltip.prototype.toggleEnabled = function() {
        this.enabled = !this.enabled
    }

    Tooltip.prototype.toggle = function(e) {
        var self = e ? $(e.currentTarget)[this.type](this.getDelegateOptions()).data('zui.' + this.type) : this
        self.tip().hasClass('in') ? self.leave(self) : self.enter(self)
    }

    Tooltip.prototype.destroy = function() {
        this.hide().$element.off('.' + this.type).removeData('zui.' + this.type)
    }


    // TOOLTIP PLUGIN DEFINITION
    // =========================

    var old = $.fn.tooltip

    $.fn.tooltip = function(option, params) {
        return this.each(function() {
            var $this = $(this)
            var data = $this.data('zui.tooltip')
            var options = typeof option == 'object' && option

            if(!data) $this.data('zui.tooltip', (data = new Tooltip(this, options)))
            if(typeof option == 'string') data[option](params)
        })
    }

    $.fn.tooltip.Constructor = Tooltip


    // TOOLTIP NO CONFLICT
    // ===================

    $.fn.tooltip.noConflict = function() {
        $.fn.tooltip = old
        return this
    }

}(window.jQuery);


/* ========================================================================
 * Bootstrap: popover.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#popovers
 *
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * Bootsrap version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */


+ function($) {
    'use strict';

    // POPOVER PUBLIC CLASS DEFINITION
    // ===============================

    var Popover = function(element, options) {
        this.init('popover', element, options)
    }

    if(!$.fn.tooltip) throw new Error('Popover requires tooltip.js')

    Popover.DEFAULTS = $.extend({}, $.fn.tooltip.Constructor.DEFAULTS, {
        placement: 'right',
        trigger: 'click',
        content: '',
        template: '<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    })


    // NOTE: POPOVER EXTENDS tooltip.js
    // ================================

    Popover.prototype = $.extend({}, $.fn.tooltip.Constructor.prototype)

    Popover.prototype.constructor = Popover

    Popover.prototype.getDefaults = function() {
        return Popover.DEFAULTS
    }

    Popover.prototype.setContent = function() {
        var $tip = this.tip()
        var target = this.getTarget()

        if(target) {
            if(target.find('.arrow').length < 1)
                $tip.addClass('no-arrow')
            $tip.html(target.html())
            return
        }

        var title = this.getTitle()
        var content = this.getContent()

        $tip.find('.popover-title')[this.options.html ? 'html' : 'text'](title)
        $tip.find('.popover-content')[this.options.html ? 'html' : 'text'](content)

        $tip.removeClass('fade top bottom left right in')

        if(this.options.tipId) $tip.attr('id', this.options.tipId)
        if(this.options.tipClass) $tip.addClass(this.options.tipClass)

        // IE8 doesn't accept hiding via the `:empty` pseudo selector, we have to do
        // this manually by checking the contents.
        if(!$tip.find('.popover-title').html()) $tip.find('.popover-title').hide()
    }

    Popover.prototype.hasContent = function() {
        return this.getTarget() || this.getTitle() || this.getContent()
    }

    Popover.prototype.getContent = function() {
        var $e = this.$element
        var o = this.options

        return $e.attr('data-content') || (typeof o.content == 'function' ?
            o.content.call($e[0]) :
            o.content)
    }

    Popover.prototype.getTarget = function() {
        var $e = this.$element
        var o = this.options

        var target = $e.attr('data-target') || (typeof o.target == 'function' ?
            o.target.call($e[0]) :
            o.target)
        return(target && true) ? (target == '$next' ? $e.next('.popover') : $(target)) : false
    }

    Popover.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find('.arrow')
    }

    Popover.prototype.tip = function() {
        if(!this.$tip) this.$tip = $(this.options.template)
        return this.$tip
    }


    // POPOVER PLUGIN DEFINITION
    // =========================

    var old = $.fn.popover

    $.fn.popover = function(option) {
        return this.each(function() {
            var $this = $(this)
            var data = $this.data('zui.popover')
            var options = typeof option == 'object' && option

            if(!data) $this.data('zui.popover', (data = new Popover(this, options)))
            if(typeof option == 'string') data[option]()
        })
    }

    $.fn.popover.Constructor = Popover


    // POPOVER NO CONFLICT
    // ===================

    $.fn.popover.noConflict = function() {
        $.fn.popover = old
        return this
    }

}(window.jQuery);


/* ========================================================================
 * Bootstrap: dropdown.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#dropdowns
 *
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * Bootsrap version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */


+ function($) {
    'use strict';

    // DROPDOWN CLASS DEFINITION
    // =========================

    var zuiname = 'zui.dropdown';
    var backdrop = '.dropdown-backdrop'
    var toggle = '[data-toggle=dropdown]'
    var Dropdown = function(element) {
        var $el = $(element).on('click.' + zuiname, this.toggle)
    }

    Dropdown.prototype.toggle = function(e) {
        var $this = $(this)

        if($this.is('.disabled, :disabled')) return

        var $parent = getParent($this)
        var isActive = $parent.hasClass('open')

        clearMenus()

        if(!isActive) {
            if('ontouchstart' in document.documentElement && !$parent.closest('.navbar-nav').length) {
                // if mobile we we use a backdrop because click events don't delegate
                $('<div class="dropdown-backdrop"/>').insertAfter($(this)).on('click', clearMenus)
            }

            $parent.trigger(e = $.Event('show.' + zuiname))

            if(e.isDefaultPrevented()) return

            $parent
                .toggleClass('open')
                .trigger('shown.' + zuiname)

            $this.focus()
        }

        return false
    }

    Dropdown.prototype.keydown = function(e) {
        if(!/(38|40|27)/.test(e.keyCode)) return

        var $this = $(this)

        e.preventDefault()
        e.stopPropagation()

        if($this.is('.disabled, :disabled')) return

        var $parent = getParent($this)
        var isActive = $parent.hasClass('open')

        if(!isActive || (isActive && e.keyCode == 27)) {
            if(e.which == 27) $parent.find(toggle).focus()
            return $this.click()
        }

        var $items = $('[role=menu] li:not(.divider):visible a', $parent)

        if(!$items.length) return

        var index = $items.index($items.filter(':focus'))

        if(e.keyCode == 38 && index > 0) index-- // up
            if(e.keyCode == 40 && index < $items.length - 1) index++ // down
                if(!~index) index = 0

        $items.eq(index).focus()
    }

    function clearMenus() {
        $(backdrop).remove()
        $(toggle).each(function(e) {
            var $parent = getParent($(this))
            if(!$parent.hasClass('open')) return
            $parent.trigger(e = $.Event('hide.' + zuiname))
            if(e.isDefaultPrevented()) return
            $parent.removeClass('open').trigger('hidden.' + zuiname)
        })
    }

    function getParent($this) {
        var selector = $this.attr('data-target')

        if(!selector) {
            selector = $this.attr('href')
            selector = selector && /#/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
        }
        var $parent;
        try {
            $parent = selector && $(selector);
        } catch(e) {}
        return $parent && $parent.length ? $parent : $this.parent()
    }


    // DROPDOWN PLUGIN DEFINITION
    // ==========================

    var old = $.fn.dropdown

    $.fn.dropdown = function(option) {
        return this.each(function() {
            var $this = $(this)
            var data = $this.data('dropdown')

            if(!data) $this.data('dropdown', (data = new Dropdown(this)))
            if(typeof option == 'string') data[option].call($this)
        })
    }

    $.fn.dropdown.Constructor = Dropdown


    // DROPDOWN NO CONFLICT
    // ====================

    $.fn.dropdown.noConflict = function() {
        $.fn.dropdown = old
        return this
    }


    // APPLY TO STANDARD DROPDOWN ELEMENTS
    // ===================================

    var apiName = zuiname + '.data-api'
    $(document)
        .on('click.' + apiName, clearMenus)
        .on('click.' + apiName, '.dropdown form', function(e) {
            e.stopPropagation()
        })
        .on('click.' + apiName, toggle, Dropdown.prototype.toggle)
        .on('keydown.' + apiName, toggle + ', [role=menu]', Dropdown.prototype.keydown)

}(window.jQuery);


/* ========================================================================
 * ZUI: messager.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014-2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($, window, undefined) {
    'use strict';

    var id = 0;
    var template = '<div class="messager messager-{type} {placement}" style="display: none"><div class="messager-content"></div><div class="messager-actions"></div></div>';
    var DEFAULTS = {
        type: 'default',
        placement: 'top',
        time: 4000,
        parent: 'body',
        // clear: false,
        icon: null,
        close: true,
        // actions: [{icon, name, action, title}],
        // contentClass: null,
        // cssClass: null,
        // onAction: function,
        fade: true,
        scale: true
    };
    var all = {};

    var Messager = function(message, options) {
        if($.isPlainObject(message)) {
            options = message;
            message = options.message;
        }

        var that = this;
        options = that.options = $.extend({}, DEFAULTS, options);

        that.id = options.id || (id++);
        var oldMessager = all[that.id];
        if(oldMessager) oldMessager.destroy();
        all[that.id] = that;
        that.message = (options.icon ? '<i class="icon-' + options.icon + ' icon"></i> ' : '') + message;

        that.$ = $(template.format(options)).toggleClass('fade', options.fade).toggleClass('scale', options.scale).attr('id', 'messager-' + that.id);

        if(options.cssClass) that.$.addClass(options.cssClass);

        var hasCloseAction = false;
        var $actions = that.$.find('.messager-actions');
        var appendAction = function(action) {
            var $btn = $('<button type="button" class="action action-' + action.name + '"/>');
            if(action.name === 'close') $btn.addClass('close');
            if(action.html !== undefined) {
                $btn.html(action.html);
            }
            if(action.icon !== undefined) {
                $btn.append('<i class="action-icon icon-' + action.icon + '"/>');
            }
            if(action.text !== undefined) {
                $btn.append('<span class="action-text">' + action.text + '</span>');
            }
            if(action.tooltip !== undefined) {
                $btn.attr('title', action.tooltip).tooltip();
            }
            $btn.data('action', action);
            $actions.append($btn);
        };
        if(options.actions) {
            $.each(options.actions, function(idx, action) {
                if(action.name === undefined) action.name = idx;
                if(action.name == 'close') hasCloseAction = true;
                appendAction(action);
            });
        }
        if(!hasCloseAction && options.close) {
            appendAction({name: 'close', html: '&times;'});
        }

        that.$.on('click', '.action', function(e) {
            var action = $(this).data('action'), result;
            if(options.onAction) {
                result = options.onAction.call(this, action.name, action, that);
                if(result === false) return;
            }
            if($.isFunction(action.action)) {
                result = action.action.call(this, that);
                if(result === false) return;
            }
            that.hide();
            e.stopPropagation();
        });

        that.$.on('click', function(e) {
            if(options.onAction) {
                var result = options.onAction.call(this, 'content', null, that);
                if(result === true) that.hide();
            }
        });

        var $content = that.$.find('.messager-content').html(that.message);
        if(options.contentClass) $content.addClass(options.contentClass);

        that.$.data('zui.messager', that);

        if(options.show && that.message !== undefined) {
            that.show();
        }
    };

    Messager.prototype.update = function(message, newOptions) {
        var that = this;
        var options = that.options;
        that.$.removeClass('messager-' + options.type);
        if(newOptions) {
            options = $.extend(options, newOptions);
        }
        that.$.addClass('messager-' + options.type);
        if(message) {
            that.message = (options.icon ? '<i class="icon-' + options.icon + ' icon"></i> ' : '') + message;
            that.$.find('.messager-content').html(that.message);
        }
    };

    Messager.prototype.show = function(message, callback) {
        var that = this,
            options = this.options;

        if($.isFunction(message)) {
            var oldCallback = callback;
            callback = message;
            if(oldCallback !== undefined) {
                message = oldCallback;
            }
        }

        if(that.isShow) {
            that.hide(function() {
                that.show(message, callback);
            });
            return;
        }

        if(that.hiding) {
            clearTimeout(that.hiding);
            that.hiding = null;
        }

        that.update(message);

        var placement = options.placement;
        var $parent = $(options.parent);
        var $holder = $parent.children('.messagers-holder.' + placement);
        if(!$holder.length) {
            $holder = $('<div/>').attr('class', 'messagers-holder ' + placement).appendTo($parent);
        }
        $holder.append(that.$);
        if(placement === 'center') {
            var offset = $(window).height() - $holder.height();
            $holder.css('top', Math.max(-offset, offset/2));
        }

        that.$.show().addClass('in');

        if(options.time) {
            that.hiding = setTimeout(function() {
                that.hide();
            }, options.time);
        }

        that.isShow = true;
        callback && callback();
        return that;
    };

    Messager.prototype.hide = function(callback, immediately) {
        if(callback === true) {
            immediately = true;
            callback = null;
        }
        var that = this;
        if(that.$.hasClass('in')) {
            that.$.removeClass('in');
            var removeMessager = function() {
                var $parent = that.$.parent();
                that.$.detach();
                if(!$parent.children().length) $parent.remove();
                callback && callback(true);
            };
            if(immediately) removeMessager();
            else setTimeout(removeMessager, 200);
        } else {
            callback && callback(false);
        }

        that.isShow = false;
    };

    Messager.prototype.destroy = function() {
        var that = this;
        that.hide(function()
        {
            that.$.remove();
            that.$ = null;
        }, true);
        delete all[that.id];
    };

    Messager.all = all;
    Messager.DEFAULTS = DEFAULTS;

    var hideMessage = function() {
        $('.messager').each(function() {
            var msg = $(this).data('zui.messager');
            if(msg && msg.hide) msg.hide(true);
        });
    };

    var showMessage = function(message, options) {
        if(typeof options === 'string') {
            options = {
                type: options
            };
        }
        options = $.extend({}, options);
        if(options.id === undefined) hideMessage();
        var msg = all[options.id] || new Messager(message, options);
        msg.show();
        return msg;
    };

    var getOptions = function(options) {
        return(typeof options === 'string') ? {
            placement: options
        } : options;
    };

    var zuiMessager = {
        show: showMessage,
        hide: hideMessage
    };

    $.each({
        primary  : 0,
        success  : 'ok-sign',
        info     : 'info-sign',
        warning  : 'warning-sign',
        danger   : 'exclamation-sign',
        important: 0,
        special  : 0
    }, function(name, icon){
        zuiMessager[name] = function(message, options) {
            return showMessage(message, $.extend({
                type: name,
                icon: icon || null
            }, getOptions(options)));
        };
    });

    $.zui({
        Messager: Messager,
        showMessager: showMessage,
        messager: zuiMessager
    });
}(jQuery, window, undefined));


/* ========================================================================
 * ZUI: color.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014-2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($, Math, window, undefined) {
    'use strict';

    var hexReg = /^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/,
        N255 = 255,
        N360 = 360,
        N100 = 100,
        STR_STRING = 'string',
        STR_OBJECT = 'object',
        namedColors = {
            aliceblue: '#f0f8ff',
            antiquewhite: '#faebd7',
            aqua: '#00ffff',
            aquamarine: '#7fffd4',
            azure: '#f0ffff',
            beige: '#f5f5dc',
            bisque: '#ffe4c4',
            black: '#000000',
            blanchedalmond: '#ffebcd',
            blue: '#0000ff',
            blueviolet: '#8a2be2',
            brown: '#a52a2a',
            burlywood: '#deb887',
            cadetblue: '#5f9ea0',
            chartreuse: '#7fff00',
            chocolate: '#d2691e',
            coral: '#ff7f50',
            cornflowerblue: '#6495ed',
            cornsilk: '#fff8dc',
            crimson: '#dc143c',
            cyan: '#00ffff',
            darkblue: '#00008b',
            darkcyan: '#008b8b',
            darkgoldenrod: '#b8860b',
            darkgray: '#a9a9a9',
            darkgreen: '#006400',
            darkkhaki: '#bdb76b',
            darkmagenta: '#8b008b',
            darkolivegreen: '#556b2f',
            darkorange: '#ff8c00',
            darkorchid: '#9932cc',
            darkred: '#8b0000',
            darksalmon: '#e9967a',
            darkseagreen: '#8fbc8f',
            darkslateblue: '#483d8b',
            darkslategray: '#2f4f4f',
            darkturquoise: '#00ced1',
            darkviolet: '#9400d3',
            deeppink: '#ff1493',
            deepskyblue: '#00bfff',
            dimgray: '#696969',
            dodgerblue: '#1e90ff',
            firebrick: '#b22222',
            floralwhite: '#fffaf0',
            forestgreen: '#228b22',
            fuchsia: '#ff00ff',
            gainsboro: '#dcdcdc',
            ghostwhite: '#f8f8ff',
            gold: '#ffd700',
            goldenrod: '#daa520',
            gray: '#808080',
            green: '#008000',
            greenyellow: '#adff2f',
            honeydew: '#f0fff0',
            hotpink: '#ff69b4',
            indianred: '#cd5c5c',
            indigo: '#4b0082',
            ivory: '#fffff0',
            khaki: '#f0e68c',
            lavender: '#e6e6fa',
            lavenderblush: '#fff0f5',
            lawngreen: '#7cfc00',
            lemonchiffon: '#fffacd',
            lightblue: '#add8e6',
            lightcoral: '#f08080',
            lightcyan: '#e0ffff',
            lightgoldenrodyellow: '#fafad2',
            lightgray: '#d3d3d3',
            lightgreen: '#90ee90',
            lightpink: '#ffb6c1',
            lightsalmon: '#ffa07a',
            lightseagreen: '#20b2aa',
            lightskyblue: '#87cefa',
            lightslategray: '#778899',
            lightsteelblue: '#b0c4de',
            lightyellow: '#ffffe0',
            lime: '#00ff00',
            limegreen: '#32cd32',
            linen: '#faf0e6',
            magenta: '#ff00ff',
            maroon: '#800000',
            mediumaquamarine: '#66cdaa',
            mediumblue: '#0000cd',
            mediumorchid: '#ba55d3',
            mediumpurple: '#9370db',
            mediumseagreen: '#3cb371',
            mediumslateblue: '#7b68ee',
            mediumspringgreen: '#00fa9a',
            mediumturquoise: '#48d1cc',
            mediumvioletred: '#c71585',
            midnightblue: '#191970',
            mintcream: '#f5fffa',
            mistyrose: '#ffe4e1',
            moccasin: '#ffe4b5',
            navajowhite: '#ffdead',
            navy: '#000080',
            oldlace: '#fdf5e6',
            olive: '#808000',
            olivedrab: '#6b8e23',
            orange: '#ffa500',
            orangered: '#ff4500',
            orchid: '#da70d6',
            palegoldenrod: '#eee8aa',
            palegreen: '#98fb98',
            paleturquoise: '#afeeee',
            palevioletred: '#db7093',
            papayawhip: '#ffefd5',
            peachpuff: '#ffdab9',
            peru: '#cd853f',
            pink: '#ffc0cb',
            plum: '#dda0dd',
            powderblue: '#b0e0e6',
            purple: '#800080',
            red: '#ff0000',
            rosybrown: '#bc8f8f',
            royalblue: '#4169e1',
            saddlebrown: '#8b4513',
            salmon: '#fa8072',
            sandybrown: '#f4a460',
            seagreen: '#2e8b57',
            seashell: '#fff5ee',
            sienna: '#a0522d',
            silver: '#c0c0c0',
            skyblue: '#87ceeb',
            slateblue: '#6a5acd',
            slategray: '#708090',
            snow: '#fffafa',
            springgreen: '#00ff7f',
            steelblue: '#4682b4',
            tan: '#d2b48c',
            teal: '#008080',
            thistle: '#d8bfd8',
            tomato: '#ff6347',
            turquoise: '#40e0d0',
            violet: '#ee82ee',
            wheat: '#f5deb3',
            white: '#ffffff',
            whitesmoke: '#f5f5f5',
            yellow: '#ffff00',
            yellowgreen: '#9acd32'
        };

    var isUndefined = function(x) {
        return x === undefined;
    };

    var isNotUndefined = function(x) {
        return !isUndefined(x);
    };

    var convertToInt = function(x) {
        return parseInt(x);
    };

    var convertToRgbInt = function(x) {
        return convertToInt(clamp(number(x), N255));
    };

    /* color */
    var Color = function(r, g, b, a) {
        var that = this;
        that.r = that.g = that.b = 0;
        that.a = 1;

        if(isNotUndefined(a)) that.a = clamp(number(a), 1);
        if(isNotUndefined(r) && isNotUndefined(g) && isNotUndefined(b)) {
            that.r = convertToRgbInt(r);
            that.g = convertToRgbInt(g);
            that.b = convertToRgbInt(b);
        } else if(isNotUndefined(r)) {
            var type = typeof(r);
            if(type == STR_STRING) {
                r = r.toLowerCase();
                if(r === 'transparent') {
                    that.a = 0;
                } else if(namedColors[r]) {
                    this.rgb(hexToRgb(namedColors[r]));
                } else if(r.indexOf('rgb') === 0) {
                    var rgbsArr = r.substring(r.indexOf('(') + 1, r.lastIndexOf(')')).split(',', 4);
                    that.rgb({
                        r: rgbsArr[0],
                        g: rgbsArr[1],
                        b: rgbsArr[2],
                        a: rgbsArr[3],
                    });
                } else {
                    that.rgb(hexToRgb(r));
                }
            } else if(type == 'number' && isUndefined(g)) {
                that.r = that.g = that.b = convertToRgbInt(r);
            } else if(type == STR_OBJECT && isNotUndefined(r.r)) {
                that.r = convertToRgbInt(r.r);
                if(isNotUndefined(r.g)) that.g = convertToRgbInt(r.g);
                if(isNotUndefined(r.b)) that.b = convertToRgbInt(r.b);
                if(isNotUndefined(r.a)) that.a = clamp(number(r.a), 1);
            } else if(type == STR_OBJECT && isNotUndefined(r.h)) {
                var hsl = {
                    h: clamp(number(r.h), N360),
                    s: 1,
                    l: 1,
                    a: 1
                };
                if(isNotUndefined(r.s)) hsl.s = clamp(number(r.s), 1);
                if(isNotUndefined(r.l)) hsl.l = clamp(number(r.l), 1);
                if(isNotUndefined(r.a)) hsl.a = clamp(number(r.a), 1);

                that.rgb(hslToRgb(hsl));
            }
        }
    };

    Color.prototype.rgb = function(rgb) {
        var that = this;
        if(isNotUndefined(rgb)) {
            if(typeof(rgb) == STR_OBJECT) {
                if(isNotUndefined(rgb.r)) that.r = convertToRgbInt(rgb.r);
                if(isNotUndefined(rgb.g)) that.g = convertToRgbInt(rgb.g);
                if(isNotUndefined(rgb.b)) that.b = convertToRgbInt(rgb.b);
                if(isNotUndefined(rgb.a)) that.a = clamp(number(rgb.a), 1);
            } else {
                var v = convertToInt(number(rgb));
                that.r = v;
                that.g = v;
                that.b = v;
            }
            return that;
        } else return {
            r: that.r,
            g: that.g,
            b: that.b,
            a: that.a
        };
    };

    Color.prototype.hue = function(hue) {
        var that = this;
        var hsl = that.toHsl();

        if(isUndefined(hue)) return hsl.h;
        else {
            hsl.h = clamp(number(hue), N360);
            that.rgb(hslToRgb(hsl));
            return that;
        }
    };

    Color.prototype.darken = function(amount) {
        var that = this;
        var hsl = that.toHsl();

        hsl.l -= amount / N100;
        hsl.l = clamp(hsl.l, 1);

        that.rgb(hslToRgb(hsl));
        return that;
    };

    Color.prototype.clone = function() {
        var that = this;
        return new Color(that.r, that.g, that.b, that.a);
    };

    Color.prototype.lighten = function(amount) {
        return this.darken(-amount);
    };

    Color.prototype.fade = function(amount) {
        this.a = clamp(amount / N100, 1);

        return this;
    };

    Color.prototype.spin = function(amount) {
        var hsl = this.toHsl();
        var hue = (hsl.h + amount) % N360;

        hsl.h = hue < 0 ? N360 + hue : hue;
        return this.rgb(hslToRgb(hsl));
    };

    Color.prototype.toHsl = function() {
        var that = this;
        var r = that.r / N255,
            g = that.g / N255,
            b = that.b / N255,
            a = that.a;

        var max = Math.max(r, g, b),
            min = Math.min(r, g, b);
        var h, s, l = (max + min) / 2,
            d = max - min;

        if(max === min) {
            h = s = 0;
        } else {
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);

            switch(max) {
                case r:
                    h = (g - b) / d + (g < b ? 6 : 0);
                    break;
                case g:
                    h = (b - r) / d + 2;
                    break;
                case b:
                    h = (r - g) / d + 4;
                    break;
            }
            h /= 6;
        }
        return {
            h: h * N360,
            s: s,
            l: l,
            a: a
        };
    };

    Color.prototype.luma = function() {
        var r = this.r / N255,
            g = this.g / N255,
            b = this.b / N255;

        r = (r <= 0.03928) ? r / 12.92 : Math.pow(((r + 0.055) / 1.055), 2.4);
        g = (g <= 0.03928) ? g / 12.92 : Math.pow(((g + 0.055) / 1.055), 2.4);
        b = (b <= 0.03928) ? b / 12.92 : Math.pow(((b + 0.055) / 1.055), 2.4);

        return 0.2126 * r + 0.7152 * g + 0.0722 * b;
    };

    Color.prototype.saturate = function(amount) {
        var hsl = this.toHsl();

        hsl.s += amount / N100;
        hsl.s = clamp(hsl.s);

        return this.rgb(hslToRgb(hsl));
    };

    Color.prototype.desaturate = function(amount) {
        return this.saturate(-amount);
    };

    Color.prototype.contrast = function(dark, light, threshold) {
        if(isUndefined(light)) light = new Color(N255, N255, N255, 1);
        else light = new Color(light);
        if(isUndefined(dark)) dark = new Color(0, 0, 0, 1);
        else dark = new Color(dark);

        if(dark.luma() > light.luma()) {
            var t = light;
            light = dark;
            dark = t;
        }

        if(this.a < 0.5) return dark;

        if(isUndefined(threshold)) threshold = 0.43;
        else threshold = number(threshold);

        if(this.luma() < threshold) {
            return light;
        } else {
            return dark;
        }
    };

    Color.prototype.hexStr = function() {
        var r = this.r.toString(16),
            g = this.g.toString(16),
            b = this.b.toString(16);
        if(r.length == 1) r = '0' + r;
        if(g.length == 1) g = '0' + g;
        if(b.length == 1) b = '0' + b;

        return '#' + r + g + b;
    };

    Color.prototype.toCssStr = function() {
        var that = this;
        if(that.a > 0) {
            if(that.a < 1) {
                return 'rgba(' + that.r + ',' + that.g + ',' + that.b + ',' + that.a + ')';
            } else {
                return that.hexStr();
            }
        } else {
            return 'transparent';
        }
    };

    Color.isColor = isColor;
    Color.names = namedColors;

    Color.get = function(colorName) {
        return new Color(colorName);
    };

    /* helpers */
    function hexToRgb(hex) {
        hex = hex.toLowerCase();
        if(hex && hexReg.test(hex)) {
            var i;
            if(hex.length === 4) {
                var hexNew = '#';
                for(i = 1; i < 4; i += 1) {
                    hexNew += hex.slice(i, i + 1).concat(hex.slice(i, i + 1));
                }
                hex = hexNew;
            }

            var hexChange = [];
            for(i = 1; i < 7; i += 2) {
                hexChange.push(convertToInt('0x' + hex.slice(i, i + 2)));
            }
            return {
                r: hexChange[0],
                g: hexChange[1],
                b: hexChange[2],
                a: 1
            };
        } else {
            throw new Error('Wrong hex string! (hex: ' + hex + ')');
        }
    }

    function isColor(hex) {
        return typeof(hex) === STR_STRING && (hex.toLowerCase() === 'transparent' || namedColors[hex.toLowerCase()] || hexReg.test($.trim(hex.toLowerCase())));
    }

    function hslToRgb(hsl) {
        var h = hsl.h,
            s = hsl.s,
            l = hsl.l,
            a = hsl.a;

        h = (number(h) % N360) / N360;
        s = clamp(number(s));
        l = clamp(number(l));
        a = clamp(number(a));

        var m2 = l <= 0.5 ? l * (s + 1) : l + s - l * s;
        var m1 = l * 2 - m2;

        var r = {
            r: hue(h + 1 / 3) * N255,
            g: hue(h) * N255,
            b: hue(h - 1 / 3) * N255,
            a: a
        };

        return r;

        function hue(h) {
            h = h < 0 ? h + 1 : (h > 1 ? h - 1 : h);
            if(h * 6 < 1) {
                return m1 + (m2 - m1) * h * 6;
            } else if(h * 2 < 1) {
                return m2;
            } else if(h * 3 < 2) {
                return m1 + (m2 - m1) * (2 / 3 - h) * 6;
            } else {
                return m1;
            }
        }
    }

    function fit(n, end, start) {
        if(isUndefined(start)) start = 0;
        if(isUndefined(end)) end = N255;

        return Math.min(Math.max(n, start), end);
    }

    function clamp(v, max) {
        return fit(v, max);
    }

    function number(n) {
        if(typeof(n) == 'number') return n;
        return parseFloat(n);
    }

    $.zui({
        Color: Color
    });

}(jQuery, Math, window, undefined));

/* ========================================================================
 * Chart.js: Chart.Core.js [Version: 1.0.2]
 * http://chartjs.org/
 *
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * official version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2015 Nick Downie, Released under the MIT license
 * https://github.com/nnnick/Chart.js/blob/master/LICENSE.md
 * ======================================================================== */


/*!
 * Chart.js 1.0.2
 * Copyright 2015 Nick Downie
 * Released under the MIT license
 * http://chartjs.org/
 */

/// ----- ZUI change begin -----
/// Add jquery object to namespace

/// (function(){ // Old code
(function($) {

    /// ----- ZUI change end -----


    "use strict";

    //Declare root variable - window in the browser, global on the server
    /// ----- ZUI change begin -----
    /// Change root to zui shared object
    ///
    ///   var root = this, // old code
    var root = $ && $.zui ? $.zui : this,
        /// ----- ZUI change end -----
        previous = root.Chart;

    //Occupy the global variable of Chart, and create a simple base class
    var Chart = function(context) {
        var chart = this;
        this.canvas = context.canvas;

        this.ctx = context;

        //Variables global to the chart
        var computeDimension = function(element, dimension) {
            if(element['offset' + dimension]) {
                return element['offset' + dimension];
            } else {
                return document.defaultView.getComputedStyle(element).getPropertyValue(dimension);
            }
        }

        var width = this.width = computeDimension(context.canvas, 'Width');
        var height = this.height = computeDimension(context.canvas, 'Height');

        // Firefox requires this to work correctly
        context.canvas.width = width;
        context.canvas.height = height;

        var width = this.width = context.canvas.width;
        var height = this.height = context.canvas.height;
        this.aspectRatio = this.width / this.height;
        //High pixel density displays - multiply the size of the canvas height/width by the device pixel ratio, then scale.
        helpers.retinaScale(this);

        return this;
    };

    //Globally expose the defaults to allow for user updating/changing
    Chart.defaults = {
        global: {
            // Boolean - Whether to animate the chart
            animation: true,

            // Number - Number of animation steps
            animationSteps: 60,

            // String - Animation easing effect
            // Possible effects are:
            // [easeInOutQuart, linear, easeOutBounce, easeInBack, easeInOutQuad,
            //  easeOutQuart, easeOutQuad, easeInOutBounce, easeOutSine, easeInOutCubic,
            //  easeInExpo, easeInOutBack, easeInCirc, easeInOutElastic, easeOutBack,
            //  easeInQuad, easeInOutExpo, easeInQuart, easeOutQuint, easeInOutCirc,
            //  easeInSine, easeOutExpo, easeOutCirc, easeOutCubic, easeInQuint,
            //  easeInElastic, easeInOutSine, easeInOutQuint, easeInBounce,
            //  easeOutElastic, easeInCubic]
            animationEasing: "easeOutQuart",

            // Boolean - If we should show the scale at all
            showScale: true,

            // Boolean - If we want to override with a hard coded scale
            scaleOverride: false,

            // ** Required if scaleOverride is true **
            // Number - The number of steps in a hard coded scale
            scaleSteps: null,
            // Number - The value jump in the hard coded scale
            scaleStepWidth: null,
            // Number - The scale starting value
            scaleStartValue: null,

            // String - Colour of the scale line
            scaleLineColor: "rgba(0,0,0,.1)",

            // Number - Pixel width of the scale line
            scaleLineWidth: 1,

            // Boolean - Whether to show labels on the scale
            scaleShowLabels: true,

            // Interpolated JS string - can access value
            scaleLabel: "<%=value%>",

            // Boolean - Whether the scale should stick to integers, and not show any floats even if drawing space is there
            scaleIntegersOnly: true,

            // Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero: false,

            // String - Scale label font declaration for the scale label
            scaleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

            // Number - Scale label font size in pixels
            scaleFontSize: 12,

            // String - Scale label font weight style
            scaleFontStyle: "normal",

            // String - Scale label font colour
            scaleFontColor: "#666",

            // Boolean - whether or not the chart should be responsive and resize when the browser does.
            responsive: false,

            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: true,

            // Boolean - Determines whether to draw tooltips on the canvas or not - attaches events to touchmove & mousemove
            showTooltips: true,

            // Boolean - Determines whether to draw built-in tooltip or call custom tooltip function
            customTooltips: false,

            // Array - Array of string names to attach tooltip events
            tooltipEvents: ["mousemove", "touchstart", "touchmove", "mouseout"],

            // String - Tooltip background colour
            tooltipFillColor: "rgba(0,0,0,0.8)",

            // String - Tooltip label font declaration for the scale label
            tooltipFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

            // Number - Tooltip label font size in pixels
            tooltipFontSize: 14,

            // String - Tooltip font weight style
            tooltipFontStyle: "normal",

            // String - Tooltip label font colour
            tooltipFontColor: "#fff",

            // String - Tooltip title font declaration for the scale label
            tooltipTitleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

            // Number - Tooltip title font size in pixels
            tooltipTitleFontSize: 14,

            // String - Tooltip title font weight style
            tooltipTitleFontStyle: "bold",

            // String - Tooltip title font colour
            tooltipTitleFontColor: "#fff",

            // Number - pixel width of padding around tooltip text
            tooltipYPadding: 6,

            // Number - pixel width of padding around tooltip text
            tooltipXPadding: 6,

            // Number - Size of the caret on the tooltip
            tooltipCaretSize: 8,

            // Number - Pixel radius of the tooltip border
            tooltipCornerRadius: 6,

            // Number - Pixel offset from point x to tooltip edge
            tooltipXOffset: 10,

            // String - Template string for single tooltips
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",

            // String - Template string for single tooltips
            multiTooltipTemplate: "<%if (datasetLabel){%><%=datasetLabel%>: <%}%><%= value %>",

            // String - Template string for multiple tooltip title
            multiTooltipTitleTemplate: '<%= label %>',

            // String - Colour behind the legend colour block
            multiTooltipKeyBackground: '#fff',

            // Function - Will fire on animation progression.
            onAnimationProgress: function() {},

            // Function - Will fire on animation completion.
            onAnimationComplete: function() {}

        }
    };

    //Create a dictionary of chart types, to allow for extension of existing types
    Chart.types = {};

    //Global Chart helpers object for utility methods and classes
    var helpers = Chart.helpers = {};

    //-- Basic js utility methods
    var each = helpers.each = function(loopable, callback, self) {
            var additionalArgs = Array.prototype.slice.call(arguments, 3);
            // Check to see if null or undefined firstly.
            if(loopable) {
                if(loopable.length === +loopable.length) {
                    var i;
                    for(i = 0; i < loopable.length; i++) {
                        callback.apply(self, [loopable[i], i].concat(additionalArgs));
                    }
                } else {
                    for(var item in loopable) {
                        callback.apply(self, [loopable[item], item].concat(additionalArgs));
                    }
                }
            }
        },
        clone = helpers.clone = function(obj) {
            var objClone = {};
            each(obj, function(value, key) {
                if(obj.hasOwnProperty(key)) objClone[key] = value;
            });
            return objClone;
        },
        extend = helpers.extend = function(base) {
            each(Array.prototype.slice.call(arguments, 1), function(extensionObject) {
                each(extensionObject, function(value, key) {
                    if(extensionObject.hasOwnProperty(key)) base[key] = value;
                });
            });
            return base;
        },
        merge = helpers.merge = function(base, master) {
            //Merge properties in left object over to a shallow clone of object right.
            var args = Array.prototype.slice.call(arguments, 0);
            args.unshift({});
            return extend.apply(null, args);
        },
        indexOf = helpers.indexOf = function(arrayToSearch, item) {
            if(Array.prototype.indexOf) {
                return arrayToSearch.indexOf(item);
            } else {
                for(var i = 0; i < arrayToSearch.length; i++) {
                    if(arrayToSearch[i] === item) return i;
                }
                return -1;
            }
        },
        where = helpers.where = function(collection, filterCallback) {
            var filtered = [];

            helpers.each(collection, function(item) {
                if(filterCallback(item)) {
                    filtered.push(item);
                }
            });

            return filtered;
        },
        findNextWhere = helpers.findNextWhere = function(arrayToSearch, filterCallback, startIndex) {
            // Default to start of the array
            if(!startIndex) {
                startIndex = -1;
            }
            for(var i = startIndex + 1; i < arrayToSearch.length; i++) {
                var currentItem = arrayToSearch[i];
                if(filterCallback(currentItem)) {
                    return currentItem;
                }
            }
        },
        findPreviousWhere = helpers.findPreviousWhere = function(arrayToSearch, filterCallback, startIndex) {
            // Default to end of the array
            if(!startIndex) {
                startIndex = arrayToSearch.length;
            }
            for(var i = startIndex - 1; i >= 0; i--) {
                var currentItem = arrayToSearch[i];
                if(filterCallback(currentItem)) {
                    return currentItem;
                }
            }
        },
        inherits = helpers.inherits = function(extensions) {
            //Basic javascript inheritance based on the model created in Backbone.js
            var parent = this;
            var ChartElement = (extensions && extensions.hasOwnProperty("constructor")) ? extensions.constructor : function() {
                return parent.apply(this, arguments);
            };

            var Surrogate = function() {
                this.constructor = ChartElement;
            };
            Surrogate.prototype = parent.prototype;
            ChartElement.prototype = new Surrogate();

            ChartElement.extend = inherits;

            if(extensions) extend(ChartElement.prototype, extensions);

            ChartElement.__super__ = parent.prototype;

            return ChartElement;
        },
        noop = helpers.noop = function() {},
        uid = helpers.uid = (function() {
            var id = 0;
            return function() {
                return "chart-" + id++;
            };
        })(),
        warn = helpers.warn = function(str) {
            //Method for warning of errors
            if(window.console && typeof window.console.warn == "function") console.warn(str);
        },
        amd = helpers.amd = (typeof define == 'function' && define.amd),
        //-- Math methods
        isNumber = helpers.isNumber = function(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        },
        max = helpers.max = function(array) {
            return Math.max.apply(Math, array);
        },
        min = helpers.min = function(array) {
            return Math.min.apply(Math, array);
        },
        cap = helpers.cap = function(valueToCap, maxValue, minValue) {
            if(isNumber(maxValue)) {
                if(valueToCap > maxValue) {
                    return maxValue;
                }
            } else if(isNumber(minValue)) {
                if(valueToCap < minValue) {
                    return minValue;
                }
            }
            return valueToCap;
        },
        getDecimalPlaces = helpers.getDecimalPlaces = function(num) {
            if(num % 1 !== 0 && isNumber(num)) {
                return num.toString().split(".")[1].length;
            } else {
                return 0;
            }
        },
        toRadians = helpers.radians = function(degrees) {
            return degrees * (Math.PI / 180);
        },
        // Gets the angle from vertical upright to the point about a centre.
        getAngleFromPoint = helpers.getAngleFromPoint = function(centrePoint, anglePoint) {
            var distanceFromXCenter = anglePoint.x - centrePoint.x,
                distanceFromYCenter = anglePoint.y - centrePoint.y,
                radialDistanceFromCenter = Math.sqrt(distanceFromXCenter * distanceFromXCenter + distanceFromYCenter * distanceFromYCenter);


            var angle = Math.PI * 2 + Math.atan2(distanceFromYCenter, distanceFromXCenter);

            //If the segment is in the top left quadrant, we need to add another rotation to the angle
            if(distanceFromXCenter < 0 && distanceFromYCenter < 0) {
                angle += Math.PI * 2;
            }

            return {
                angle: angle,
                distance: radialDistanceFromCenter
            };
        },
        aliasPixel = helpers.aliasPixel = function(pixelWidth) {
            return(pixelWidth % 2 === 0) ? 0 : 0.5;
        },
        splineCurve = helpers.splineCurve = function(FirstPoint, MiddlePoint, AfterPoint, t) {
            //Props to Rob Spencer at scaled innovation for his post on splining between points
            //http://scaledinnovation.com/analytics/splines/aboutSplines.html
            var d01 = Math.sqrt(Math.pow(MiddlePoint.x - FirstPoint.x, 2) + Math.pow(MiddlePoint.y - FirstPoint.y, 2)),
                d12 = Math.sqrt(Math.pow(AfterPoint.x - MiddlePoint.x, 2) + Math.pow(AfterPoint.y - MiddlePoint.y, 2)),
                fa = t * d01 / (d01 + d12), // scaling factor for triangle Ta
                fb = t * d12 / (d01 + d12);
            return {
                inner: {
                    x: MiddlePoint.x - fa * (AfterPoint.x - FirstPoint.x),
                    y: MiddlePoint.y - fa * (AfterPoint.y - FirstPoint.y)
                },
                outer: {
                    x: MiddlePoint.x + fb * (AfterPoint.x - FirstPoint.x),
                    y: MiddlePoint.y + fb * (AfterPoint.y - FirstPoint.y)
                }
            };
        },
        calculateOrderOfMagnitude = helpers.calculateOrderOfMagnitude = function(val) {
            return Math.floor(Math.log(val) / Math.LN10);
        },
        calculateScaleRange = helpers.calculateScaleRange = function(valuesArray, drawingSize, textSize, startFromZero, integersOnly) {

            //Set a minimum step of two - a point at the top of the graph, and a point at the base
            var minSteps = 2,
                maxSteps = Math.floor(drawingSize / (textSize * 1.5)),
                skipFitting = (minSteps >= maxSteps);

            var maxValue = max(valuesArray),
                minValue = min(valuesArray);

            // We need some degree of seperation here to calculate the scales if all the values are the same
            // Adding/minusing 0.5 will give us a range of 1.
            if(maxValue === minValue) {
                maxValue += 0.5;
                // So we don't end up with a graph with a negative start value if we've said always start from zero
                if(minValue >= 0.5 && !startFromZero) {
                    minValue -= 0.5;
                } else {
                    // Make up a whole number above the values
                    maxValue += 0.5;
                }
            }

            var valueRange = Math.abs(maxValue - minValue),
                rangeOrderOfMagnitude = calculateOrderOfMagnitude(valueRange),
                graphMax = Math.ceil(maxValue / (1 * Math.pow(10, rangeOrderOfMagnitude))) * Math.pow(10, rangeOrderOfMagnitude),
                graphMin = (startFromZero) ? 0 : Math.floor(minValue / (1 * Math.pow(10, rangeOrderOfMagnitude))) * Math.pow(10, rangeOrderOfMagnitude),
                graphRange = graphMax - graphMin,
                stepValue = Math.pow(10, rangeOrderOfMagnitude),
                numberOfSteps = Math.round(graphRange / stepValue);

            //If we have more space on the graph we'll use it to give more definition to the data
            while((numberOfSteps > maxSteps || (numberOfSteps * 2) < maxSteps) && !skipFitting) {
                if(numberOfSteps > maxSteps) {
                    stepValue *= 2;
                    numberOfSteps = Math.round(graphRange / stepValue);
                    // Don't ever deal with a decimal number of steps - cancel fitting and just use the minimum number of steps.
                    if(numberOfSteps % 1 !== 0) {
                        skipFitting = true;
                    }
                }
                //We can fit in double the amount of scale points on the scale
                else {
                    //If user has declared ints only, and the step value isn't a decimal
                    if(integersOnly && rangeOrderOfMagnitude >= 0) {
                        //If the user has said integers only, we need to check that making the scale more granular wouldn't make it a float
                        if(stepValue / 2 % 1 === 0) {
                            stepValue /= 2;
                            numberOfSteps = Math.round(graphRange / stepValue);
                        }
                        //If it would make it a float break out of the loop
                        else {
                            break;
                        }
                    }
                    //If the scale doesn't have to be an int, make the scale more granular anyway.
                    else {
                        stepValue /= 2;
                        numberOfSteps = Math.round(graphRange / stepValue);
                    }

                }
            }

            if(skipFitting) {
                numberOfSteps = minSteps;
                stepValue = graphRange / numberOfSteps;
            }

            return {
                steps: numberOfSteps,
                stepValue: stepValue,
                min: graphMin,
                max: graphMin + (numberOfSteps * stepValue)
            };

        },
        /* jshint ignore:start */
        // Blows up jshint errors based on the new Function constructor
        //Templating methods
        //Javascript micro templating by John Resig - source at http://ejohn.org/blog/javascript-micro-templating/
        template = helpers.template = function(templateString, valuesObject) {

            // If templateString is function rather than string-template - call the function for valuesObject

            if(templateString instanceof Function) {
                return templateString(valuesObject);
            }

            var cache = {};

            function tmpl(str, data) {
                // Figure out if we're getting a template, or if we need to
                // load the template - and be sure to cache the result.
                var fn = !/\W/.test(str) ?
                    cache[str] = cache[str] :

                    // Generate a reusable function that will serve as a template
                    // generator (and which will be cached).
                    new Function("obj",
                        "var p=[],print=function(){p.push.apply(p,arguments);};" +

                        // Introduce the data as local variables using with(){}
                        "with(obj){p.push('" +

                        // Convert the template into pure JavaScript
                        str
                        .replace(/[\r\t\n]/g, " ")
                        .split("<%").join("\t")
                        .replace(/((^|%>)[^\t]*)'/g, "$1\r")
                        .replace(/\t=(.*?)%>/g, "',$1,'")
                        .split("\t").join("');")
                        .split("%>").join("p.push('")
                        .split("\r").join("\\'") +
                        "');}return p.join('');"
                    );

                // Provide some basic currying to the user
                return data ? fn(data) : fn;
            }
            return tmpl(templateString, valuesObject);
        },
        /* jshint ignore:end */
        generateLabels = helpers.generateLabels = function(templateString, numberOfSteps, graphMin, stepValue) {
            var labelsArray = new Array(numberOfSteps);
            if(labelTemplateString) {
                each(labelsArray, function(val, index) {
                    labelsArray[index] = template(templateString, {
                        value: (graphMin + (stepValue * (index + 1)))
                    });
                });
            }
            return labelsArray;
        },
        //--Animation methods
        //Easing functions adapted from Robert Penner's easing equations
        //http://www.robertpenner.com/easing/
        easingEffects = helpers.easingEffects = {
            linear: function(t) {
                return t;
            },
            easeInQuad: function(t) {
                return t * t;
            },
            easeOutQuad: function(t) {
                return -1 * t * (t - 2);
            },
            easeInOutQuad: function(t) {
                if((t /= 1 / 2) < 1) return 1 / 2 * t * t;
                return -1 / 2 * ((--t) * (t - 2) - 1);
            },
            easeInCubic: function(t) {
                return t * t * t;
            },
            easeOutCubic: function(t) {
                return 1 * ((t = t / 1 - 1) * t * t + 1);
            },
            easeInOutCubic: function(t) {
                if((t /= 1 / 2) < 1) return 1 / 2 * t * t * t;
                return 1 / 2 * ((t -= 2) * t * t + 2);
            },
            easeInQuart: function(t) {
                return t * t * t * t;
            },
            easeOutQuart: function(t) {
                return -1 * ((t = t / 1 - 1) * t * t * t - 1);
            },
            easeInOutQuart: function(t) {
                if((t /= 1 / 2) < 1) return 1 / 2 * t * t * t * t;
                return -1 / 2 * ((t -= 2) * t * t * t - 2);
            },
            easeInQuint: function(t) {
                return 1 * (t /= 1) * t * t * t * t;
            },
            easeOutQuint: function(t) {
                return 1 * ((t = t / 1 - 1) * t * t * t * t + 1);
            },
            easeInOutQuint: function(t) {
                if((t /= 1 / 2) < 1) return 1 / 2 * t * t * t * t * t;
                return 1 / 2 * ((t -= 2) * t * t * t * t + 2);
            },
            easeInSine: function(t) {
                return -1 * Math.cos(t / 1 * (Math.PI / 2)) + 1;
            },
            easeOutSine: function(t) {
                return 1 * Math.sin(t / 1 * (Math.PI / 2));
            },
            easeInOutSine: function(t) {
                return -1 / 2 * (Math.cos(Math.PI * t / 1) - 1);
            },
            easeInExpo: function(t) {
                return(t === 0) ? 1 : 1 * Math.pow(2, 10 * (t / 1 - 1));
            },
            easeOutExpo: function(t) {
                return(t === 1) ? 1 : 1 * (-Math.pow(2, -10 * t / 1) + 1);
            },
            easeInOutExpo: function(t) {
                if(t === 0) return 0;
                if(t === 1) return 1;
                if((t /= 1 / 2) < 1) return 1 / 2 * Math.pow(2, 10 * (t - 1));
                return 1 / 2 * (-Math.pow(2, -10 * --t) + 2);
            },
            easeInCirc: function(t) {
                if(t >= 1) return t;
                return -1 * (Math.sqrt(1 - (t /= 1) * t) - 1);
            },
            easeOutCirc: function(t) {
                return 1 * Math.sqrt(1 - (t = t / 1 - 1) * t);
            },
            easeInOutCirc: function(t) {
                if((t /= 1 / 2) < 1) return -1 / 2 * (Math.sqrt(1 - t * t) - 1);
                return 1 / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1);
            },
            easeInElastic: function(t) {
                var s = 1.70158;
                var p = 0;
                var a = 1;
                if(t === 0) return 0;
                if((t /= 1) == 1) return 1;
                if(!p) p = 1 * 0.3;
                if(a < Math.abs(1)) {
                    a = 1;
                    s = p / 4;
                } else s = p / (2 * Math.PI) * Math.asin(1 / a);
                return -(a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * 1 - s) * (2 * Math.PI) / p));
            },
            easeOutElastic: function(t) {
                var s = 1.70158;
                var p = 0;
                var a = 1;
                if(t === 0) return 0;
                if((t /= 1) == 1) return 1;
                if(!p) p = 1 * 0.3;
                if(a < Math.abs(1)) {
                    a = 1;
                    s = p / 4;
                } else s = p / (2 * Math.PI) * Math.asin(1 / a);
                return a * Math.pow(2, -10 * t) * Math.sin((t * 1 - s) * (2 * Math.PI) / p) + 1;
            },
            easeInOutElastic: function(t) {
                var s = 1.70158;
                var p = 0;
                var a = 1;
                if(t === 0) return 0;
                if((t /= 1 / 2) == 2) return 1;
                if(!p) p = 1 * (0.3 * 1.5);
                if(a < Math.abs(1)) {
                    a = 1;
                    s = p / 4;
                } else s = p / (2 * Math.PI) * Math.asin(1 / a);
                if(t < 1) return -0.5 * (a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * 1 - s) * (2 * Math.PI) / p));
                return a * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * 1 - s) * (2 * Math.PI) / p) * 0.5 + 1;
            },
            easeInBack: function(t) {
                var s = 1.70158;
                return 1 * (t /= 1) * t * ((s + 1) * t - s);
            },
            easeOutBack: function(t) {
                var s = 1.70158;
                return 1 * ((t = t / 1 - 1) * t * ((s + 1) * t + s) + 1);
            },
            easeInOutBack: function(t) {
                var s = 1.70158;
                if((t /= 1 / 2) < 1) return 1 / 2 * (t * t * (((s *= (1.525)) + 1) * t - s));
                return 1 / 2 * ((t -= 2) * t * (((s *= (1.525)) + 1) * t + s) + 2);
            },
            easeInBounce: function(t) {
                return 1 - easingEffects.easeOutBounce(1 - t);
            },
            easeOutBounce: function(t) {
                if((t /= 1) < (1 / 2.75)) {
                    return 1 * (7.5625 * t * t);
                } else if(t < (2 / 2.75)) {
                    return 1 * (7.5625 * (t -= (1.5 / 2.75)) * t + 0.75);
                } else if(t < (2.5 / 2.75)) {
                    return 1 * (7.5625 * (t -= (2.25 / 2.75)) * t + 0.9375);
                } else {
                    return 1 * (7.5625 * (t -= (2.625 / 2.75)) * t + 0.984375);
                }
            },
            easeInOutBounce: function(t) {
                if(t < 1 / 2) return easingEffects.easeInBounce(t * 2) * 0.5;
                return easingEffects.easeOutBounce(t * 2 - 1) * 0.5 + 1 * 0.5;
            }
        },
        //Request animation polyfill - http://www.paulirish.com/2011/requestanimationframe-for-smart-animating/
        requestAnimFrame = helpers.requestAnimFrame = (function() {
            return window.requestAnimationFrame ||
                window.webkitRequestAnimationFrame ||
                window.mozRequestAnimationFrame ||
                window.oRequestAnimationFrame ||
                window.msRequestAnimationFrame ||
                function(callback) {
                    return window.setTimeout(callback, 1000 / 60);
                };
        })(),
        cancelAnimFrame = helpers.cancelAnimFrame = (function() {
            return window.cancelAnimationFrame ||
                window.webkitCancelAnimationFrame ||
                window.mozCancelAnimationFrame ||
                window.oCancelAnimationFrame ||
                window.msCancelAnimationFrame ||
                function(callback) {
                    return window.clearTimeout(callback, 1000 / 60);
                };
        })(),
        animationLoop = helpers.animationLoop = function(callback, totalSteps, easingString, onProgress, onComplete, chartInstance) {

            var currentStep = 0,
                easingFunction = easingEffects[easingString] || easingEffects.linear;

            var animationFrame = function() {
                currentStep++;
                var stepDecimal = currentStep / totalSteps;
                var easeDecimal = easingFunction(stepDecimal);

                callback.call(chartInstance, easeDecimal, stepDecimal, currentStep);
                onProgress.call(chartInstance, easeDecimal, stepDecimal);
                if(currentStep < totalSteps) {
                    chartInstance.animationFrame = requestAnimFrame(animationFrame);
                } else {
                    onComplete.apply(chartInstance);
                }
            };
            requestAnimFrame(animationFrame);
        },
        //-- DOM methods
        getRelativePosition = helpers.getRelativePosition = function(evt) {
            var mouseX, mouseY;
            var e = evt.originalEvent || evt,
                canvas = evt.currentTarget || evt.srcElement,
                boundingRect = canvas.getBoundingClientRect();

            if(e.touches) {
                mouseX = e.touches[0].clientX - boundingRect.left;
                mouseY = e.touches[0].clientY - boundingRect.top;

            } else {
                mouseX = e.clientX - boundingRect.left;
                mouseY = e.clientY - boundingRect.top;
            }

            return {
                x: mouseX,
                y: mouseY
            };

        },
        addEvent = helpers.addEvent = function(node, eventType, method) {
            if(node.addEventListener) {
                node.addEventListener(eventType, method);
            } else if(node.attachEvent) {
                node.attachEvent("on" + eventType, method);
            } else {
                node["on" + eventType] = method;
            }
        },
        removeEvent = helpers.removeEvent = function(node, eventType, handler) {
            if(node.removeEventListener) {
                node.removeEventListener(eventType, handler, false);
            } else if(node.detachEvent) {
                node.detachEvent("on" + eventType, handler);
            } else {
                node["on" + eventType] = noop;
            }
        },
        bindEvents = helpers.bindEvents = function(chartInstance, arrayOfEvents, handler) {
            // Create the events object if it's not already present
            if(!chartInstance.events) chartInstance.events = {};

            each(arrayOfEvents, function(eventName) {
                chartInstance.events[eventName] = function() {
                    handler.apply(chartInstance, arguments);
                };
                addEvent(chartInstance.chart.canvas, eventName, chartInstance.events[eventName]);
            });
        },
        unbindEvents = helpers.unbindEvents = function(chartInstance, arrayOfEvents) {
            each(arrayOfEvents, function(handler, eventName) {
                removeEvent(chartInstance.chart.canvas, eventName, handler);
            });
        },
        getMaximumWidth = helpers.getMaximumWidth = function(domNode) {
            var container = domNode.parentNode;
            // TODO = check cross browser stuff with this.
            return container.clientWidth;
        },
        getMaximumHeight = helpers.getMaximumHeight = function(domNode) {
            var container = domNode.parentNode;
            // TODO = check cross browser stuff with this.
            return container.clientHeight;
        },
        getMaximumSize = helpers.getMaximumSize = helpers.getMaximumWidth, // legacy support
        retinaScale = helpers.retinaScale = function(chart) {
            var ctx = chart.ctx,
                width = chart.canvas.width,
                height = chart.canvas.height;

            if(window.devicePixelRatio) {
                ctx.canvas.style.width = width + "px";
                ctx.canvas.style.height = height + "px";
                ctx.canvas.height = height * window.devicePixelRatio;
                ctx.canvas.width = width * window.devicePixelRatio;
                ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
            }
        },
        //-- Canvas methods
        clear = helpers.clear = function(chart) {
            chart.ctx.clearRect(0, 0, chart.width, chart.height);
        },
        fontString = helpers.fontString = function(pixelSize, fontStyle, fontFamily) {
            return fontStyle + " " + pixelSize + "px " + fontFamily;
        },
        longestText = helpers.longestText = function(ctx, font, arrayOfStrings) {
            ctx.font = font;
            var longest = 0;
            each(arrayOfStrings, function(string) {
                var textWidth = ctx.measureText(string).width;
                longest = (textWidth > longest) ? textWidth : longest;
            });
            return longest;
        },
        drawRoundedRectangle = helpers.drawRoundedRectangle = function(ctx, x, y, width, height, radius) {
            ctx.beginPath();
            ctx.moveTo(x + radius, y);
            ctx.lineTo(x + width - radius, y);
            ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
            ctx.lineTo(x + width, y + height - radius);
            ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
            ctx.lineTo(x + radius, y + height);
            ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
            ctx.lineTo(x, y + radius);
            ctx.quadraticCurveTo(x, y, x + radius, y);
            ctx.closePath();
        };



    //Store a reference to each instance - allowing us to globally resize chart instances on window resize.
    //Destroy method on the chart will remove the instance of the chart from this reference.
    Chart.instances = {};

    Chart.Type = function(data, options, chart) {
        this.options = options;
        this.chart = chart;
        this.id = uid();
        //Add the chart instance to the global namespace
        Chart.instances[this.id] = this;

        // Initialize is always called when a chart type is created
        // By default it is a no op, but it should be extended
        if(options.responsive) {
            this.resize();
        }
        this.initialize.call(this, data);
    };

    //Core methods that'll be a part of every chart type
    extend(Chart.Type.prototype, {
        initialize: function() {
            return this;
        },
        clear: function() {
            clear(this.chart);
            return this;
        },
        stop: function() {
            // Stops any current animation loop occuring
            cancelAnimFrame(this.animationFrame);
            return this;
        },
        resize: function(callback) {
            this.stop();
            var canvas = this.chart.canvas,
                newWidth = getMaximumWidth(this.chart.canvas),
                newHeight = this.options.maintainAspectRatio ? newWidth / this.chart.aspectRatio : getMaximumHeight(this.chart.canvas);

            canvas.width = this.chart.width = newWidth;
            canvas.height = this.chart.height = newHeight;

            retinaScale(this.chart);

            if(typeof callback === "function") {
                callback.apply(this, Array.prototype.slice.call(arguments, 1));
            }
            return this;
        },
        reflow: noop,
        render: function(reflow) {
            if(reflow) {
                this.reflow();
            }
            if(this.options.animation && !reflow) {
                helpers.animationLoop(
                    this.draw,
                    this.options.animationSteps,
                    this.options.animationEasing,
                    this.options.onAnimationProgress,
                    this.options.onAnimationComplete,
                    this
                );
            } else {
                this.draw();
                this.options.onAnimationComplete.call(this);
            }
            return this;
        },
        generateLegend: function() {
            return template(this.options.legendTemplate, this);
        },
        destroy: function() {
            this.clear();
            unbindEvents(this, this.events);
            var canvas = this.chart.canvas;

            // Reset canvas height/width attributes starts a fresh with the canvas context
            canvas.width = this.chart.width;
            canvas.height = this.chart.height;

            // < IE9 doesn't support removeProperty
            if(canvas.style.removeProperty) {
                canvas.style.removeProperty('width');
                canvas.style.removeProperty('height');
            } else {
                canvas.style.removeAttribute('width');
                canvas.style.removeAttribute('height');
            }

            delete Chart.instances[this.id];
        },
        showTooltip: function(ChartElements, forceRedraw) {
            // Only redraw the chart if we've actually changed what we're hovering on.
            if(typeof this.activeElements === 'undefined') this.activeElements = [];

            var isChanged = (function(Elements) {
                var changed = false;

                if(Elements.length !== this.activeElements.length) {
                    changed = true;
                    return changed;
                }

                each(Elements, function(element, index) {
                    if(element !== this.activeElements[index]) {
                        changed = true;
                    }
                }, this);
                return changed;
            }).call(this, ChartElements);

            if(!isChanged && !forceRedraw) {
                return;
            } else {
                this.activeElements = ChartElements;
            }
            this.draw();
            if(this.options.customTooltips) {
                this.options.customTooltips(false);
            }
            if(ChartElements.length > 0) {
                // If we have multiple datasets, show a MultiTooltip for all of the data points at that index
                if(this.datasets && this.datasets.length > 1) {
                    var dataArray,
                        dataIndex;

                    for(var i = this.datasets.length - 1; i >= 0; i--) {
                        dataArray = this.datasets[i].points || this.datasets[i].bars || this.datasets[i].segments;
                        dataIndex = indexOf(dataArray, ChartElements[0]);
                        if(dataIndex !== -1) {
                            break;
                        }
                    }
                    var tooltipLabels = [],
                        tooltipColors = [],
                        medianPosition = (function(index) {

                            // Get all the points at that particular index
                            var Elements = [],
                                dataCollection,
                                xPositions = [],
                                yPositions = [],
                                xMax,
                                yMax,
                                xMin,
                                yMin;
                            helpers.each(this.datasets, function(dataset) {
                                /// ZUI change begin
                                if(dataset.showTooltips === false) return;
                                /// ZUI change end
                                dataCollection = dataset.points || dataset.bars || dataset.segments;
                                if(dataCollection[dataIndex] && dataCollection[dataIndex].hasValue()) {
                                    Elements.push(dataCollection[dataIndex]);
                                }
                            });

                            helpers.each(Elements, function(element) {
                                xPositions.push(element.x);
                                yPositions.push(element.y);


                                //Include any colour information about the element
                                tooltipLabels.push(helpers.template(this.options.multiTooltipTemplate, element));
                                tooltipColors.push({
                                    fill: element._saved.fillColor || element.fillColor,
                                    stroke: element._saved.strokeColor || element.strokeColor
                                });

                            }, this);

                            yMin = min(yPositions);
                            yMax = max(yPositions);

                            xMin = min(xPositions);
                            xMax = max(xPositions);

                            return {
                                x: (xMin > this.chart.width / 2) ? xMin : xMax,
                                y: (yMin + yMax) / 2
                            };
                        }).call(this, dataIndex);

                    new Chart.MultiTooltip({
                        x: medianPosition.x,
                        y: medianPosition.y,
                        xPadding: this.options.tooltipXPadding,
                        yPadding: this.options.tooltipYPadding,
                        xOffset: this.options.tooltipXOffset,
                        fillColor: this.options.tooltipFillColor,
                        textColor: this.options.tooltipFontColor,
                        fontFamily: this.options.tooltipFontFamily,
                        fontStyle: this.options.tooltipFontStyle,
                        fontSize: this.options.tooltipFontSize,
                        titleTextColor: this.options.tooltipTitleFontColor,
                        titleFontFamily: this.options.tooltipTitleFontFamily,
                        titleFontStyle: this.options.tooltipTitleFontStyle,
                        titleFontSize: this.options.tooltipTitleFontSize,
                        cornerRadius: this.options.tooltipCornerRadius,
                        labels: tooltipLabels,
                        legendColors: tooltipColors,
                        legendColorBackground: this.options.multiTooltipKeyBackground,
                        title: template(this.options.multiTooltipTitleTemplate, ChartElements[0]),
                        chart: this.chart,
                        ctx: this.chart.ctx,
                        custom: this.options.customTooltips
                    }).draw();

                } else {
                    each(ChartElements, function(Element) {
                        var tooltipPosition = Element.tooltipPosition();
                        new Chart.Tooltip({
                            x: Math.round(tooltipPosition.x),
                            y: Math.round(tooltipPosition.y),
                            xPadding: this.options.tooltipXPadding,
                            yPadding: this.options.tooltipYPadding,
                            fillColor: this.options.tooltipFillColor,
                            textColor: this.options.tooltipFontColor,
                            fontFamily: this.options.tooltipFontFamily,
                            fontStyle: this.options.tooltipFontStyle,
                            fontSize: this.options.tooltipFontSize,
                            caretHeight: this.options.tooltipCaretSize,
                            cornerRadius: this.options.tooltipCornerRadius,
                            text: template(this.options.tooltipTemplate, Element),
                            chart: this.chart,
                            custom: this.options.customTooltips
                        }).draw();
                    }, this);
                }
            }
            return this;
        },
        toBase64Image: function() {
            return this.chart.canvas.toDataURL.apply(this.chart.canvas, arguments);
        }
    });

    Chart.Type.extend = function(extensions) {

        var parent = this;

        var ChartType = function() {
            return parent.apply(this, arguments);
        };

        //Copy the prototype object of the this class
        ChartType.prototype = clone(parent.prototype);
        //Now overwrite some of the properties in the base class with the new extensions
        extend(ChartType.prototype, extensions);

        ChartType.extend = Chart.Type.extend;

        if(extensions.name || parent.prototype.name) {

            var chartName = extensions.name || parent.prototype.name;
            //Assign any potential default values of the new chart type

            //If none are defined, we'll use a clone of the chart type this is being extended from.
            //I.e. if we extend a line chart, we'll use the defaults from the line chart if our new chart
            //doesn't define some defaults of their own.

            var baseDefaults = (Chart.defaults[parent.prototype.name]) ? clone(Chart.defaults[parent.prototype.name]) : {};

            Chart.defaults[chartName] = extend(baseDefaults, extensions.defaults);

            Chart.types[chartName] = ChartType;

            //Register this new chart type in the Chart prototype
            Chart.prototype[chartName] = function(data, options) {
                var config = merge(Chart.defaults.global, Chart.defaults[chartName], options || {});
                return new ChartType(data, config, this);
            };
        } else {
            warn("Name not provided for this chart, so it hasn't been registered");
        }
        return parent;
    };

    Chart.Element = function(configuration) {
        extend(this, configuration);
        this.initialize.apply(this, arguments);
        this.save();
    };
    extend(Chart.Element.prototype, {
        initialize: function() {},
        restore: function(props) {
            if(!props) {
                extend(this, this._saved);
            } else {
                each(props, function(key) {
                    this[key] = this._saved[key];
                }, this);
            }
            return this;
        },
        save: function() {
            this._saved = clone(this);
            delete this._saved._saved;
            return this;
        },
        update: function(newProps) {
            each(newProps, function(value, key) {
                this._saved[key] = this[key];
                this[key] = value;
            }, this);
            return this;
        },
        transition: function(props, ease) {
            each(props, function(value, key) {
                this[key] = ((value - this._saved[key]) * ease) + this._saved[key];
            }, this);
            return this;
        },
        tooltipPosition: function() {
            return {
                x: this.x,
                y: this.y
            };
        },
        hasValue: function() {
            return isNumber(this.value);
        }
    });

    Chart.Element.extend = inherits;


    Chart.Point = Chart.Element.extend({
        display: true,
        inRange: function(chartX, chartY) {
            var hitDetectionRange = this.hitDetectionRadius + this.radius;
            return((Math.pow(chartX - this.x, 2) + Math.pow(chartY - this.y, 2)) < Math.pow(hitDetectionRange, 2));
        },
        draw: function() {
            if(this.display) {
                var ctx = this.ctx;
                ctx.beginPath();

                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.closePath();

                ctx.strokeStyle = this.strokeColor;
                ctx.lineWidth = this.strokeWidth;

                ctx.fillStyle = this.fillColor;

                ctx.fill();
                ctx.stroke();
            }


            //Quick debug for bezier curve splining
            //Highlights control points and the line between them.
            //Handy for dev - stripped in the min version.

            // ctx.save();
            // ctx.fillStyle = "black";
            // ctx.strokeStyle = "black"
            // ctx.beginPath();
            // ctx.arc(this.controlPoints.inner.x,this.controlPoints.inner.y, 2, 0, Math.PI*2);
            // ctx.fill();

            // ctx.beginPath();
            // ctx.arc(this.controlPoints.outer.x,this.controlPoints.outer.y, 2, 0, Math.PI*2);
            // ctx.fill();

            // ctx.moveTo(this.controlPoints.inner.x,this.controlPoints.inner.y);
            // ctx.lineTo(this.x, this.y);
            // ctx.lineTo(this.controlPoints.outer.x,this.controlPoints.outer.y);
            // ctx.stroke();

            // ctx.restore();



        }
    });

    Chart.Arc = Chart.Element.extend({
        inRange: function(chartX, chartY) {

            var pointRelativePosition = helpers.getAngleFromPoint(this, {
                x: chartX,
                y: chartY
            });

            //Check if within the range of the open/close angle
            var betweenAngles = (pointRelativePosition.angle >= this.startAngle && pointRelativePosition.angle <= this.endAngle),
                withinRadius = (pointRelativePosition.distance >= this.innerRadius && pointRelativePosition.distance <= this.outerRadius);

            return(betweenAngles && withinRadius);
            //Ensure within the outside of the arc centre, but inside arc outer
        },
        tooltipPosition: function() {
            var centreAngle = this.startAngle + ((this.endAngle - this.startAngle) / 2),
                rangeFromCentre = (this.outerRadius - this.innerRadius) / 2 + this.innerRadius;
            return {
                x: this.x + (Math.cos(centreAngle) * rangeFromCentre),
                y: this.y + (Math.sin(centreAngle) * rangeFromCentre)
            };
        },
        draw: function(animationPercent) {
            var easingDecimal = animationPercent || 1;

            var ctx = this.ctx;

            ctx.beginPath();

            ctx.arc(this.x, this.y, this.outerRadius, this.startAngle, this.endAngle);

            ctx.arc(this.x, this.y, this.innerRadius, this.endAngle, this.startAngle, true);

            ctx.closePath();

            ctx.strokeStyle = this.strokeColor;
            ctx.lineWidth = this.strokeWidth;

            ctx.fillStyle = this.fillColor;

            ctx.fill();
            ctx.lineJoin = 'bevel';

            if(this.showStroke) {
                ctx.stroke();
            }

            // ZUI change begin
            if (this.circleBeginEnd) {
                var pointCenterRadius = (this.outerRadius + this.innerRadius) / 2;
                var circleRadius = (this.outerRadius - this.innerRadius) / 2;

                ctx.beginPath();
                ctx.arc(this.x + Math.cos(this.startAngle) * pointCenterRadius, this.y + Math.sin(this.startAngle) * pointCenterRadius, circleRadius, 0, Math.PI * 2);
                ctx.closePath();

                ctx.fill();

                ctx.beginPath();
                ctx.arc(this.x + Math.cos(this.endAngle) * pointCenterRadius, this.y + Math.sin(this.endAngle) * pointCenterRadius, circleRadius, 0, Math.PI * 2);
                ctx.closePath();

                ctx.fill();
            }
            // ZUI change end
        }
    });

    Chart.Rectangle = Chart.Element.extend({
        draw: function() {
            var ctx = this.ctx,
                halfWidth = this.width / 2,
                leftX = this.x - halfWidth,
                rightX = this.x + halfWidth,
                top = this.base - (this.base - this.y),
                halfStroke = this.strokeWidth / 2;

            // Canvas doesn't allow us to stroke inside the width so we can
            // adjust the sizes to fit if we're setting a stroke on the line
            if(this.showStroke) {
                leftX += halfStroke;
                rightX -= halfStroke;
                top += halfStroke;
            }

            ctx.beginPath();

            ctx.fillStyle = this.fillColor;
            ctx.strokeStyle = this.strokeColor;
            ctx.lineWidth = this.strokeWidth;

            // It'd be nice to keep this class totally generic to any rectangle
            // and simply specify which border to miss out.
            ctx.moveTo(leftX, this.base);
            ctx.lineTo(leftX, top);
            ctx.lineTo(rightX, top);
            ctx.lineTo(rightX, this.base);
            ctx.fill();
            if(this.showStroke) {
                ctx.stroke();
            }
        },
        height: function() {
            return this.base - this.y;
        },
        inRange: function(chartX, chartY) {
            return(chartX >= this.x - this.width / 2 && chartX <= this.x + this.width / 2) && (chartY >= this.y && chartY <= this.base);
        }
    });

    Chart.Tooltip = Chart.Element.extend({
        draw: function() {

            var ctx = this.chart.ctx;

            ctx.font = fontString(this.fontSize, this.fontStyle, this.fontFamily);

            this.xAlign = "center";
            this.yAlign = "above";

            //Distance between the actual element.y position and the start of the tooltip caret
            var caretPadding = this.caretPadding = 2;

            var tooltipWidth = ctx.measureText(this.text).width + 2 * this.xPadding,
                tooltipRectHeight = this.fontSize + 2 * this.yPadding,
                tooltipHeight = tooltipRectHeight + this.caretHeight + caretPadding;

            if(this.x + tooltipWidth / 2 > this.chart.width) {
                this.xAlign = "left";
            } else if(this.x - tooltipWidth / 2 < 0) {
                this.xAlign = "right";
            }

            if(this.y - tooltipHeight < 0) {
                this.yAlign = "below";
            }


            var tooltipX = this.x - tooltipWidth / 2,
                tooltipY = this.y - tooltipHeight;

            ctx.fillStyle = this.fillColor;

            // Custom Tooltips
            if(this.custom) {
                this.custom(this);
            } else {
                switch(this.yAlign) {
                    case "above":
                        //Draw a caret above the x/y
                        ctx.beginPath();
                        ctx.moveTo(this.x, this.y - caretPadding);
                        ctx.lineTo(this.x + this.caretHeight, this.y - (caretPadding + this.caretHeight));
                        ctx.lineTo(this.x - this.caretHeight, this.y - (caretPadding + this.caretHeight));
                        ctx.closePath();
                        ctx.fill();
                        break;
                    case "below":
                        tooltipY = this.y + caretPadding + this.caretHeight;
                        //Draw a caret below the x/y
                        ctx.beginPath();
                        ctx.moveTo(this.x, this.y + caretPadding);
                        ctx.lineTo(this.x + this.caretHeight, this.y + caretPadding + this.caretHeight);
                        ctx.lineTo(this.x - this.caretHeight, this.y + caretPadding + this.caretHeight);
                        ctx.closePath();
                        ctx.fill();
                        break;
                }

                switch(this.xAlign) {
                    case "left":
                        tooltipX = this.x - tooltipWidth + (this.cornerRadius + this.caretHeight);
                        break;
                    case "right":
                        tooltipX = this.x - (this.cornerRadius + this.caretHeight);
                        break;
                }

                drawRoundedRectangle(ctx, tooltipX, tooltipY, tooltipWidth, tooltipRectHeight, this.cornerRadius);

                ctx.fill();

                ctx.fillStyle = this.textColor;
                ctx.textAlign = "center";
                ctx.textBaseline = "middle";
                ctx.fillText(this.text, tooltipX + tooltipWidth / 2, tooltipY + tooltipRectHeight / 2);
            }
        }
    });

    Chart.MultiTooltip = Chart.Element.extend({
        initialize: function() {
            this.font = fontString(this.fontSize, this.fontStyle, this.fontFamily);

            this.titleFont = fontString(this.titleFontSize, this.titleFontStyle, this.titleFontFamily);

            this.height = (this.labels.length * this.fontSize) + ((this.labels.length - 1) * (this.fontSize / 2)) + (this.yPadding * 2) + this.titleFontSize * 1.5;

            this.ctx.font = this.titleFont;

            var titleWidth = this.ctx.measureText(this.title).width,
                //Label has a legend square as well so account for this.
                labelWidth = longestText(this.ctx, this.font, this.labels) + this.fontSize + 3,
                longestTextWidth = max([labelWidth, titleWidth]);

            this.width = longestTextWidth + (this.xPadding * 2);


            var halfHeight = this.height / 2;

            //Check to ensure the height will fit on the canvas
            if(this.y - halfHeight < 0) {
                this.y = halfHeight;
            } else if(this.y + halfHeight > this.chart.height) {
                this.y = this.chart.height - halfHeight;
            }

            //Decide whether to align left or right based on position on canvas
            if(this.x > this.chart.width / 2) {
                this.x -= this.xOffset + this.width;
            } else {
                this.x += this.xOffset;
            }


        },
        getLineHeight: function(index) {
            var baseLineHeight = this.y - (this.height / 2) + this.yPadding,
                afterTitleIndex = index - 1;

            //If the index is zero, we're getting the title
            if(index === 0) {
                return baseLineHeight + this.titleFontSize / 2;
            } else {
                return baseLineHeight + ((this.fontSize * 1.5 * afterTitleIndex) + this.fontSize / 2) + this.titleFontSize * 1.5;
            }

        },
        draw: function() {
            // Custom Tooltips
            if(this.custom) {
                this.custom(this);
            } else {
                drawRoundedRectangle(this.ctx, this.x, this.y - this.height / 2, this.width, this.height, this.cornerRadius);
                var ctx = this.ctx;
                ctx.fillStyle = this.fillColor;
                ctx.fill();
                ctx.closePath();

                ctx.textAlign = "left";
                ctx.textBaseline = "middle";
                ctx.fillStyle = this.titleTextColor;
                ctx.font = this.titleFont;

                ctx.fillText(this.title, this.x + this.xPadding, this.getLineHeight(0));

                ctx.font = this.font;
                helpers.each(this.labels, function(label, index) {
                    ctx.fillStyle = this.textColor;
                    ctx.fillText(label, this.x + this.xPadding + this.fontSize + 3, this.getLineHeight(index + 1));

                    //A bit gnarly, but clearing this rectangle breaks when using explorercanvas (clears whole canvas)
                    //ctx.clearRect(this.x + this.xPadding, this.getLineHeight(index + 1) - this.fontSize/2, this.fontSize, this.fontSize);
                    //Instead we'll make a white filled block to put the legendColour palette over.

                    ctx.fillStyle = this.legendColorBackground;
                    ctx.fillRect(this.x + this.xPadding, this.getLineHeight(index + 1) - this.fontSize / 2, this.fontSize, this.fontSize);

                    ctx.fillStyle = this.legendColors[index].fill;
                    ctx.fillRect(this.x + this.xPadding, this.getLineHeight(index + 1) - this.fontSize / 2, this.fontSize, this.fontSize);


                }, this);
            }
        }
    });

    Chart.Scale = Chart.Element.extend({
        initialize: function() {
            this.fit();
        },
        buildYLabels: function() {
            this.yLabels = [];

            var stepDecimalPlaces = getDecimalPlaces(this.stepValue);

            for(var i = 0; i <= this.steps; i++) {
                this.yLabels.push(template(this.templateString, {
                    value: (this.min + (i * this.stepValue)).toFixed(stepDecimalPlaces)
                }));
            }
            this.yLabelWidth = (this.display && this.showLabels) ? longestText(this.ctx, this.font, this.yLabels) : 0;
        },
        addXLabel: function(label) {
            this.xLabels.push(label);
            this.valuesCount++;
            this.fit();
        },
        removeXLabel: function() {
            this.xLabels.shift();
            this.valuesCount--;
            this.fit();
        },
        // Fitting loop to rotate x Labels and figure out what fits there, and also calculate how many Y steps to use
        fit: function() {
            // First we need the width of the yLabels, assuming the xLabels aren't rotated

            // To do that we need the base line at the top and base of the chart, assuming there is no x label rotation
            this.startPoint = (this.display) ? this.fontSize : 0;
            this.endPoint = (this.display) ? this.height - (this.fontSize * 1.5) - 5 : this.height; // -5 to pad labels

            // Apply padding settings to the start and end point.
            this.startPoint += this.padding;
            this.endPoint -= this.padding;

            // Cache the starting height, so can determine if we need to recalculate the scale yAxis
            var cachedHeight = this.endPoint - this.startPoint,
                cachedYLabelWidth;

            // Build the current yLabels so we have an idea of what size they'll be to start
            /*
             *  This sets what is returned from calculateScaleRange as static properties of this class:
             *
              this.steps;
              this.stepValue;
              this.min;
              this.max;
             *
             */
            this.calculateYRange(cachedHeight);

            // With these properties set we can now build the array of yLabels
            // and also the width of the largest yLabel
            this.buildYLabels();

            this.calculateXLabelRotation();

            while((cachedHeight > this.endPoint - this.startPoint)) {
                cachedHeight = this.endPoint - this.startPoint;
                cachedYLabelWidth = this.yLabelWidth;

                this.calculateYRange(cachedHeight);
                this.buildYLabels();

                // Only go through the xLabel loop again if the yLabel width has changed
                if(cachedYLabelWidth < this.yLabelWidth) {
                    this.calculateXLabelRotation();
                }
            }

        },
        calculateXLabelRotation: function() {
            //Get the width of each grid by calculating the difference
            //between x offsets between 0 and 1.

            this.ctx.font = this.font;

            var firstWidth = this.ctx.measureText(this.xLabels[0]).width,
                lastWidth = this.ctx.measureText(this.xLabels[this.xLabels.length - 1]).width,
                firstRotated,
                lastRotated;


            this.xScalePaddingRight = lastWidth / 2 + 3;
            this.xScalePaddingLeft = (firstWidth / 2 > this.yLabelWidth + 10) ? firstWidth / 2 : this.yLabelWidth + 10;

            this.xLabelRotation = 0;
            if(this.display) {
                var originalLabelWidth = longestText(this.ctx, this.font, this.xLabels),
                    cosRotation,
                    firstRotatedWidth;
                this.xLabelWidth = originalLabelWidth;
                //Allow 3 pixels x2 padding either side for label readability
                var xGridWidth = Math.floor(this.calculateX(1) - this.calculateX(0)) - 6;

                //Max label rotate should be 90 - also act as a loop counter
                while((this.xLabelWidth > xGridWidth && this.xLabelRotation === 0) || (this.xLabelWidth > xGridWidth && this.xLabelRotation <= 90 && this.xLabelRotation > 0)) {
                    cosRotation = Math.cos(toRadians(this.xLabelRotation));

                    firstRotated = cosRotation * firstWidth;
                    lastRotated = cosRotation * lastWidth;

                    // We're right aligning the text now.
                    if(firstRotated + this.fontSize / 2 > this.yLabelWidth + 8) {
                        this.xScalePaddingLeft = firstRotated + this.fontSize / 2;
                    }
                    this.xScalePaddingRight = this.fontSize / 2;


                    this.xLabelRotation++;
                    this.xLabelWidth = cosRotation * originalLabelWidth;

                }
                if(this.xLabelRotation > 0) {
                    this.endPoint -= Math.sin(toRadians(this.xLabelRotation)) * originalLabelWidth + 3;
                }
            } else {
                this.xLabelWidth = 0;
                this.xScalePaddingRight = this.padding;
                this.xScalePaddingLeft = this.padding;
            }

        },
        // Needs to be overidden in each Chart type
        // Otherwise we need to pass all the data into the scale class
        calculateYRange: noop,
        drawingArea: function() {
            return this.startPoint - this.endPoint;
        },
        calculateY: function(value) {
            var scalingFactor = this.drawingArea() / (this.min - this.max);
            return this.endPoint - (scalingFactor * (value - this.min));
        },
        calculateX: function(index) {
            var isRotated = (this.xLabelRotation > 0),
                // innerWidth = (this.offsetGridLines) ? this.width - offsetLeft - this.padding : this.width - (offsetLeft + halfLabelWidth * 2) - this.padding,
                innerWidth = this.width - (this.xScalePaddingLeft + this.xScalePaddingRight),
                valueWidth = innerWidth / Math.max((this.valuesCount - ((this.offsetGridLines) ? 0 : 1)), 1),
                valueOffset = (valueWidth * index) + this.xScalePaddingLeft;

            if(this.offsetGridLines) {
                valueOffset += (valueWidth / 2);
            }

            return Math.round(valueOffset);
        },
        update: function(newProps) {
            helpers.extend(this, newProps);
            this.fit();
        },
        draw: function() {
            var ctx = this.ctx,
                yLabelGap = (this.endPoint - this.startPoint) / this.steps,
                xStart = Math.round(this.xScalePaddingLeft);
            if(this.display) {
                ctx.fillStyle = this.textColor;
                ctx.font = this.font;
                var beyondLineLength = this.showBeyondLine ? 5 : 0;
                each(this.yLabels, function(labelString, index) {
                    var yLabelCenter = this.endPoint - (yLabelGap * index),
                        linePositionY = Math.round(yLabelCenter),
                        drawHorizontalLine = this.showHorizontalLines;

                    ctx.textAlign = "right";
                    ctx.textBaseline = "middle";
                    if(this.showLabels) {
                        ctx.fillText(labelString, xStart - 10, yLabelCenter);
                    }

                    // This is X axis, so draw it
                    if(index === 0 && !drawHorizontalLine) {
                        drawHorizontalLine = true;
                    }

                    if(drawHorizontalLine) {
                        ctx.beginPath();
                    }

                    if(index > 0) {
                        // This is a grid line in the centre, so drop that
                        ctx.lineWidth = this.gridLineWidth;
                        ctx.strokeStyle = this.gridLineColor;
                    } else {
                        // This is the first line on the scale
                        ctx.lineWidth = this.lineWidth;
                        ctx.strokeStyle = this.lineColor;
                    }

                    linePositionY += helpers.aliasPixel(ctx.lineWidth);

                    if(drawHorizontalLine) {
                        ctx.moveTo(xStart, linePositionY);
                        ctx.lineTo(this.width, linePositionY);
                        ctx.stroke();
                        ctx.closePath();
                    }

                    ctx.lineWidth = this.lineWidth;
                    ctx.strokeStyle = this.lineColor;
                    ctx.beginPath();
                    ctx.moveTo(xStart - beyondLineLength, linePositionY);
                    ctx.lineTo(xStart, linePositionY);
                    ctx.stroke();
                    ctx.closePath();

                }, this);

                each(this.xLabels, function(label, index) {
                    var xPos = this.calculateX(index) + aliasPixel(this.lineWidth),
                        // Check to see if line/bar here and decide where to place the line
                        linePos = this.calculateX(index - (this.offsetGridLines ? 0.5 : 0)) + aliasPixel(this.lineWidth),
                        isRotated = (this.xLabelRotation > 0),
                        drawVerticalLine = this.showVerticalLines;

                    // This is Y axis, so draw it
                    if(index === 0 && !drawVerticalLine) {
                        drawVerticalLine = true;
                    }

                    if(drawVerticalLine) {
                        ctx.beginPath();
                    }

                    if(index > 0) {
                        // This is a grid line in the centre, so drop that
                        ctx.lineWidth = this.gridLineWidth;
                        ctx.strokeStyle = this.gridLineColor;
                    } else {
                        // This is the first line on the scale
                        ctx.lineWidth = this.lineWidth;
                        ctx.strokeStyle = this.lineColor;
                    }

                    if(drawVerticalLine) {
                        ctx.moveTo(linePos, this.endPoint);
                        ctx.lineTo(linePos, this.startPoint - 3);
                        ctx.stroke();
                        ctx.closePath();
                    }


                    ctx.lineWidth = this.lineWidth;
                    ctx.strokeStyle = this.lineColor;


                    // Small lines at the bottom of the base grid line
                    ctx.beginPath();
                    ctx.moveTo(linePos, this.endPoint);
                    ctx.lineTo(linePos, this.endPoint + beyondLineLength);
                    ctx.stroke();
                    ctx.closePath();

                    ctx.save();
                    ctx.translate(xPos, (isRotated) ? this.endPoint + 12 : this.endPoint + 8);
                    ctx.rotate(toRadians(this.xLabelRotation) * -1);
                    ctx.font = this.font;
                    ctx.textAlign = (isRotated) ? "right" : "center";
                    ctx.textBaseline = (isRotated) ? "middle" : "top";
                    ctx.fillText(label, 0, 0);
                    ctx.restore();
                }, this);

            }
        }

    });

    Chart.RadialScale = Chart.Element.extend({
        initialize: function() {
            this.size = min([this.height, this.width]);
            this.drawingArea = (this.display) ? (this.size / 2) - (this.fontSize / 2 + this.backdropPaddingY) : (this.size / 2);
        },
        calculateCenterOffset: function(value) {
            // Take into account half font size + the yPadding of the top value
            var scalingFactor = this.drawingArea / (this.max - this.min);

            return(value - this.min) * scalingFactor;
        },
        update: function() {
            if(!this.lineArc) {
                this.setScaleSize();
            } else {
                this.drawingArea = (this.display) ? (this.size / 2) - (this.fontSize / 2 + this.backdropPaddingY) : (this.size / 2);
            }
            this.buildYLabels();
        },
        buildYLabels: function() {
            this.yLabels = [];

            var stepDecimalPlaces = getDecimalPlaces(this.stepValue);

            for(var i = 0; i <= this.steps; i++) {
                this.yLabels.push(template(this.templateString, {
                    value: (this.min + (i * this.stepValue)).toFixed(stepDecimalPlaces)
                }));
            }
        },
        getCircumference: function() {
            return((Math.PI * 2) / this.valuesCount);
        },
        setScaleSize: function() {
            /*
             * Right, this is really confusing and there is a lot of maths going on here
             * The gist of the problem is here: https://gist.github.com/nnnick/696cc9c55f4b0beb8fe9
             *
             * Reaction: https://dl.dropboxusercontent.com/u/34601363/toomuchscience.gif
             *
             * Solution:
             *
             * We assume the radius of the polygon is half the size of the canvas at first
             * at each index we check if the text overlaps.
             *
             * Where it does, we store that angle and that index.
             *
             * After finding the largest index and angle we calculate how much we need to remove
             * from the shape radius to move the point inwards by that x.
             *
             * We average the left and right distances to get the maximum shape radius that can fit in the box
             * along with labels.
             *
             * Once we have that, we can find the centre point for the chart, by taking the x text protrusion
             * on each side, removing that from the size, halving it and adding the left x protrusion width.
             *
             * This will mean we have a shape fitted to the canvas, as large as it can be with the labels
             * and position it in the most space efficient manner
             *
             * https://dl.dropboxusercontent.com/u/34601363/yeahscience.gif
             */


            // Get maximum radius of the polygon. Either half the height (minus the text width) or half the width.
            // Use this to calculate the offset + change. - Make sure L/R protrusion is at least 0 to stop issues with centre points
            var largestPossibleRadius = min([(this.height / 2 - this.pointLabelFontSize - 5), this.width / 2]),
                pointPosition,
                i,
                textWidth,
                halfTextWidth,
                furthestRight = this.width,
                furthestRightIndex,
                furthestRightAngle,
                furthestLeft = 0,
                furthestLeftIndex,
                furthestLeftAngle,
                xProtrusionLeft,
                xProtrusionRight,
                radiusReductionRight,
                radiusReductionLeft,
                maxWidthRadius;
            this.ctx.font = fontString(this.pointLabelFontSize, this.pointLabelFontStyle, this.pointLabelFontFamily);
            for(i = 0; i < this.valuesCount; i++) {
                // 5px to space the text slightly out - similar to what we do in the draw function.
                pointPosition = this.getPointPosition(i, largestPossibleRadius);
                textWidth = this.ctx.measureText(template(this.templateString, {
                    value: this.labels[i]
                })).width + 5;
                if(i === 0 || i === this.valuesCount / 2) {
                    // If we're at index zero, or exactly the middle, we're at exactly the top/bottom
                    // of the radar chart, so text will be aligned centrally, so we'll half it and compare
                    // w/left and right text sizes
                    halfTextWidth = textWidth / 2;
                    if(pointPosition.x + halfTextWidth > furthestRight) {
                        furthestRight = pointPosition.x + halfTextWidth;
                        furthestRightIndex = i;
                    }
                    if(pointPosition.x - halfTextWidth < furthestLeft) {
                        furthestLeft = pointPosition.x - halfTextWidth;
                        furthestLeftIndex = i;
                    }
                } else if(i < this.valuesCount / 2) {
                    // Less than half the values means we'll left align the text
                    if(pointPosition.x + textWidth > furthestRight) {
                        furthestRight = pointPosition.x + textWidth;
                        furthestRightIndex = i;
                    }
                } else if(i > this.valuesCount / 2) {
                    // More than half the values means we'll right align the text
                    if(pointPosition.x - textWidth < furthestLeft) {
                        furthestLeft = pointPosition.x - textWidth;
                        furthestLeftIndex = i;
                    }
                }
            }

            xProtrusionLeft = furthestLeft;

            xProtrusionRight = Math.ceil(furthestRight - this.width);

            furthestRightAngle = this.getIndexAngle(furthestRightIndex);

            furthestLeftAngle = this.getIndexAngle(furthestLeftIndex);

            radiusReductionRight = xProtrusionRight / Math.sin(furthestRightAngle + Math.PI / 2);

            radiusReductionLeft = xProtrusionLeft / Math.sin(furthestLeftAngle + Math.PI / 2);

            // Ensure we actually need to reduce the size of the chart
            radiusReductionRight = (isNumber(radiusReductionRight)) ? radiusReductionRight : 0;
            radiusReductionLeft = (isNumber(radiusReductionLeft)) ? radiusReductionLeft : 0;

            this.drawingArea = largestPossibleRadius - (radiusReductionLeft + radiusReductionRight) / 2;

            //this.drawingArea = min([maxWidthRadius, (this.height - (2 * (this.pointLabelFontSize + 5)))/2])
            this.setCenterPoint(radiusReductionLeft, radiusReductionRight);

        },
        setCenterPoint: function(leftMovement, rightMovement) {

            var maxRight = this.width - rightMovement - this.drawingArea,
                maxLeft = leftMovement + this.drawingArea;

            this.xCenter = (maxLeft + maxRight) / 2;
            // Always vertically in the centre as the text height doesn't change
            this.yCenter = (this.height / 2);
        },

        getIndexAngle: function(index) {
            var angleMultiplier = (Math.PI * 2) / this.valuesCount;
            // Start from the top instead of right, so remove a quarter of the circle

            return index * angleMultiplier - (Math.PI / 2);
        },
        getPointPosition: function(index, distanceFromCenter) {
            var thisAngle = this.getIndexAngle(index);
            return {
                x: (Math.cos(thisAngle) * distanceFromCenter) + this.xCenter,
                y: (Math.sin(thisAngle) * distanceFromCenter) + this.yCenter
            };
        },
        draw: function() {
            if(this.display) {
                var ctx = this.ctx;
                each(this.yLabels, function(label, index) {
                    // Don't draw a centre value
                    if(index > 0) {
                        var yCenterOffset = index * (this.drawingArea / this.steps),
                            yHeight = this.yCenter - yCenterOffset,
                            pointPosition;

                        // Draw circular lines around the scale
                        if(this.lineWidth > 0) {
                            ctx.strokeStyle = this.lineColor;
                            ctx.lineWidth = this.lineWidth;

                            if(this.lineArc) {
                                ctx.beginPath();
                                ctx.arc(this.xCenter, this.yCenter, yCenterOffset, 0, Math.PI * 2);
                                ctx.closePath();
                                ctx.stroke();
                            } else {
                                ctx.beginPath();
                                for(var i = 0; i < this.valuesCount; i++) {
                                    pointPosition = this.getPointPosition(i, this.calculateCenterOffset(this.min + (index * this.stepValue)));
                                    if(i === 0) {
                                        ctx.moveTo(pointPosition.x, pointPosition.y);
                                    } else {
                                        ctx.lineTo(pointPosition.x, pointPosition.y);
                                    }
                                }
                                ctx.closePath();
                                ctx.stroke();
                            }
                        }
                        if(this.showLabels) {
                            ctx.font = fontString(this.fontSize, this.fontStyle, this.fontFamily);
                            if(this.showLabelBackdrop) {
                                var labelWidth = ctx.measureText(label).width;
                                ctx.fillStyle = this.backdropColor;
                                ctx.fillRect(
                                    this.xCenter - labelWidth / 2 - this.backdropPaddingX,
                                    yHeight - this.fontSize / 2 - this.backdropPaddingY,
                                    labelWidth + this.backdropPaddingX * 2,
                                    this.fontSize + this.backdropPaddingY * 2
                                );
                            }
                            ctx.textAlign = 'center';
                            ctx.textBaseline = "middle";
                            ctx.fillStyle = this.fontColor;
                            ctx.fillText(label, this.xCenter, yHeight);
                        }
                    }
                }, this);

                if(!this.lineArc) {
                    ctx.lineWidth = this.angleLineWidth;
                    ctx.strokeStyle = this.angleLineColor;
                    for(var i = this.valuesCount - 1; i >= 0; i--) {
                        if(this.angleLineWidth > 0) {
                            var outerPosition = this.getPointPosition(i, this.calculateCenterOffset(this.max));
                            ctx.beginPath();
                            ctx.moveTo(this.xCenter, this.yCenter);
                            ctx.lineTo(outerPosition.x, outerPosition.y);
                            ctx.stroke();
                            ctx.closePath();
                        }
                        // Extra 3px out for some label spacing
                        var pointLabelPosition = this.getPointPosition(i, this.calculateCenterOffset(this.max) + 5);
                        ctx.font = fontString(this.pointLabelFontSize, this.pointLabelFontStyle, this.pointLabelFontFamily);
                        ctx.fillStyle = this.pointLabelFontColor;

                        var labelsCount = this.labels.length,
                            halfLabelsCount = this.labels.length / 2,
                            quarterLabelsCount = halfLabelsCount / 2,
                            upperHalf = (i < quarterLabelsCount || i > labelsCount - quarterLabelsCount),
                            exactQuarter = (i === quarterLabelsCount || i === labelsCount - quarterLabelsCount);
                        if(i === 0) {
                            ctx.textAlign = 'center';
                        } else if(i === halfLabelsCount) {
                            ctx.textAlign = 'center';
                        } else if(i < halfLabelsCount) {
                            ctx.textAlign = 'left';
                        } else {
                            ctx.textAlign = 'right';
                        }

                        // Set the correct text baseline based on outer positioning
                        if(exactQuarter) {
                            ctx.textBaseline = 'middle';
                        } else if(upperHalf) {
                            ctx.textBaseline = 'bottom';
                        } else {
                            ctx.textBaseline = 'top';
                        }

                        ctx.fillText(this.labels[i], pointLabelPosition.x, pointLabelPosition.y);
                    }
                }
            }
        }
    });

    // Attach global event to resize each chart instance when the browser resizes
    helpers.addEvent(window, "resize", (function() {
        // Basic debounce of resize function so it doesn't hurt performance when resizing browser.
        var timeout;
        return function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                each(Chart.instances, function(instance) {
                    // If the responsive flag is set in the chart instance config
                    // Cascade the resize event down to the chart.
                    if(instance.options.responsive) {
                        instance.resize(instance.render, true);
                    }
                });
            }, 50);
        };
    })());


    if(amd) {
        define(function() {
            return Chart;
        });
    } else if(typeof module === 'object' && module.exports) {
        module.exports = Chart;
    }

    root.Chart = Chart;

    /// ----- ZUI change begin -----
    /// Use jquery object to create Chart object
    $.fn.chart = function() {
        var charts = [];
        this.each(function() {
            charts.push(new Chart(this.getContext("2d")));
        });
        return charts.length === 1 ? charts[0] : charts;
    }

    /// ----- ZUI change end -----

    /// ----- ZUI change begin -----
    /// Remove unused code
    //  Chart.noConflict = function() // old code begin
    //  {
    //      root.Chart = previous;
    //      return Chart;
    //  }; // old code end
    /// ----- ZUI change end -----

    /// ----- ZUI change begin -----
    /// Add jquery object to namespace

    /// }).call(this); // Old code
}).call(this, jQuery);

/// ----- ZUI change end -----


/* ========================================================================
 * Chart.js: Chart.line.js [Version: 1.0.2]
 * http://chartjs.org/
 *
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * official version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2015 Nick Downie, Released under the MIT license
 * https://github.com/nnnick/Chart.js/blob/master/LICENSE.md
 * ======================================================================== */


/// ----- ZUI change begin -----
/// Add jquery object to namespace

/// (function(){ // Old code
(function($) {

    /// ----- ZUI change end -----
    "use strict";

    /// ----- ZUI change begin -----
    /// Change root to zui shared object
    ///
    ///   var root = this, // old code
    var root = $ && $.zui ? $.zui : this,
        /// ----- ZUI change end -----
        Chart = root.Chart,
        helpers = Chart.helpers;

    var defaultConfig = {

        ///Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: true,

        //String - Colour of the grid lines
        scaleGridLineColor: "rgba(0,0,0,.05)",

        //Number - Width of the grid lines
        scaleGridLineWidth: 1,

        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,

        /// ZUI change end
        //Boolean - Whether to show beyond lines
        scaleShowBeyondLine: true,
        /// ZUI change end
        ///
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,

        //Boolean - Whether the line is curved between points
        bezierCurve: true,

        //Number - Tension of the bezier curve between points
        bezierCurveTension: 0.4,

        //Boolean - Whether to show a dot for each point
        pointDot: true,

        //Number - Radius of each point dot in pixels
        pointDotRadius: 4,

        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth: 1,

        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius: 20,

        //Boolean - Whether to show a stroke for datasets
        datasetStroke: true,

        //Number - Pixel width of dataset stroke
        datasetStrokeWidth: 2,

        //Boolean - Whether to fill the dataset with a colour
        datasetFill: true,

        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

    };


    Chart.Type.extend({
        name: "Line",
        defaults: defaultConfig,

        initialize: function(data) {
            //Declare the extension of the default point, to cater for the options passed in to the constructor
            this.PointClass = Chart.Point.extend({
                strokeWidth: this.options.pointDotStrokeWidth,
                radius: this.options.pointDotRadius,
                display: this.options.pointDot,
                hitDetectionRadius: this.options.pointHitDetectionRadius,
                ctx: this.chart.ctx,
                inRange: function(mouseX) {
                    return(Math.pow(mouseX - this.x, 2) < Math.pow(this.radius + this.hitDetectionRadius, 2));
                }
            });

            this.datasets = [];

            //Set up tooltip events on the chart
            if(this.options.showTooltips) {
                helpers.bindEvents(this, this.options.tooltipEvents, function(evt) {
                    var activePoints = (evt.type !== 'mouseout') ? this.getPointsAtEvent(evt) : [];
                    this.eachPoints(function(point) {
                        point.restore(['fillColor', 'strokeColor']);
                    });
                    helpers.each(activePoints, function(activePoint) {
                        activePoint.fillColor = activePoint.highlightFill;
                        activePoint.strokeColor = activePoint.highlightStroke;
                    });
                    this.showTooltip(activePoints);
                });
            }

            //Iterate through each of the datasets, and build this into a property of the chart
            helpers.each(data.datasets, function(dataset) {
                /// ----- ZUI change begin -----
                // add color theme
                if($.zui && $.zui.Color && $.zui.Color.get) {
                    var accentColor = $.zui.Color.get(dataset.color);
                    var accentColorValue = accentColor.toCssStr();

                    if(!dataset.fillColor) dataset.fillColor = accentColor.clone().fade(20).toCssStr();
                    if(!dataset.strokeColor) dataset.strokeColor = accentColorValue;
                    if(!dataset.pointColor) dataset.pointColor = accentColorValue;
                    if(!dataset.pointStrokeColor) dataset.pointStrokeColor = '#fff';
                    if(!dataset.pointHighlightFill) dataset.pointHighlightFill = '#fff';
                    if(!dataset.pointHighlightStroke) dataset.pointHighlightStroke = accentColorValue;
                }
                /// ----- ZUI change begin -----

                var datasetObject = {
                    label: dataset.label || null,
                    fillColor: dataset.fillColor,
                    strokeColor: dataset.strokeColor,
                    pointColor: dataset.pointColor,
                    pointStrokeColor: dataset.pointStrokeColor,
                    /// ZUI change begin
                    showTooltips: dataset.showTooltips !== false,
                    /// ZUI change end
                    points: []
                };

                this.datasets.push(datasetObject);


                helpers.each(dataset.data, function(dataPoint, index) {
                    //Add a new point for each piece of data, passing any required data to draw.
                    datasetObject.points.push(new this.PointClass({
                        value: dataPoint,
                        label: data.labels[index],
                        datasetLabel: dataset.label,
                        strokeColor: dataset.pointStrokeColor,
                        fillColor: dataset.pointColor,
                        highlightFill: dataset.pointHighlightFill || dataset.pointColor,
                        highlightStroke: dataset.pointHighlightStroke || dataset.pointStrokeColor
                    }));
                }, this);

                this.buildScale(data.labels);


                this.eachPoints(function(point, index) {
                    helpers.extend(point, {
                        x: this.scale.calculateX(index),
                        y: this.scale.endPoint
                    });
                    point.save();
                }, this);

            }, this);


            this.render();
        },
        update: function() {
            this.scale.update();
            // Reset any highlight colours before updating.
            helpers.each(this.activeElements, function(activeElement) {
                activeElement.restore(['fillColor', 'strokeColor']);
            });
            this.eachPoints(function(point) {
                point.save();
            });
            this.render();
        },
        eachPoints: function(callback) {
            helpers.each(this.datasets, function(dataset) {
                helpers.each(dataset.points, callback, this);
            }, this);
        },
        getPointsAtEvent: function(e) {
            var pointsArray = [],
                eventPosition = helpers.getRelativePosition(e);
            helpers.each(this.datasets, function(dataset) {
                helpers.each(dataset.points, function(point) {
                    if(point.inRange(eventPosition.x, eventPosition.y)) pointsArray.push(point);
                });
            }, this);
            return pointsArray;
        },
        buildScale: function(labels) {
            var self = this;

            var dataTotal = function() {
                var values = [];
                self.eachPoints(function(point) {
                    values.push(point.value);
                });

                return values;
            };

            var scaleOptions = {
                templateString: this.options.scaleLabel,
                height: this.chart.height,
                width: this.chart.width,
                ctx: this.chart.ctx,
                textColor: this.options.scaleFontColor,
                fontSize: this.options.scaleFontSize,
                fontStyle: this.options.scaleFontStyle,
                fontFamily: this.options.scaleFontFamily,
                valuesCount: labels.length,
                beginAtZero: this.options.scaleBeginAtZero,
                integersOnly: this.options.scaleIntegersOnly,
                calculateYRange: function(currentHeight) {
                    var updatedRanges = helpers.calculateScaleRange(
                        dataTotal(),
                        currentHeight,
                        this.fontSize,
                        this.beginAtZero,
                        this.integersOnly
                    );
                    helpers.extend(this, updatedRanges);
                },
                xLabels: labels,
                font: helpers.fontString(this.options.scaleFontSize, this.options.scaleFontStyle, this.options.scaleFontFamily),
                lineWidth: this.options.scaleLineWidth,
                lineColor: this.options.scaleLineColor,
                showHorizontalLines: this.options.scaleShowHorizontalLines,
                showVerticalLines: this.options.scaleShowVerticalLines,
                /// ZUI change begin
                showBeyondLine: this.options.scaleShowBeyondLine,
                /// ZUI change end
                gridLineWidth: (this.options.scaleShowGridLines) ? this.options.scaleGridLineWidth : 0,
                gridLineColor: (this.options.scaleShowGridLines) ? this.options.scaleGridLineColor : "rgba(0,0,0,0)",
                padding: (this.options.showScale) ? 0 : this.options.pointDotRadius + this.options.pointDotStrokeWidth,
                showLabels: this.options.scaleShowLabels,
                display: this.options.showScale
            };

            if(this.options.scaleOverride) {
                helpers.extend(scaleOptions, {
                    calculateYRange: helpers.noop,
                    steps: this.options.scaleSteps,
                    stepValue: this.options.scaleStepWidth,
                    min: this.options.scaleStartValue,
                    max: this.options.scaleStartValue + (this.options.scaleSteps * this.options.scaleStepWidth)
                });
            }


            this.scale = new Chart.Scale(scaleOptions);
        },
        addData: function(valuesArray, label) {
			//Map the values array for each of the datasets

			helpers.each(valuesArray,function(value,datasetIndex){
				//Add a new point for each piece of data, passing any required data to draw.
				this.datasets[datasetIndex].points.push(new this.PointClass({
					value : value,
					label : label,
					datasetLabel: this.datasets[datasetIndex].label,
					x: this.scale.calculateX(this.scale.valuesCount+1),
					y: this.scale.endPoint,
					strokeColor : this.datasets[datasetIndex].pointStrokeColor,
					fillColor : this.datasets[datasetIndex].pointColor
				}));
			},this);

			this.scale.addXLabel(label);
			//Then re-render the chart.
			this.update();
        },
        removeData: function() {
            this.scale.removeXLabel();
            //Then re-render the chart.
            helpers.each(this.datasets, function(dataset) {
                dataset.points.shift();
            }, this);
            this.update();
        },
        reflow: function() {
            var newScaleProps = helpers.extend({
                height: this.chart.height,
                width: this.chart.width
            });
            this.scale.update(newScaleProps);
        },
        draw: function(ease) {
            var easingDecimal = ease || 1;
            this.clear();

            var ctx = this.chart.ctx;

            // Some helper methods for getting the next/prev points
            var hasValue = function(item) {
                    return item.value !== null;
                },
                nextPoint = function(point, collection, index) {
                    return helpers.findNextWhere(collection, hasValue, index) || point;
                },
                previousPoint = function(point, collection, index) {
                    return helpers.findPreviousWhere(collection, hasValue, index) || point;
                };

            this.scale.draw(easingDecimal);


            helpers.each(this.datasets, function(dataset) {
                var pointsWithValues = helpers.where(dataset.points, hasValue);

                //Transition each point first so that the line and point drawing isn't out of sync
                //We can use this extra loop to calculate the control points of this dataset also in this loop

                helpers.each(dataset.points, function(point, index) {
                    if(point.hasValue()) {
                        point.transition({
                            y: this.scale.calculateY(point.value),
                            x: this.scale.calculateX(index)
                        }, easingDecimal);
                    }
                }, this);


                // Control points need to be calculated in a seperate loop, because we need to know the current x/y of the point
                // This would cause issues when there is no animation, because the y of the next point would be 0, so beziers would be skewed
                if(this.options.bezierCurve) {
                    helpers.each(pointsWithValues, function(point, index) {
                        var tension = (index > 0 && index < pointsWithValues.length - 1) ? this.options.bezierCurveTension : 0;
                        point.controlPoints = helpers.splineCurve(
                            previousPoint(point, pointsWithValues, index),
                            point,
                            nextPoint(point, pointsWithValues, index),
                            tension
                        );

                        // Prevent the bezier going outside of the bounds of the graph

                        // Cap puter bezier handles to the upper/lower scale bounds
                        if(point.controlPoints.outer.y > this.scale.endPoint) {
                            point.controlPoints.outer.y = this.scale.endPoint;
                        } else if(point.controlPoints.outer.y < this.scale.startPoint) {
                            point.controlPoints.outer.y = this.scale.startPoint;
                        }

                        // Cap inner bezier handles to the upper/lower scale bounds
                        if(point.controlPoints.inner.y > this.scale.endPoint) {
                            point.controlPoints.inner.y = this.scale.endPoint;
                        } else if(point.controlPoints.inner.y < this.scale.startPoint) {
                            point.controlPoints.inner.y = this.scale.startPoint;
                        }
                    }, this);
                }


                //Draw the line between all the points
                ctx.lineWidth = this.options.datasetStrokeWidth;
                ctx.strokeStyle = dataset.strokeColor;
                ctx.beginPath();

                helpers.each(pointsWithValues, function(point, index) {
                    if(index === 0) {
                        ctx.moveTo(point.x, point.y);
                    } else {
                        if(this.options.bezierCurve) {
                            var previous = previousPoint(point, pointsWithValues, index);

                            ctx.bezierCurveTo(
                                previous.controlPoints.outer.x,
                                previous.controlPoints.outer.y,
                                point.controlPoints.inner.x,
                                point.controlPoints.inner.y,
                                point.x,
                                point.y
                            );
                        } else {
                            ctx.lineTo(point.x, point.y);
                        }
                    }
                }, this);

                ctx.stroke();

                if(this.options.datasetFill && pointsWithValues.length > 0) {
                    //Round off the line by going to the base of the chart, back to the start, then fill.
                    ctx.lineTo(pointsWithValues[pointsWithValues.length - 1].x, this.scale.endPoint);
                    ctx.lineTo(pointsWithValues[0].x, this.scale.endPoint);
                    ctx.fillStyle = dataset.fillColor;
                    ctx.closePath();
                    ctx.fill();
                }

                //Now draw the points over the line
                //A little inefficient double looping, but better than the line
                //lagging behind the point positions
                helpers.each(pointsWithValues, function(point) {
                    point.draw();
                });
            }, this);
        }
    });

    /// ----- ZUI change begin -----
    /// Use jquery object to create Chart object
    $.fn.lineChart = function(data, options) {
        var lineCharts = [];
        this.each(function() {
            var $this = $(this);
            lineCharts.push(new Chart(this.getContext("2d")).Line(data, $.extend($this.data(), options)));
        });
        return lineCharts.length === 1 ? lineCharts[0] : lineCharts;
    }

    /// ----- ZUI change end -----

    /// ----- ZUI change begin -----
    /// Add jquery object to namespace

    /// }).call(this); // Old code
}).call(this, jQuery);

/// ----- ZUI change end -----


/* ========================================================================
 * Chart.js: Chart.Doughnut.js [Version: 1.0.2]
 * http://chartjs.org/
 *
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * official version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2015 Nick Downie, Released under the MIT license
 * https://github.com/nnnick/Chart.js/blob/master/LICENSE.md
 * ======================================================================== */

/// ----- ZUI change begin -----
/// Add jquery object to namespace

/// (function(){ // Old code
(function($) {

    /// ----- ZUI change end -----
    "use strict";

    /// ----- ZUI change begin -----
    /// Change root to zui shared object
    ///
    ///   var root = this, // old code
    var root = $ && $.zui ? $.zui : this,
        /// ----- ZUI change end -----
        Chart = root.Chart,
        //Cache a local reference to Chart.helpers
        helpers = Chart.helpers;

    var defaultConfig = {
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,

        //String - The colour of each segment stroke
        segmentStrokeColor: "#fff",

        //Number - The width of each segment stroke
        /// ZUI change begin
        ///        segmentStrokeWidth: 2, // old code
        segmentStrokeWidth: 1,
        /// ZUI change end

        //The percentage of the chart that we cut out of the middle.
        percentageInnerCutout: 50,

        /// ZUI change begin
        // Boolean - Whether to show labels on the scale
        scaleShowLabels: false,

        // Interpolated JS string - can access value
        scaleLabel: "<%=value%>",

        // String - Scale label position
        scaleLabelPlacement: 'auto',

        ///        Number - Amount of animation steps // old code
        ///        animationSteps: 100, // old code
        animationSteps: 60,
        /// ZUI change end

        //String - Animation easing effect
        animationEasing: "easeOutBounce",

        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate: true,

        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale: false,

        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"

    };


    Chart.Type.extend({
        //Passing in a name registers this chart in the Chart namespace
        name: "Doughnut",
        //Providing a defaults will also register the deafults in the chart namespace
        defaults: defaultConfig,
        //Initialize is fired when the chart is initialized - Data is passed in as a parameter
        //Config is automatically merged by the core of Chart.js, and is available at this.options
        initialize: function(data) {

            //Declare segments as a static property to prevent inheriting across the Chart type prototype
            this.segments = [];
            this.outerRadius = (helpers.min([this.chart.width, this.chart.height]) - this.options.segmentStrokeWidth / 2) / 2;

            this.SegmentArc = Chart.Arc.extend({
                ctx: this.chart.ctx,
                x: this.chart.width / 2,
                y: this.chart.height / 2
            });

            //Set up tooltip events on the chart
            if(this.options.showTooltips) {
                helpers.bindEvents(this, this.options.tooltipEvents, function(evt) {
                    var activeSegments = (evt.type !== 'mouseout') ? this.getSegmentsAtEvent(evt) : [];

                    helpers.each(this.segments, function(segment) {
                        segment.restore(["fillColor"]);
                    });
                    helpers.each(activeSegments, function(activeSegment) {
                        activeSegment.fillColor = activeSegment.highlightColor;
                    });
                    this.showTooltip(activeSegments);
                });
            }
            this.calculateTotal(data);

            helpers.each(data, function(datapoint, index) {
                this.addData(datapoint, index, true);
            }, this);

            this.render();
        },
        getSegmentsAtEvent: function(e) {
            var segmentsArray = [];

            var location = helpers.getRelativePosition(e);

            helpers.each(this.segments, function(segment) {
                if(segment.inRange(location.x, location.y)) segmentsArray.push(segment);
            }, this);
            return segmentsArray;
        },
        addData: function(segment, atIndex, silent) {
            /// ----- ZUI change begin -----
            /// Init segment color
            if($.zui && $.zui.Color && $.zui.Color.get) {
                var color = new $.zui.Color.get(segment.color);
                segment.color = color.toCssStr();
                if(!segment.highlight) segment.highlight = color.lighten(5).toCssStr();
            }
            /// ----- ZUI change end -----
            var index = atIndex || this.segments.length;
            this.segments.splice(index, 0, new this.SegmentArc({
                id: typeof segment.id === 'undefined' ? index : segment.id,
                value: segment.value,
                outerRadius: (this.options.animateScale) ? 0 : this.outerRadius,
                innerRadius: (this.options.animateScale) ? 0 : (this.outerRadius / 100) * this.options.percentageInnerCutout,
                fillColor: segment.color,
                highlightColor: segment.highlight || segment.color,
                showStroke: this.options.segmentShowStroke,
                strokeWidth: this.options.segmentStrokeWidth,
                strokeColor: this.options.segmentStrokeColor,
                startAngle: Math.PI * 1.5,
                circumference: (this.options.animateRotate) ? 0 : this.calculateCircumference(segment.value),
                /// ----- ZUI change begin -----
                showLabel: segment.showLabel !== false,
                circleBeginEnd: segment.circleBeginEnd,
                /// ----- ZUI change end -----
                label: segment.label
            }));
            if(!silent) {
                this.reflow();
                this.update();
            }
        },
        calculateCircumference: function(value) {
            return(Math.PI * 2) * (Math.abs(value) / this.total);
        },
        calculateTotal: function(data) {
            this.total = 0;
            helpers.each(data, function(segment) {
                this.total += Math.abs(segment.value);
            }, this);
        },
        update: function() {
            this.calculateTotal(this.segments);

            // Reset any highlight colours before updating.
            helpers.each(this.activeElements, function(activeElement) {
                activeElement.restore(['fillColor']);
            });

            helpers.each(this.segments, function(segment) {
                segment.save();
            });
            this.render();
        },

        removeData: function(atIndex) {
            var indexToDelete = (helpers.isNumber(atIndex)) ? atIndex : this.segments.length - 1;
            this.segments.splice(indexToDelete, 1);
            this.reflow();
            this.update();
        },

        reflow: function() {
            helpers.extend(this.SegmentArc.prototype, {
                x: this.chart.width / 2,
                y: this.chart.height / 2
            });
            this.outerRadius = (helpers.min([this.chart.width, this.chart.height]) - this.options.segmentStrokeWidth / 2) / 2;
            helpers.each(this.segments, function(segment) {
                segment.update({
                    outerRadius: this.outerRadius,
                    innerRadius: (this.outerRadius / 100) * this.options.percentageInnerCutout
                });
            }, this);
        },
        /// ZUI change begin
        drawLabel: function(segment, easeDecimal, labelPosMap) {
            var options = this.options;
            var middleAngle = (segment.endAngle + segment.startAngle) / 2;
            var placement = options.scaleLabelPlacement;
            if(placement !== 'inside' && placement !== 'outside') {
                if((this.chart.width - this.chart.height) > 50) {
                    if(segment.circumference < (Math.PI / 18)) {
                        placement = 'outside';
                    }
                }
            }

            var x = Math.cos(middleAngle) * segment.outerRadius,
                y = Math.sin(middleAngle) * segment.outerRadius,
                text = helpers.template(options.scaleLabel, {
                    value: typeof easeDecimal === 'undefined' ? segment.value : Math.round(easeDecimal * segment.value),
                    label: segment.label
                });

            var ctx = this.chart.ctx;
            ctx.font = helpers.fontString(options.scaleFontSize, options.scaleFontStyle, options.scaleFontFamily);
            ctx.textBaseline = "middle";
            ctx.textAlign = "center";

            var textWidth = ctx.measureText(text).width;
            var chartWidthHalf = this.chart.width / 2;
            var chartHeightHalf = this.chart.height / 2;

            if(placement === 'outside') { // outside
                var isRight = x >= 0;
                var lineX = x + chartWidthHalf;
                var lineY = y + chartHeightHalf;
                ctx.textAlign = isRight ? 'left' : 'right';
                ctx.measureText(text).width;
                if(isRight) {
                    x = Math.max(chartWidthHalf + segment.outerRadius + 10, x + 30 + chartWidthHalf);
                } else {
                    x = Math.min(chartWidthHalf - segment.outerRadius - 10, x - 30 + chartWidthHalf);
                }

                var textHeight = options.scaleFontSize * (options.scaleLineHeight || 1);
                var labelPos = Math.round((y * 0.8 + chartHeightHalf) / textHeight) + 1;
                var maxPos = Math.floor(this.chart.width / textHeight) + 1;
                var labelPosDirection = isRight ? 1 : (-1);
                if(labelPosMap[labelPos*labelPosDirection]) {
                    if(labelPos > 1) labelPos--;
                    else labelPos++;
                }
                // while(labelPosMap[labelPos*labelPosDirection] && labelPos < maxPos) labelPos++;

                if(labelPosMap[labelPos*labelPosDirection]) return;
                y = (labelPos - 1) * textHeight + options.scaleFontSize / 2;
                labelPosMap[labelPos*labelPosDirection] = true;

                ctx.beginPath();
                ctx.moveTo(lineX, lineY);
                ctx.lineTo(x, y);
                x = isRight ? (x + 5) : (x - 5);
                ctx.lineTo(x, y);
                ctx.strokeStyle = ($.zui && $.zui.Color) ? (new $.zui.Color(segment.fillColor).fade(40).toCssStr()) : segment.fillColor;
                ctx.strokeWidth = options.scaleLineWidth;
                ctx.stroke();
                ctx.fillStyle = segment.fillColor;
            } else { // inside
                x = x * 0.7 + chartWidthHalf;
                y = y * 0.7 + chartHeightHalf;
                ctx.fillStyle = ($.zui && $.zui.Color) ? (new $.zui.Color(segment.fillColor).contrast().toCssStr()) : '#fff';
            }
            ctx.fillText(text, x, y);
        },
        // ZUI change end
        draw: function(easeDecimal) {
            var animDecimal = (easeDecimal) ? easeDecimal : 1;
            this.clear();
            // ZUI change begin
            var labelPositionMap;
            // ZUI change end
            helpers.each(this.segments, function(segment, index) {
                segment.transition({
                    circumference: this.calculateCircumference(segment.value),
                    outerRadius: this.outerRadius,
                    innerRadius: (this.outerRadius / 100) * this.options.percentageInnerCutout
                }, animDecimal);

                segment.endAngle = segment.startAngle + segment.circumference;

                // ZUI change begin
                if (!this.options.reverseDrawOrder) {
                    // ZUI change end
                    // ZUI change begin
                    segment.draw();
                    // ZUI change end
                }
                // ZUI change end

                if(index === 0) {
                    segment.startAngle = Math.PI * 1.5;
                }
                //Check to see if it's the last segment, if not get the next and update the start angle
                if(index < this.segments.length - 1) {
                    this.segments[index + 1].startAngle = segment.endAngle;
                }
            }, this);

            // ZUI change begin
            if (this.options.reverseDrawOrder) {
                helpers.each(this.segments.slice().reverse(), function(segment, index) {
                    segment.draw();
                }, this);
            }
            /// ZUI change end

            /// ZUI change begin
            if(this.options.scaleShowLabels) {
                var segmentsArray = this.segments.slice().sort(function(a,b){return b.value - a.value;});
                var labelPositionMap = {};
                helpers.each(segmentsArray, function(segment, index) {
                    if(segment.showLabel) this.drawLabel(segment, easeDecimal, labelPositionMap);
                }, this);
            }
            /// ZUI change end
        }
    });

    Chart.types.Doughnut.extend({
        name: "Pie",
        defaults: helpers.merge(defaultConfig, {
            percentageInnerCutout: 0
        })
    });

    /// ----- ZUI change begin -----
    /// Use jquery object to create Chart object
    $.fn.pieChart = function(data, options) {
        var pieCharts = [];
        this.each(function() {
            var $this = $(this);
            pieCharts.push(new Chart(this.getContext("2d")).Pie(data, $.extend($this.data(), options)));
        });
        return pieCharts.length === 1 ? pieCharts[0] : pieCharts;
    }

    $.fn.doughnutChart = function(data, options) {
        var doughnutCharts = [];
        this.each(function() {
            var $this = $(this);
            doughnutCharts.push(new Chart(this.getContext("2d")).Doughnut(data, $.extend($this.data(), options)));
        });
        return doughnutCharts.length === 1 ? doughnutCharts[0] : doughnutCharts;
    }

    /// ----- ZUI change end -----

    /// ----- ZUI change begin -----
    /// Add jquery object to namespace

    /// }).call(this); // Old code
}).call(this, jQuery);

/// ----- ZUI change end -----


/* ========================================================================
 * Chart.js: Chart.Bar.js [Version: 1.0.2]
 * http://chartjs.org/
 * 
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * official version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2015 Nick Downie, Released under the MIT license
 * https://github.com/nnnick/Chart.js/blob/master/LICENSE.md
 * ======================================================================== */


/// ----- ZUI change begin -----
/// Add jquery object to namespace

/// (function(){ // Old code
(function($) {

    /// ----- ZUI change end -----
    "use strict";

    /// ----- ZUI change begin -----
    /// Change root to zui shared object
    ///
    ///   var root = this, // old code
    var root = $ && $.zui ? $.zui : this,
        /// ----- ZUI change end -----
        Chart = root.Chart,
        helpers = Chart.helpers;


    var defaultConfig = {
        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
        scaleBeginAtZero: true,

        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: true,

        //String - Colour of the grid lines
        scaleGridLineColor: "rgba(0,0,0,.05)",

        //Number - Width of the grid lines
        scaleGridLineWidth: 1,

        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,

        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,

        /// ZUI change begin
        //Boolean - Whether to show beyond lines
        scaleShowBeyondLine: true,
        /// ZUI change end
        /// 
        //Boolean - If there is a stroke on each bar
        barShowStroke: true,

        //Number - Pixel width of the bar stroke
        /// ZUI change begin
        ///        barStrokeWidth: 2,
        barStrokeWidth: 1,

        // String - Sacle value labels placement
        scaleValuePlacement: 'auto', // none, auto, outside, inside-top, inside-middle, inside-bottom
        /// ZUI change end

        //Number - Spacing between each of the X value sets
        barValueSpacing: 5,

        //Number - Spacing between data sets within X values
        barDatasetSpacing: 1,

        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
    };


    Chart.Type.extend({
        name: "Bar",
        defaults: defaultConfig,
        initialize: function(data) {

            //Expose options as a scope variable here so we can access it in the ScaleClass
            var options = this.options;

            this.ScaleClass = Chart.Scale.extend({
                offsetGridLines: true,
                calculateBarX: function(datasetCount, datasetIndex, barIndex) {
                    //Reusable method for calculating the xPosition of a given bar based on datasetIndex & width of the bar
                    var xWidth = this.calculateBaseWidth(),
                        xAbsolute = this.calculateX(barIndex) - (xWidth / 2),
                        barWidth = this.calculateBarWidth(datasetCount);

                    return xAbsolute + (barWidth * datasetIndex) + (datasetIndex * options.barDatasetSpacing) + barWidth / 2;
                },
                calculateBaseWidth: function() {
                    return(this.calculateX(1) - this.calculateX(0)) - (2 * options.barValueSpacing);
                },
                calculateBarWidth: function(datasetCount) {
                    //The padding between datasets is to the right of each bar, providing that there are more than 1 dataset
                    var baseWidth = this.calculateBaseWidth() - ((datasetCount - 1) * options.barDatasetSpacing);

                    return(baseWidth / datasetCount);
                }
            });

            this.datasets = [];

            //Set up tooltip events on the chart
            if(this.options.showTooltips) {
                helpers.bindEvents(this, this.options.tooltipEvents, function(evt) {
                    var activeBars = (evt.type !== 'mouseout') ? this.getBarsAtEvent(evt) : [];

                    this.eachBars(function(bar) {
                        bar.restore(['fillColor', 'strokeColor']);
                    });
                    helpers.each(activeBars, function(activeBar) {
                        activeBar.fillColor = activeBar.highlightFill;
                        activeBar.strokeColor = activeBar.highlightStroke;
                    });
                    this.showTooltip(activeBars);
                });
            }

            //Declare the extension of the default point, to cater for the options passed in to the constructor
            this.BarClass = Chart.Rectangle.extend({
                strokeWidth: this.options.barStrokeWidth,
                showStroke: this.options.barShowStroke,
                ctx: this.chart.ctx
            });

            //Iterate through each of the datasets, and build this into a property of the chart
            helpers.each(data.datasets, function(dataset, datasetIndex) {
                /// ----- ZUI change begin -----
                // add color theme
                if($.zui && $.zui.Color && $.zui.Color.get) {
                    var accentColor = $.zui.Color.get(dataset.color);
                    var accentColorValue = accentColor.toCssStr();

                    if(!dataset.fillColor) dataset.fillColor = accentColor.clone().fade(50).toCssStr();
                    if(!dataset.strokeColor) dataset.strokeColor = accentColorValue;
                }
                /// ----- ZUI change begin -----

                var datasetObject = {
                    label: dataset.label || null,
                    fillColor: dataset.fillColor,
                    strokeColor: dataset.strokeColor,
                    bars: []
                };

                this.datasets.push(datasetObject);

                helpers.each(dataset.data, function(dataPoint, index) {
                    //Add a new point for each piece of data, passing any required data to draw.
                    datasetObject.bars.push(new this.BarClass({
                        value: dataPoint,
                        label: data.labels[index],
                        datasetLabel: dataset.label,
                        strokeColor: dataset.strokeColor,
                        fillColor: dataset.fillColor,
                        highlightFill: dataset.highlightFill || dataset.fillColor,
                        highlightStroke: dataset.highlightStroke || dataset.strokeColor
                    }));
                }, this);

            }, this);

            this.buildScale(data.labels);

            this.BarClass.prototype.base = this.scale.endPoint;

            this.eachBars(function(bar, index, datasetIndex) {
                helpers.extend(bar, {
                    width: this.scale.calculateBarWidth(this.datasets.length),
                    x: this.scale.calculateBarX(this.datasets.length, datasetIndex, index),
                    y: this.scale.endPoint
                });
                bar.save();
            }, this);

            this.render();
        },
        update: function() {
            this.scale.update();
            // Reset any highlight colours before updating.
            helpers.each(this.activeElements, function(activeElement) {
                activeElement.restore(['fillColor', 'strokeColor']);
            });

            this.eachBars(function(bar) {
                bar.save();
            });
            this.render();
        },
        eachBars: function(callback) {
            helpers.each(this.datasets, function(dataset, datasetIndex) {
                helpers.each(dataset.bars, callback, this, datasetIndex);
            }, this);
        },
        getBarsAtEvent: function(e) {
            var barsArray = [],
                eventPosition = helpers.getRelativePosition(e),
                datasetIterator = function(dataset) {
                    barsArray.push(dataset.bars[barIndex]);
                },
                barIndex;

            for(var datasetIndex = 0; datasetIndex < this.datasets.length; datasetIndex++) {
                for(barIndex = 0; barIndex < this.datasets[datasetIndex].bars.length; barIndex++) {
                    if(this.datasets[datasetIndex].bars[barIndex].inRange(eventPosition.x, eventPosition.y)) {
                        helpers.each(this.datasets, datasetIterator);
                        return barsArray;
                    }
                }
            }

            return barsArray;
        },
        buildScale: function(labels) {
            var self = this;

            var dataTotal = function() {
                var values = [];
                self.eachBars(function(bar) {
                    values.push(bar.value);
                });
                return values;
            };

            var scaleOptions = {
                templateString: this.options.scaleLabel,
                height: this.chart.height,
                width: this.chart.width,
                ctx: this.chart.ctx,
                textColor: this.options.scaleFontColor,
                fontSize: this.options.scaleFontSize,
                fontStyle: this.options.scaleFontStyle,
                fontFamily: this.options.scaleFontFamily,
                valuesCount: labels.length,
                beginAtZero: this.options.scaleBeginAtZero,
                integersOnly: this.options.scaleIntegersOnly,
                calculateYRange: function(currentHeight) {
                    var updatedRanges = helpers.calculateScaleRange(
                        dataTotal(),
                        currentHeight,
                        this.fontSize,
                        this.beginAtZero,
                        this.integersOnly
                    );
                    helpers.extend(this, updatedRanges);
                },
                xLabels: labels,
                font: helpers.fontString(this.options.scaleFontSize, this.options.scaleFontStyle, this.options.scaleFontFamily),
                lineWidth: this.options.scaleLineWidth,
                lineColor: this.options.scaleLineColor,
                showHorizontalLines: this.options.scaleShowHorizontalLines,
                showVerticalLines: this.options.scaleShowVerticalLines,
                /// ZUI change begin
                showBeyondLine: this.options.scaleShowBeyondLine,
                /// ZUI change end
                gridLineWidth: (this.options.scaleShowGridLines) ? this.options.scaleGridLineWidth : 0,
                gridLineColor: (this.options.scaleShowGridLines) ? this.options.scaleGridLineColor : "rgba(0,0,0,0)",
                padding: (this.options.showScale) ? 0 : (this.options.barShowStroke) ? this.options.barStrokeWidth : 0,
                showLabels: this.options.scaleShowLabels,
                display: this.options.showScale
            };

            if(this.options.scaleOverride) {
                helpers.extend(scaleOptions, {
                    calculateYRange: helpers.noop,
                    steps: this.options.scaleSteps,
                    stepValue: this.options.scaleStepWidth,
                    min: this.options.scaleStartValue,
                    max: this.options.scaleStartValue + (this.options.scaleSteps * this.options.scaleStepWidth)
                });
            }

            this.scale = new this.ScaleClass(scaleOptions);
        },
        addData: function(valuesArray, label) {
            //Map the values array for each of the datasets
            helpers.each(valuesArray, function(value, datasetIndex) {
                //Add a new point for each piece of data, passing any required data to draw.
                this.datasets[datasetIndex].bars.push(new this.BarClass({
                    value: value,
                    label: label,
                    x: this.scale.calculateBarX(this.datasets.length, datasetIndex, this.scale.valuesCount + 1),
                    y: this.scale.endPoint,
                    width: this.scale.calculateBarWidth(this.datasets.length),
                    base: this.scale.endPoint,
                    strokeColor: this.datasets[datasetIndex].strokeColor,
                    fillColor: this.datasets[datasetIndex].fillColor
                }));
            }, this);

            this.scale.addXLabel(label);
            //Then re-render the chart.
            this.update();
        },
        removeData: function() {
            this.scale.removeXLabel();
            //Then re-render the chart.
            helpers.each(this.datasets, function(dataset) {
                dataset.bars.shift();
            }, this);
            this.update();
        },
        reflow: function() {
            helpers.extend(this.BarClass.prototype, {
                y: this.scale.endPoint,
                base: this.scale.endPoint
            });
            var newScaleProps = helpers.extend({
                height: this.chart.height,
                width: this.chart.width
            });
            this.scale.update(newScaleProps);
        },
        /// ZUI change begin
        drawLabel: function(bar, placement) {
            var options = this.options;
            placement = placement || options.scaleValuePlacement;
            placement = placement ? placement.toLowerCase() : 'auto';
            if(placement === 'auto') {
                placement = bar.y < 15 ? 'insdie' : 'outside';
            }

            var y = placement === 'insdie' ? (bar.y + 10) : (bar.y - 10);
            var ctx = this.chart.ctx;
            ctx.font = helpers.fontString(options.scaleFontSize, options.scaleFontStyle, options.scaleFontFamily);
            ctx.textBaseline = "middle";
            ctx.textAlign = "center";
            ctx.fillStyle = options.scaleFontColor;
            ctx.fillText(bar.value, bar.x, y);
        },
        /// ZUI change end
        draw: function(ease) {
            var easingDecimal = ease || 1;
            this.clear();

            var ctx = this.chart.ctx;

            this.scale.draw(easingDecimal);

            /// ZUI change begin
            var showScaleValue = this.options.scaleShowLabels && this.options.scaleValuePlacement;
            /// ZUI change end
            //Draw all the bars for each dataset
            helpers.each(this.datasets, function(dataset, datasetIndex) {
                helpers.each(dataset.bars, function(bar, index) {
                    if(bar.hasValue()) {
                        bar.base = this.scale.endPoint;
                        //Transition then draw
                        bar.transition({
                            x: this.scale.calculateBarX(this.datasets.length, datasetIndex, index),
                            y: this.scale.calculateY(bar.value),
                            width: this.scale.calculateBarWidth(this.datasets.length)
                        }, easingDecimal).draw();
                    }
                    /// ZUI change begin
                    if(showScaleValue) {
                        this.drawLabel(bar);
                    }
                    /// ZUI change end
                }, this);

            }, this);
        }
    });

    /// ----- ZUI change begin -----
    /// Use jquery object to create Chart object
    $.fn.barChart = function(data, options) {
        var barCharts = [];
        this.each(function() {
            var $this = $(this);
            barCharts.push(new Chart(this.getContext("2d")).Bar(data, $.extend($this.data(), options)));
        });
        return barCharts.length === 1 ? barCharts[0] : barCharts;
    }

    /// ----- ZUI change end -----

    /// ----- ZUI change begin -----
    /// Add jquery object to namespace

    /// }).call(this); // Old code
}).call(this, jQuery);

/// ----- ZUI change end -----


/* ========================================================================
 * Datetimepicker: bootstrap-datetimepicker.js
 * http://www.malot.fr/bootstrap-datetimepicker
 *
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * official version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2012 Stefan Petre
 * Improvements by Andrew Rowls
 * Improvements by Sébastien Malot
 * Improvements by Yun Lai
 * Improvement by CuGBabyBeaR @ 2013-09-12
 * Project URL : http://www.malot.fr/bootstrap-datetimepicker
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================================
 * Improvement in ZUI:
 * 1. Determine client language and apply setting automatically.
 * 2. New option for 'pickerPosition':
 *    - 'auto-left';
 *    - 'auto-right'.
 * ======================================================================== */


/*!
 * Datetimepicker for Bootstrap
 * Copyright 2012 Stefan Petre
 * Licensed under the Apache License v2.0
 */

! function($) {

    function UTCDate() {
        return new Date(Date.UTC.apply(Date, arguments));
    }

    function UTCToday() {
        var today = new Date();
        return UTCDate(today.getUTCFullYear(), today.getUTCMonth(), today.getUTCDate(), today.getUTCHours(), today.getUTCMinutes(), today.getUTCSeconds(), 0);
    }

    // Picker object

    var Datetimepicker = function(element, options) {
        var that = this;

        this.element = $(element);

        this.language = (options.language || this.element.data('date-language') || ($.zui && $.zui.clientLang ? $.zui.clientLang().replace('_', '-') : "zh-cn")).toLowerCase();
        this.language = this.language in dates ? this.language : "en";
        this.isRTL = dates[this.language].rtl || false;
        this.formatType = options.formatType || this.element.data('format-type') || 'standard';
        this.format = DPGlobal.parseFormat(options.format || this.element.data('date-format') || dates[this.language].format || DPGlobal.getDefaultFormat(this.formatType, 'input'), this.formatType);
        this.isInline = false;
        this.isVisible = false;
        this.isInput = this.element.is('input');

        this.component = this.element.is('.date') ? this.element.find('.input-group-addon .icon-th, .input-group-addon .icon-time, .input-group-addon .icon-calendar').parent() : false;
        this.componentReset = this.element.is('.date') ? this.element.find('.input-group-addon .icon-remove').parent() : false;
        this.hasInput = this.component && this.element.find('input').length;
        if(this.component && this.component.length === 0) {
            this.component = false;
        }
        this.linkField = options.linkField || this.element.data('link-field') || false;
        this.linkFormat = DPGlobal.parseFormat(options.linkFormat || this.element.data('link-format') || DPGlobal.getDefaultFormat(this.formatType, 'link'), this.formatType);
        this.minuteStep = options.minuteStep || this.element.data('minute-step') || 5;
        this.pickerPosition = options.pickerPosition || this.element.data('picker-position') || 'bottom-right';
        this.showMeridian = options.showMeridian || this.element.data('show-meridian') || false;
        this.initialDate = options.initialDate || new Date();
        this.pickerClass = options.eleClass;
        this.pickerId = options.eleId;

        this._attachEvents();

        this.formatViewType = "datetime";
        if('formatViewType' in options) {
            this.formatViewType = options.formatViewType;
        } else if('formatViewType' in this.element.data()) {
            this.formatViewType = this.element.data('formatViewType');
        }

        this.minView = 0;
        if('minView' in options) {
            this.minView = options.minView;
        } else if('minView' in this.element.data()) {
            this.minView = this.element.data('min-view');
        }
        this.minView = DPGlobal.convertViewMode(this.minView);

        this.maxView = DPGlobal.modes.length - 1;
        if('maxView' in options) {
            this.maxView = options.maxView;
        } else if('maxView' in this.element.data()) {
            this.maxView = this.element.data('max-view');
        }
        this.maxView = DPGlobal.convertViewMode(this.maxView);

        this.wheelViewModeNavigation = false;
        if('wheelViewModeNavigation' in options) {
            this.wheelViewModeNavigation = options.wheelViewModeNavigation;
        } else if('wheelViewModeNavigation' in this.element.data()) {
            this.wheelViewModeNavigation = this.element.data('view-mode-wheel-navigation');
        }

        this.wheelViewModeNavigationInverseDirection = false;

        if('wheelViewModeNavigationInverseDirection' in options) {
            this.wheelViewModeNavigationInverseDirection = options.wheelViewModeNavigationInverseDirection;
        } else if('wheelViewModeNavigationInverseDirection' in this.element.data()) {
            this.wheelViewModeNavigationInverseDirection = this.element.data('view-mode-wheel-navigation-inverse-dir');
        }

        this.wheelViewModeNavigationDelay = 100;
        if('wheelViewModeNavigationDelay' in options) {
            this.wheelViewModeNavigationDelay = options.wheelViewModeNavigationDelay;
        } else if('wheelViewModeNavigationDelay' in this.element.data()) {
            this.wheelViewModeNavigationDelay = this.element.data('view-mode-wheel-navigation-delay');
        }

        this.startViewMode = 2;
        if('startView' in options) {
            this.startViewMode = options.startView;
        } else if('startView' in this.element.data()) {
            this.startViewMode = this.element.data('start-view');
        }
        this.startViewMode = DPGlobal.convertViewMode(this.startViewMode);
        this.viewMode = this.startViewMode;

        this.viewSelect = this.minView;
        if('viewSelect' in options) {
            this.viewSelect = options.viewSelect;
        } else if('viewSelect' in this.element.data()) {
            this.viewSelect = this.element.data('view-select');
        }
        this.viewSelect = DPGlobal.convertViewMode(this.viewSelect);

        this.forceParse = true;
        if('forceParse' in options) {
            this.forceParse = options.forceParse;
        } else if('dateForceParse' in this.element.data()) {
            this.forceParse = this.element.data('date-force-parse');
        }

        this.picker = $(DPGlobal.template)
            .appendTo(this.isInline ? this.element : 'body')
            .on({
                click: $.proxy(this.click, this),
                mousedown: $.proxy(this.mousedown, this)
            });

        if(this.wheelViewModeNavigation) {
            if($.fn.mousewheel) {
                this.picker.on({
                    mousewheel: $.proxy(this.mousewheel, this)
                });
            } else {
                console.log("Mouse Wheel event is not supported. Please include the jQuery Mouse Wheel plugin before enabling this option");
            }
        }

        if(this.isInline) {
            this.picker.addClass('datetimepicker-inline');
        } else {
            this.picker.addClass('datetimepicker-dropdown-' + this.pickerPosition + ' dropdown-menu');
        }
        if(this.isRTL) {
            this.picker.addClass('datetimepicker-rtl');
            this.picker.find('.prev span, .next span')
                .toggleClass('icon-arrow-left icon-arrow-right');;

        }
        $(document).on('mousedown', function(e) {
            // Clicked outside the datetimepicker, hide it
            if($(e.target).closest('.datetimepicker').length === 0) {
                that.hide();
            }
        });

        this.autoclose = false;
        if('autoclose' in options) {
            this.autoclose = options.autoclose;
        } else if('dateAutoclose' in this.element.data()) {
            this.autoclose = this.element.data('date-autoclose');
        }

        this.keyboardNavigation = true;
        if('keyboardNavigation' in options) {
            this.keyboardNavigation = options.keyboardNavigation;
        } else if('dateKeyboardNavigation' in this.element.data()) {
            this.keyboardNavigation = this.element.data('date-keyboard-navigation');
        }

        this.todayBtn = (options.todayBtn || this.element.data('date-today-btn') || false);
        this.todayHighlight = (options.todayHighlight || this.element.data('date-today-highlight') || false);

        this.weekStart = ((options.weekStart || this.element.data('date-weekstart') || dates[this.language].weekStart || 0) % 7);
        this.weekEnd = ((this.weekStart + 6) % 7);
        this.startDate = -Infinity;
        this.endDate = Infinity;
        this.daysOfWeekDisabled = [];
        this.setStartDate(options.startDate || this.element.data('date-startdate'));
        this.setEndDate(options.endDate || this.element.data('date-enddate'));
        this.setDaysOfWeekDisabled(options.daysOfWeekDisabled || this.element.data('date-days-of-week-disabled'));
        this.fillDow();
        this.fillMonths();
        this.update();
        this.showMode();

        if(this.isInline) {
            this.show();
        }
    };

    Datetimepicker.prototype = {
        constructor: Datetimepicker,

        _events: [],
        _attachEvents: function() {
            this._detachEvents();
            if(this.isInput) { // single input
                this._events = [
                    [this.element, {
                        focus: $.proxy(this.show, this),
                        keyup: $.proxy(this.update, this),
                        keydown: $.proxy(this.keydown, this)
                    }]
                ];
            } else if(this.component && this.hasInput) { // component: input + button
                this._events = [
                    // For components that are not readonly, allow keyboard nav
                    [this.element.find('input'), {
                        focus: $.proxy(this.show, this),
                        keyup: $.proxy(this.update, this),
                        keydown: $.proxy(this.keydown, this)
                    }],
                    [this.component, {
                        click: $.proxy(this.show, this)
                    }]
                ];
                if(this.componentReset) {
                    this._events.push([
                        this.componentReset, {
                            click: $.proxy(this.reset, this)
                        }
                    ]);
                }
            } else if(this.element.is('div')) { // inline datetimepicker
                this.isInline = true;
            } else {
                this._events = [
                    [this.element, {
                        click: $.proxy(this.show, this)
                    }]
                ];
            }
            for(var i = 0, el, ev; i < this._events.length; i++) {
                el = this._events[i][0];
                ev = this._events[i][1];
                el.on(ev);
            }
        },

        _detachEvents: function() {
            for(var i = 0, el, ev; i < this._events.length; i++) {
                el = this._events[i][0];
                ev = this._events[i][1];
                el.off(ev);
            }
            this._events = [];
        },

        show: function(e) {
            this.picker.show();
            this.height = this.component ? this.component.outerHeight() : this.element.outerHeight();
            if(this.forceParse) {
                this.update();
            }
            this.place();
            $(window).on('resize', $.proxy(this.place, this));
            if(e) {
                e.stopPropagation();
                e.preventDefault();
            }
            this.isVisible = true;
            this.element.trigger({
                type: 'show',
                date: this.date
            });
        },

        hide: function(e) {
            if(!this.isVisible) return;
            if(this.isInline) return;
            this.picker.hide();
            $(window).off('resize', this.place);
            this.viewMode = this.startViewMode;
            this.showMode();
            if(!this.isInput) {
                $(document).off('mousedown', this.hide);
            }

            if(
                this.forceParse &&
                (
                    this.isInput && this.element.val() ||
                    this.hasInput && this.element.find('input').val()
                )
            )
                this.setValue();
            this.isVisible = false;
            this.element.trigger({
                type: 'hide',
                date: this.date
            });
        },

        remove: function() {
            this._detachEvents();
            this.picker.remove();
            delete this.picker;
            delete this.element.data().datetimepicker;
        },

        getDate: function() {
            var d = this.getUTCDate();
            return new Date(d.getTime() + (d.getTimezoneOffset() * 60000));
        },

        getUTCDate: function() {
            return this.date;
        },

        setDate: function(d) {
            this.setUTCDate(new Date(d.getTime() - (d.getTimezoneOffset() * 60000)));
        },

        setUTCDate: function(d) {
            if(d >= this.startDate && d <= this.endDate) {
                this.date = d;
                this.setValue();
                this.viewDate = this.date;
                this.fill();
            } else {
                this.element.trigger({
                    type: 'outOfRange',
                    date: d,
                    startDate: this.startDate,
                    endDate: this.endDate
                });
            }
        },

        setFormat: function(format) {
            this.format = DPGlobal.parseFormat(format, this.formatType);
            var element;
            if(this.isInput) {
                element = this.element;
            } else if(this.component) {
                element = this.element.find('input');
            }
            if(element && element.val()) {
                this.setValue();
            }
        },

        setValue: function() {
            var formatted = this.getFormattedDate();
            if(!this.isInput) {
                if(this.component) {
                    this.element.find('input').val(formatted);
                }
                this.element.data('date', formatted);
            } else {
                this.element.val(formatted);
            }
            if(this.linkField) {
                $('#' + this.linkField).val(this.getFormattedDate(this.linkFormat));
            }
        },

        getFormattedDate: function(format) {
            if(format == undefined) format = this.format;
            return DPGlobal.formatDate(this.date, format, this.language, this.formatType);
        },

        setStartDate: function(startDate) {
            this.startDate = startDate || -Infinity;
            if(this.startDate !== -Infinity) {
                this.startDate = DPGlobal.parseDate(this.startDate, this.format, this.language, this.formatType);
            }
            this.update();
            this.updateNavArrows();
        },

        setEndDate: function(endDate) {
            this.endDate = endDate || Infinity;
            if(this.endDate !== Infinity) {
                this.endDate = DPGlobal.parseDate(this.endDate, this.format, this.language, this.formatType);
            }
            this.update();
            this.updateNavArrows();
        },

        setDaysOfWeekDisabled: function(daysOfWeekDisabled) {
            this.daysOfWeekDisabled = daysOfWeekDisabled || [];
            if(!$.isArray(this.daysOfWeekDisabled)) {
                this.daysOfWeekDisabled = this.daysOfWeekDisabled.split(/,\s*/);
            }
            this.daysOfWeekDisabled = $.map(this.daysOfWeekDisabled, function(d) {
                return parseInt(d, 10);
            });
            this.update();
            this.updateNavArrows();
        },

        place: function() {
            if(this.isInline) return;

            var index_highest = 0;
            $('div').each(function() {
                var index_current = parseInt($(this).css("zIndex"), 10);
                if(index_current > index_highest) {
                    index_highest = index_current;
                }
            });
            var zIndex = index_highest + 10;

            var offset, top, left;
            if(this.component) {
                offset = this.component.offset();
                left = offset.left;
                if(this.pickerPosition === 'bottom-left' || this.pickerPosition === 'top-left' || this.pickerPosition === 'auto-left') {
                    left += this.component.outerWidth() - this.picker.outerWidth();
                }
            } else {
                offset = this.element.offset();
                left = offset.left;
            }

            var isAutoPosition = this.pickerPosition.indexOf('auto-') === 0;
            var realPickerPosition = isAutoPosition ? (((offset.top + this.picker.outerHeight() > $(window).height() + $(window).scrollTop()) ? 'top' : 'bottom') + (this.pickerPosition.lastIndexOf('-left') === 0 ? '-left' : '-right')) : this.pickerPosition;

            if(realPickerPosition === 'top-left' || realPickerPosition === 'top-right') {
                top = offset.top - this.picker.outerHeight();
            } else {
                top = offset.top + this.height;
            }
            this.picker.css({
                top: top,
                left: left,
                zIndex: zIndex
            }).attr('class', 'datetimepicker dropdown-menu datetimepicker-dropdown-' + realPickerPosition);
            if(this.pickerClass) this.picker.addClass(this.pickerClass);
            if(this.pickerId) this.picker.attr('id', this.pickerId);
        },

        update: function() {
            var date, fromArgs = false;
            if(arguments && arguments.length && (typeof arguments[0] === 'string' || arguments[0] instanceof Date)) {
                date = arguments[0];
                fromArgs = true;
            } else {
                date = this.element.data('date') || (this.isInput ? this.element.val() : this.element.find('input').val()) || this.initialDate;
                if(typeof date == 'string' || date instanceof String) {
                    date = date.replace(/^\s+|\s+$/g, '');
                }
            }

            if(!date) {
                date = new Date();
                fromArgs = false;
            }

            this.date = DPGlobal.parseDate(date, this.format, this.language, this.formatType);

            if(fromArgs) this.setValue();

            if(this.date < this.startDate) {
                this.viewDate = new Date(this.startDate);
            } else if(this.date > this.endDate) {
                this.viewDate = new Date(this.endDate);
            } else {
                this.viewDate = new Date(this.date);
            }
            this.fill();
        },

        fillDow: function() {
            var dowCnt = this.weekStart,
                html = '<tr>';
            while(dowCnt < this.weekStart + 7) {
                html += '<th class="dow">' + dates[this.language].daysMin[(dowCnt++) % 7] + '</th>';
            }
            html += '</tr>';
            this.picker.find('.datetimepicker-days thead').append(html);
        },

        fillMonths: function() {
            var html = '',
                i = 0;
            while(i < 12) {
                html += '<span class="month">' + dates[this.language].monthsShort[i++] + '</span>';
            }
            this.picker.find('.datetimepicker-months td').html(html);
        },

        fill: function() {
            if(this.date == null || this.viewDate == null) {
                return;
            }
            var d = new Date(this.viewDate),
                year = d.getUTCFullYear(),
                month = d.getUTCMonth(),
                dayMonth = d.getUTCDate(),
                hours = d.getUTCHours(),
                minutes = d.getUTCMinutes(),
                startYear = this.startDate !== -Infinity ? this.startDate.getUTCFullYear() : -Infinity,
                startMonth = this.startDate !== -Infinity ? this.startDate.getUTCMonth() : -Infinity,
                endYear = this.endDate !== Infinity ? this.endDate.getUTCFullYear() : Infinity,
                endMonth = this.endDate !== Infinity ? this.endDate.getUTCMonth() : Infinity,
                currentDate = (new UTCDate(this.date.getUTCFullYear(), this.date.getUTCMonth(), this.date.getUTCDate())).valueOf(),
                today = new Date();
            this.picker.find('.datetimepicker-days thead th:eq(1)')
                .text(dates[this.language].months[month] + ' ' + year);
            if(this.formatViewType == "time") {
                var hourConverted = hours % 12 ? hours % 12 : 12;
                var hoursDisplay = (hourConverted < 10 ? '0' : '') + hourConverted;
                var minutesDisplay = (minutes < 10 ? '0' : '') + minutes;
                var meridianDisplay = dates[this.language].meridiem[hours < 12 ? 0 : 1];
                this.picker.find('.datetimepicker-hours thead th:eq(1)')
                    .text(hoursDisplay + ':' + minutesDisplay + ' ' + meridianDisplay.toUpperCase());
                this.picker.find('.datetimepicker-minutes thead th:eq(1)')
                    .text(hoursDisplay + ':' + minutesDisplay + ' ' + meridianDisplay.toUpperCase());
            } else {
                this.picker.find('.datetimepicker-hours thead th:eq(1)')
                    .text(dayMonth + ' ' + dates[this.language].months[month] + ' ' + year);
                this.picker.find('.datetimepicker-minutes thead th:eq(1)')
                    .text(dayMonth + ' ' + dates[this.language].months[month] + ' ' + year);
            }
            this.picker.find('tfoot th.today')
                .text(dates[this.language].today)
                .toggle(this.todayBtn !== false);
            this.updateNavArrows();
            this.fillMonths();
            /*var prevMonth = UTCDate(year, month, 0,0,0,0,0);
             prevMonth.setUTCDate(prevMonth.getDate() - (prevMonth.getUTCDay() - this.weekStart + 7)%7);*/
            var prevMonth = UTCDate(year, month - 1, 28, 0, 0, 0, 0),
                day = DPGlobal.getDaysInMonth(prevMonth.getUTCFullYear(), prevMonth.getUTCMonth());
            prevMonth.setUTCDate(day);
            prevMonth.setUTCDate(day - (prevMonth.getUTCDay() - this.weekStart + 7) % 7);
            var nextMonth = new Date(prevMonth);
            nextMonth.setUTCDate(nextMonth.getUTCDate() + 42);
            nextMonth = nextMonth.valueOf();
            var html = [];
            var clsName;
            while(prevMonth.valueOf() < nextMonth) {
                if(prevMonth.getUTCDay() == this.weekStart) {
                    html.push('<tr>');
                }
                clsName = '';
                if(prevMonth.getUTCFullYear() < year || (prevMonth.getUTCFullYear() == year && prevMonth.getUTCMonth() < month)) {
                    clsName += ' old';
                } else if(prevMonth.getUTCFullYear() > year || (prevMonth.getUTCFullYear() == year && prevMonth.getUTCMonth() > month)) {
                    clsName += ' new';
                }
                // Compare internal UTC date with local today, not UTC today
                if(this.todayHighlight &&
                    prevMonth.getUTCFullYear() == today.getFullYear() &&
                    prevMonth.getUTCMonth() == today.getMonth() &&
                    prevMonth.getUTCDate() == today.getDate()) {
                    clsName += ' today';
                }
                if(prevMonth.valueOf() == currentDate) {
                    clsName += ' active';
                }
                if((prevMonth.valueOf() + 86400000) <= this.startDate || prevMonth.valueOf() > this.endDate ||
                    $.inArray(prevMonth.getUTCDay(), this.daysOfWeekDisabled) !== -1) {
                    clsName += ' disabled';
                }
                html.push('<td class="day' + clsName + '">' + prevMonth.getUTCDate() + '</td>');
                if(prevMonth.getUTCDay() == this.weekEnd) {
                    html.push('</tr>');
                }
                prevMonth.setUTCDate(prevMonth.getUTCDate() + 1);
            }
            this.picker.find('.datetimepicker-days tbody').empty().append(html.join(''));

            html = [];
            var txt = '',
                meridian = '',
                meridianOld = '';
            for(var i = 0; i < 24; i++) {
                var actual = UTCDate(year, month, dayMonth, i);
                clsName = '';
                // We want the previous hour for the startDate
                if((actual.valueOf() + 3600000) <= this.startDate || actual.valueOf() > this.endDate) {
                    clsName += ' disabled';
                } else if(hours == i) {
                    clsName += ' active';
                }
                if(this.showMeridian && dates[this.language].meridiem.length == 2) {
                    meridian = (i < 12 ? dates[this.language].meridiem[0] : dates[this.language].meridiem[1]);
                    if(meridian != meridianOld) {
                        if(meridianOld != '') {
                            html.push('</fieldset>');
                        }
                        html.push('<fieldset class="hour"><legend>' + meridian.toUpperCase() + '</legend>');
                    }
                    meridianOld = meridian;
                    txt = (i % 12 ? i % 12 : 12);
                    html.push('<span class="hour' + clsName + ' hour_' + (i < 12 ? 'am' : 'pm') + '">' + txt + '</span>');
                    if(i == 23) {
                        html.push('</fieldset>');
                    }
                } else {
                    txt = i + ':00';
                    html.push('<span class="hour' + clsName + '">' + txt + '</span>');
                }
            }
            this.picker.find('.datetimepicker-hours td').html(html.join(''));

            html = [];
            txt = '', meridian = '', meridianOld = '';
            for(var i = 0; i < 60; i += this.minuteStep) {
                var actual = UTCDate(year, month, dayMonth, hours, i, 0);
                clsName = '';
                if(actual.valueOf() < this.startDate || actual.valueOf() > this.endDate) {
                    clsName += ' disabled';
                } else if(Math.floor(minutes / this.minuteStep) == Math.floor(i / this.minuteStep)) {
                    clsName += ' active';
                }
                if(this.showMeridian && dates[this.language].meridiem.length == 2) {
                    meridian = (hours < 12 ? dates[this.language].meridiem[0] : dates[this.language].meridiem[1]);
                    if(meridian != meridianOld) {
                        if(meridianOld != '') {
                            html.push('</fieldset>');
                        }
                        html.push('<fieldset class="minute"><legend>' + meridian.toUpperCase() + '</legend>');
                    }
                    meridianOld = meridian;
                    txt = (hours % 12 ? hours % 12 : 12);
                    //html.push('<span class="minute'+clsName+' minute_'+(hours<12?'am':'pm')+'">'+txt+'</span>');
                    html.push('<span class="minute' + clsName + '">' + txt + ':' + (i < 10 ? '0' + i : i) + '</span>');
                    if(i == 59) {
                        html.push('</fieldset>');
                    }
                } else {
                    txt = i + ':00';
                    //html.push('<span class="hour'+clsName+'">'+txt+'</span>');
                    html.push('<span class="minute' + clsName + '">' + hours + ':' + (i < 10 ? '0' + i : i) + '</span>');
                }
            }
            this.picker.find('.datetimepicker-minutes td').html(html.join(''));

            var currentYear = this.date.getUTCFullYear();
            var months = this.picker.find('.datetimepicker-months')
                .find('th:eq(1)')
                .text(year)
                .end()
                .find('span').removeClass('active');
            if(currentYear == year) {
                months.eq(this.date.getUTCMonth()).addClass('active');
            }
            if(year < startYear || year > endYear) {
                months.addClass('disabled');
            }
            if(year == startYear) {
                months.slice(0, startMonth).addClass('disabled');
            }
            if(year == endYear) {
                months.slice(endMonth + 1).addClass('disabled');
            }

            html = '';
            year = parseInt(year / 10, 10) * 10;
            var yearCont = this.picker.find('.datetimepicker-years')
                .find('th:eq(1)')
                .text(year + '-' + (year + 9))
                .end()
                .find('td');
            year -= 1;
            for(var i = -1; i < 11; i++) {
                html += '<span class="year' + (i == -1 || i == 10 ? ' old' : '') + (currentYear == year ? ' active' : '') + (year < startYear || year > endYear ? ' disabled' : '') + '">' + year + '</span>';
                year += 1;
            }
            yearCont.html(html);
            this.place();
        },

        updateNavArrows: function() {
            var d = new Date(this.viewDate),
                year = d.getUTCFullYear(),
                month = d.getUTCMonth(),
                day = d.getUTCDate(),
                hour = d.getUTCHours();
            switch(this.viewMode) {
                case 0:
                    if(this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear() && month <= this.startDate.getUTCMonth() && day <= this.startDate.getUTCDate() && hour <= this.startDate.getUTCHours()) {
                        this.picker.find('.prev').css({
                            visibility: 'hidden'
                        });
                    } else {
                        this.picker.find('.prev').css({
                            visibility: 'visible'
                        });
                    }
                    if(this.endDate !== Infinity && year >= this.endDate.getUTCFullYear() && month >= this.endDate.getUTCMonth() && day >= this.endDate.getUTCDate() && hour >= this.endDate.getUTCHours()) {
                        this.picker.find('.next').css({
                            visibility: 'hidden'
                        });
                    } else {
                        this.picker.find('.next').css({
                            visibility: 'visible'
                        });
                    }
                    break;
                case 1:
                    if(this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear() && month <= this.startDate.getUTCMonth() && day <= this.startDate.getUTCDate()) {
                        this.picker.find('.prev').css({
                            visibility: 'hidden'
                        });
                    } else {
                        this.picker.find('.prev').css({
                            visibility: 'visible'
                        });
                    }
                    if(this.endDate !== Infinity && year >= this.endDate.getUTCFullYear() && month >= this.endDate.getUTCMonth() && day >= this.endDate.getUTCDate()) {
                        this.picker.find('.next').css({
                            visibility: 'hidden'
                        });
                    } else {
                        this.picker.find('.next').css({
                            visibility: 'visible'
                        });
                    }
                    break;
                case 2:
                    if(this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear() && month <= this.startDate.getUTCMonth()) {
                        this.picker.find('.prev').css({
                            visibility: 'hidden'
                        });
                    } else {
                        this.picker.find('.prev').css({
                            visibility: 'visible'
                        });
                    }
                    if(this.endDate !== Infinity && year >= this.endDate.getUTCFullYear() && month >= this.endDate.getUTCMonth()) {
                        this.picker.find('.next').css({
                            visibility: 'hidden'
                        });
                    } else {
                        this.picker.find('.next').css({
                            visibility: 'visible'
                        });
                    }
                    break;
                case 3:
                case 4:
                    if(this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear()) {
                        this.picker.find('.prev').css({
                            visibility: 'hidden'
                        });
                    } else {
                        this.picker.find('.prev').css({
                            visibility: 'visible'
                        });
                    }
                    if(this.endDate !== Infinity && year >= this.endDate.getUTCFullYear()) {
                        this.picker.find('.next').css({
                            visibility: 'hidden'
                        });
                    } else {
                        this.picker.find('.next').css({
                            visibility: 'visible'
                        });
                    }
                    break;
            }
        },

        mousewheel: function(e) {

            e.preventDefault();
            e.stopPropagation();

            if(this.wheelPause) {
                return;
            }

            this.wheelPause = true;

            var originalEvent = e.originalEvent;

            var delta = originalEvent.wheelDelta;

            var mode = delta > 0 ? 1 : (delta === 0) ? 0 : -1;

            if(this.wheelViewModeNavigationInverseDirection) {
                mode = -mode;
            }

            this.showMode(mode);

            setTimeout($.proxy(function() {

                this.wheelPause = false

            }, this), this.wheelViewModeNavigationDelay);

        },

        click: function(e) {
            e.stopPropagation();
            e.preventDefault();
            var target = $(e.target).closest('span, td, th, legend');
            if(target.length == 1) {
                if(target.is('.disabled')) {
                    this.element.trigger({
                        type: 'outOfRange',
                        date: this.viewDate,
                        startDate: this.startDate,
                        endDate: this.endDate
                    });
                    return;
                }
                switch(target[0].nodeName.toLowerCase()) {
                    case 'th':
                        switch(target[0].className) {
                            case 'switch':
                                this.showMode(1);
                                break;
                            case 'prev':
                            case 'next':
                                var dir = DPGlobal.modes[this.viewMode].navStep * (target[0].className == 'prev' ? -1 : 1);
                                switch(this.viewMode) {
                                    case 0:
                                        this.viewDate = this.moveHour(this.viewDate, dir);
                                        break;
                                    case 1:
                                        this.viewDate = this.moveDate(this.viewDate, dir);
                                        break;
                                    case 2:
                                        this.viewDate = this.moveMonth(this.viewDate, dir);
                                        break;
                                    case 3:
                                    case 4:
                                        this.viewDate = this.moveYear(this.viewDate, dir);
                                        break;
                                }
                                this.fill();
                                break;
                            case 'today':
                                var date = new Date();
                                date = UTCDate(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(), date.getSeconds(), 0);

                                // Respect startDate and endDate.
                                if(date < this.startDate) date = this.startDate;
                                else if(date > this.endDate) date = this.endDate;

                                this.viewMode = this.startViewMode;
                                this.showMode(0);
                                this._setDate(date);
                                this.fill();
                                if(this.autoclose) {
                                    this.hide();
                                }
                                break;
                        }
                        break;
                    case 'span':
                        if(!target.is('.disabled')) {
                            var year = this.viewDate.getUTCFullYear(),
                                month = this.viewDate.getUTCMonth(),
                                day = this.viewDate.getUTCDate(),
                                hours = this.viewDate.getUTCHours(),
                                minutes = this.viewDate.getUTCMinutes(),
                                seconds = this.viewDate.getUTCSeconds();

                            if(target.is('.month')) {
                                this.viewDate.setUTCDate(1);
                                month = target.parent().find('span').index(target);
                                day = this.viewDate.getUTCDate();
                                this.viewDate.setUTCMonth(month);
                                this.element.trigger({
                                    type: 'changeMonth',
                                    date: this.viewDate
                                });
                                if(this.viewSelect >= 3) {
                                    this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
                                }
                            } else if(target.is('.year')) {
                                this.viewDate.setUTCDate(1);
                                year = parseInt(target.text(), 10) || 0;
                                this.viewDate.setUTCFullYear(year);
                                this.element.trigger({
                                    type: 'changeYear',
                                    date: this.viewDate
                                });
                                if(this.viewSelect >= 4) {
                                    this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
                                }
                            } else if(target.is('.hour')) {
                                hours = parseInt(target.text(), 10) || 0;
                                if(target.hasClass('hour_am') || target.hasClass('hour_pm')) {
                                    if(hours == 12 && target.hasClass('hour_am')) {
                                        hours = 0;
                                    } else if(hours != 12 && target.hasClass('hour_pm')) {
                                        hours += 12;
                                    }
                                }
                                this.viewDate.setUTCHours(hours);
                                this.element.trigger({
                                    type: 'changeHour',
                                    date: this.viewDate
                                });
                                if(this.viewSelect >= 1) {
                                    this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
                                }
                            } else if(target.is('.minute')) {
                                minutes = parseInt(target.text().substr(target.text().indexOf(':') + 1), 10) || 0;
                                this.viewDate.setUTCMinutes(minutes);
                                this.element.trigger({
                                    type: 'changeMinute',
                                    date: this.viewDate
                                });
                                if(this.viewSelect >= 0) {
                                    this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
                                }
                            }
                            if(this.viewMode != 0) {
                                var oldViewMode = this.viewMode;
                                this.showMode(-1);
                                this.fill();
                                if(oldViewMode == this.viewMode && this.autoclose) {
                                    this.hide();
                                }
                            } else {
                                this.fill();
                                if(this.autoclose) {
                                    this.hide();
                                }
                            }
                        }
                        break;
                    case 'td':
                        if(target.is('.day') && !target.is('.disabled')) {
                            var day = parseInt(target.text(), 10) || 1;
                            var year = this.viewDate.getUTCFullYear(),
                                month = this.viewDate.getUTCMonth(),
                                hours = this.viewDate.getUTCHours(),
                                minutes = this.viewDate.getUTCMinutes(),
                                seconds = this.viewDate.getUTCSeconds();
                            if(target.is('.old')) {
                                if(month === 0) {
                                    month = 11;
                                    year -= 1;
                                } else {
                                    month -= 1;
                                }
                            } else if(target.is('.new')) {
                                if(month == 11) {
                                    month = 0;
                                    year += 1;
                                } else {
                                    month += 1;
                                }
                            }
                            this.viewDate.setUTCFullYear(year);
                            this.viewDate.setUTCMonth(month, day);
                            this.element.trigger({
                                type: 'changeDay',
                                date: this.viewDate
                            });
                            if(this.viewSelect >= 2) {
                                this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
                            }
                        }
                        var oldViewMode = this.viewMode;
                        this.showMode(-1);
                        this.fill();
                        if(oldViewMode == this.viewMode && this.autoclose) {
                            this.hide();
                        }
                        break;
                }
            }
        },

        _setDate: function(date, which) {
            if(!which || which == 'date')
                this.date = date;
            if(!which || which == 'view')
                this.viewDate = date;
            this.fill();
            this.setValue();
            var element;
            if(this.isInput) {
                element = this.element;
            } else if(this.component) {
                element = this.element.find('input');
            }
            if(element) {
                element.change();
                if(this.autoclose && (!which || which == 'date')) {
                    //this.hide();
                }
            }
            this.element.trigger({
                type: 'changeDate',
                date: this.date
            });
            if(date === null) {
                this.date = this.viewDate;
            }
        },

        moveMinute: function(date, dir) {
            if(!dir) return date;
            var new_date = new Date(date.valueOf());
            //dir = dir > 0 ? 1 : -1;
            new_date.setUTCMinutes(new_date.getUTCMinutes() + (dir * this.minuteStep));
            return new_date;
        },

        moveHour: function(date, dir) {
            if(!dir) return date;
            var new_date = new Date(date.valueOf());
            //dir = dir > 0 ? 1 : -1;
            new_date.setUTCHours(new_date.getUTCHours() + dir);
            return new_date;
        },

        moveDate: function(date, dir) {
            if(!dir) return date;
            var new_date = new Date(date.valueOf());
            //dir = dir > 0 ? 1 : -1;
            new_date.setUTCDate(new_date.getUTCDate() + dir);
            return new_date;
        },

        moveMonth: function(date, dir) {
            if(!dir) return date;
            var new_date = new Date(date.valueOf()),
                day = new_date.getUTCDate(),
                month = new_date.getUTCMonth(),
                mag = Math.abs(dir),
                new_month, test;
            dir = dir > 0 ? 1 : -1;
            if(mag == 1) {
                test = dir == -1
                    // If going back one month, make sure month is not current month
                    // (eg, Mar 31 -> Feb 31 == Feb 28, not Mar 02)
                    ? function() {
                        return new_date.getUTCMonth() == month;
                    }
                    // If going forward one month, make sure month is as expected
                    // (eg, Jan 31 -> Feb 31 == Feb 28, not Mar 02)
                    : function() {
                        return new_date.getUTCMonth() != new_month;
                    };
                new_month = month + dir;
                new_date.setUTCMonth(new_month);
                // Dec -> Jan (12) or Jan -> Dec (-1) -- limit expected date to 0-11
                if(new_month < 0 || new_month > 11)
                    new_month = (new_month + 12) % 12;
            } else {
                // For magnitudes >1, move one month at a time...
                for(var i = 0; i < mag; i++)
                // ...which might decrease the day (eg, Jan 31 to Feb 28, etc)...
                    new_date = this.moveMonth(new_date, dir);
                // ...then reset the day, keeping it in the new month
                new_month = new_date.getUTCMonth();
                new_date.setUTCDate(day);
                test = function() {
                    return new_month != new_date.getUTCMonth();
                };
            }
            // Common date-resetting loop -- if date is beyond end of month, make it
            // end of month
            while(test()) {
                new_date.setUTCDate(--day);
                new_date.setUTCMonth(new_month);
            }
            return new_date;
        },

        moveYear: function(date, dir) {
            return this.moveMonth(date, dir * 12);
        },

        dateWithinRange: function(date) {
            return date >= this.startDate && date <= this.endDate;
        },

        keydown: function(e) {
            if(this.picker.is(':not(:visible)')) {
                if(e.keyCode == 27) // allow escape to hide and re-show picker
                    this.show();
                return;
            }
            var dateChanged = false,
                dir, day, month,
                newDate, newViewDate;
            switch(e.keyCode) {
                case 27: // escape
                    this.hide();
                    e.preventDefault();
                    break;
                case 37: // left
                case 39: // right
                    if(!this.keyboardNavigation) break;
                    dir = e.keyCode == 37 ? -1 : 1;
                    viewMode = this.viewMode;
                    if(e.ctrlKey) {
                        viewMode += 2;
                    } else if(e.shiftKey) {
                        viewMode += 1;
                    }
                    if(viewMode == 4) {
                        newDate = this.moveYear(this.date, dir);
                        newViewDate = this.moveYear(this.viewDate, dir);
                    } else if(viewMode == 3) {
                        newDate = this.moveMonth(this.date, dir);
                        newViewDate = this.moveMonth(this.viewDate, dir);
                    } else if(viewMode == 2) {
                        newDate = this.moveDate(this.date, dir);
                        newViewDate = this.moveDate(this.viewDate, dir);
                    } else if(viewMode == 1) {
                        newDate = this.moveHour(this.date, dir);
                        newViewDate = this.moveHour(this.viewDate, dir);
                    } else if(viewMode == 0) {
                        newDate = this.moveMinute(this.date, dir);
                        newViewDate = this.moveMinute(this.viewDate, dir);
                    }
                    if(this.dateWithinRange(newDate)) {
                        this.date = newDate;
                        this.viewDate = newViewDate;
                        this.setValue();
                        this.update();
                        e.preventDefault();
                        dateChanged = true;
                    }
                    break;
                case 38: // up
                case 40: // down
                    if(!this.keyboardNavigation) break;
                    dir = e.keyCode == 38 ? -1 : 1;
                    viewMode = this.viewMode;
                    if(e.ctrlKey) {
                        viewMode += 2;
                    } else if(e.shiftKey) {
                        viewMode += 1;
                    }
                    if(viewMode == 4) {
                        newDate = this.moveYear(this.date, dir);
                        newViewDate = this.moveYear(this.viewDate, dir);
                    } else if(viewMode == 3) {
                        newDate = this.moveMonth(this.date, dir);
                        newViewDate = this.moveMonth(this.viewDate, dir);
                    } else if(viewMode == 2) {
                        newDate = this.moveDate(this.date, dir * 7);
                        newViewDate = this.moveDate(this.viewDate, dir * 7);
                    } else if(viewMode == 1) {
                        if(this.showMeridian) {
                            newDate = this.moveHour(this.date, dir * 6);
                            newViewDate = this.moveHour(this.viewDate, dir * 6);
                        } else {
                            newDate = this.moveHour(this.date, dir * 4);
                            newViewDate = this.moveHour(this.viewDate, dir * 4);
                        }
                    } else if(viewMode == 0) {
                        newDate = this.moveMinute(this.date, dir * 4);
                        newViewDate = this.moveMinute(this.viewDate, dir * 4);
                    }
                    if(this.dateWithinRange(newDate)) {
                        this.date = newDate;
                        this.viewDate = newViewDate;
                        this.setValue();
                        this.update();
                        e.preventDefault();
                        dateChanged = true;
                    }
                    break;
                case 13: // enter
                    if(this.viewMode != 0) {
                        var oldViewMode = this.viewMode;
                        this.showMode(-1);
                        this.fill();
                        if(oldViewMode == this.viewMode && this.autoclose) {
                            this.hide();
                        }
                    } else {
                        this.fill();
                        if(this.autoclose) {
                            this.hide();
                        }
                    }
                    e.preventDefault();
                    break;
                case 9: // tab
                    this.hide();
                    break;
            }
            if(dateChanged) {
                var element;
                if(this.isInput) {
                    element = this.element;
                } else if(this.component) {
                    element = this.element.find('input');
                }
                if(element) {
                    element.change();
                }
                this.element.trigger({
                    type: 'changeDate',
                    date: this.date
                });
            }
        },

        showMode: function(dir) {
            if(dir) {
                var newViewMode = Math.max(0, Math.min(DPGlobal.modes.length - 1, this.viewMode + dir));
                if(newViewMode >= this.minView && newViewMode <= this.maxView) {
                    this.element.trigger({
                        type: 'changeMode',
                        date: this.viewDate,
                        oldViewMode: this.viewMode,
                        newViewMode: newViewMode
                    });

                    this.viewMode = newViewMode;
                }
            }
            /*
             vitalets: fixing bug of very special conditions:
             jquery 1.7.1 + webkit + show inline datetimepicker in bootstrap popover.
             Method show() does not set display css correctly and datetimepicker is not shown.
             Changed to .css('display', 'block') solve the problem.
             See https://github.com/vitalets/x-editable/issues/37

             In jquery 1.7.2+ everything works fine.
             */
            //this.picker.find('>div').hide().filter('.datetimepicker-'+DPGlobal.modes[this.viewMode].clsName).show();
            this.picker.find('>div').hide().filter('.datetimepicker-' + DPGlobal.modes[this.viewMode].clsName).css('display', 'block');
            this.updateNavArrows();
        },

        reset: function(e) {
            this._setDate(null, 'date');
        }
    };

    $.fn.datetimepicker = function(option) {
        var args = Array.apply(null, arguments);
        args.shift();
        return this.each(function() {
            var $this = $(this),
                data = $this.data('datetimepicker'),
                options = typeof option == 'object' && option;
            if(!data) {
                $this.data('datetimepicker', (data = new Datetimepicker(this, $.extend({}, $.fn.datetimepicker.defaults, $this.data(), options))));
            }
            if(typeof option == 'string' && typeof data[option] == 'function') {
                data[option].apply(data, args);
            }
        });
    };

    $.fn.datetimepicker.defaults = {
        pickerPosition: 'auto-right'
    };
    $.fn.datetimepicker.Constructor = Datetimepicker;
    var dates = $.fn.datetimepicker.dates = {
        en: {
            days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
            months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            meridiem: ["am", "pm"],
            suffix: ["st", "nd", "rd", "th"],
            today: "Today"
        }
    };

    dates['zh-cn'] = {
        days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
        daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六", "周日"],
        daysMin: ["日", "一", "二", "三", "四", "五", "六", "日"],
        months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        monthsShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        today: "今日",
        suffix: [],
        meridiem: []
    };
    dates['zh-tw'] = {
        days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
        daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六", "周日"],
        daysMin: ["日", "一", "二", "三", "四", "五", "六", "日"],
        months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        monthsShort: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
        today: "今天",
        suffix: [],
        meridiem: ["上午", "下午"]
    };

    var DPGlobal = {
        modes: [{
            clsName: 'minutes',
            navFnc: 'Hours',
            navStep: 1
        }, {
            clsName: 'hours',
            navFnc: 'Date',
            navStep: 1
        }, {
            clsName: 'days',
            navFnc: 'Month',
            navStep: 1
        }, {
            clsName: 'months',
            navFnc: 'FullYear',
            navStep: 1
        }, {
            clsName: 'years',
            navFnc: 'FullYear',
            navStep: 10
        }],
        isLeapYear: function(year) {
            return(((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0))
        },
        getDaysInMonth: function(year, month) {
            return [31, (DPGlobal.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month]
        },
        getDefaultFormat: function(type, field) {
            if(type == "standard") {
                if(field == 'input')
                    return 'yyyy-mm-dd hh:ii';
                else
                    return 'yyyy-mm-dd hh:ii:ss';
            } else if(type == "php") {
                if(field == 'input')
                    return 'Y-m-d H:i';
                else
                    return 'Y-m-d H:i:s';
            } else {
                throw new Error("Invalid format type.");
            }
        },
        validParts: function(type) {
            if(type == "standard") {
                return /hh?|HH?|p|P|ii?|ss?|dd?|DD?|mm?|MM?|yy(?:yy)?/g;
            } else if(type == "php") {
                return /[dDjlNwzFmMnStyYaABgGhHis]/g;
            } else {
                throw new Error("Invalid format type.");
            }
        },
        nonpunctuation: /[^ -\/:-@\[-`{-~\t\n\rTZ]+/g,
        parseFormat: function(format, type) {
            // IE treats \0 as a string end in inputs (truncating the value),
            // so it's a bad format delimiter, anyway
            var separators = format.replace(this.validParts(type), '\0').split('\0'),
                parts = format.match(this.validParts(type));
            if(!separators || !separators.length || !parts || parts.length == 0) {
                throw new Error("Invalid date format.");
            }
            return {
                separators: separators,
                parts: parts
            };
        },
        parseDate: function(date, format, language, type) {
            if(date instanceof Date) {
                var dateUTC = new Date(date.valueOf() - date.getTimezoneOffset() * 60000);
                dateUTC.setMilliseconds(0);
                return dateUTC;
            }
            if(/^\d{4}\-\d{1,2}\-\d{1,2}$/.test(date)) {
                format = this.parseFormat('yyyy-mm-dd', type);
            }
            if(/^\d{4}\-\d{1,2}\-\d{1,2}[T ]\d{1,2}\:\d{1,2}$/.test(date)) {
                format = this.parseFormat('yyyy-mm-dd hh:ii', type);
            }
            if(/^\d{4}\-\d{1,2}\-\d{1,2}[T ]\d{1,2}\:\d{1,2}\:\d{1,2}[Z]{0,1}$/.test(date)) {
                format = this.parseFormat('yyyy-mm-dd hh:ii:ss', type);
            }
            if(/^[-+]\d+[dmwy]([\s,]+[-+]\d+[dmwy])*$/.test(date)) {
                var part_re = /([-+]\d+)([dmwy])/,
                    parts = date.match(/([-+]\d+)([dmwy])/g),
                    part, dir;
                date = new Date();
                for(var i = 0; i < parts.length; i++) {
                    part = part_re.exec(parts[i]);
                    dir = parseInt(part[1]);
                    switch(part[2]) {
                        case 'd':
                            date.setUTCDate(date.getUTCDate() + dir);
                            break;
                        case 'm':
                            date = Datetimepicker.prototype.moveMonth.call(Datetimepicker.prototype, date, dir);
                            break;
                        case 'w':
                            date.setUTCDate(date.getUTCDate() + dir * 7);
                            break;
                        case 'y':
                            date = Datetimepicker.prototype.moveYear.call(Datetimepicker.prototype, date, dir);
                            break;
                    }
                }
                return UTCDate(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds(), 0);
            }
            var parts = date && date.match(this.nonpunctuation) || [],
                date = new Date(0, 0, 0, 0, 0, 0, 0),
                parsed = {},
                setters_order = ['hh', 'h', 'ii', 'i', 'ss', 's', 'yyyy', 'yy', 'M', 'MM', 'm', 'mm', 'D', 'DD', 'd', 'dd', 'H', 'HH', 'p', 'P'],
                setters_map = {
                    hh: function(d, v) {
                        return d.setUTCHours(v);
                    },
                    h: function(d, v) {
                        return d.setUTCHours(v);
                    },
                    HH: function(d, v) {
                        return d.setUTCHours(v == 12 ? 0 : v);
                    },
                    H: function(d, v) {
                        return d.setUTCHours(v == 12 ? 0 : v);
                    },
                    ii: function(d, v) {
                        return d.setUTCMinutes(v);
                    },
                    i: function(d, v) {
                        return d.setUTCMinutes(v);
                    },
                    ss: function(d, v) {
                        return d.setUTCSeconds(v);
                    },
                    s: function(d, v) {
                        return d.setUTCSeconds(v);
                    },
                    yyyy: function(d, v) {
                        return d.setUTCFullYear(v);
                    },
                    yy: function(d, v) {
                        return d.setUTCFullYear(2000 + v);
                    },
                    m: function(d, v) {
                        v -= 1;
                        while(v < 0) v += 12;
                        v %= 12;
                        d.setUTCMonth(v);
                        while(d.getUTCMonth() != v)
                            d.setUTCDate(d.getUTCDate() - 1);
                        return d;
                    },
                    d: function(d, v) {
                        return d.setUTCDate(v);
                    },
                    p: function(d, v) {
                        return d.setUTCHours(v == 1 ? d.getUTCHours() + 12 : d.getUTCHours());
                    }
                },
                val, filtered, part;
            setters_map['M'] = setters_map['MM'] = setters_map['mm'] = setters_map['m'];
            setters_map['dd'] = setters_map['d'];
            setters_map['P'] = setters_map['p'];
            date = UTCDate(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(), date.getSeconds());
            if(parts.length == format.parts.length) {
                for(var i = 0, cnt = format.parts.length; i < cnt; i++) {
                    val = parseInt(parts[i], 10);
                    part = format.parts[i];
                    if(isNaN(val)) {
                        switch(part) {
                            case 'MM':
                                filtered = $(dates[language].months).filter(function() {
                                    var m = this.slice(0, parts[i].length),
                                        p = parts[i].slice(0, m.length);
                                    return m == p;
                                });
                                val = $.inArray(filtered[0], dates[language].months) + 1;
                                break;
                            case 'M':
                                filtered = $(dates[language].monthsShort).filter(function() {
                                    var m = this.slice(0, parts[i].length),
                                        p = parts[i].slice(0, m.length);
                                    return m == p;
                                });
                                val = $.inArray(filtered[0], dates[language].monthsShort) + 1;
                                break;
                            case 'p':
                            case 'P':
                                val = $.inArray(parts[i].toLowerCase(), dates[language].meridiem);
                                break;
                        }
                    }
                    parsed[part] = val;
                }
                for(var i = 0, s; i < setters_order.length; i++) {
                    s = setters_order[i];
                    if(s in parsed && !isNaN(parsed[s]))
                        setters_map[s](date, parsed[s])
                }
            }
            return date;
        },
        formatDate: function(date, format, language, type) {
            if(date == null) {
                return '';
            }
            var val;
            if(type == 'standard') {
                val = {
                    // year
                    yy: date.getUTCFullYear().toString().substring(2),
                    yyyy: date.getUTCFullYear(),
                    // month
                    m: date.getUTCMonth() + 1,
                    M: dates[language].monthsShort[date.getUTCMonth()],
                    MM: dates[language].months[date.getUTCMonth()],
                    // day
                    d: date.getUTCDate(),
                    D: dates[language].daysShort[date.getUTCDay()],
                    DD: dates[language].days[date.getUTCDay()],
                    p: (dates[language].meridiem.length == 2 ? dates[language].meridiem[date.getUTCHours() < 12 ? 0 : 1] : ''),
                    // hour
                    h: date.getUTCHours(),
                    // minute
                    i: date.getUTCMinutes(),
                    // second
                    s: date.getUTCSeconds()
                };

                if(dates[language].meridiem.length == 2) {
                    val.H = (val.h % 12 == 0 ? 12 : val.h % 12);
                } else {
                    val.H = val.h;
                }
                val.HH = (val.H < 10 ? '0' : '') + val.H;
                val.P = val.p.toUpperCase();
                val.hh = (val.h < 10 ? '0' : '') + val.h;
                val.ii = (val.i < 10 ? '0' : '') + val.i;
                val.ss = (val.s < 10 ? '0' : '') + val.s;
                val.dd = (val.d < 10 ? '0' : '') + val.d;
                val.mm = (val.m < 10 ? '0' : '') + val.m;
            } else if(type == 'php') {
                // php format
                val = {
                    // year
                    y: date.getUTCFullYear().toString().substring(2),
                    Y: date.getUTCFullYear(),
                    // month
                    F: dates[language].months[date.getUTCMonth()],
                    M: dates[language].monthsShort[date.getUTCMonth()],
                    n: date.getUTCMonth() + 1,
                    t: DPGlobal.getDaysInMonth(date.getUTCFullYear(), date.getUTCMonth()),
                    // day
                    j: date.getUTCDate(),
                    l: dates[language].days[date.getUTCDay()],
                    D: dates[language].daysShort[date.getUTCDay()],
                    w: date.getUTCDay(), // 0 -> 6
                    N: (date.getUTCDay() == 0 ? 7 : date.getUTCDay()), // 1 -> 7
                    S: (date.getUTCDate() % 10 <= dates[language].suffix.length ? dates[language].suffix[date.getUTCDate() % 10 - 1] : ''),
                    // hour
                    a: (dates[language].meridiem.length == 2 ? dates[language].meridiem[date.getUTCHours() < 12 ? 0 : 1] : ''),
                    g: (date.getUTCHours() % 12 == 0 ? 12 : date.getUTCHours() % 12),
                    G: date.getUTCHours(),
                    // minute
                    i: date.getUTCMinutes(),
                    // second
                    s: date.getUTCSeconds()
                };
                val.m = (val.n < 10 ? '0' : '') + val.n;
                val.d = (val.j < 10 ? '0' : '') + val.j;
                val.A = val.a.toString().toUpperCase();
                val.h = (val.g < 10 ? '0' : '') + val.g;
                val.H = (val.G < 10 ? '0' : '') + val.G;
                val.i = (val.i < 10 ? '0' : '') + val.i;
                val.s = (val.s < 10 ? '0' : '') + val.s;
            } else {
                throw new Error("Invalid format type.");
            }
            var date = [],
                seps = $.extend([], format.separators);
            for(var i = 0, cnt = format.parts.length; i < cnt; i++) {
                if(seps.length) {
                    date.push(seps.shift());
                }
                date.push(val[format.parts[i]]);
            }
            if(seps.length) {
                date.push(seps.shift());
            }
            return date.join('');
        },
        convertViewMode: function(viewMode) {
            switch(viewMode) {
                case 4:
                case 'decade':
                    viewMode = 4;
                    break;
                case 3:
                case 'year':
                    viewMode = 3;
                    break;
                case 2:
                case 'month':
                    viewMode = 2;
                    break;
                case 1:
                case 'day':
                    viewMode = 1;
                    break;
                case 0:
                case 'hour':
                    viewMode = 0;
                    break;
            }

            return viewMode;
        },
        headTemplate: '<thead>' +
            '<tr>' +
            '<th class="prev"><i class="icon-arrow-left"/></th>' +
            '<th colspan="5" class="switch"></th>' +
            '<th class="next"><i class="icon-arrow-right"/></th>' +
            '</tr>' +
            '</thead>',
        contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>',
        footTemplate: '<tfoot><tr><th colspan="7" class="today"></th></tr></tfoot>'
    };
    DPGlobal.template = '<div class="datetimepicker">' +
        '<div class="datetimepicker-minutes">' +
        '<table class=" table-condensed">' +
        DPGlobal.headTemplate +
        DPGlobal.contTemplate +
        DPGlobal.footTemplate +
        '</table>' +
        '</div>' +
        '<div class="datetimepicker-hours">' +
        '<table class=" table-condensed">' +
        DPGlobal.headTemplate +
        DPGlobal.contTemplate +
        DPGlobal.footTemplate +
        '</table>' +
        '</div>' +
        '<div class="datetimepicker-days">' +
        '<table class=" table-condensed">' +
        DPGlobal.headTemplate +
        '<tbody></tbody>' +
        DPGlobal.footTemplate +
        '</table>' +
        '</div>' +
        '<div class="datetimepicker-months">' +
        '<table class="table-condensed">' +
        DPGlobal.headTemplate +
        DPGlobal.contTemplate +
        DPGlobal.footTemplate +
        '</table>' +
        '</div>' +
        '<div class="datetimepicker-years">' +
        '<table class="table-condensed">' +
        DPGlobal.headTemplate +
        DPGlobal.contTemplate +
        DPGlobal.footTemplate +
        '</table>' +
        '</div>' +
        '</div>';
    $.fn.datetimepicker.DPGlobal = DPGlobal;

    /* DATETIMEPICKER NO CONFLICT
     * =================== */

    $.fn.datetimepicker.noConflict = function() {
        $.fn.datetimepicker = old;
        return this;
    };

    /* DATETIMEPICKER DATA-API
     * ================== */

    $(document).on(
        'focus.datetimepicker.data-api click.datetimepicker.data-api',
        '[data-provide="datetimepicker"]',
        function(e) {
            var $this = $(this);
            if($this.data('datetimepicker')) return;
            e.preventDefault();
            // component click requires us to explicitly show it
            $this.datetimepicker('show');
        }
    );
    $(function() {
        $('[data-provide="datetimepicker-inline"]').datetimepicker();
    });

}(window.jQuery);


/* ========================================================================
 * Bootbox: bootbox.js [v4.4.0]
 * http://bootboxjs.com/
 *
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * official version in the future.
 * http://zui.sexy
 * ========================================================================
 * http://bootboxjs.com/license.txt
 * Improvement in ZUI:
 * 1. Determine client language and apply setting automatically.
 * 2. Changed button position.
 * ======================================================================== */

/*! bootbox.js v4.4.0 http://bootboxjs.com/license.txt */

// @see https://github.com/makeusabrew/bootbox/issues/180
// @see https://github.com/makeusabrew/bootbox/issues/186
(function(root, factory) {

    'use strict';
    if(typeof define === "function" && define.amd) {
        // AMD. Register as an anonymous module.
        define(["jquery"], factory);
    } else if(typeof exports === "object") {
        // Node. Does not work with strict CommonJS, but
        // only CommonJS-like environments that support module.exports,
        // like Node.
        module.exports = factory(require("jquery"));
    } else {
        // Browser globals (root is window)
        root.bootbox = factory(root.jQuery);
    }

}(this, function init($, undefined) {

    'use strict';

    // the base DOM structure needed to create a modal
    var templates = {
        dialog: "<div class='bootbox modal' tabindex='-1' role='dialog'>" +
            "<div class='modal-dialog'>" +
            "<div class='modal-content'>" +
            "<div class='modal-body'><div class='bootbox-body'></div></div>" +
            "</div>" +
            "</div>" +
            "</div>",
        header: "<div class='modal-header'>" +
            "<h4 class='modal-title'></h4>" +
            "</div>",
        footer: "<div class='modal-footer'></div>",
        closeButton: "<button type='button' class='bootbox-close-button close' data-dismiss='modal' aria-hidden='true'>&times;</button>",
        form: "<form class='bootbox-form'></form>",
        inputs: {
            text: "<input class='bootbox-input bootbox-input-text form-control' autocomplete=off type=text />",
            textarea: "<textarea class='bootbox-input bootbox-input-textarea form-control'></textarea>",
            email: "<input class='bootbox-input bootbox-input-email form-control' autocomplete='off' type='email' />",
            select: "<select class='bootbox-input bootbox-input-select form-control'></select>",
            checkbox: "<div class='checkbox'><label><input class='bootbox-input bootbox-input-checkbox' type='checkbox' /></label></div>",
            date: "<input class='bootbox-input bootbox-input-date form-control' autocomplete=off type='date' />",
            time: "<input class='bootbox-input bootbox-input-time form-control' autocomplete=off type='time' />",
            number: "<input class='bootbox-input bootbox-input-number form-control' autocomplete=off type='number' />",
            password: "<input class='bootbox-input bootbox-input-password form-control' autocomplete='off' type='password' />"
        }
    };

    var defaults = {
        // default language
        locale: $.zui && $.zui.clientLang ? $.zui.clientLang() : 'zh_cn',
        // show backdrop or not. Default to static so user has to interact with dialog
        backdrop: "static",
        // animate the modal in/out
        animate: true,
        // additional class string applied to the top level dialog
        className: null,
        // whether or not to include a close button
        closeButton: true,
        // show the dialog immediately by default
        show: true,
        // dialog container
        container: "body"
    };

    // our public object; augmented after our private API
    var exports = {};

    /**
     * @private
     */
    function _t(key) {
        var locale = locales[defaults.locale];
        return locale ? locale[key] : locales.en[key];
    }

    function processCallback(e, dialog, callback) {
        e.stopPropagation();
        e.preventDefault();

        // by default we assume a callback will get rid of the dialog,
        // although it is given the opportunity to override this

        // so, if the callback can be invoked and it *explicitly returns false*
        // then we'll set a flag to keep the dialog active...
        var preserveDialog = $.isFunction(callback) && callback.call(dialog, e) === false;

        // ... otherwise we'll bin it
        if(!preserveDialog) {
            dialog.modal("hide");
        }
    }

    function getKeyLength(obj) {
        // @TODO defer to Object.keys(x).length if available?
        var k, t = 0;
        for(k in obj) {
            t++;
        }
        return t;
    }

    function each(collection, iterator) {
        var index = 0;
        $.each(collection, function(key, value) {
            iterator(key, value, index++);
        });
    }

    function sanitize(options) {
        var buttons;
        var total;

        if(typeof options !== "object") {
            throw new Error("Please supply an object of options");
        }

        if(!options.message) {
            throw new Error("Please specify a message");
        }

        // make sure any supplied options take precedence over defaults
        options = $.extend({}, defaults, options);

        if(!options.buttons) {
            options.buttons = {};
        }

        buttons = options.buttons;

        total = getKeyLength(buttons);

        each(buttons, function(key, button, index) {

            if($.isFunction(button)) {
                // short form, assume value is our callback. Since button
                // isn't an object it isn't a reference either so re-assign it
                button = buttons[key] = {
                    callback: button
                };
            }

            // before any further checks make sure by now button is the correct type
            if($.type(button) !== "object") {
                throw new Error("button with key " + key + " must be an object");
            }

            if(!button.label) {
                // the lack of an explicit label means we'll assume the key is good enough
                button.label = key;
            }

            if(!button.className) {
                if((total === 2 && (key === 'ok' || key === 'confirm')) || total === 1) {
                    // always add a primary to the main option in a two-button dialog
                    button.className = "btn-primary";
                } else {
                    button.className = "btn-default";
                }
            }
        });

        return options;
    }

    /**
     * map a flexible set of arguments into a single returned object
     * if args.length is already one just return it, otherwise
     * use the properties argument to map the unnamed args to
     * object properties
     * so in the latter case:
     * mapArguments(["foo", $.noop], ["message", "callback"])
     * -> { message: "foo", callback: $.noop }
     */
    function mapArguments(args, properties) {
        var argn = args.length;
        var options = {};

        if(argn < 1 || argn > 2) {
            throw new Error("Invalid argument length");
        }

        if(argn === 2 || typeof args[0] === "string") {
            options[properties[0]] = args[0];
            options[properties[1]] = args[1];
        } else {
            options = args[0];
        }

        return options;
    }

    /**
     * merge a set of default dialog options with user supplied arguments
     */
    function mergeArguments(defaults, args, properties) {
        return $.extend(
            // deep merge
            true,
            // ensure the target is an empty, unreferenced object
            {},
            // the base options object for this type of dialog (often just buttons)
            defaults,
            // args could be an object or array; if it's an array properties will
            // map it to a proper options object
            mapArguments(
                args,
                properties
            )
        );
    }

    /**
     * this entry-level method makes heavy use of composition to take a simple
     * range of inputs and return valid options suitable for passing to bootbox.dialog
     */
    function mergeDialogOptions(className, labels, properties, args) {
        //  build up a base set of dialog properties
        var baseOptions = {
            className: "bootbox-" + className,
            buttons: createLabels.apply(null, labels)
        };

        // ensure the buttons properties generated, *after* merging
        // with user args are still valid against the supplied labels
        return validateButtons(
            // merge the generated base properties with user supplied arguments
            mergeArguments(
                baseOptions,
                args,
                // if args.length > 1, properties specify how each arg maps to an object key
                properties
            ),
            labels
        );
    }

    /**
     * from a given list of arguments return a suitable object of button labels
     * all this does is normalise the given labels and translate them where possible
     * e.g. "ok", "confirm" -> { ok: "OK, cancel: "Annuleren" }
     */
    function createLabels() {
        var buttons = {};

        for(var i = 0, j = arguments.length; i < j; i++) {
            var argument = arguments[i];
            var key = argument.toLowerCase();
            var value = argument.toUpperCase();

            buttons[key] = {
                label: _t(value)
            };
        }

        return buttons;
    }

    function validateButtons(options, buttons) {
        var allowedButtons = {};
        each(buttons, function(key, value) {
            allowedButtons[value] = true;
        });

        each(options.buttons, function(key) {
            if(allowedButtons[key] === undefined) {
                throw new Error("button key " + key + " is not allowed (options are " + buttons.join("\n") + ")");
            }
        });

        return options;
    }

    exports.alert = function() {
        var options;

        options = mergeDialogOptions("alert", ["ok"], ["message", "callback"], arguments);

        if(options.callback && !$.isFunction(options.callback)) {
            throw new Error("alert requires callback property to be a function when provided");
        }

        /**
         * overrides
         */
        options.buttons.ok.callback = options.onEscape = function() {
            if($.isFunction(options.callback)) {
                return options.callback.call(this);
            }
            return true;
        };

        return exports.dialog(options);
    };

    exports.confirm = function() {
        var options;

        // ZUI change begin
        options = mergeDialogOptions("confirm", ["confirm", "cancel"], ["message", "callback"], arguments);
        // OLD WAY: options = mergeDialogOptions("confirm", ["cancel", "confirm"], ["message", "callback"], arguments);
        // ZUI change end

        /**
         * overrides; undo anything the user tried to set they shouldn't have
         */
        options.buttons.cancel.callback = options.onEscape = function() {
            return options.callback.call(this, false);
        };

        options.buttons.confirm.callback = function() {
            return options.callback.call(this, true);
        };

        // confirm specific validation
        if(!$.isFunction(options.callback)) {
            throw new Error("confirm requires a callback");
        }

        return exports.dialog(options);
    };

    exports.prompt = function() {
        var options;
        var defaults;
        var dialog;
        var form;
        var input;
        var shouldShow;
        var inputOptions;

        // we have to create our form first otherwise
        // its value is undefined when gearing up our options
        // @TODO this could be solved by allowing message to
        // be a function instead...
        form = $(templates.form);

        // prompt defaults are more complex than others in that
        // users can override more defaults
        // @TODO I don't like that prompt has to do a lot of heavy
        // lifting which mergeDialogOptions can *almost* support already
        // just because of 'value' and 'inputType' - can we refactor?
        defaults = {
            className: "bootbox-prompt",
            buttons: createLabels("cancel", "confirm"),
            value: "",
            inputType: "text"
        };

        options = validateButtons(
            // ZUI change begin
            mergeArguments(defaults, arguments, ["title", "callback"]), ["confirm", "cancel"]
            // OLD WAY: mergeArguments(defaults, arguments, ["title", "callback"]), ["cancel", "confirm"]arguments);
            // ZUI change end
        );

        // capture the user's show value; we always set this to false before
        // spawning the dialog to give us a chance to attach some handlers to
        // it, but we need to make sure we respect a preference not to show it
        shouldShow = (options.show === undefined) ? true : options.show;

        /**
         * overrides; undo anything the user tried to set they shouldn't have
         */
        options.message = form;

        options.buttons.cancel.callback = options.onEscape = function() {
            return options.callback.call(this, null);
        };

        options.buttons.confirm.callback = function() {
            var value;

            switch(options.inputType) {
                case "text":
                case "textarea":
                case "email":
                case "select":
                case "date":
                case "time":
                case "number":
                case "password":
                    value = input.val();
                    break;

                case "checkbox":
                    var checkedItems = input.find("input:checked");

                    // we assume that checkboxes are always multiple,
                    // hence we default to an empty array
                    value = [];

                    each(checkedItems, function(_, item) {
                        value.push($(item).val());
                    });
                    break;
            }

            return options.callback.call(this, value);
        };

        options.show = false;

        // prompt specific validation
        if(!options.title) {
            throw new Error("prompt requires a title");
        }

        if(!$.isFunction(options.callback)) {
            throw new Error("prompt requires a callback");
        }

        if(!templates.inputs[options.inputType]) {
            throw new Error("invalid prompt type");
        }

        // create the input based on the supplied type
        input = $(templates.inputs[options.inputType]);

        switch(options.inputType) {
            case "text":
            case "textarea":
            case "email":
            case "date":
            case "time":
            case "number":
            case "password":
                input.val(options.value);
                break;

            case "select":
                var groups = {};
                inputOptions = options.inputOptions || [];

                if(!$.isArray(inputOptions)) {
                    throw new Error("Please pass an array of input options");
                }

                if(!inputOptions.length) {
                    throw new Error("prompt with select requires options");
                }

                each(inputOptions, function(_, option) {

                    // assume the element to attach to is the input...
                    var elem = input;

                    if(option.value === undefined || option.text === undefined) {
                        throw new Error("given options in wrong format");
                    }

                    // ... but override that element if this option sits in a group

                    if(option.group) {
                        // initialise group if necessary
                        if(!groups[option.group]) {
                            groups[option.group] = $("<optgroup/>").attr("label", option.group);
                        }

                        elem = groups[option.group];
                    }

                    elem.append("<option value='" + option.value + "'>" + option.text + "</option>");
                });

                each(groups, function(_, group) {
                    input.append(group);
                });

                // safe to set a select's value as per a normal input
                input.val(options.value);
                break;

            case "checkbox":
                var values = $.isArray(options.value) ? options.value : [options.value];
                inputOptions = options.inputOptions || [];

                if(!inputOptions.length) {
                    throw new Error("prompt with checkbox requires options");
                }

                if(!inputOptions[0].value || !inputOptions[0].text) {
                    throw new Error("given options in wrong format");
                }

                // checkboxes have to nest within a containing element, so
                // they break the rules a bit and we end up re-assigning
                // our 'input' element to this container instead
                input = $("<div/>");

                each(inputOptions, function(_, option) {
                    var checkbox = $(templates.inputs[options.inputType]);

                    checkbox.find("input").attr("value", option.value);
                    checkbox.find("label").append(option.text);

                    // we've ensured values is an array so we can always iterate over it
                    each(values, function(_, value) {
                        if(value === option.value) {
                            checkbox.find("input").prop("checked", true);
                        }
                    });

                    input.append(checkbox);
                });
                break;
        }

        // @TODO provide an attributes option instead
        // and simply map that as keys: vals
        if(options.placeholder) {
            input.attr("placeholder", options.placeholder);
        }

        if(options.pattern) {
            input.attr("pattern", options.pattern);
        }

        if(options.maxlength) {
            input.attr("maxlength", options.maxlength);
        }

        // now place it in our form
        form.append(input);

        form.on("submit", function(e) {
            e.preventDefault();
            // Fix for SammyJS (or similar JS routing library) hijacking the form post.
            e.stopPropagation();
            // @TODO can we actually click *the* button object instead?
            // e.g. buttons.confirm.click() or similar
            dialog.find(".btn-primary").click();
        });

        dialog = exports.dialog(options);

        // clear the existing handler focusing the submit button...
        dialog.off("shown.zui.modal");

        // ...and replace it with one focusing our input, if possible
        dialog.on("shown.zui.modal", function() {
            // need the closure here since input isn't
            // an object otherwise
            input.focus();
        });

        if(shouldShow === true) {
            dialog.modal("show");
        }

        return dialog;
    };

    exports.dialog = function(options) {
        options = sanitize(options);


        var dialog = $(templates.dialog);
        var innerDialog = dialog.find(".modal-dialog");
        var body = dialog.find(".modal-body");
        var buttons = options.buttons;
        var buttonStr = "";
        var callbacks = {
            onEscape: options.onEscape
        };

        if($.fn.modal === undefined) {
            throw new Error(
                "$.fn.modal is not defined; please double check you have included " +
                "the Bootstrap JavaScript library. See http://getbootstrap.com/javascript/ " +
                "for more details."
            );
        }

        each(buttons, function(key, button) {

            // @TODO I don't like this string appending to itself; bit dirty. Needs reworking
            // can we just build up button elements instead? slower but neater. Then button
            // can just become a template too
            buttonStr += "<button data-bb-handler='" + key + "' type='button' class='btn " + button.className + "'>" + button.label + "</button>";
            callbacks[key] = button.callback;
        });

        body.find(".bootbox-body").html(options.message);

        if(options.animate === true) {
            dialog.addClass("fade");
        }

        if(options.className) {
            dialog.addClass(options.className);
        }

        if(options.size === "large") {
            innerDialog.addClass("modal-lg");
        } else if(options.size === "small") {
            innerDialog.addClass("modal-sm");
        }

        if(options.title) {
            body.before(templates.header);
        }

        if(options.closeButton) {
            var closeButton = $(templates.closeButton);

            if(options.title) {
                dialog.find(".modal-header").prepend(closeButton);
            } else {
                closeButton.css("margin-top", "-10px").prependTo(body);
            }
        }

        if(options.title) {
            dialog.find(".modal-title").html(options.title);
        }

        if(buttonStr.length) {
            body.after(templates.footer);
            dialog.find(".modal-footer").html(buttonStr);
        }


        /**
         * Bootstrap event listeners; used handle extra
         * setup & teardown required after the underlying
         * modal has performed certain actions
         */

        dialog.on("hidden.zui.modal", function(e) {
            // ensure we don't accidentally intercept hidden events triggered
            // by children of the current dialog. We shouldn't anymore now BS
            // namespaces its events; but still worth doing
            if(e.target === this) {
                dialog.remove();
            }
        });

        /*
        dialog.on("show.zui.modal", function() {
          // sadly this doesn't work; show is called *just* before
          // the backdrop is added so we'd need a setTimeout hack or
          // otherwise... leaving in as would be nice
          if (options.backdrop) {
            dialog.next(".modal-backdrop").addClass("bootbox-backdrop");
          }
        });
        */

        dialog.on("shown.zui.modal", function() {
            dialog.find(".btn-primary:first").focus();
        });

        /**
         * Bootbox event listeners; experimental and may not last
         * just an attempt to decouple some behaviours from their
         * respective triggers
         */

        if(options.backdrop !== "static") {
            // A boolean true/false according to the Bootstrap docs
            // should show a dialog the user can dismiss by clicking on
            // the background.
            // We always only ever pass static/false to the actual
            // $.modal function because with `true` we can't trap
            // this event (the .modal-backdrop swallows it)
            // However, we still want to sort of respect true
            // and invoke the escape mechanism instead
            dialog.on("click.dismiss.zui.modal", function(e) {
                // @NOTE: the target varies in >= 3.3.x releases since the modal backdrop
                // moved *inside* the outer dialog rather than *alongside* it
                if(dialog.children(".modal-backdrop").length) {
                    e.currentTarget = dialog.children(".modal-backdrop").get(0);
                }

                if(e.target !== e.currentTarget) {
                    return;
                }

                dialog.trigger("escape.close.bb");
            });
        }

        dialog.on("escape.close.bb", function(e) {
            if(callbacks.onEscape) {
                processCallback(e, dialog, callbacks.onEscape);
            }
        });

        /**
         * Standard jQuery event listeners; used to handle user
         * interaction with our dialog
         */

        dialog.on("click", ".modal-footer button", function(e) {
            var callbackKey = $(this).data("bb-handler");

            processCallback(e, dialog, callbacks[callbackKey]);
        });

        dialog.on("click", ".bootbox-close-button", function(e) {
            // onEscape might be falsy but that's fine; the fact is
            // if the user has managed to click the close button we
            // have to close the dialog, callback or not
            processCallback(e, dialog, callbacks.onEscape);
        });

        dialog.on("keyup", function(e) {
            if(e.which === 27) {
                dialog.trigger("escape.close.bb");
            }
        });

        // the remainder of this method simply deals with adding our
        // dialogent to the DOM, augmenting it with Bootstrap's modal
        // functionality and then giving the resulting object back
        // to our caller

        $(options.container).append(dialog);

        dialog.modal({
            backdrop: options.backdrop ? "static" : false,
            keyboard: false,
            show: false
        });

        if(options.show) {
            dialog.modal("show");
        }

        // @TODO should we return the raw element here or should
        // we wrap it in an object on which we can expose some neater
        // methods, e.g. var d = bootbox.alert(); d.hide(); instead
        // of d.modal("hide");

        /*
         function BBDialog(elem) {
           this.elem = elem;
         }

         BBDialog.prototype = {
           hide: function() {
             return this.elem.modal("hide");
           },
           show: function() {
             return this.elem.modal("show");
           }
         };
         */

        return dialog;

    };

    exports.setDefaults = function() {
        var values = {};

        if(arguments.length === 2) {
            // allow passing of single key/value...
            values[arguments[0]] = arguments[1];
        } else {
            // ... and as an object too
            values = arguments[0];
        }

        $.extend(defaults, values);
    };

    exports.hideAll = function() {
        $(".bootbox").modal("hide");

        return exports;
    };


    /**
     * standard locales. Please add more according to ISO 639-1 standard. Multiple language variants are
     * unlikely to be required. If this gets too large it can be split out into separate JS files.
     */
    var locales = {
        en: {
            OK: "OK",
            CANCEL: "Cancel",
            CONFIRM: "OK"
        },
        zh_cn: {
            OK: "确认",
            CANCEL: "取消",
            CONFIRM: "确认"
        },
        zh_tw: {
            OK: "確認",
            CANCEL: "取消",
            CONFIRM: "確認"
        }
    };

    exports.addLocale = function(name, values) {
        $.each(["OK", "CANCEL", "CONFIRM"], function(_, v) {
            if(!values[v]) {
                throw new Error("Please supply a translation for '" + v + "'");
            }
        });

        locales[name] = {
            OK: values.OK,
            CANCEL: values.CANCEL,
            CONFIRM: values.CONFIRM
        };

        return exports;
    };

    exports.removeLocale = function(name) {
        delete locales[name];

        return exports;
    };

    exports.setLocale = function(name) {
        return exports.setDefaults("locale", name);
    };

    exports.init = function(_$) {
        return init(_$ || $);
    };

    return exports;
}));

/* ========================================================================
 * Chosen: chosen.js [version 1.1.0]
 * https://github.com/harvesthq/chosen
 *
 * Chosen, a Select Box Enhancer for jQuery and Prototype
 * by Patrick Filler for Harvest, http://getharvest.com
 *
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * official version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2011 Harvest http://getharvest.com
 * MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md
 * ========================================================================
 * Improvement in ZUI:
 * 1. New option 'drop_direction': 'auto' | 'top' | 'bottom';
 * 2. Enhance the search experience, support search items by custom data
 *    with 'data-keys=*' attribute in option;
 * 3. ‘middle_highlight’ option can make hightlight item in the middle of
 *    the dropdown menu
 * ======================================================================== */


/*!
Chosen, a Select Box Enhancer for jQuery and Prototype
by Patrick Filler for Harvest, http://getharvest.com

Version 1.1.0
Full source at https://github.com/harvesthq/chosen
Copyright (c) 2011 Harvest http://getharvest.com

MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md
*/


(function() {
    var $, AbstractChosen, Chosen, SelectParser, _ref,
        __hasProp = {}.hasOwnProperty,
        __extends = function(child, parent) {
            for(var key in parent) {
                if(__hasProp.call(parent, key)) child[key] = parent[key];
            }

            function ctor() {
                this.constructor = child;
            }
            ctor.prototype = parent.prototype;
            child.prototype = new ctor();
            child.__super__ = parent.prototype;
            return child;
        };

    var LANGUAGE = {
        zh_cn: {
            no_results_text: "没有找到"
        },
        zh_tw: {
            no_results_text: "沒有找到"
        },
        en: {
            no_results_text: "No results match"
        }
    };

    SelectParser = (function() {
        function SelectParser() {
            this.options_index = 0;
            this.parsed = [];
        }

        SelectParser.prototype.add_node = function(child) {
            if(child.nodeName.toUpperCase() === "OPTGROUP") {
                return this.add_group(child);
            } else {
                return this.add_option(child);
            }
        };

        SelectParser.prototype.add_group = function(group) {
            var group_position, option, _i, _len, _ref, _results;
            group_position = this.parsed.length;
            this.parsed.push({
                array_index: group_position,
                group: true,
                label: this.escapeExpression(group.label),
                children: 0,
                disabled: group.disabled,
                title: group.title,
                search_keys: ($.trim(group.getAttribute('data-keys') || '')).replace(/,/g, ' ')
            });
            _ref = group.childNodes;
            _results = [];
            for(_i = 0, _len = _ref.length; _i < _len; _i++) {
                option = _ref[_i];
                _results.push(this.add_option(option, group_position, group.disabled));
            }
            return _results;
        };

        SelectParser.prototype.add_option = function(option, group_position, group_disabled) {
            if(option.nodeName.toUpperCase() === "OPTION") {
                if(option.text !== "") {
                    if(group_position != null) {
                        this.parsed[group_position].children += 1;
                    }
                    this.parsed.push({
                        array_index: this.parsed.length,
                        options_index: this.options_index,
                        value: option.value,
                        text: option.text,
                        title: option.title,
                        html: option.innerHTML,
                        selected: option.selected,
                        disabled: group_disabled === true ? group_disabled : option.disabled,
                        group_array_index: group_position,
                        classes: option.className,
                        style: option.style.cssText,
                        search_keys: ($.trim(option.getAttribute('data-keys') || '') + option.value).replace(/,/, ' ')
                    });
                } else {
                    this.parsed.push({
                        array_index: this.parsed.length,
                        options_index: this.options_index,
                        empty: true
                    });
                }
                return this.options_index += 1;
            }
        };

        SelectParser.prototype.escapeExpression = function(text) {
            var map, unsafe_chars;
            if((text == null) || text === false) {
                return "";
            }
            if(!/[\&\<\>\"\'\`]/.test(text)) {
                return text;
            }
            map = {
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#x27;",
                "`": "&#x60;"
            };
            unsafe_chars = /&(?!\w+;)|[\<\>\"\'\`]/g;
            return text.replace(unsafe_chars, function(chr) {
                return map[chr] || "&amp;";
            });
        };

        return SelectParser;

    })();

    SelectParser.select_to_array = function(select) {
        var child, parser, _i, _len, _ref;
        parser = new SelectParser();
        _ref = select.childNodes;
        for(_i = 0, _len = _ref.length; _i < _len; _i++) {
            child = _ref[_i];
            parser.add_node(child);
        }
        return parser.parsed;
    };

    AbstractChosen = (function() {
        function AbstractChosen(form_field, options) {
            this.form_field = form_field;
            this.options = options != null ? options : {};
            if(!AbstractChosen.browser_is_supported()) {
                return;
            }

            this.lang = LANGUAGE[this.options.lang || ($.zui.clientLang ? $.zui.clientLang() : 'zh_cn')];
            this.is_multiple = this.form_field.multiple;
            this.set_default_text();
            this.set_default_values();
            this.setup();
            this.set_up_html();
            this.register_observers();
        }

        AbstractChosen.prototype.set_default_values = function() {
            var _this = this;
            this.click_test_action = function(evt) {
                return _this.test_active_click(evt);
            };
            this.activate_action = function(evt) {
                return _this.activate_field(evt);
            };
            this.active_field = false;
            this.mouse_on_container = false;
            this.results_showing = false;
            this.result_highlighted = null;
            this.allow_single_deselect = (this.options.allow_single_deselect != null) && (this.form_field.options[0] != null) && this.form_field.options[0].text === "" ? this.options.allow_single_deselect : false;
            this.disable_search_threshold = this.options.disable_search_threshold || 0;
            this.disable_search = this.options.disable_search || false;
            this.enable_split_word_search = this.options.enable_split_word_search != null ? this.options.enable_split_word_search : true;
            this.group_search = this.options.group_search != null ? this.options.group_search : true;
            this.search_contains = this.options.search_contains || false;
            this.single_backstroke_delete = this.options.single_backstroke_delete != null ? this.options.single_backstroke_delete : true;
            this.max_selected_options = this.options.max_selected_options || Infinity;
            this.drop_direction = this.options.drop_direction || 'auto';
            this.middle_highlight = this.options.middle_highlight;
            this.inherit_select_classes = this.options.inherit_select_classes || false;
            this.display_selected_options = this.options.display_selected_options != null ? this.options.display_selected_options : true;
            return this.display_disabled_options = this.options.display_disabled_options != null ? this.options.display_disabled_options : true;
        };

        AbstractChosen.prototype.set_default_text = function() {
            if(this.form_field.getAttribute("data-placeholder")) {
                this.default_text = this.form_field.getAttribute("data-placeholder");
            } else if(this.is_multiple) {
                this.default_text = this.options.placeholder_text_multiple || this.options.placeholder_text || AbstractChosen.default_multiple_text;
            } else {
                this.default_text = this.options.placeholder_text_single || this.options.placeholder_text || AbstractChosen.default_single_text;
            }
            return this.results_none_found = this.form_field.getAttribute("data-no_results_text") || this.options.no_results_text || this.lang.no_results_text || AbstractChosen.default_no_result_text;
        };

        AbstractChosen.prototype.mouse_enter = function() {
            return this.mouse_on_container = true;
        };

        AbstractChosen.prototype.mouse_leave = function() {
            return this.mouse_on_container = false;
        };

        AbstractChosen.prototype.input_focus = function(evt) {
            var _this = this;
            if(this.is_multiple) {
                if(!this.active_field) {
                    return setTimeout((function() {
                        return _this.container_mousedown();
                    }), 50);
                }
            } else {
                if(!this.active_field) {
                    return this.activate_field();
                }
            }
        };

        AbstractChosen.prototype.input_blur = function(evt) {
            var _this = this;
            if(!this.mouse_on_container) {
                this.active_field = false;
                return setTimeout((function() {
                    return _this.blur_test();
                }), 100);
            }
        };

        AbstractChosen.prototype.results_option_build = function(options) {
            var content, data, _i, _len, _ref;
            content = '';
            _ref = this.results_data;
            for(_i = 0, _len = _ref.length; _i < _len; _i++) {
                data = _ref[_i];
                if(data.group) {
                    content += this.result_add_group(data);
                } else {
                    content += this.result_add_option(data);
                }
                if(options != null ? options.first : void 0) {
                    if(data.selected && this.is_multiple) {
                        this.choice_build(data);
                    } else if(data.selected && !this.is_multiple) {
                        this.single_set_selected_text(data.text);
                    }
                }
            }
            return content;
        };

        AbstractChosen.prototype.result_add_option = function(option) {
            var classes, option_el;
            if(!option.search_match) {
                return '';
            }
            if(!this.include_option_in_results(option)) {
                return '';
            }
            classes = [];
            if(!option.disabled && !(option.selected && this.is_multiple)) {
                classes.push("active-result");
            }
            if(option.disabled && !(option.selected && this.is_multiple)) {
                classes.push("disabled-result");
            }
            if(option.selected) {
                classes.push("result-selected");
            }
            if(option.group_array_index != null) {
                classes.push("group-option");
            }
            if(option.classes !== "") {
                classes.push(option.classes);
            }
            option_el = document.createElement("li");
            option_el.className = classes.join(" ");
            option_el.style.cssText = option.style;
            option_el.title = option.title;
            option_el.setAttribute("data-option-array-index", option.array_index);
            option_el.innerHTML = option.search_text;
            return this.outerHTML(option_el);
        };

        AbstractChosen.prototype.result_add_group = function(group) {
            var group_el;
            if(!(group.search_match || group.group_match)) {
                return '';
            }
            if(!(group.active_options > 0)) {
                return '';
            }
            group_el = document.createElement("li");
            group_el.className = "group-result";
            group_el.title = group.title;
            group_el.innerHTML = group.search_text;
            return this.outerHTML(group_el);
        };

        AbstractChosen.prototype.results_update_field = function() {
            this.set_default_text();
            if(!this.is_multiple) {
                this.results_reset_cleanup();
            }
            this.result_clear_highlight();
            this.results_build();
            if(this.results_showing) {
                return this.winnow_results();
            }
        };

        AbstractChosen.prototype.reset_single_select_options = function() {
            var result, _i, _len, _ref, _results;
            _ref = this.results_data;
            _results = [];
            for(_i = 0, _len = _ref.length; _i < _len; _i++) {
                result = _ref[_i];
                if(result.selected) {
                    _results.push(result.selected = false);
                } else {
                    _results.push(void 0);
                }
            }
            return _results;
        };

        AbstractChosen.prototype.results_toggle = function() {
            if(this.results_showing) {
                return this.results_hide();
            } else {
                return this.results_show();
            }
        };

        AbstractChosen.prototype.results_search = function(evt) {
            if(this.results_showing) {
                return this.winnow_results(1);
            } else {
                return this.results_show();
            }
        };

        AbstractChosen.prototype.winnow_results = function(canMiddleHighlight) {
            var escapedSearchText, option, regex, regexAnchor, results, results_group, searchText, startpos, text, zregex, _i, _len, _ref;
            this.no_results_clear();
            results = 0;
            searchText = this.get_search_text();
            escapedSearchText = searchText.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
            regexAnchor = this.search_contains ? "" : "^";
            regex = new RegExp(regexAnchor + escapedSearchText, 'i');
            zregex = new RegExp(escapedSearchText, 'i');
            _ref = this.results_data;
            for(_i = 0, _len = _ref.length; _i < _len; _i++) {
                option = _ref[_i];
                option.search_match = false;
                results_group = null;
                if(this.include_option_in_results(option)) {
                    if(option.group) {
                        option.group_match = false;
                        option.active_options = 0;
                    }
                    if((option.group_array_index != null) && this.results_data[option.group_array_index]) {
                        results_group = this.results_data[option.group_array_index];
                        if(results_group.active_options === 0 && results_group.search_match) {
                            results += 1;
                        }
                        results_group.active_options += 1;
                    }
                    if(!(option.group && !this.group_search)) {
                        option.search_text = option.group ? option.label : option.html;
                        option.search_keys_match = this.search_string_match(option.search_keys, regex);
                        option.search_text_match = this.search_string_match(option.search_text, regex);
                        option.search_match = option.search_text_match || option.search_keys_match;
                        if(option.search_match && !option.group) {
                            results += 1;
                        }
                        if(option.search_match) {
                            if(option.search_text_match && option.search_text.length) {
                                startpos = option.search_text.search(zregex);
                                text = option.search_text.substr(0, startpos + searchText.length) + '</em>' + option.search_text.substr(startpos + searchText.length);
                                option.search_text = text.substr(0, startpos) + '<em>' + text.substr(startpos);
                            } else if(option.search_keys_match && option.search_keys.length) {
                                startpos = option.search_keys.search(zregex);
                                text = option.search_keys.substr(0, startpos + searchText.length) + '</em>' + option.search_keys.substr(startpos + searchText.length);
                                option.search_text += '&nbsp; <small style="opacity: 0.7">' + text.substr(0, startpos) + '<em>' + text.substr(startpos) + '</small>';
                            }
                            if(results_group != null) {
                                results_group.group_match = true;
                            }
                        } else if((option.group_array_index != null) && this.results_data[option.group_array_index].search_match) {
                            option.search_match = true;
                        }
                    }
                }
            }
            this.result_clear_highlight();
            if(results < 1 && searchText.length) {
                this.update_results_content("");
                return this.no_results(searchText);
            } else {
                this.update_results_content(this.results_option_build());
                return this.winnow_results_set_highlight(canMiddleHighlight);
            }
        };

        AbstractChosen.prototype.search_string_match = function(search_string, regex) {
            var part, parts, _i, _len;
            if(regex.test(search_string)) {
                return true;
            } else if(this.enable_split_word_search && (search_string.indexOf(" ") >= 0 || search_string.indexOf("[") === 0)) {
                parts = search_string.replace(/\[|\]/g, "").split(" ");
                if(parts.length) {
                    for(_i = 0, _len = parts.length; _i < _len; _i++) {
                        part = parts[_i];
                        if(regex.test(part)) {
                            return true;
                        }
                    }
                }
            }
        };

        AbstractChosen.prototype.choices_count = function() {
            var option, _i, _len, _ref;
            if(this.selected_option_count != null) {
                return this.selected_option_count;
            }
            this.selected_option_count = 0;
            _ref = this.form_field.options;
            for(_i = 0, _len = _ref.length; _i < _len; _i++) {
                option = _ref[_i];
                if(option.selected && option.value != '') {
                    this.selected_option_count += 1;
                }
            }
            return this.selected_option_count;
        };

        AbstractChosen.prototype.choices_click = function(evt) {
            evt.preventDefault();
            if(!(this.results_showing || this.is_disabled)) {
                return this.results_show();
            }
        };

        AbstractChosen.prototype.keyup_checker = function(evt) {
            var stroke, _ref;
            stroke = (_ref = evt.which) != null ? _ref : evt.keyCode;
            this.search_field_scale();
            switch(stroke) {
                case 8:
                    if(this.is_multiple && this.backstroke_length < 1 && this.choices_count() > 0) {
                        return this.keydown_backstroke();
                    } else if(!this.pending_backstroke) {
                        this.result_clear_highlight();
                        return this.results_search();
                    }
                    break;
                case 13:
                    evt.preventDefault();
                    if(this.results_showing) {
                        return this.result_select(evt);
                    }
                    break;
                case 27:
                    if(this.results_showing) {
                        this.results_hide();
                    }
                    return true;
                case 9:
                case 38:
                case 40:
                case 16:
                case 91:
                case 17:
                    break;
                default:
                    return this.results_search();
            }
        };

        AbstractChosen.prototype.clipboard_event_checker = function(evt) {
            var _this = this;
            return setTimeout((function() {
                return _this.results_search();
            }), 50);
        };

        AbstractChosen.prototype.container_width = function() {
            if (this.options.width != null) {
                return this.options.width;
            } else if (this.form_field && this.form_field.classList && this.form_field.classList.contains('form-control')) {
                return '100%';
            } else {
                return "" + this.form_field.offsetWidth + "px";
            }
        };

        AbstractChosen.prototype.include_option_in_results = function(option) {
            if(this.is_multiple && (!this.display_selected_options && option.selected)) {
                return false;
            }
            if(!this.display_disabled_options && option.disabled) {
                return false;
            }
            if(option.empty) {
                return false;
            }
            return true;
        };

        AbstractChosen.prototype.search_results_touchstart = function(evt) {
            this.touch_started = true;
            return this.search_results_mouseover(evt);
        };

        AbstractChosen.prototype.search_results_touchmove = function(evt) {
            this.touch_started = false;
            return this.search_results_mouseout(evt);
        };

        AbstractChosen.prototype.search_results_touchend = function(evt) {
            if(this.touch_started) {
                return this.search_results_mouseup(evt);
            }
        };

        AbstractChosen.prototype.outerHTML = function(element) {
            var tmp;
            if(element.outerHTML) {
                return element.outerHTML;
            }
            tmp = document.createElement("div");
            tmp.appendChild(element);
            return tmp.innerHTML;
        };

        AbstractChosen.browser_is_supported = function() {
            if(window.navigator.appName === "Microsoft Internet Explorer") {
                return document.documentMode >= 8;
            }
            if(/iP(od|hone)/i.test(window.navigator.userAgent)) {
                return false;
            }
            if(/Android/i.test(window.navigator.userAgent)) {
                if(/Mobile/i.test(window.navigator.userAgent)) {
                    return false;
                }
            }
            return true;
        };

        AbstractChosen.default_multiple_text = "";

        AbstractChosen.default_single_text = "";

        AbstractChosen.default_no_result_text = "No results match";

        return AbstractChosen;

    })();

    $ = jQuery;

    $.fn.extend({
        chosen: function(options) {
            if(!AbstractChosen.browser_is_supported()) {
                return this;
            }
            return this.each(function(input_field) {
                var $this, chosen;
                $this = $(this);
                chosen = $this.data('chosen');
                if(options === 'destroy' && chosen) {
                    chosen.destroy();
                } else if(!chosen) {
                    $this.data('chosen', new Chosen(this, options));
                }
            });
        }
    });

    Chosen = (function(_super) {
        __extends(Chosen, _super);

        function Chosen() {
            _ref = Chosen.__super__.constructor.apply(this, arguments);
            return _ref;
        }

        Chosen.prototype.setup = function() {
            this.form_field_jq = $(this.form_field);
            this.current_selectedIndex = this.form_field.selectedIndex;
            return this.is_rtl = this.form_field_jq.hasClass("chosen-rtl");
        };

        Chosen.prototype.set_up_html = function() {
            var container_classes, container_props;
            container_classes = ["chosen-container"];
            container_classes.push("chosen-container-" + (this.is_multiple ? "multi" : "single"));
            if(this.inherit_select_classes && this.form_field.className) {
                container_classes.push(this.form_field.className);
            }
            if(this.is_rtl) {
                container_classes.push("chosen-rtl");
            }
            var strClass = this.form_field.getAttribute('data-css-class');
            if(strClass) {
                container_classes.push(strClass);
            }

            container_props = {
                'class': container_classes.join(' '),
                'style': "width: " + (this.container_width()) + ";",
                'title': this.form_field.title
            };
            if(this.form_field.id.length) {
                container_props.id = this.form_field.id.replace(/[^\w]/g, '_') + "_chosen";
            }
            this.container = $("<div />", container_props);
            if(this.is_multiple) {
                this.container.html('<ul class="chosen-choices"><li class="search-field"><input type="text" value="' + this.default_text + '" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chosen-drop"><ul class="chosen-results"></ul></div>');
            } else {
                this.container.html('<a class="chosen-single chosen-default" tabindex="-1"><span>' + this.default_text + '</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off" /></div><ul class="chosen-results"></ul></div>');
            }
            this.form_field_jq.hide().after(this.container);
            this.dropdown = this.container.find('div.chosen-drop').first();
            this.search_field = this.container.find('input').first();
            this.search_results = this.container.find('ul.chosen-results').first();
            this.search_field_scale();
            this.search_no_results = this.container.find('li.no-results').first();
            if(this.is_multiple) {
                this.search_choices = this.container.find('ul.chosen-choices').first();
                this.search_container = this.container.find('li.search-field').first();
            } else {
                this.search_container = this.container.find('div.chosen-search').first();
                this.selected_item = this.container.find('.chosen-single').first();
            }
            if(this.options.drop_width) {
                this.dropdown.css('width', this.options.drop_width).addClass('chosen-drop-size-limited');
            }
            this.results_build();
            this.set_tab_index();
            this.set_label_behavior();
            return this.form_field_jq.trigger("chosen:ready", {
                chosen: this
            });
        };

        Chosen.prototype.register_observers = function() {
            var _this = this;
            this.container.bind('mousedown.chosen', function(evt) {
                _this.container_mousedown(evt);
            });
            this.container.bind('mouseup.chosen', function(evt) {
                _this.container_mouseup(evt);
            });
            this.container.bind('mouseenter.chosen', function(evt) {
                _this.mouse_enter(evt);
            });
            this.container.bind('mouseleave.chosen', function(evt) {
                _this.mouse_leave(evt);
            });
            this.search_results.bind('mouseup.chosen', function(evt) {
                _this.search_results_mouseup(evt);
            });
            this.search_results.bind('mouseover.chosen', function(evt) {
                _this.search_results_mouseover(evt);
            });
            this.search_results.bind('mouseout.chosen', function(evt) {
                _this.search_results_mouseout(evt);
            });
            this.search_results.bind('mousewheel.chosen DOMMouseScroll.chosen', function(evt) {
                _this.search_results_mousewheel(evt);
            });
            this.search_results.bind('touchstart.chosen', function(evt) {
                _this.search_results_touchstart(evt);
            });
            this.search_results.bind('touchmove.chosen', function(evt) {
                _this.search_results_touchmove(evt);
            });
            this.search_results.bind('touchend.chosen', function(evt) {
                _this.search_results_touchend(evt);
            });
            this.form_field_jq.bind("chosen:updated.chosen", function(evt) {
                _this.results_update_field(evt);
            });
            this.form_field_jq.bind("chosen:activate.chosen", function(evt) {
                _this.activate_field(evt);
            });
            this.form_field_jq.bind("chosen:open.chosen", function(evt) {
                _this.container_mousedown(evt);
            });
            this.form_field_jq.bind("chosen:close.chosen", function(evt) {
                _this.input_blur(evt);
            });
            this.search_field.bind('blur.chosen', function(evt) {
                _this.input_blur(evt);
            });
            this.search_field.bind('keyup.chosen', function(evt) {
                _this.keyup_checker(evt);
            });
            this.search_field.bind('keydown.chosen', function(evt) {
                _this.keydown_checker(evt);
            });
            this.search_field.bind('focus.chosen', function(evt) {
                _this.input_focus(evt);
            });
            this.search_field.bind('cut.chosen', function(evt) {
                _this.clipboard_event_checker(evt);
            });
            this.search_field.bind('paste.chosen', function(evt) {
                _this.clipboard_event_checker(evt);
            });
            if(this.is_multiple) {
                return this.search_choices.bind('click.chosen', function(evt) {
                    _this.choices_click(evt);
                });
            } else {
                return this.container.bind('click.chosen', function(evt) {
                    evt.preventDefault();
                });
            }
        };

        Chosen.prototype.destroy = function() {
            $(this.container[0].ownerDocument).unbind("click.chosen", this.click_test_action);
            if(this.search_field[0].tabIndex) {
                this.form_field_jq[0].tabIndex = this.search_field[0].tabIndex;
            }
            this.container.remove();
            this.form_field_jq.removeData('chosen');
            return this.form_field_jq.show();
        };

        Chosen.prototype.search_field_disabled = function() {
            this.is_disabled = this.form_field_jq[0].disabled;
            if(this.is_disabled) {
                this.container.addClass('chosen-disabled');
                this.search_field[0].disabled = true;
                if(!this.is_multiple) {
                    this.selected_item.unbind("focus.chosen", this.activate_action);
                }
                return this.close_field();
            } else {
                this.container.removeClass('chosen-disabled');
                this.search_field[0].disabled = false;
                if(!this.is_multiple) {
                    return this.selected_item.bind("focus.chosen", this.activate_action);
                }
            }
        };

        Chosen.prototype.container_mousedown = function(evt) {
            if(!this.is_disabled) {
                if(evt && evt.type === "mousedown" && !this.results_showing) {
                    evt.preventDefault();
                }
                if(!((evt != null) && ($(evt.target)).hasClass("search-choice-close"))) {
                    if(!this.active_field) {
                        if(this.is_multiple) {
                            this.search_field.val("");
                        }
                        $(this.container[0].ownerDocument).bind('click.chosen', this.click_test_action);
                        this.results_show();
                    } else if(!this.is_multiple && evt && (($(evt.target)[0] === this.selected_item[0]) || $(evt.target).parents("a.chosen-single").length)) {
                        evt.preventDefault();
                        this.results_toggle();
                    }
                    return this.activate_field();
                }
            }
        };

        Chosen.prototype.container_mouseup = function(evt) {
            if(evt.target.nodeName === "ABBR" && !this.is_disabled) {
                return this.results_reset(evt);
            }
        };

        Chosen.prototype.search_results_mousewheel = function(evt) {
            var delta;
            if(evt.originalEvent) {
                delta = -evt.originalEvent.wheelDelta || evt.originalEvent.detail;
            }
            if(delta != null) {
                evt.preventDefault();
                if(evt.type === 'DOMMouseScroll') {
                    delta = delta * 40;
                }
                return this.search_results.scrollTop(delta + this.search_results.scrollTop());
            }
        };

        Chosen.prototype.blur_test = function(evt) {
            if(!this.active_field && this.container.hasClass("chosen-container-active")) {
                return this.close_field();
            }
        };

        Chosen.prototype.close_field = function() {
            $(this.container[0].ownerDocument).unbind("click.chosen", this.click_test_action);
            this.active_field = false;
            this.results_hide();
            this.container.removeClass("chosen-container-active");
            this.clear_backstroke();
            this.show_search_field_default();
            return this.search_field_scale();
        };

        Chosen.prototype.activate_field = function() {
            this.container.addClass("chosen-container-active");
            this.active_field = true;
            this.search_field.val(this.search_field.val());
            // return this.search_field.focus(); ZUI: bug: when user select a item by click, container scroll to left automaticly.
        };

        Chosen.prototype.test_active_click = function(evt) {
            var active_container;
            active_container = $(evt.target).closest('.chosen-container');
            if(active_container.length && this.container[0] === active_container[0]) {
                return this.active_field = true;
            } else {
                return this.close_field();
            }
        };

        Chosen.prototype.results_build = function() {
            this.parsing = true;
            this.selected_option_count = null;
            this.results_data = SelectParser.select_to_array(this.form_field);
            if(this.is_multiple) {
                this.search_choices.find("li.search-choice").remove();
            } else if(!this.is_multiple) {
                this.single_set_selected_text();
                if(this.disable_search || this.form_field.options.length <= this.disable_search_threshold) {
                    this.search_field[0].readOnly = true;
                    this.container.addClass("chosen-container-single-nosearch");
                } else {
                    this.search_field[0].readOnly = false;
                    this.container.removeClass("chosen-container-single-nosearch");
                }
            }
            this.update_results_content(this.results_option_build({
                first: true
            }));
            this.search_field_disabled();
            this.show_search_field_default();
            this.search_field_scale();
            return this.parsing = false;
        };

        Chosen.prototype.result_do_highlight = function(el, canMiddleHighlight) {
            var high_bottom, high_top, maxHeight, visible_bottom, visible_top, resultHeight, scrollTop = -1;
            if(el.length) {
                this.result_clear_highlight();
                this.result_highlight = el;
                this.result_highlight.addClass("highlighted");
                maxHeight = parseInt(this.search_results.css("maxHeight"), 10);
                resultHeight = this.result_highlight.outerHeight();
                visible_top = this.search_results.scrollTop();
                visible_bottom = maxHeight + visible_top;
                high_top = this.result_highlight.position().top + this.search_results.scrollTop();
                high_bottom = high_top + resultHeight;
                if(this.middle_highlight && (canMiddleHighlight || this.middle_highlight === 'always' || high_bottom >= visible_bottom || high_top < visible_top)) {
                    scrollTop = Math.min(high_top - resultHeight, Math.max(0, high_top - (maxHeight - resultHeight)/2));
                } else if(high_bottom >= visible_bottom) {
                    scrollTop = (high_bottom - maxHeight) > 0 ? high_bottom - maxHeight : 0;
                } else if(high_top < visible_top) {
                    scrollTop = high_top;
                }
                if(scrollTop > -1) {
                    this.search_results.scrollTop(scrollTop);
                }
            }
        };

        Chosen.prototype.result_clear_highlight = function() {
            if(this.result_highlight) {
                this.result_highlight.removeClass("highlighted");
            }
            return this.result_highlight = null;
        };

        Chosen.prototype.results_show = function() {
            if(this.is_multiple && this.max_selected_options <= this.choices_count()) {
                this.form_field_jq.trigger("chosen:maxselected", {
                    chosen: this
                });
                return false;
            }
            this.results_showing = true;
            this.search_field.focus();
            this.search_field.val(this.search_field.val());
            this.winnow_results(1);

            var dropDirection = this.drop_direction;
            if(dropDirection === 'auto') {
                if (!this.drop_directionFixed) {
                    var $drop = this.container.find('.chosen-drop');
                    var offset = this.container.offset();
                    if(offset.top + $drop.outerHeight() + 30 > $(window).height() + $(window).scrollTop()) {
                        dropDirection = 'up';
                    }
                    this.drop_directionFixed = dropDirection;
                } else {
                    dropDirection = this.drop_directionFixed;
                }
            }
            this.container.toggleClass('chosen-up', dropDirection === 'up').addClass("chosen-with-drop");

            return this.form_field_jq.trigger("chosen:showing_dropdown", {
                chosen: this
            });
        };

        Chosen.prototype.update_results_content = function(content) {
            return this.search_results.html(content);
        };

        Chosen.prototype.results_hide = function() {
            if(this.results_showing) {
                this.result_clear_highlight();
                this.container.removeClass("chosen-with-drop");
                this.form_field_jq.trigger("chosen:hiding_dropdown", {
                    chosen: this
                });
                this.drop_directionFixed = 0;
            }
            return this.results_showing = false;
        };

        Chosen.prototype.set_tab_index = function(el) {
            var ti;
            if(this.form_field.tabIndex) {
                ti = this.form_field.tabIndex;
                this.form_field.tabIndex = -1;
                return this.search_field[0].tabIndex = ti;
            }
        };

        Chosen.prototype.set_label_behavior = function() {
            var _this = this;
            this.form_field_label = this.form_field_jq.parents("label");
            if(!this.form_field_label.length && this.form_field.id.length) {
                this.form_field_label = $("label[for='" + this.form_field.id + "']");
            }
            if(this.form_field_label.length > 0) {
                return this.form_field_label.bind('click.chosen', function(evt) {
                    if(_this.is_multiple) {
                        return _this.container_mousedown(evt);
                    } else {
                        return _this.activate_field();
                    }
                });
            }
        };

        Chosen.prototype.show_search_field_default = function() {
            if(this.is_multiple && this.choices_count() < 1 && !this.active_field) {
                this.search_field.val(this.default_text);
                return this.search_field.addClass("default");
            } else {
                this.search_field.val("");
                return this.search_field.removeClass("default");
            }
        };

        Chosen.prototype.search_results_mouseup = function(evt) {
            var target;
            target = $(evt.target).hasClass("active-result") ? $(evt.target) : $(evt.target).parents(".active-result").first();
            if(target.length) {
                this.result_highlight = target;
                this.result_select(evt);
                // return this.search_field.focus(); // ZUI: bug: when user select a item by click, container scroll to left automaticly.
            }
        };

        Chosen.prototype.search_results_mouseover = function(evt) {
            var target;
            target = $(evt.target).hasClass("active-result") ? $(evt.target) : $(evt.target).parents(".active-result").first();
            if(target) {
                return this.result_do_highlight(target);
            }
        };

        Chosen.prototype.search_results_mouseout = function(evt) {
            if($(evt.target).hasClass("active-result" || $(evt.target).parents('.active-result').first())) {
                return this.result_clear_highlight();
            }
        };

        Chosen.prototype.choice_build = function(item) {
            var choice, close_link,
                _this = this;
            choice = $('<li />', {
                "class": "search-choice"
                    /// ZUI change begin
                    /// Set title to span with item value
                    ///            }).html("<span>" + item.html + "</span>");
            }).html("<span title='" + item.html + "'>" + item.html + "</span>");
            /// ZUI change end
            if(item.disabled) {
                choice.addClass('search-choice-disabled');
            } else {
                close_link = $('<a />', {
                    "class": 'search-choice-close',
                    'data-option-array-index': item.array_index
                });
                close_link.bind('click.chosen', function(evt) {
                    return _this.choice_destroy_link_click(evt);
                });
                choice.append(close_link);
            }
            return this.search_container.before(choice);
        };

        Chosen.prototype.choice_destroy_link_click = function(evt) {
            evt.preventDefault();
            evt.stopPropagation();
            if(!this.is_disabled) {
                return this.choice_destroy($(evt.target));
            }
        };

        Chosen.prototype.choice_destroy = function(link) {
            if(this.result_deselect(link[0].getAttribute("data-option-array-index"))) {
                this.show_search_field_default();
                if(this.is_multiple && this.choices_count() > 0 && this.search_field.val().length < 1) {
                    this.results_hide();
                }
                link.parents('li').first().remove();
                return this.search_field_scale();
            }
        };

        Chosen.prototype.results_reset = function() {
            this.reset_single_select_options();
            this.form_field.options[0].selected = true;
            this.single_set_selected_text();
            this.show_search_field_default();
            this.results_reset_cleanup();
            this.form_field_jq.trigger("change");
            if(this.active_field) {
                return this.results_hide();
            }
        };

        Chosen.prototype.results_reset_cleanup = function() {
            this.current_selectedIndex = this.form_field.selectedIndex;
            return this.selected_item.find("abbr").remove();
        };

        Chosen.prototype.result_select = function(evt) {
            var high, item;
            if(this.result_highlight) {
                high = this.result_highlight;
                this.result_clear_highlight();
                if(this.is_multiple && this.max_selected_options <= this.choices_count()) {
                    this.form_field_jq.trigger("chosen:maxselected", {
                        chosen: this
                    });
                    return false;
                }
                if(this.is_multiple) {
                    high.removeClass("active-result");
                } else {
                    this.reset_single_select_options();
                }
                item = this.results_data[high[0].getAttribute("data-option-array-index")];
                item.selected = true;
                this.form_field.options[item.options_index].selected = true;
                this.selected_option_count = null;
                if(this.is_multiple) {
                    this.choice_build(item);
                } else {
                    this.single_set_selected_text(item.text);
                }
                if(!((evt.metaKey || evt.ctrlKey) && this.is_multiple)) {
                    this.results_hide();
                }
                this.search_field.val("");
                if(this.is_multiple || this.form_field.selectedIndex !== this.current_selectedIndex) {
                    this.form_field_jq.trigger("change", {
                        'selected': this.form_field.options[item.options_index].value
                    });
                }
                this.current_selectedIndex = this.form_field.selectedIndex;
                return this.search_field_scale();
            }
        };

        Chosen.prototype.single_set_selected_text = function(text) {
            if(text == null) {
                text = this.default_text;
            }
            if(text === this.default_text) {
                this.selected_item.addClass("chosen-default");
            } else {
                this.single_deselect_control_build();
                this.selected_item.removeClass("chosen-default");
            }
            /// ZUI change begin
            /// Change title with text
            ///         return this.selected_item.find("span").text(text); // old code
            return this.selected_item.find("span").attr('title', text).text(text);
            /// ZUI change end
        };

        Chosen.prototype.result_deselect = function(pos) {
            var result_data;
            result_data = this.results_data[pos];
            if(!this.form_field.options[result_data.options_index].disabled) {
                result_data.selected = false;
                this.form_field.options[result_data.options_index].selected = false;
                this.selected_option_count = null;
                this.result_clear_highlight();
                if(this.results_showing) {
                    this.winnow_results();
                }
                this.form_field_jq.trigger("change", {
                    deselected: this.form_field.options[result_data.options_index].value
                });
                this.search_field_scale();
                return true;
            } else {
                return false;
            }
        };

        Chosen.prototype.single_deselect_control_build = function() {
            if(!this.allow_single_deselect) {
                return;
            }
            if(!this.selected_item.find("abbr").length) {
                this.selected_item.find("span").first().after("<abbr class=\"search-choice-close\"></abbr>");
            }
            return this.selected_item.addClass("chosen-single-with-deselect");
        };

        Chosen.prototype.get_search_text = function() {
            if(this.search_field.val() === this.default_text) {
                return "";
            } else {
                return $('<div/>').text($.trim(this.search_field.val())).html();
            }
        };

        Chosen.prototype.winnow_results_set_highlight = function(canMiddleHighlight) {
            var do_high, selected_results;
            selected_results = !this.is_multiple ? this.search_results.find(".result-selected.active-result") : [];
            do_high = selected_results.length ? selected_results.first() : this.search_results.find(".active-result").first();
            if(do_high != null) {
                return this.result_do_highlight(do_high, canMiddleHighlight);
            }
        };

        Chosen.prototype.no_results = function(terms) {
            var no_results_html;
            no_results_html = $('<li class="no-results">' + this.results_none_found + ' "<span></span>"</li>');
            no_results_html.find("span").first().html(terms);
            this.search_results.append(no_results_html);
            return this.form_field_jq.trigger("chosen:no_results", {
                chosen: this
            });
        };

        Chosen.prototype.no_results_clear = function() {
            return this.search_results.find(".no-results").remove();
        };

        Chosen.prototype.keydown_arrow = function() {
            var next_sib;
            if(this.results_showing && this.result_highlight) {
                next_sib = this.result_highlight.nextAll("li.active-result").first();
                if(next_sib) {
                    return this.result_do_highlight(next_sib);
                }
            } else {
                return this.results_show();
            }
        };

        Chosen.prototype.keyup_arrow = function() {
            var prev_sibs;
            if(!this.results_showing && !this.is_multiple) {
                return this.results_show();
            } else if(this.result_highlight) {
                prev_sibs = this.result_highlight.prevAll("li.active-result");
                if(prev_sibs.length) {
                    return this.result_do_highlight(prev_sibs.first());
                } else {
                    if(this.choices_count() > 0) {
                        this.results_hide();
                    }
                    return this.result_clear_highlight();
                }
            }
        };

        Chosen.prototype.keydown_backstroke = function() {
            var next_available_destroy;
            if(this.pending_backstroke) {
                this.choice_destroy(this.pending_backstroke.find("a").first());
                return this.clear_backstroke();
            } else {
                next_available_destroy = this.search_container.siblings("li.search-choice").last();
                if(next_available_destroy.length && !next_available_destroy.hasClass("search-choice-disabled")) {
                    this.pending_backstroke = next_available_destroy;
                    if(this.single_backstroke_delete) {
                        return this.keydown_backstroke();
                    } else {
                        return this.pending_backstroke.addClass("search-choice-focus");
                    }
                }
            }
        };

        Chosen.prototype.clear_backstroke = function() {
            if(this.pending_backstroke) {
                this.pending_backstroke.removeClass("search-choice-focus");
            }
            return this.pending_backstroke = null;
        };

        Chosen.prototype.keydown_checker = function(evt) {
            var stroke, _ref1;
            stroke = (_ref1 = evt.which) != null ? _ref1 : evt.keyCode;
            this.search_field_scale();
            if(stroke !== 8 && this.pending_backstroke) {
                this.clear_backstroke();
            }
            switch(stroke) {
                case 8:
                    this.backstroke_length = this.search_field.val().length;
                    break;
                case 9:
                    if(this.results_showing && !this.is_multiple) {
                        this.result_select(evt);
                    }
                    this.mouse_on_container = false;
                    break;
                case 13:
                    evt.preventDefault();
                    break;
                case 38:
                    evt.preventDefault();
                    this.keyup_arrow();
                    break;
                case 40:
                    evt.preventDefault();
                    this.keydown_arrow();
                    break;
            }
        };

        Chosen.prototype.search_field_scale = function() {
            var div, f_width, h, style, style_block, styles, w, _i, _len;
            if(this.is_multiple) {
                h = 0;
                w = 0;
                style_block = "position:absolute; left: -1000px; top: -1000px; display:none;";
                styles = ['font-size', 'font-style', 'font-weight', 'font-family', 'line-height', 'text-transform', 'letter-spacing'];
                for(_i = 0, _len = styles.length; _i < _len; _i++) {
                    style = styles[_i];
                    style_block += style + ":" + this.search_field.css(style) + ";";
                }
                div = $('<div />', {
                    'style': style_block
                });
                div.text(this.search_field.val());
                $('body').append(div);
                w = div.width() + 25;
                div.remove();
                f_width = this.container.outerWidth();
                if(w > f_width - 10) {
                    w = f_width - 10;
                }
                return this.search_field.css({
                    'width': w + 'px'
                });
            }
        };

        return Chosen;

    })(AbstractChosen);
}).call(this);


/* ========================================================================
 * ZUI: selectable.js [1.5.0+]
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($) {
    'use strict';

    var name = 'zui.selectable'; // module name

    // The selectable modal class
    var Selectable = function(element, options) {
        this.name = name;
        this.$ = $(element);
        this.id = $.zui.uuid();
        this.selectOrder = 1;
        this.selections = {};

        this.getOptions(options);
        this._init();
    };

    var isPointInner = function(x, y, a) {
        return x >= a.left && x <= (a.left + a.width) && y >= a.top && y <= (a.top + a.height);
    };

    var isIntersectArea = function(a, b) {
        var x1 = Math.max(a.left, b.left),
            y1 = Math.max(a.top, b.top),
            x2 = Math.min(a.left + a.width, b.left + b.width),
            y2 = Math.min(a.top + a.height, b.top + b.height);

        return isPointInner(x1, y1, a) && isPointInner(x2, y2, a) && isPointInner(x1, y1, b) && isPointInner(x2, y2, b);
    };

    // default options
    Selectable.DEFAULTS = {
        selector: 'li,tr,div',
        trigger: '',
        selectClass: 'active',
        rangeStyle: {
            border: '1px solid ' + ($.zui.colorset ? $.zui.colorset.primary : '#3280fc'),
            backgroundColor: $.zui.colorset ? (new $.zui.Color($.zui.colorset.primary).fade(20).toCssStr()) : 'rgba(50, 128, 252, 0.2)'
        },
        clickBehavior: 'toggle',
        ignoreVal: 3,
        listenClick: true
        // mouseButton: -1 // 0, 1, 2, -1, all, left,  right, middle
    };

    // Get and init options
    Selectable.prototype.getOptions = function(options) {
        this.options = $.extend({}, Selectable.DEFAULTS, this.$.data(), options);
    };

    Selectable.prototype.select = function(elementOrid) {
        this.toggle(elementOrid, true);
    };

    Selectable.prototype.unselect = function(elementOrid) {
        this.toggle(elementOrid, false);
    };

    Selectable.prototype.toggle = function(elementOrid, isSelect, handle) {
        var $element, id, selector = this.options.selector, that = this;
        if(elementOrid === undefined) {
            this.$.find(selector).each(function() {
                that.toggle(this, isSelect);
            });
            return;
        } else if(typeof elementOrid === 'object') {
            $element = $(elementOrid).closest(selector);
            id = $element.data('id');
        } else {
            id = elementOrid;
            $element = that.$.find('.slectable-item[data-id="' + id + '"]');
        }
        if($element && $element.length) {
            if(!id) {
                id = $.zui.uuid();
                $element.attr('data-id', id);
            }
            if(isSelect === undefined || isSelect === null) {
                isSelect = !that.selections[id];
            }
            if(!!isSelect !== !!that.selections[id]) {
                var handleResult;
                if($.isFunction(handle)) {
                    handleResult = handle(isSelect);
                }
                if(handleResult !== true) {
                    that.selections[id] = isSelect ? that.selectOrder++ : false;
                    that.callEvent(isSelect ? 'select' : 'unselect', {id: id, selections: that.selections, target: $element, selected: that.getSelectedArray()}, that);
                }
            }
            if (that.options.selectClass) {
                $element.toggleClass(that.options.selectClass, isSelect);
            }
        }
    };

    Selectable.prototype.getSelectedArray = function() {
        var selected = [];
        $.each(this.selections, function(thisId, thisIsSelected) {
            if(thisIsSelected) selected.push(thisId);
        });
        return selected;
    };

    Selectable.prototype.syncSelectionsFromClass = function() {
        var that = this;
        var $children = that.$children = that.$.find(that.options.selector);
        that.selections = {};
        that.$children.each(function() {
            var $item = $(this);
            that.selections[$item.data('id')] = $item.hasClass(that.options.selectClass);
        });
    };

    Selectable.prototype._init = function() {
        var options = this.options, that = this;
        var ignoreVal = options.ignoreVal;
        var isIgnoreMove = true;
        var eventNamespace = '.' + this.name + '.' + this.id;
        var startX, startY, $range, range, x, y, checkRangeCall;
        var checkFunc = $.isFunction(options.checkFunc) ? options.checkFunc : null;
        var rangeFunc = $.isFunction(options.rangeFunc) ? options.rangeFunc : null;
        var isMouseDown    = false;
        var mouseDownBackEventCall = null;
        var mouseDownEventName = 'mousedown' + eventNamespace;

        var checkRange = function() {
            if(!range) return;
            that.$children.each(function() {
                var $item = $(this);
                var offset = $item.offset();
                offset.width = $item.outerWidth();
                offset.height = $item.outerHeight();
                var isIntersect = rangeFunc ? rangeFunc.call(this, range, offset) : isIntersectArea(range, offset);
                if(checkFunc) {
                    var result = checkFunc.call(that, {
                        intersect: isIntersect,
                        target: $item,
                        range: range,
                        targetRange: offset
                    });
                    if(result === true) {
                        that.select($item);
                    } else if(result === false) {
                        that.unselect($item);
                    }
                } else {
                    if(isIntersect) {
                        that.select($item);
                    } else if(!that.multiKey) {
                        that.unselect($item);
                    }
                }
            });
        };

        var mousemove = function(e) {
            if(!isMouseDown) return;
            x = e.pageX;
            y = e.pageY;
            range = {
                width: Math.abs(x - startX),
                height: Math.abs(y - startY),
                left: x > startX ? startX : x,
                top: y > startY ? startY : y
            };

            if(isIgnoreMove && range.width < ignoreVal && range.height < ignoreVal) return;
            if(!$range) {
                $range = $('.selectable-range[data-id="' + that.id + '"]');
                if(!$range.length) {
                    $range = $('<div class="selectable-range" data-id="' + that.id + '"></div>')
                        .css($.extend({
                            zIndex: 1060,
                            position: 'absolute',
                            top: startX,
                            left: startY,
                            pointerEvents: 'none',
                        }, that.options.rangeStyle))
                        .appendTo($('body'));
                }
            }
            $range.css(range);
            clearTimeout(checkRangeCall);
            checkRangeCall = setTimeout(checkRange, 10);
            isIgnoreMove = false;
        };

        var mouseup = function(e) {
            $(document).off(eventNamespace);
            clearTimeout(mouseDownBackEventCall);
            if(!isMouseDown) return;
            isMouseDown = false;
            if($range) $range.remove();
            if(!isIgnoreMove)
            {
                if(range) {
                    clearTimeout(checkRangeCall);
                    checkRange();
                    range = null;
                }
            }
            that.callEvent('finish', {selections: that.selections, selected: that.getSelectedArray()});
            e.preventDefault();
        };

        var mousedown = function(e) {
            if(isMouseDown) {
                return mouseup(e);
            }

            var mouseButton = $.zui.getMouseButtonCode(options.mouseButton);
            if(mouseButton > -1 && e.button !== mouseButton) {
                return;
            }

            if(that.altKey || e.which === 3 || that.callEvent('start', e) === false) {
                return;
            }

            var $children = that.$children = that.$.find(options.selector);
            $children.addClass('slectable-item');

            var clickBehavior = that.multiKey ? 'multi' : options.clickBehavior;
            if(clickBehavior === 'single') {
                that.unselect();
            }
            if (options.listenClick) {
                if(clickBehavior === 'multi') {
                    that.toggle(e.target);
                } else if(clickBehavior === 'single') {
                    that.select(e.target);
                } else if(clickBehavior === 'toggle') {
                    that.toggle(e.target, null, function(isSelect) {
                        that.unselect();
                    });
                }
            }

            if(that.callEvent('startDrag', e) === false) {
                that.callEvent('finish', {selections: that.selections, selected: that.getSelectedArray()});
                return;
            }

            startX = e.pageX;
            startY = e.pageY;

            $range = null;
            isIgnoreMove = true;
            isMouseDown = true;

            $(document).on('mousemove' + eventNamespace, mousemove).on('mouseup' + eventNamespace, mouseup);
            mouseDownBackEventCall = setTimeout(function() {
                $(document).on(mouseDownEventName, mouseup);
            }, 10);
            e.preventDefault();
        };

        var $container = options.container && options.container !== 'default' ? $(options.container) : this.$;
        if(options.trigger) {
            $container.on(mouseDownEventName, options.trigger, mousedown);
        } else {
            $container.on(mouseDownEventName, mousedown);
        }

        $(document).on('keydown', function(e) {
            var code = e.keyCode;
            if(code === 17 || code == 91) that.multiKey = code;
            else if(code === 18) that.altKey = true;
        }).on('keyup', function(e) {
            that.multiKey = false;
            that.altKey = false;
        });
    };

    // Call event helper
    Selectable.prototype.callEvent = function(name, params) {
        var event = $.Event(name + '.' + this.name);
        this.$.trigger(event, params);
        var result = event.result;
        var callback = this.options[name];
        if($.isFunction(callback)) {
            result = callback.apply(this, $.isArray(params) ? params : [params]);
        }
        return result;
    };

    // Extense jquery element
    $.fn.selectable = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(name);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(name, (data = new Selectable(this, options)));

            if(typeof option == 'string') data[option]();
        });
    };

    $.fn.selectable.Constructor = Selectable;

    // Auto call selectable after document load complete
    $(function() {
        $('[data-ride="selectable"]').selectable();
    });
}(jQuery));


/* ========================================================================
 * ZUI: sortable.js
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2014-2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


+ function($, window, document) {
    'use strict';

    if(!$.fn.droppable) {
        console.error('Sortable requires droppable.js');
        return;
    }

    var NAME     = 'zui.sortable',
        DEFAULTS = {
            selector     : 'li,div',
            dragCssClass : 'invisible',
            sortingClass : 'sortable-sorting'
        },
        STR_ORDER = 'order';

    var Sortable = function(element, options) {
        var that     = this;
        that.$       = $(element);
        that.options = $.extend({}, DEFAULTS, that.$.data(), options);
        that.init();
    };

    Sortable.DEFAULTS = DEFAULTS;
    Sortable.NAME     = NAME;

    Sortable.prototype.init = function() {
        var that         = this,
            $root        = that.$,
            options      = that.options,
            selector     = options.selector,
            containerSelector = options.containerSelector,
            sortingClass = options.sortingClass,
            dragCssClass = options.dragCssClass,
            targetSelector = options.targetSelector,
            isReverse    = options.reverse,
            orderChanged;

        var markOrders = function($items) {
            $items = $items || that.getItems(1);
            var itemsCount = $items.length;
            if (itemsCount) {
                $items.each(function(itemIndex) {
                    var itemOrder = isReverse ? itemsCount - itemIndex : itemIndex;
                    $(this).attr('data-' + STR_ORDER, itemOrder).data(STR_ORDER, itemOrder);
                });
            }
        };

        markOrders();

        $root.droppable({
            handle      : options.trigger,
            target      : targetSelector ? targetSelector : (containerSelector ? (selector + ',' + containerSelector) : selector),
            selector    : selector,
            container   : $root,
            always      : options.always,
            flex        : true,
            lazy        : options.lazy,
            canMoveHere : options.canMoveHere,
            dropToClass : options.dropToClass,
            before      : options.before,
            nested      : !!containerSelector,
            mouseButton : options.mouseButton,
            stopPropagation : options.stopPropagation,
            start: function(e) {
                if(dragCssClass) e.element.addClass(dragCssClass);
                orderChanged = false;
                that.trigger('start', e);
            },
            drag: function(e) {
                $root.addClass(sortingClass);
                if(e.isIn) {
                    var $ele        = e.element,
                        $target     = e.target,
                        isContainer = containerSelector && $target.is(containerSelector);

                    if (isContainer) {
                        if (!$target.children(selector).filter('.dragging').length) {
                            $target.append($ele);
                            var $items = that.getItems(1);
                            markOrders($items);
                            that.trigger(STR_ORDER, {
                                list: $items,
                                element: $ele
                            });
                        }
                        return;
                    }

                    var eleOrder    = $ele.data(STR_ORDER),
                        targetOrder = $target.data(STR_ORDER);
                    if(eleOrder === targetOrder) return markOrders($items);
                    else if(eleOrder > targetOrder) {
                        $target[isReverse ? 'after' : 'before']($ele);
                    } else {
                        $target[isReverse ? 'before' : 'after']($ele);
                    }
                    orderChanged = true;
                    var $items = that.getItems(1);
                    markOrders($items);
                    that.trigger(STR_ORDER, {
                        list: $items,
                        element: $ele
                    });
                }
            },
            finish: function(e) {
                if(dragCssClass && e.element) e.element.removeClass(dragCssClass);
                $root.removeClass(sortingClass);
                that.trigger('finish', {
                    list: that.getItems(),
                    element: e.element,
                    changed: orderChanged
                });
            }
        });
    };

    Sortable.prototype.destroy = function() {
        this.$.droppable('destroy');
        this.$.data(NAME, null);
    };

    Sortable.prototype.reset = function() {
        this.destroy();
        this.init();
    };

    Sortable.prototype.getItems = function(onlyElements) {
        var $items = this.$.find(this.options.selector).not('.drag-shadow');
        if(!onlyElements) {
            return $items.map(function() {
                var $item = $(this);
                return {
                    item: $item,
                    order: $item.data('order')
                };
            });
        }
        return $items;
    };

    Sortable.prototype.trigger = function(name, params) {
        return $.zui.callEvent(this.options[name], params, this);
    };

    $.fn.sortable = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(NAME);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(NAME, (data = new Sortable(this, options)));
            else if(typeof option == 'object') data.reset();

            if(typeof option == 'string') data[option]();
        });
    };

    $.fn.sortable.Constructor = Sortable;
}(jQuery, window, document);


/*!
 * jQuery Form Plugin
 * version: 4.2.2
 * Requires jQuery v1.7.2 or later
 * Project repository: https://github.com/jquery-form/form

 * Copyright 2017 Kevin Morris
 * Copyright 2006 M. Alsup

 * Dual licensed under the LGPL-2.1+ or MIT licenses
 * https://github.com/jquery-form/form#license

 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 */
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof module&&module.exports?module.exports=function(t,r){return void 0===r&&(r="undefined"!=typeof window?require("jquery"):require("jquery")(t)),e(r),r}:e(jQuery)}(function(e){"use strict";function t(t){var r=t.data;t.isDefaultPrevented()||(t.preventDefault(),e(t.target).closest("form").ajaxSubmit(r))}function r(t){var r=t.target,a=e(r);if(!a.is("[type=submit],[type=image]")){var n=a.closest("[type=submit]");if(0===n.length)return;r=n[0]}var i=r.form;if(i.clk=r,"image"===r.type)if(void 0!==t.offsetX)i.clk_x=t.offsetX,i.clk_y=t.offsetY;else if("function"==typeof e.fn.offset){var o=a.offset();i.clk_x=t.pageX-o.left,i.clk_y=t.pageY-o.top}else i.clk_x=t.pageX-r.offsetLeft,i.clk_y=t.pageY-r.offsetTop;setTimeout(function(){i.clk=i.clk_x=i.clk_y=null},100)}function a(){if(e.fn.ajaxSubmit.debug){var t="[jquery.form] "+Array.prototype.join.call(arguments,"");window.console&&window.console.log?window.console.log(t):window.opera&&window.opera.postError&&window.opera.postError(t)}}var n=/\r?\n/g,i={};i.fileapi=void 0!==e('<input type="file">').get(0).files,i.formdata=void 0!==window.FormData;var o=!!e.fn.prop;e.fn.attr2=function(){if(!o)return this.attr.apply(this,arguments);var e=this.prop.apply(this,arguments);return e&&e.jquery||"string"==typeof e?e:this.attr.apply(this,arguments)},e.fn.ajaxSubmit=function(t,r,n,s){function u(r){var a,n,i=e.param(r,t.traditional).split("&"),o=i.length,s=[];for(a=0;a<o;a++)i[a]=i[a].replace(/\+/g," "),n=i[a].split("="),s.push([decodeURIComponent(n[0]),decodeURIComponent(n[1])]);return s}function c(r){function n(e){var t=null;try{e.contentWindow&&(t=e.contentWindow.document)}catch(e){a("cannot get iframe.contentWindow document: "+e)}if(t)return t;try{t=e.contentDocument?e.contentDocument:e.document}catch(r){a("cannot get iframe.contentDocument: "+r),t=e.document}return t}function i(){function t(){try{var e=n(v).readyState;a("state = "+e),e&&"uninitialized"===e.toLowerCase()&&setTimeout(t,50)}catch(e){a("Server abort: ",e," (",e.name,")"),s(L),j&&clearTimeout(j),j=void 0}}var r=p.attr2("target"),i=p.attr2("action"),o=p.attr("enctype")||p.attr("encoding")||"multipart/form-data";w.setAttribute("target",m),l&&!/post/i.test(l)||w.setAttribute("method","POST"),i!==f.url&&w.setAttribute("action",f.url),f.skipEncodingOverride||l&&!/post/i.test(l)||p.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"}),f.timeout&&(j=setTimeout(function(){T=!0,s(A)},f.timeout));var u=[];try{if(f.extraData)for(var c in f.extraData)f.extraData.hasOwnProperty(c)&&(e.isPlainObject(f.extraData[c])&&f.extraData[c].hasOwnProperty("name")&&f.extraData[c].hasOwnProperty("value")?u.push(e('<input type="hidden" name="'+f.extraData[c].name+'">',k).val(f.extraData[c].value).appendTo(w)[0]):u.push(e('<input type="hidden" name="'+c+'">',k).val(f.extraData[c]).appendTo(w)[0]));f.iframeTarget||h.appendTo(D),v.attachEvent?v.attachEvent("onload",s):v.addEventListener("load",s,!1),setTimeout(t,15);try{w.submit()}catch(e){document.createElement("form").submit.apply(w)}}finally{w.setAttribute("action",i),w.setAttribute("enctype",o),r?w.setAttribute("target",r):p.removeAttr("target"),e(u).remove()}}function s(t){if(!x.aborted&&!X){if((O=n(v))||(a("cannot access response document"),t=L),t===A&&x)return x.abort("timeout"),void S.reject(x,"timeout");if(t===L&&x)return x.abort("server abort"),void S.reject(x,"error","server abort");if(O&&O.location.href!==f.iframeSrc||T){v.detachEvent?v.detachEvent("onload",s):v.removeEventListener("load",s,!1);var r,i="success";try{if(T)throw"timeout";var o="xml"===f.dataType||O.XMLDocument||e.isXMLDoc(O);if(a("isXml="+o),!o&&window.opera&&(null===O.body||!O.body.innerHTML)&&--C)return a("requeing onLoad callback, DOM not available"),void setTimeout(s,250);var u=O.body?O.body:O.documentElement;x.responseText=u?u.innerHTML:null,x.responseXML=O.XMLDocument?O.XMLDocument:O,o&&(f.dataType="xml"),x.getResponseHeader=function(e){return{"content-type":f.dataType}[e.toLowerCase()]},u&&(x.status=Number(u.getAttribute("status"))||x.status,x.statusText=u.getAttribute("statusText")||x.statusText);var c=(f.dataType||"").toLowerCase(),l=/(json|script|text)/.test(c);if(l||f.textarea){var p=O.getElementsByTagName("textarea")[0];if(p)x.responseText=p.value,x.status=Number(p.getAttribute("status"))||x.status,x.statusText=p.getAttribute("statusText")||x.statusText;else if(l){var m=O.getElementsByTagName("pre")[0],g=O.getElementsByTagName("body")[0];m?x.responseText=m.textContent?m.textContent:m.innerText:g&&(x.responseText=g.textContent?g.textContent:g.innerText)}}else"xml"===c&&!x.responseXML&&x.responseText&&(x.responseXML=q(x.responseText));try{M=N(x,c,f)}catch(e){i="parsererror",x.error=r=e||i}}catch(e){a("error caught: ",e),i="error",x.error=r=e||i}x.aborted&&(a("upload aborted"),i=null),x.status&&(i=x.status>=200&&x.status<300||304===x.status?"success":"error"),"success"===i?(f.success&&f.success.call(f.context,M,"success",x),S.resolve(x.responseText,"success",x),d&&e.event.trigger("ajaxSuccess",[x,f])):i&&(void 0===r&&(r=x.statusText),f.error&&f.error.call(f.context,x,i,r),S.reject(x,"error",r),d&&e.event.trigger("ajaxError",[x,f,r])),d&&e.event.trigger("ajaxComplete",[x,f]),d&&!--e.active&&e.event.trigger("ajaxStop"),f.complete&&f.complete.call(f.context,x,i),X=!0,f.timeout&&clearTimeout(j),setTimeout(function(){f.iframeTarget?h.attr("src",f.iframeSrc):h.remove(),x.responseXML=null},100)}}}var u,c,f,d,m,h,v,x,y,b,T,j,w=p[0],S=e.Deferred();if(S.abort=function(e){x.abort(e)},r)for(c=0;c<g.length;c++)u=e(g[c]),o?u.prop("disabled",!1):u.removeAttr("disabled");(f=e.extend(!0,{},e.ajaxSettings,t)).context=f.context||f,m="jqFormIO"+(new Date).getTime();var k=w.ownerDocument,D=p.closest("body");if(f.iframeTarget?(b=(h=e(f.iframeTarget,k)).attr2("name"))?m=b:h.attr2("name",m):(h=e('<iframe name="'+m+'" src="'+f.iframeSrc+'" />',k)).css({position:"absolute",top:"-1000px",left:"-1000px"}),v=h[0],x={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(t){var r="timeout"===t?"timeout":"aborted";a("aborting upload... "+r),this.aborted=1;try{v.contentWindow.document.execCommand&&v.contentWindow.document.execCommand("Stop")}catch(e){}h.attr("src",f.iframeSrc),x.error=r,f.error&&f.error.call(f.context,x,r,t),d&&e.event.trigger("ajaxError",[x,f,r]),f.complete&&f.complete.call(f.context,x,r)}},(d=f.global)&&0==e.active++&&e.event.trigger("ajaxStart"),d&&e.event.trigger("ajaxSend",[x,f]),f.beforeSend&&!1===f.beforeSend.call(f.context,x,f))return f.global&&e.active--,S.reject(),S;if(x.aborted)return S.reject(),S;(y=w.clk)&&(b=y.name)&&!y.disabled&&(f.extraData=f.extraData||{},f.extraData[b]=y.value,"image"===y.type&&(f.extraData[b+".x"]=w.clk_x,f.extraData[b+".y"]=w.clk_y));var A=1,L=2,F=e("meta[name=csrf-token]").attr("content"),E=e("meta[name=csrf-param]").attr("content");E&&F&&(f.extraData=f.extraData||{},f.extraData[E]=F),f.forceSync?i():setTimeout(i,10);var M,O,X,C=50,q=e.parseXML||function(e,t){return window.ActiveXObject?((t=new ActiveXObject("Microsoft.XMLDOM")).async="false",t.loadXML(e)):t=(new DOMParser).parseFromString(e,"text/xml"),t&&t.documentElement&&"parsererror"!==t.documentElement.nodeName?t:null},_=e.parseJSON||function(e){return window.eval("("+e+")")},N=function(t,r,a){var n=t.getResponseHeader("content-type")||"",i=("xml"===r||!r)&&n.indexOf("xml")>=0,o=i?t.responseXML:t.responseText;return i&&"parsererror"===o.documentElement.nodeName&&e.error&&e.error("parsererror"),a&&a.dataFilter&&(o=a.dataFilter(o,r)),"string"==typeof o&&(("json"===r||!r)&&n.indexOf("json")>=0?o=_(o):("script"===r||!r)&&n.indexOf("javascript")>=0&&e.globalEval(o)),o};return S}if(!this.length)return a("ajaxSubmit: skipping submit process - no element selected"),this;var l,f,d,p=this;"function"==typeof t?t={success:t}:"string"==typeof t||!1===t&&arguments.length>0?(t={url:t,data:r,dataType:n},"function"==typeof s&&(t.success=s)):void 0===t&&(t={}),l=t.method||t.type||this.attr2("method"),(d=(d="string"==typeof(f=t.url||this.attr2("action"))?e.trim(f):"")||window.location.href||"")&&(d=(d.match(/^([^#]+)/)||[])[1]),t=e.extend(!0,{url:d,success:e.ajaxSettings.success,type:l||e.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},t);var m={};if(this.trigger("form-pre-serialize",[this,t,m]),m.veto)return a("ajaxSubmit: submit vetoed via form-pre-serialize trigger"),this;if(t.beforeSerialize&&!1===t.beforeSerialize(this,t))return a("ajaxSubmit: submit aborted via beforeSerialize callback"),this;var h=t.traditional;void 0===h&&(h=e.ajaxSettings.traditional);var v,g=[],x=this.formToArray(t.semantic,g,t.filtering);if(t.data){var y=e.isFunction(t.data)?t.data(x):t.data;t.extraData=y,v=e.param(y,h)}if(t.beforeSubmit&&!1===t.beforeSubmit(x,this,t))return a("ajaxSubmit: submit aborted via beforeSubmit callback"),this;if(this.trigger("form-submit-validate",[x,this,t,m]),m.veto)return a("ajaxSubmit: submit vetoed via form-submit-validate trigger"),this;var b=e.param(x,h);v&&(b=b?b+"&"+v:v),"GET"===t.type.toUpperCase()?(t.url+=(t.url.indexOf("?")>=0?"&":"?")+b,t.data=null):t.data=b;var T=[];if(t.resetForm&&T.push(function(){p.resetForm()}),t.clearForm&&T.push(function(){p.clearForm(t.includeHidden)}),!t.dataType&&t.target){var j=t.success||function(){};T.push(function(r,a,n){var i=arguments,o=t.replaceTarget?"replaceWith":"html";e(t.target)[o](r).each(function(){j.apply(this,i)})})}else t.success&&(e.isArray(t.success)?e.merge(T,t.success):T.push(t.success));if(t.success=function(e,r,a){for(var n=t.context||this,i=0,o=T.length;i<o;i++)T[i].apply(n,[e,r,a||p,p])},t.error){var w=t.error;t.error=function(e,r,a){var n=t.context||this;w.apply(n,[e,r,a,p])}}if(t.complete){var S=t.complete;t.complete=function(e,r){var a=t.context||this;S.apply(a,[e,r,p])}}var k=e("input[type=file]:enabled",this).filter(function(){return""!==e(this).val()}).length>0,D="multipart/form-data",A=p.attr("enctype")===D||p.attr("encoding")===D,L=i.fileapi&&i.formdata;a("fileAPI :"+L);var F,E=(k||A)&&!L;!1!==t.iframe&&(t.iframe||E)?t.closeKeepAlive?e.get(t.closeKeepAlive,function(){F=c(x)}):F=c(x):F=(k||A)&&L?function(r){for(var a=new FormData,n=0;n<r.length;n++)a.append(r[n].name,r[n].value);if(t.extraData){var i=u(t.extraData);for(n=0;n<i.length;n++)i[n]&&a.append(i[n][0],i[n][1])}t.data=null;var o=e.extend(!0,{},e.ajaxSettings,t,{contentType:!1,processData:!1,cache:!1,type:l||"POST"});t.uploadProgress&&(o.xhr=function(){var r=e.ajaxSettings.xhr();return r.upload&&r.upload.addEventListener("progress",function(e){var r=0,a=e.loaded||e.position,n=e.total;e.lengthComputable&&(r=Math.ceil(a/n*100)),t.uploadProgress(e,a,n,r)},!1),r}),o.data=null;var s=o.beforeSend;return o.beforeSend=function(e,r){t.formData?r.data=t.formData:r.data=a,s&&s.call(this,e,r)},e.ajax(o)}(x):e.ajax(t),p.removeData("jqxhr").data("jqxhr",F);for(var M=0;M<g.length;M++)g[M]=null;return this.trigger("form-submit-notify",[this,t]),this},e.fn.ajaxForm=function(n,i,o,s){if(("string"==typeof n||!1===n&&arguments.length>0)&&(n={url:n,data:i,dataType:o},"function"==typeof s&&(n.success=s)),n=n||{},n.delegation=n.delegation&&e.isFunction(e.fn.on),!n.delegation&&0===this.length){var u={s:this.selector,c:this.context};return!e.isReady&&u.s?(a("DOM not ready, queuing ajaxForm"),e(function(){e(u.s,u.c).ajaxForm(n)}),this):(a("terminating; zero elements found by selector"+(e.isReady?"":" (DOM not ready)")),this)}return n.delegation?(e(document).off("submit.form-plugin",this.selector,t).off("click.form-plugin",this.selector,r).on("submit.form-plugin",this.selector,n,t).on("click.form-plugin",this.selector,n,r),this):this.ajaxFormUnbind().on("submit.form-plugin",n,t).on("click.form-plugin",n,r)},e.fn.ajaxFormUnbind=function(){return this.off("submit.form-plugin click.form-plugin")},e.fn.formToArray=function(t,r,a){var n=[];if(0===this.length)return n;var o,s=this[0],u=this.attr("id"),c=t||void 0===s.elements?s.getElementsByTagName("*"):s.elements;if(c&&(c=e.makeArray(c)),u&&(t||/(Edge|Trident)\//.test(navigator.userAgent))&&(o=e(':input[form="'+u+'"]').get()).length&&(c=(c||[]).concat(o)),!c||!c.length)return n;e.isFunction(a)&&(c=e.map(c,a));var l,f,d,p,m,h,v;for(l=0,h=c.length;l<h;l++)if(m=c[l],(d=m.name)&&!m.disabled)if(t&&s.clk&&"image"===m.type)s.clk===m&&(n.push({name:d,value:e(m).val(),type:m.type}),n.push({name:d+".x",value:s.clk_x},{name:d+".y",value:s.clk_y}));else if((p=e.fieldValue(m,!0))&&p.constructor===Array)for(r&&r.push(m),f=0,v=p.length;f<v;f++)n.push({name:d,value:p[f]});else if(i.fileapi&&"file"===m.type){r&&r.push(m);var g=m.files;if(g.length)for(f=0;f<g.length;f++)n.push({name:d,value:g[f],type:m.type});else n.push({name:d,value:"",type:m.type})}else null!==p&&void 0!==p&&(r&&r.push(m),n.push({name:d,value:p,type:m.type,required:m.required}));if(!t&&s.clk){var x=e(s.clk),y=x[0];(d=y.name)&&!y.disabled&&"image"===y.type&&(n.push({name:d,value:x.val()}),n.push({name:d+".x",value:s.clk_x},{name:d+".y",value:s.clk_y}))}return n},e.fn.formSerialize=function(t){return e.param(this.formToArray(t))},e.fn.fieldSerialize=function(t){var r=[];return this.each(function(){var a=this.name;if(a){var n=e.fieldValue(this,t);if(n&&n.constructor===Array)for(var i=0,o=n.length;i<o;i++)r.push({name:a,value:n[i]});else null!==n&&void 0!==n&&r.push({name:this.name,value:n})}}),e.param(r)},e.fn.fieldValue=function(t){for(var r=[],a=0,n=this.length;a<n;a++){var i=this[a],o=e.fieldValue(i,t);null===o||void 0===o||o.constructor===Array&&!o.length||(o.constructor===Array?e.merge(r,o):r.push(o))}return r},e.fieldValue=function(t,r){var a=t.name,i=t.type,o=t.tagName.toLowerCase();if(void 0===r&&(r=!0),r&&(!a||t.disabled||"reset"===i||"button"===i||("checkbox"===i||"radio"===i)&&!t.checked||("submit"===i||"image"===i)&&t.form&&t.form.clk!==t||"select"===o&&-1===t.selectedIndex))return null;if("select"===o){var s=t.selectedIndex;if(s<0)return null;for(var u=[],c=t.options,l="select-one"===i,f=l?s+1:c.length,d=l?s:0;d<f;d++){var p=c[d];if(p.selected&&!p.disabled){var m=p.value;if(m||(m=p.attributes&&p.attributes.value&&!p.attributes.value.specified?p.text:p.value),l)return m;u.push(m)}}return u}return e(t).val().replace(n,"\r\n")},e.fn.clearForm=function(t){return this.each(function(){e("input,select,textarea",this).clearFields(t)})},e.fn.clearFields=e.fn.clearInputs=function(t){var r=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var a=this.type,n=this.tagName.toLowerCase();r.test(a)||"textarea"===n?this.value="":"checkbox"===a||"radio"===a?this.checked=!1:"select"===n?this.selectedIndex=-1:"file"===a?/MSIE/.test(navigator.userAgent)?e(this).replaceWith(e(this).clone(!0)):e(this).val(""):t&&(!0===t&&/hidden/.test(a)||"string"==typeof t&&e(this).is(t))&&(this.value="")})},e.fn.resetForm=function(){return this.each(function(){var t=e(this),r=this.tagName.toLowerCase();switch(r){case"input":this.checked=this.defaultChecked;case"textarea":return this.value=this.defaultValue,!0;case"option":case"optgroup":var a=t.parents("select");return a.length&&a[0].multiple?"option"===r?this.selected=this.defaultSelected:t.find("option").resetForm():a.resetForm(),!0;case"select":return t.find("option").each(function(e){if(this.selected=this.defaultSelected,this.defaultSelected&&!t[0].multiple)return t[0].selectedIndex=e,!1}),!0;case"label":var n=e(t.attr("for")),i=t.find("input,select,textarea");return n[0]&&i.unshift(n[0]),i.resetForm(),!0;case"form":return("function"==typeof this.reset||"object"==typeof this.reset&&!this.reset.nodeType)&&this.reset(),!0;default:return t.find("form,input,label,select,textarea").resetForm(),!0}})},e.fn.enable=function(e){return void 0===e&&(e=!0),this.each(function(){this.disabled=!e})},e.fn.selected=function(t){return void 0===t&&(t=!0),this.each(function(){var r=this.type;if("checkbox"===r||"radio"===r)this.checked=t;else if("option"===this.tagName.toLowerCase()){var a=e(this).parent("select");t&&a[0]&&"select-one"===a[0].type&&a.find("option").selected(!1),this.selected=t}})},e.fn.ajaxSubmit.debug=!1});
//# sourceMappingURL=jquery.form.min.js.map

/* ========================================================================
 * jQuery Hotkeys Plugin
 * Based upon the plugin by Tzury Bar Yochay:
 * https://github.com/tzuryby/jquery.hotkeys
 *  
 * ZUI: The file has been changed in ZUI. It will not keep update with the
 * official version in the future.
 * http://zui.sexy
 * ========================================================================
 * Copyright 2010, John Resig
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * Original idea by:
 * Binny V A, http://www.openjs.com/scripts/events/keyboard_shortcuts/
 * ======================================================================== */


/*!
 * jQuery Hotkeys Plugin
 * Copyright 2010, John Resig
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Based upon the plugin by Tzury Bar Yochay:
 * http://github.com/tzuryby/hotkeys
 *
 * Original idea by:
 * Binny V A, http://www.openjs.com/scripts/events/keyboard_shortcuts/
*/

(function(jQuery) {

    jQuery.hotkeys = {
        version: "0.8",

        specialKeys: {
            8: "backspace",
            9: "tab",
            13: "return",
            16: "shift",
            17: "ctrl",
            18: "alt",
            19: "pause",
            20: "capslock",
            27: "esc",
            32: "space",
            33: "pageup",
            34: "pagedown",
            35: "end",
            36: "home",
            37: "left",
            38: "up",
            39: "right",
            40: "down",
            45: "insert",
            46: "del",
            96: "0",
            97: "1",
            98: "2",
            99: "3",
            100: "4",
            101: "5",
            102: "6",
            103: "7",
            104: "8",
            105: "9",
            106: "*",
            107: "+",
            109: "-",
            110: ".",
            111: "/",
            112: "f1",
            113: "f2",
            114: "f3",
            115: "f4",
            116: "f5",
            117: "f6",
            118: "f7",
            119: "f8",
            120: "f9",
            121: "f10",
            122: "f11",
            123: "f12",
            144: "numlock",
            145: "scroll",
            191: "/",
            224: "meta"
        },

        shiftNums: {
            "`": "~",
            "1": "!",
            "2": "@",
            "3": "#",
            "4": "$",
            "5": "%",
            "6": "^",
            "7": "&",
            "8": "*",
            "9": "(",
            "0": ")",
            "-": "_",
            "=": "+",
            ";": ": ",
            "'": "\"",
            ",": "<",
            ".": ">",
            "/": "?",
            "\\": "|"
        }
    };

    function keyHandler(handleObj) {
        // Only care when a possible input has been specified
        if(typeof handleObj.data !== "string") {
            return;
        }

        var origHandler = handleObj.handler,
            keys = handleObj.data.toLowerCase().split(" ");

        handleObj.handler = function(event) {
            // Don't fire in text-accepting inputs that we didn't directly bind to
            if(this !== event.target && (/textarea|select/i.test(event.target.nodeName) ||
                    event.target.type === "text")) {
                return;
            }

            // Keypress represents characters, not special keys
            var special = event.type !== "keypress" && jQuery.hotkeys.specialKeys[event.which],
                character = String.fromCharCode(event.which).toLowerCase(),
                key, modif = "",
                possible = {};

            // check combinations (alt|ctrl|shift+anything)
            if(event.altKey && special !== "alt") {
                modif += "alt+";
            }

            if(event.ctrlKey && special !== "ctrl") {
                modif += "ctrl+";
            }

            // TODO: Need to make sure this works consistently across platforms
            if(event.metaKey && !event.ctrlKey && special !== "meta") {
                modif += "meta+";
            }

            if(event.shiftKey && special !== "shift") {
                modif += "shift+";
            }

            if(special) {
                possible[modif + special] = true;

            } else {
                possible[modif + character] = true;
                possible[modif + jQuery.hotkeys.shiftNums[character]] = true;

                // "$" can be triggered as "Shift+4" or "Shift+$" or just "$"
                if(modif === "shift+") {
                    possible[jQuery.hotkeys.shiftNums[character]] = true;
                }
            }

            for(var i = 0, l = keys.length; i < l; i++) {
                if(possible[keys[i]]) {
                    return origHandler.apply(this, arguments);
                }
            }
        };
    }

    jQuery.each(["keydown", "keyup", "keypress"], function() {
        jQuery.event.special[this] = {
            add: keyHandler
        };
    });

})(jQuery);


/* ========================================================================
 * ZUI: tree.js [1.4.0+]
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($) {
    'use strict';

    var name = 'zui.tree'; // modal name
    var globalId = 0;

    // The tree modal class
    var Tree = function(element, options) {
        this.name = name;
        this.$ = $(element);

        this.getOptions(options);
        this._init();
    };

    var DETAULT_ACTIONS = {
        sort: {
            template: '<a class="sort-handler" href="javascript:;"><i class="icon icon-move"></i></a>'
        },
        add: {
            template: '<a href="javascript:;"><i class="icon icon-plus"></i></a>'
        },
        edit: {
            template: '<a href="javascript:;"><i class="icon icon-pencil"></i></a>'
        },
        "delete": {
            template: '<a href="javascript:;"><i class="icon icon-trash"></i></a>'
        }
    };

    function formatActions(actions, parentActions) {
        if(actions === false) return actions;
        if(!actions) return parentActions;

        if(actions === true) {
            actions = {add: true, "delete": true, edit: true, sort: true};
        } else if(typeof actions === 'string') {
            actions = actions.split(',');
        }
        var _actions;
        if($.isArray(actions)) {
            _actions = {};
            $.each(actions, function(idx, action) {
                if($.isPlainObject(action)) {
                    _actions[action.action] = action;
                } else {
                    _actions[action] = true;
                }
            });
            actions = _actions;
        }
        if($.isPlainObject(actions)) {
            _actions = {};
            $.each(actions, function(name, action) {
                if(action) {
                    _actions[name] = $.extend({type: name}, DETAULT_ACTIONS[name], $.isPlainObject(action) ? action : null);
                } else {
                    _actions[name] = false;
                }
            });
            actions = _actions;
        }
        return parentActions ? $.extend(true, {}, parentActions, actions) : actions;
    }

    function createActionEle(action, name, template) {
        name = name || action.type;
        return $(template || action.template).addClass('tree-action').attr($.extend({'data-type': name, title: action.title || ''}, action.attr)).data('action', action);
    }

    // default options
    Tree.DEFAULTS = {
        animate: null,
        initialState: 'normal', // 'normal' | 'preserve' | 'expand' | 'collapse',
        toggleTemplate: '<i class="list-toggle icon"></i>',
        // sortable: false, //
    };

    Tree.prototype.add = function(rootEle, items, expand, disabledAnimate, notStore) {
        var $e = $(rootEle), $ul, options = this.options;
        if($e.is('li')) {
            $ul = $e.children('ul');
            if(!$ul.length) {
                $ul = $('<ul/>');
                $e.append($ul);
                this._initList($ul, $e);
            }
        } else {
            $ul = $e;
        }

        if($ul) {
            var that = this;
            if(!$.isArray(items)) {
                items = [items];
            }
            $.each(items, function(idx, item) {
                var $li = $('<li/>').data(item).appendTo($ul);
                if(item.id !== undefined) $li.attr('data-id', item.id);
                var $wrapper = options.itemWrapper ? $(options.itemWrapper === true ? '<div class="tree-item-wrapper"/>' : options.itemWrapper).appendTo($li) : $li;
                if(item.html) {
                    $wrapper.html(item.html)
                } else if($.isFunction(that.options.itemCreator)) {
                    var itemContent = that.options.itemCreator($li, item);
                    if(itemContent !== true && itemContent !== false) $wrapper.html(itemContent);
                } else if(item.url) {
                    $wrapper.append($('<a/>', {href: item.url}).text(item.title || item.name));
                } else {
                    $wrapper.append($('<span/>').text(item.title || item.name));
                }
                that._initItem($li, item.idx || idx, $ul, item);
                if(item.children && item.children.length) {
                    that.add($li, item.children);
                }
            });
            this._initList($ul);
            if(expand && !$ul.hasClass('tree')) {
                that.expand($ul.parent('li'), disabledAnimate, notStore);
            }
        }
    };

    Tree.prototype.reload = function(data) {
        var that = this;

        if(data) {
            that.$.empty();
            that.add(that.$, data);
        }

        if(that.isPreserve)
        {
            if(that.store.time) {
                that.$.find('li:not(.tree-action-item)').each(function() {
                    var $li= $(this);
                    that[that.store[$li.data('id')] ? 'expand' : 'collapse']($li, true, true);
                });
            }
        }
    };

    Tree.prototype._initList = function($list, $parentItem, idx, data) {
        var that = this;
        if(!$list.hasClass('tree')) {
            $parentItem = ($parentItem || $list.closest('li')).addClass('has-list');
            if(!$parentItem.find('.list-toggle').length) {
                $parentItem.prepend(this.options.toggleTemplate);
            }
            idx = idx || $parentItem.data('idx');
        } else {
            idx = 0;
            $parentItem = null;
        }
        var $children = $list.attr('data-idx', idx || 0).children('li:not(.tree-action-item)').each(function(index) {
            that._initItem($(this), index + 1, $list);
        });
        if($children.length === 1 && !$children.find('ul').length)
        {
            $children.addClass('tree-single-item');
        }
        data = data || ($parentItem ? $parentItem.data() : null);
        var actions = formatActions(data ? data.actions : null, this.actions);
        if(actions) {
            if(actions.add && actions.add.templateInList !== false) {
                var $actionItem = $list.children('li.tree-action-item');
                if(!$actionItem.length) {
                    $('<li class="tree-action-item"/>').append(createActionEle(actions.add, 'add', actions.add.templateInList)).appendTo($list);
                } else {
                    $actionItem.detach().appendTo($list);
                }
            }
            if(actions.sort) {
                $list.sortable($.extend({
                    dragCssClass: 'tree-drag-holder', 
                    trigger: '.sort-handler', 
                    selector: 'li:not(.tree-action-item)',
                    finish: function(e) {
                        that.callEvent('action', {action: actions.sort, $list: $list, target: e.target, item: data});
                    }
                }, actions.sort.options, $.isPlainObject(this.options.sortable) ? this.options.sortable : null));
            }
        }
        if($parentItem && ($parentItem.hasClass('open') || (data && data.open))) {
            $parentItem.addClass('open in');
        }
    };

    Tree.prototype._initItem = function($item, idx, $parentList, data) {
        if(idx === undefined) {
            var $pre = $item.prev('li');
            idx = $pre.length ? ($pre.data('idx') + 1) : 1;
        }
        $parentList = $parentList || $item.closest('ul');
        $item.attr('data-idx', idx).removeClass('tree-single-item');
        if(!$item.data('id')) {
            var id = idx;
            if(!$parentList.hasClass('tree')) {
                id = $parentList.parent('li').data('id') + '-' + id;
            }
            $item.attr('data-id', id);
        }
        data = data || $item.data();
        var actions = formatActions(data.actions, this.actions);
        if(actions) {
            var $actions = $item.find('.tree-actions');
            if(!$actions.length) {
                $actions = $('<div class="tree-actions"/>').appendTo(this.options.itemWrapper ? $item.find('.tree-item-wrapper') : $item);
                $.each(actions, function(actionName, action) {
                    if(action) $actions.append(createActionEle(action, actionName));
                });
            }
        }

        var $children = $item.children('ul');
        if($children.length) {
            this._initList($children, $item, idx, data);
        }
    };

    Tree.prototype._init = function() {
        var options = this.options, that = this;
        this.actions = formatActions(options.actions);

        this.$.addClass('tree');
        if(options.animate) this.$.addClass('tree-animate');

        this._initList(this.$);

        var initialState = options.initialState;
        var isPreserveEnable = $.zui && $.zui.store && $.zui.store.enable;
        if(isPreserveEnable) {
            this.selector = name + '::' + (options.name || '') + '#' + (this.$.attr('id') || globalId++);
            this.store = $.zui.store[options.name ? 'get' : 'pageGet'](this.selector, {});
        }
        if(initialState === 'preserve') {
            if(isPreserveEnable) this.isPreserve = true;
            else this.options.initialState = initialState = 'normal';
        }

        // init data
        this.reload(options.data);
        if(isPreserveEnable) this.isPreserve = true;

        if(initialState === 'expand') {
            this.expand();
        } else if(initialState === 'collapse') {
            this.collapse();
        }

        // Bind event
        this.$.on('click', '.list-toggle,a[href="#"],.tree-toggle', function(e) {
            var $this = $(this);
            var $li = $this.parent('li');
            that.callEvent('hit', {target: $li, item: $li.data()});
            that.toggle($li);
            if($this.is('a')) e.preventDefault();
        }).on('click', '.tree-action', function() {
            var $action = $(this);
            var action = $action.data();
            if(action.action) action = action.action;
            if(action.type === 'sort') return;
            var $li = $action.closest('li:not(.tree-action-item)');
            that.callEvent('action', {action: action, target: this, $item: $li, item: $li.data()});
        });
    };

    Tree.prototype.preserve = function($li, id, expand) {
        if(!this.isPreserve) return;
        if($li) {
            id = id || $li.data('id');
            expand = expand === undefined ? $li.hasClass('open') : false;
            if(expand) this.store[id] = expand;
            else delete this.store[id];
            this.store.time = new Date().getTime();
            $.zui.store[this.options.name ? 'set' : 'pageSet'](this.selector, this.store);
        } else {
            var that = this;
            this.store = {};
            this.$.find('li').each(function() {
                that.preserve($(this));
            });
        }
    };

    Tree.prototype.expand = function($li, disabledAnimate, notStore) {
        if($li) {
            $li.addClass('open');
            if(!disabledAnimate && this.options.animate) {
                setTimeout(function() {
                    $li.addClass('in');
                }, 10);
            } else {
                $li.addClass('in');
            }
        } else {
            $li = this.$.find('li.has-list').addClass('open in');
        }
        if(!notStore) this.preserve($li);
        this.callEvent('expand', $li, this);
    };

    Tree.prototype.show = function($lis, disabledAnimate, notStore) {
        var that = this;
        $lis.each(function() {
            var $li = $(this);
            that.expand($li, disabledAnimate, notStore);
            if($li) {
                var $ul = $li.parent('ul');
                while($ul && $ul.length && !$ul.hasClass('tree')) {
                    var $parentLi = $ul.parent('li');
                    if($parentLi.length) {
                        that.expand($parentLi, disabledAnimate, notStore);
                        $ul = $parentLi.parent('ul');
                    } else {
                        $ul = false;
                    }
                }
            }
        });
    };

    Tree.prototype.collapse = function($li, disabledAnimate, notStore) {
        if($li) {
            if(!disabledAnimate && this.options.animate) {
                $li.removeClass('in');
                setTimeout(function() {
                    $li.removeClass('open');
                }, 300);
            } else {
                $li.removeClass('open in');
            }
        } else {
            $li = this.$.find('li.has-list').removeClass('open in');
        }
        if(!notStore) this.preserve($li);
        this.callEvent('collapse', $li, this);
    };

    Tree.prototype.toggle = function($li) {
        var collapse = ($li && $li.hasClass('open')) || $li === false || ($li === undefined && this.$.find('li.has-list.open').length);
        this[collapse ? 'collapse' : 'expand']($li);
    };

    // Get and init options
    Tree.prototype.getOptions = function(options) {
        this.options = $.extend({}, Tree.DEFAULTS, this.$.data(), options);
        if(this.options.animate === null && this.$.hasClass('tree-animate')) {
            this.options.animate = true;
        }
    };

    Tree.prototype.toData = function($ul, filter) {
        if($.isFunction($ul)) {
            filter = $ul;
            $ul = null;
        }
        $ul = $ul || this.$;
        var that = this;
        return $ul.children('li:not(.tree-action-item)').map(function() {
            var $li = $(this);
            var data = $li.data();
            delete data['zui.droppable'];
            var $children = $li.children('ul');
            if($children.length) data.children = that.toData($children);
            return $.isFunction(filter) ? filter(data, $li) : data;
        }).get();
    };

    // Call event helper
    Tree.prototype.callEvent = function(name, params) {
        var result;
        if($.isFunction(this.options[name])) {
            result = this.options[name](params, this);
        }
        this.$.trigger($.Event(name + '.' + this.name, params));
        return result;
    };

    // Extense jquery element
    $.fn.tree = function(option, params) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(name);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(name, (data = new Tree(this, options)));

            if(typeof option == 'string') data[option](params);
        });
    };

    $.fn.tree.Constructor = Tree;

    // Auto call tree after document load complete
    $(function() {
        $('[data-ride="tree"]').tree();
    });
}(jQuery));


/* ========================================================================
 * ZUI: ColorPicker.js [1.5.0+]
 * http://zui.sexy
 * ========================================================================
 * Copyright (c) 2016 cnezsoft.com; Licensed MIT
 * ======================================================================== */


(function($) {
    'use strict';

    var name = 'zui.colorPicker'; // modal name
    var TEAMPLATE = '<div class="colorpicker"><button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><i class="ic"></i></button><ul class="dropdown-menu clearfix"></ul></div>';
    var LANG = {
        zh_cn: {
            errorTip: "不是有效的颜色值"
        },
        zh_tw: {
            errorTip: "不是有效的顏色值"
        },
        en: {
            errorTip: "Not a valid color value"
        }
    };

    // The ColorPicker modal class
    var ColorPicker = function(element, options) {
        this.name = name;
        this.$ = $(element);

        this.getOptions(options);
        this.init();
    };

    // default options
    ColorPicker.DEFAULTS = {
        colors: ['#00BCD4', '#388E3C', '#3280fc', '#3F51B5', '#9C27B0', '#795548', '#F57C00', '#F44336', '#E91E63'],
        pullMenuRight: true,
        wrapper: 'btn-wrapper',
        tileSize: 30,
        lineCount: 5,
        optional: true,
        tooltip: 'top',
        icon: 'caret-down',
        updateBtn: 'auto'
        // btnTip: 'Tool tip in button'
    };

    ColorPicker.prototype.init = function() {
        var that = this;
        var options = that.options;
        var $input = that.$;
        var $parent = $input.parent();
        var createdPicker = false;
        if ($parent.hasClass('colorpicker')) {
            that.$picker = $parent;
        } else {
            that.$picker = $(options.template || TEAMPLATE);
            createdPicker = true;
        }
        that.$picker.addClass(options.wrapper).find('.cp-title').toggle(options.title !== undefined).text(options.title);
        that.$menu = that.$picker.find('.dropdown-menu').toggleClass('pull-right', options.pullMenuRight);
        that.$btn = that.$picker.find('.btn.dropdown-toggle');
        that.$btn.find('.ic').addClass('icon-' + options.icon);
        if(options.btnTip) {
            that.$picker.attr('data-toggle', 'tooltip').tooltip({title: options.btnTip, placement: options.tooltip, container: 'body'});
        }
        $input.attr('data-provide', null);
        if (createdPicker) {
            $input.after(that.$picker);
        }

        // init colors
        that.colors = {};
        $.each(options.colors, function(idx, rawColor) {
            if($.zui.Color.isColor(rawColor)) {
                var color = new $.zui.Color(rawColor);
                that.colors[color.toCssStr()] = color;
            }
        });

        that.updateColors();
        that.$picker.on('click', '.cp-tile', function() {
            that.setValue($(this).data('color'));
        });

        var setInputColor = function() {
            var val = $input.val();
            var isColor = $.zui.Color.isColor(val);
            $input.parent().toggleClass('has-error', !isColor && !(options.optional && val === ''));
            if(isColor) {
                that.setValue(val, true);
            } else {
                if(options.optional && val === '') {
                    $input.tooltip('hide');
                } else if(!$input.is(':focus')) {
                    $input.tooltip('show', options.errorTip);
                }
            }
        }
        if($input.is('input:not([type=hidden])')) {
            if(options.tooltip) {
                $input.attr('data-toggle', 'tooltip').tooltip({trigger: 'manual', placement: options.tooltip, tipClass: 'tooltip-danger', container: 'body'});
            }
            $input.on('keyup paste input change', setInputColor);
        } else {
            $input.appendTo(that.$picker);
        }
        setInputColor();
    };

    ColorPicker.prototype.addColor = function(color) {
        if(!(color instanceof $.zui.Color)) {
            color = new $.zui.Color(color);
        }
        var hex = color.toCssStr(),
            options = this.options;

        if(!this.colors[hex]) {
            this.colors[hex] = color;
        }

        var $a = $('<a href="###" class="cp-tile"></a>', {
            titile: color
        }).data('color', color).css({
            'color': color.contrast().toCssStr(),
            'background': hex,
            'border-color': color.luma() > 0.43 ? '#ccc' : 'transparent'
        }).attr('data-color', hex);
        this.$menu.append($('<li/>').css({width: options.tileSize, height: options.tileSize}).append($a));
        if(options.optional) {
            this.$menu.find('.cp-tile.empty').parent().detach().appendTo(this.$menu);
        }
    };

    ColorPicker.prototype.updateColors = function(colors) {
        var $picker = this.$picker,
            $menu = this.$menu.children('li:not(.heading)').remove(),
            options = this.options,
            colors = colors || this.colors,
            that = this;
        var bestLineCount = 0;
        $.each(colors, function(idx, color) {
            that.addColor(color);
            bestLineCount++;
        });
        if(options.optional) {
            var $li = $('<li><a class="cp-tile empty" href="###"></a></li>').css({width: options.tileSize, height: options.tileSize});
            this.$menu.append($li);
            bestLineCount++;
        }
        $menu.css('width', Math.min(bestLineCount, options.lineCount) * options.tileSize + 6);
    };

    ColorPicker.prototype.setValue = function(color, notSetInput) {
        var that = this;
        var options = that.options;
        var $btn = that.$btn;
        that.$menu.find('.cp-tile.active').removeClass('active');
        var hex = '';
        var updateBtn = options.updateBtn;
        if (updateBtn === 'auto') {
            var $btnBar = $btn.find('.color-bar');
            updateBtn = $btnBar.length ? function(hexColor) {
                $btnBar.css('background', hexColor || '');
            } : true;
        }
        if(color) {
            var c = new $.zui.Color(color);
            hex = c.toCssStr().toLowerCase();
            if (updateBtn) {
                if ($.isFunction(updateBtn)) {
                    updateBtn(hex, $btn, that);
                } else {
                    $btn.css({
                        background: hex,
                        color: c.contrast().toCssStr(),
                        borderColor: c.luma() > 0.43 ? '#ccc' : hex
                    });
                }
            }
            if(!that.colors[hex]) {
                that.addColor(c);
            }
            if(!notSetInput && that.$.val().toLowerCase() !== hex) {
                that.$.val(hex).trigger('change');
            }
            that.$menu.find('.cp-tile[data-color="' + hex + '"]').addClass('active');
            that.$.tooltip('hide');
            that.$.trigger('colorchange', c);
        } else {
            if (updateBtn) {
                if ($.isFunction(updateBtn)) {
                    updateBtn(null, $btn, that);
                } else {
                    $btn.attr('style', null);
                }
            }
            if(!notSetInput && that.$.val() !== '') {
                that.$.val(hex).trigger('change');
            }
            if(options.optional) {
                that.$.tooltip('hide');
            }
            that.$menu.find('.cp-tile.empty').addClass('active');
            that.$.trigger('colorchange', null);
        }

        if(options.updateBorder) {
            $(options.updateBorder).css('border-color', hex);
        }
        if(options.updateBackground) {
            $(options.updateBackground).css('background-color', hex);
        }
        if(options.updateColor) {
            $(options.updateColor).css('color', hex);
        }
        if(options.updateText) {
            $(options.updateText).text(hex);
        }
    };

    // Get and init options
    ColorPicker.prototype.getOptions = function(options) {
        var thisOptions = $.extend({}, ColorPicker.DEFAULTS, this.$.data(), options);
        if(typeof thisOptions.colors === 'string') thisOptions.colors = thisOptions.colors.split(',');
        var lang = (thisOptions.lang || $.zui.clientLang()).toLowerCase();
        if(!thisOptions.errorTip) {
            thisOptions.errorTip = LANG[lang].errorTip;
        }
        if(!$.fn.tooltip) thisOptions.btnTip = false;
        this.options = thisOptions;
    };

    // Extense jquery element
    $.fn.colorPicker = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(name);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(name, (data = new ColorPicker(this, options)));

            if(typeof option == 'string') data[option]();
        });
    };

    $.fn.colorPicker.Constructor = ColorPicker;

    // Auto call colorPicker after document load complete
    $(function() {
        $('[data-provide="colorpicker"]').colorPicker();
    });
}(jQuery));

(function ($, undefined) {
    var colorIndex = 0;
    var accentsColors = [
        '#00a9fc', '#ff5d5d', '#fdc137', '#00da88', '#7ec5ff', '#8666b8', '#bd7b46', '#ff9100', '#ff3d00', '#f57f17', '#00e5ff', '#00b0ff', '#2979ff', '#3d5afe', '#651fff', '#d500f9', '#f50057', '#ff1744'
    ];
    function nextAccentColor(idx) {
        if (idx === undefined) {
            idx = colorIndex += 1;
        }
        return accentsColors[idx%accentsColors.length];
    }

    jQuery.fn.tableChart = function () {
        $(this).each(function () {
            var $table = $(this);
            var options = $table.data();
            var chartType = options.chart || 'pie';
            var $canvas = $(options.target);
            if (!$canvas.length) return;
            var chart = null;

            if (chartType === 'pie') {
                options = $.extend({ scaleShowLabels: true, scaleLabel: '<%=label%>: <%=value%>' }, options);
                var data = [];
                var $rows = $table.find('tbody > tr').each(function (idx) {
                    var $row = $(this);
                    var color = nextAccentColor();

                    $row.attr('data-id', idx).find('.chart-color-dot').css('background', color);
                    data.push({ label: $row.find('.chart-label').text(), value: parseInt($row.find('.chart-value').text()), color: color, id: idx });
                });

                if (data.length > 1) options.scaleLabelPlacement = 'outside';
                else if (data.length === 1) {
                    options.scaleLabelPlacement = 'inside';
                    data.push({ label: '', value: data[0].value / 2000, color: '#fff', showLabel: false })
                }

                chart = $canvas.pieChart(data, options);
                $canvas.on('mousemove', function (e) {
                    var activePoints = chart.getSegmentsAtEvent(e);
                    $rows.removeClass('active');
                    if (activePoints.length) {
                        $rows.filter('[data-id="' + activePoints[0].id + '"]').addClass('active');
                    }
                });
            }
            else if (chartType === 'bar') {
                var color = nextAccentColor();
                var labels = [], dataset = { label: $table.find('thead .chart-label').text(), color: color, data: [] };

                var $rows = $table.find('tbody > tr').each(function (idx) {
                    var $row = $(this);
                    labels.push($row.find('.chart-label').text());
                    dataset.data.push(parseInt($row.find('.chart-value').text()));
                    $row.find('.chart-color-dot').css('background', color);
                });
                var data = { labels: labels, datasets: [dataset] };
                if (labels.length) options.barValueSpacing = 5;

                chart = $canvas.barChart(data, options);
            }
            else if (chartType === 'line') {
                var color = nextAccentColor();
                var labels = [], dataset = { label: $table.find('thead .chart-label').text(), color: color, data: [] };

                var $rows = $table.find('tbody > tr').each(function (idx) {
                    var $row = $(this);
                    labels.push($row.find('.chart-label').text());
                    dataset.data.push(parseInt($row.find('.chart-value').text()));
                    $row.find('.chart-color-dot').css('background', color);
                });
                var data = { labels: labels, datasets: [dataset] };
                if (labels.length) options.barValueSpacing = 5;

                chart = $canvas.lineChart(data, options);
            }

            if (chart !== null) $table.data('zui.chart', chart);
        });
    };

    var initProgressPie = function(element, setting) {
        var $this = $(element);
        if ($this.data('initProgressPie')) return;
        $this.data('initProgressPie', 1);
        var $canvas = $this.is('canvas') ? $this : $this.find('canvas');
        var options = $.extend({
            value: 0,
            color: $.getThemeColor('primary') || '#006af1',
            backColor: $.getThemeColor('pale') || '#E9F2FB',
            doughnut: true,
            doughnutSize: 85,
            width: 20,
            height: 20,
            showTip: false,
            name: '',
            tipTemplate: "<%=value%>%",
            animation: 'auto',
            realValue: parseFloat($this.find('.progress-value').text())
        }, setting, $this.data());
        var hasCanvas = $canvas.length;
        if (!hasCanvas) $canvas = $('<canvas>').appendTo($this);
        if ($canvas.attr('width') !== undefined) options.width = $canvas.attr('width');
        else $canvas.attr('width', options.width);
        if ($canvas.attr('height') !== undefined) options.height = $canvas.attr('height');
        else $canvas.attr('height', options.height);
        if (!hasCanvas && $.zui.browser.ie == 8) G_vmlCanvasManager.initElement($canvas[0]);
        if (options.animation === 'auto') {
            options.animation = options.width > 30;
        }

        options.value = Math.max(0, Math.min(100, options.value));

        $this.addClass('progress-pie-' + options.width);

        var data = [
            { value: options.value, label: options.name, color: options.color, circleBeginEnd: true },
            { value: 100 - options.value, label: '', color: options.backColor }
        ];

        $canvas[options.doughnut ? 'doughnutChart' : 'pieChart'](data, $.extend({
            segmentShowStroke: false,
            animation: options.animation,
            showTooltips: options.showTip,
            tooltipTemplate: options.tipTemplate,
            percentageInnerCutout: options.doughnutSize,
            reverseDrawOrder: true,
            animationEasing: 'easeInOutQuart',
            onAnimationProgress: options.realValue ? function(progress) {
                $this.find('.progress-value').text(Math.floor(options.realValue * progress));
            } : undefined,
            onAnimationComplete: options.realValue ? function(progress) {
                $this.find('.progress-value').text(options.realValue);
            } : undefined
        }, options.chartOptions));
    };

    jQuery.fn.progressPie = function (setting) {
        $(this).each(function () {
            var $this = $(this);
            if ($this.closest('.hidden').length) return;
            var $tabPane = $this.closest('.tab-pane');
            if ($tabPane.length && !$tabPane.hasClass('active')) {
                $('[data-toggle="tab"][data-target="#' + $tabPane.attr('id') + '"]').one('shown.zui.tab', function() {
                    initProgressPie($this, setting);
                });
            } else {
                initProgressPie(this, setting);
            }
        });
    };

    $(function () {
        $('.table-chart').tableChart();
        var $pies = $('.progress-pie');
        if ($pies.length > 100) setTimeout(function () { $pies.progressPie(); }, 1000);
        else $pies.progressPie();
    });
}(jQuery, undefined));

(function($) {
    jQuery.fn.sparkline = function(setting)
    {
        $(this).each(function()
        {
            var $e = $(this);
            var options = $.extend({
                values: $e.attr('values'),
                width: $e.width() - 4,
                height: $e.height() - 4
            }, $e.data(), setting);
            var height = options.height,
                values = [],
                maxWidth = options.width;

            var strValues = options.values.split(','), maxValue = 0;
            for(var i in strValues)
            {
                var v = parseFloat(strValues[i]);
                if(v != NaN)
                {
                    values.push(v);
                    maxValue = Math.max(v, maxValue);
                }
            }

            var scaleSteps = Math.min(maxValue, 30);

            var width = Math.min(maxWidth, Math.max(10, values.length*maxWidth/30));
            var canvas = $e.children('canvas');
            if(!canvas.length)
            {
                $e.append('<canvas class="projectline-canvas"></canvas>');
                canvas = $e.children('canvas');
            }
            canvas.attr('width', width).attr('height',height);
            var data = {
                labels : values,
                datasets:
                [{
                    fillColor : $.getThemeColor('pale') || "rgba(0,0,255,0.05)",
                    strokeColor : $.getThemeColor('primary') || "#0054EC",
                    pointColor :  $.getThemeColor('secondary') || "rgba(255,136,0,1)",
                    pointStrokeColor : "#fff",
                    data : values
                }]
            };
            var chartOtions = {
                animation: true,
                scaleOverride: true,
                scaleStepWidth: Math.ceil(maxValue/10),
                scaleSteps: 10,
                scaleStartValue: 0,
                showScale: false,
                showTooltips: false,
                pointDot: false,
                scaleShowGridLines: false,
                datasetStrokeWidth: 1
            };
            var lineChart = $(canvas).lineChart(data, chartOtions);
            $e.data('sparklineChart', lineChart);
        });
    }

    $(function(){
        $('.sparkline').sparkline();
    });
}(jQuery));

(function ($) {
    $(function () {
        $.fn.fixedDate = function () {
            return $(this).each(function () {
                var $this = $(this).attr('autocomplete', 'off');

                if ($this.val() == '0000-00-00') {
                    $this.focus(function () {
                        if ($this.val() == '0000-00-00') $this.val('').datetimepicker('update');
                    }).blur(function () {
                        if ($this.val() == '') $this.val('0000-00-00')
                    });
                }
            });
        };

        var options = {
            language: $('html').attr('lang'),
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1,
            format: 'yyyy-mm-dd hh:ii',
            startDate: '1970-1-1'
        };
        var dateOptions = $.extend({}, options, {minView: 2, format: 'yyyy-mm-dd' });
        var timeOptions = $.extend({}, options, {startView: 1, minView: 0, maxView: 1, format: 'hh:ii'});

        $('.datepicker-wrapper').click(function () {
            $(this).find('.form-date, .form-datetime, .form-time').datetimepicker('show').focus();
        });

        window.datepickerOptions = options;
        $.fn.datepicker = function(setting) {
            return this.datetimepicker($.extend({}, dateOptions, setting));
        };
        $.fn.timepicker = function(setting) {
            return this.datetimepicker($.extend({}, timeOptions, setting));
        };

        $.fn.datepickerAll = function() {
            this.find('.form-datetime').fixedDate().datetimepicker(options);
            this.find('.form-date').fixedDate().datepicker();
            this.find('.form-time').fixedDate().timepicker();
            return this;
        };

        $('body').datepickerAll();
    });
}(jQuery));


(function ($) {

    var batchActionForm = function($form, options) {
        options = $.extend({
            idStart: 0,
            idEnd: 9,
            chosen: true,
            datetimepicker: true,
            colorPicker: true,
            hotkeys: true
        }, options, $form.data());
        var $template = $form.find('.template');

        if (!$template.length && options.template) {
            $template = $(options.template);
        }

        var dataCtrlCount = 0;
        var rowsCount = 0;
        var focusControl = function($ctrl) {
            if ($ctrl.is('select.chosen')) {
                $ctrl.next('.chosen-container').find('input').focus();
            } else {
                $ctrl.focus();
            }
        };

        var focusNext = function(direction) {
            var $currentCtrl = $form.find('[data-ctrl-index]:focus,.chosen-container-active').first();
            if (!$currentCtrl.length) {
                return;
            }
            if ($currentCtrl.is('.chosen-container-active')) {
                if ($currentCtrl.hasClass('chosen-with-drop') && (direction === 'down' || direction === 'up')) {
                    return;
                }
                $currentCtrl = $currentCtrl.prev('select.chosen');
            }
            var index = $currentCtrl.data('ctrlIndex');
            var rowIndex = $currentCtrl.closest('tr').data('row');
            if (direction === 'down') {
                if (rowIndex < (rowsCount - 1)) {
                    rowIndex += 1;
                } else {
                    rowIndex = 0;
                }
            } else if (direction === 'up') {
                if (rowIndex > 0) {
                    rowIndex -= 1;
                } else {
                    rowIndex = rowsCount - 1;
                }
            } else if (direction === 'left') {
                if (index > 0) {
                    index -= 1;
                } else {
                    index = dataCtrlCount - 1;
                }
            } else if (direction === 'right') {
                if (index < (dataCtrlCount - 1)) {
                    index += 1;
                } else {
                    index = 0;
                }
            }
            focusControl($form.find('tr[data-row="' + rowIndex + '"]').find('[data-ctrl-index="' + index + '"]'));
        };

        var comObject = {
            options: options,
            focusNext: focusNext,
            focusControl: focusControl
        };
        var $body = $form.find('tbody,.batch-rows');
        var initRows = function($ele) {
            if ($.fn.chosen && options.chosen) {
                $ele.find('.chosen').chosen($.isPlainObject(options.chosen) ? options.chosen : null);
            }

            if ($.fn.datetimepicker && options.datetimepicker) {
                $ele.datepickerAll($.isPlainObject(options.datetimepicker) ? options.datetimepicker : null);
            }

            if ($.fn.colorPicker && options.colorPicker) {
                $ele.find('input.colorpicker').colorPicker($.isPlainObject(options.colorPicker) ? options.colorPicker : null);
            }
            var ctrlIndex = 0;
            $ele.find('input[type!="hidden"],textarea,select').each(function() {
                var $ctrl = $(this);
                if ($ctrl.parent().hasClass('chosen-search')) {
                    return;
                }
                $ctrl.attr('data-ctrl-index', ctrlIndex++);
            });
            dataCtrlCount = Math.max(dataCtrlCount, ctrlIndex);
        };
        if ($template.length) {
            var template = $template.remove().html();
            var createRow = function(id, $after) {
                var rowTemplate = template;
                if (typeof id !== 'number') {
                    id = rowsCount;
                }
                rowsCount = Math.max(id + 1, rowsCount);
                rowTemplate = rowTemplate.replace(/\$idPlus/g, id + 1).replace(/\$id/g, id);
                var $row = $('<' + $template[0].tagName.toLowerCase() + ' />').html(rowTemplate);
                $row.attr('data-row', id).addClass($template.attr('class')).removeClass('template');
                options.rowCreator && options.rowCreator($row, id, options);
                if ($after) {
                    $after.after($row);
                } else {
                    $body.append($row);
                }

                initRows($row);
                return $row;
            };
            $.extend(comObject, {createRow: createRow, template: template});

            for(var id = options.idStart; id <= options.idEnd; ++id) {
                createRow(id);
            }
        } else {
            initRows($form);
        }

        $form.on('click', '.btn-copy', function()
        {
            var $btn = $(this);
            var copyFrom = $($btn.data('copyFrom')).val();
            var $copyTo = $($btn.data('copyTo')).val(copyFrom).addClass('highlight');
            setTimeout(function() {
                $copyTo.removeClass('highlight');
            }, 2000);
        });

        if (options.hotkeys) {
            $(document).on('keydown', function(e) {
                var focusControlMap = {
                    'Ctrl+#37': 'left',
                    'Ctrl+#39': 'right',
                    '#38': 'up',
                    '#40': 'down',
                    'Ctrl+#38': 'up',
                    'Ctrl+#40': 'down'
                }
                var keys = [];
                if (e.ctrlKey) keys.push('Ctrl');
                keys.push('#' + e.keyCode);
                var focusControlDir = focusControlMap[keys.join('+')];
                if (focusControlDir)
                {
                    focusNext(focusControlDir);
                    if (e.ctrlKey) {
                        e.stopPropagation();
                        e.preventDefault();
                    }
                }
            });
        }

        $form.data('zui.batchActionForm', comObject);
    };

    $.fn.batchActionForm = function(options)
    {
        return this.each(function() {
            batchActionForm($(this), options);
        });
    };
}(jQuery));

(function($, undefined) {
    'use strict';

    var NAME = 'zui.table'; // model name

    var LANG = {
        zh_cn: {
            selectedItems: '已选择 <strong>{0}</strong> 项',
            attrTotal: '{0}总计 <strong>{1}</strong>'
        },
        zh_tw: {
            selectedItems: '已选择 <strong>{0}</strong> 项',
            attrTotal: '{0}总计 <strong>{1}</strong>'
        },
        en: {
            selectedItems: 'Seleted <strong>{0}</strong> items',
            attrTotal: '{0} total <strong>{1}</strong>'
        }
    };

    var IS_SAFARI = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

    // The Table model class
    var Table = function(element, options) {
        var that = this;
        that.name = NAME;
        var $element = that.$ = $(element);

        options = that.options = $.extend({}, Table.DEFAULTS, this.$.data(), options);
        var lang   = options.lang || 'zh_cn';
        that.lang  = $.isPlainObject(lang) ? ($.extend(true, {}, LANG[lang.lang || $.zui.clientLang()], lang)) : LANG[lang];

        if (!$element.attr('id')) {
            $element.attr('id', 'table-' + $.zui.uuid());
            if (options.hot) {
                console.warn('ZUI: table hot replace id not defined, the element id attribute should be set.');
            }
        }
        if (!$element.attr('data-ride')) {
            $element.attr('data-ride', 'table');
        }

        // Add title for table head cell
        that.getTable().find('thead>tr>th').each(function() {
            var $th = $(this);
            if (!$th.attr('title')) {
                var titleText = $.trim($th.find('a').text() || $th.text() || '');
                if (titleText.length) {
                    $th.attr('title', titleText);
                }
            }
        });

        // Initialize here
        if (options.checkable) {
            $element.on('click', '.check-all', function() {
                that.checkAll(!$(this).hasClass('checked'));
            }).on('click', 'tbody>tr', function(e) {
                if ($(e.target).closest('.btn,a,.not-check,.form-control,input[type="text"],.chosen-container').length) return;
                that.checkRow($(this));
            }).on('click', 'tbody input[type="checkbox"],tbody label[for]', function(e){
                e.stopPropagation();
                var $checkbox = $(this);
                if ($checkbox.is('label')) {
                    $checkbox = $checkbox.closest('.checkbox-primary').find('input[type="checkbox"]');
                }
                that.checkRow($checkbox.closest('tr'), $checkbox.is(':checked'));
            });

            if (options.selectable) {
                $element.selectable($.extend({}, {
                    selector: that.isDataTable ? '.fixed-left tbody>tr' : 'tbody>tr',
                    selectClass: '',
                    trigger: '.c-id',
                    clickBehavior: 'multi',
                    listenClick: false,
                    select: function(e) {
                        that.checkRow(e.target, true);
                        if (!$.cookie('ajax_dragSelected')) {
                            $.cookie('ajax_dragSelected', 'on', {expires: config.cookieLife, path: config.webRoot});
                            $.ajaxSendScore('dragSelected');
                        }
                    },
                    unselect: function(e) {
                        that.checkRow(e.target, false);
                    },
                    rangeStyle: {border: '1px solid #006af1', backgroundColor: 'rgba(50,128,252,0.2)', borderRadius: '2px'}
                }, $.isPlainObject(options.selectable) ? options.selectable : null));
            }
        }

        var $form = that.$form = $element.is('form') ? $element : $element.find('form');
        if ($form.length) {
            if (options.ajaxForm) {
                $form.ajaxForm($.isPlainObject(options.ajaxForm) ? options.ajaxForm : null);
            } else {
                $form.on('click', '[data-form-action]', function() {
                    $form.attr('action', $(this).data('formAction')).submit();
                });
            }
        }

        if (options.fixFooter || options.fixHeader) {
            that.pageFooterHeight = $('#footer').outerHeight();
            that.updateFixUI();
            $(window).on('scroll resize', function() {
                that.updateFixUI();
            }).on('sidebar.toggle', function() {
                setTimeout(function() {
                    that.updateFixUI();
                }, 200);
            });
        }

        if (options.group) {
            $element.on('click', '.group-toggle', function() {
                that.toggleRowGroup($(this).closest('tr').data('id'));
            });
            $(document).on('click', '.group-collapse-all', function() {
                that.toggleGroups(false);
            }).on('click', '.group-expand-all', function() {
                that.toggleGroups(true);
            });
        }

        that.defaultStatistic = $element.find('.table-statistic').html();
        that.updateStatistic();

        // Init iframe modals
        that.initModals();

        that.checkItems = {};

        that.updateCheckUI();
    };

    Table.prototype.reload = function(callback) {
        var that = this;
        var options = that.options;
        var replaceId = options.replaceId;
        if (!replaceId) {
            return callback && callback();
        }
        if (replaceId === 'self') {
            replaceId = that.$.attr('id');
        }
        var $tmpDiv = $('<div></div>');
        that.$.addClass('load-indicator loading');
        $tmpDiv.load(window.location.href + ' #' + replaceId, function() {
            that.$.empty().html($tmpDiv.children().html()).removeClass('load-indicator loading');

            that.$.trigger('beforeTableReload');

            that.updateStatistic();
            that.initModals();
            that.$.datepickerAll();

            var $rows = that.$.find('tbody>tr');
            var hasRowChecked = false;
            $.each(that.checkItems, function(rowId, isChecked) {
                if(isChecked) {
                    that.checkRow($rows.filter('[data-id="' + rowId + '"]'), true, true);
                    hasRowChecked = true;
                }
            });
            if (hasRowChecked) {
                that.updateCheckUI();
            }

            that.$.trigger('tableReload');

            var $badgeLabel = $('#mainMenu>.btn-toolbar>.btn-active-text>.label');
            if($badgeLabel.length) {
                $badgeLabel.text(that.getTable().find('tbody:first>tr:not(.table-children)').length);
            }

            that.$.find('[data-ride="pager"]').pager();

            callback && callback();
            options.afterReload && options.afterReload();
        });
    };

    Table.prototype.initModals = function() {
        var that = this;
        var options = that.options;
        var $iframeTriggers = that.$.find(options.iframeModalTrigger);
        if (!$iframeTriggers.length) return;

        var modalOptions = {
            type: 'iframe',
            onHide: options.replaceId ? function() {
                var selfClose = $.cookie('selfClose');
                if(selfClose != 1 && !options.hot) return;

                $('#triggerModal').data('cancel-reload', 1);

                that.reload(function() {
                    $.cookie('selfClose', 0);
                });
            } : null
        };
        $iframeTriggers.modalTrigger(modalOptions);
    };

    Table.prototype.getTable = function() {
        var $element = this.$;
        if (this.isDataTable) {
            return $element.find('div.datatable');
        }
        var $table = $element.is('table') ? $element : $element.find('table:not(.fixed-header-copy)').first();
        if ($table.is('.datatable')) {
            this.isDataTable = true;
            $table = $element.find('div.datatable');
        }
        return $table;
    };

    Table.prototype.toggleGroups = function(expand) {
        var that = this;
        var toggled = {};
        that.$.find('tbody>tr').each(function() {
            var id = $(this).closest('tr').data('id');
            if (toggled[id]) return;
            that.toggleRowGroup(id, expand);
        });
    };

    Table.prototype.toggleRowGroup = function(id, expand) {
        var $rows = this.$.find('tbody>tr[data-id="' + id + '"]');
        var $sumRow = $rows.filter('.group-summary');
        var isSumHidden = expand === undefined ? !$sumRow.hasClass('hidden') : !!expand;
        $rows.not('.group-summary').toggleClass('hidden', !isSumHidden);
        $sumRow.toggleClass('hidden', isSumHidden);
        $('body').toggleClass('table-group-collapsed', !this.$.find('tbody>tr.group-summary.hidden').length)
    };

    Table.prototype.updateStatistic = function() {
        var that = this;
        var $statistic = that.$.find('.table-statistic');;
        if (!$statistic.length) return;

        if (that.defaultStatistic === undefined) {
            that.defaultStatistic = $statistic.html();
        }

        if (that.options.statisticCreator) {
            $statistic.html(that.options.statisticCreator(that) || that.defaultStatistic);
            return;
        }

        var statisticCols = that.statisticCols;
        if (!statisticCols && statisticCols !== false) {
            statisticCols = {};
            var hasStatisticCol = false;
            that.getTable().find('thead th').each(function(index) {
                var $th = $(this);
                var statisticFormat = $th.data('statistic');
                if (statisticFormat) {
                    hasStatisticCol = true;
                    statisticCols[index] = {format: statisticFormat, name: $th.text()};
                }
            });
            that.statisticCols = hasStatisticCol ? statisticCols : false;
        }

        var checkedCount = 0;
        if (statisticCols) {
            $.each(statisticCols, function(colIndex) {
                statisticCols[colIndex].total = 0;
                statisticCols[colIndex].checkedTotal = 0;
            });
        }
        that.$.find('tbody>tr').each(function(rowIndex) {
            var $row = $(this);
            var isRowChecked = $row.hasClass('checked');
            var $tds = $row.children('td');
            if (isRowChecked) checkedCount++;
            if (statisticCols) {
                $.each(statisticCols, function(colIndex) {
                    var cellVal = parseFloat($tds.eq(colIndex).text());
                    if (isNaN(cellVal)) cellVal = 0;
                    statisticCols[colIndex].total += cellVal;
                    if(isRowChecked) statisticCols[colIndex].checkedTotal += cellVal;
                });
            }
        });
        var statisticContents = [];
        if (checkedCount) {
            statisticContents.push(that.lang.selectedItems.format(checkedCount));
        } else if (that.defaultStatistic) {
            $statistic.html(that.defaultStatistic);
            return;
        }
        if (statisticCols) {
            $.each(statisticCols, function(colIndex) {
                var statisticCol = statisticCols[colIndex];
                var staticVal = statisticCol[checkedCount ? 'checkedTotal' : 'total'];
                if (statisticCol.format) {
                    staticVal = statisticCol.format.format(staticVal);
                }
                statisticContents.push(that.lang.attrTotal.format(statisticCol.name, staticVal));
            });
        }
        $statistic.html(statisticContents.join(', '));
    };

    Table.prototype.updateFixUI = function(immediately) {
        var that = this;
        var now = new Date().getTime();
        if (!immediately) {
            if (that.lastUpdateCall) {
                clearTimeout(that.lastUpdateCall);
            }
            if (!that.lastUpdateTime || (now - that.lastUpdateTime) < 100) {
                that.lastUpdateCall = setTimeout(function() {
                    that.updateFixUI(true);
                }, 30);
                return;
            }
        }

        that.lastUpdateTime = now;

        if (that.lastUpdateCall) {
            clearTimeout(that.lastUpdateCall);
            that.lastUpdateCall = null;
        }

        // Fix for safari
        if (IS_SAFARI) {
            var $table = that.getTable();
            if ($table.parent().is('.table-responsive')) {
                var $header = $table.find('thead');
                var columnsWidth = 0;
                $header.find('th').each(function() {
                    columnsWidth += $(this).outerWidth();
                });
                $table.css('min-width', columnsWidth);
            }
        }

        if (that.options.fixHeader && !that.isDataTable) {
            that.fixHeader();
        }
        if (that.options.fixFooter) {
            that.fixFooter();
        }
    };

    Table.prototype.fixHeader = function() {
        var that = this;
        var $table = that.getTable();
        var $header = $table.find('thead');
        var bounding = $header[0].getBoundingClientRect();
        var fixFooterOption = that.options.fixFooter;
        var isFixed = $.isFunction(fixFooterOption) ? fixFooterOption(bounding, $header) : bounding.top < (typeof fixFooterOption === 'number' ? fixFooterOption : -5);
        var $fixHeaderTable = that.$.find('.fix-table-copy-wrapper');
        var $tableParent = $table.parent();
        var isResponsiveTable = $tableParent.is('.table-responsive');
        if (isFixed) {
            if (!$fixHeaderTable.length) {
                $fixHeaderTable = $('<div class="fix-table-copy-wrapper" style="overflow: hidden; position:fixed; z-index: 3; top: 0;"></div>').append($('<table class="fixed-header-copy"></table>').addClass($table.attr('class')).append($header.clone())).insertAfter($table);
            }
            if (isResponsiveTable) {
                var tableBounding = $tableParent[0].getBoundingClientRect();
                $fixHeaderTable.css({
                    left: tableBounding.left,
                    width: $tableParent.width()
                });
                $fixHeaderTable.find('.fixed-header-copy').css({
                    left: bounding.left - tableBounding.left,
                    position: 'relative',
                    minWidth: $table.width()
                });
                if (!$tableParent.data('fixHeaderScroll')) {
                    $tableParent.data('fixHeaderScroll', 1).on('scroll', function() {
                        that.fixHeader();
                    });
                }
            } else {
                $fixHeaderTable.css({
                    left: bounding.left,
                    width: bounding.width
                });
            }
            var $ths = $fixHeaderTable.find('th');
            $header.find('th').each(function(index) {
                $ths.eq(index).css('width', $(this).outerWidth());
            });
        } else {
            $fixHeaderTable.remove();
        }
    };

    Table.prototype.fixFooter = function() {
        var that = this;
        var $table = that.getTable();
        var $footer = that.$.find('.table-footer');
        var bounding;
        if (that.isDataTable) {
            bounding = $table[0].getBoundingClientRect();
        } else {
            var $tbody = $table.find('tbody');
            if (!$tbody.length) return;
            bounding = $tbody[0].getBoundingClientRect();
            bounding = $tbody[0].getBoundingClientRect();
        }
        var fixFooterOption = that.options.fixFooter;
        $footer.toggleClass('fixed-footer', !!isFixed);
        var isFixed = $.isFunction(fixFooterOption) ? fixFooterOption(bounding, $footer) : (bounding.bottom) > (window.innerHeight - 50 - (typeof fixFooterOption === 'number' ? fixFooterOption : (that.pageFooterHeight || 5)));
        $footer.toggleClass('fixed-footer', !!isFixed);
        $table.toggleClass('with-footer-fixed', !!isFixed);
        $table.trigger('fixFooter', isFixed);
        var $body = $('body');
        var isInModal = $body.hasClass('body-modal');
        if (isFixed) {
            var $tableParent = $table.parent();
            var isResponsiveTable = $tableParent.is('.table-responsive');
            $footer.css({
                bottom: that.pageFooterHeight || 0,
                left: isResponsiveTable ? $tableParent[0].getBoundingClientRect().left : bounding.left,
                width: isResponsiveTable ? $tableParent.width() : bounding.width
            });
            if (isInModal) {
                $body.css('padding-bottom', 40);
            }
        } else {
            $footer.css({
                width: '',
                left: 0,
                bottom: 0
            });
            if (isInModal) {
                $body.css('padding-bottom', 0);
            }
        }
    };

    Table.prototype.checkAll = function(checked) {
        var that = this;
        var $rows = that.$.find('tbody>tr');
        $rows.each(function() {
            that.checkRow($(this), checked, true);
        });
        that.updateCheckUI();
    };

    Table.prototype.checkRow = function($row, checked, notUpdateCheckUI) {
        var that = this;
        if (that.isDataTable && !$row.is('.datatable-row-left')) {
            $row = that.getTable().find('.datatable-row-left[data-index="' + $row.data('index') + '"]');
        }
        var $checkbox = $row.find('input[type="checkbox"]');
        if (checked === undefined) {
            checked = !$checkbox.is(':checked');
        }

        if (that.isDataTable) {
            that.getTable().find('.datatable-row[data-index="' + $row.data('index') + '"]').toggleClass('checked', checked);
        } else {
            $row.toggleClass('checked', checked);
        }
        this.checkItems[$row.data('id')] = checked;
        $checkbox.prop('checked', checked).trigger('change');
        if (!notUpdateCheckUI) {
            that.updateCheckUI();
        }
    };

    Table.prototype.updateCheckUI = function() {
        var that = this;
        var $table = that.getTable();
        var $rows = $table.find(that.isDataTable ? '.fixed-left tbody>tr' : 'tbody>tr');
        var isLastRowChecked = false, $lastRow = null, checkCount = 0, isRowChecked = false, rowsCount = $rows.length;
        $rows.each(function(index) {
            var $row = $(this);
            var $checkbox = $row.find('input[type="checkbox"]');
            isRowChecked =  $checkbox.is(':checked');
            var $rowTrs = that.isDataTable ? $table.find('.datatable-row[data-index="' + $row.data('index') + '"]') : $row;

            $rowTrs.toggleClass('checked', isRowChecked);
            $rowTrs.toggleClass('row-check-begin', isRowChecked && !isLastRowChecked);
            $lastRow && $lastRow.toggleClass('row-check-end', !isRowChecked && isLastRowChecked);
            if (isRowChecked) checkCount += 1;
            $lastRow = $rowTrs;
            isLastRowChecked = isRowChecked;
            if (rowsCount === (index + 1)) $rowTrs.toggleClass('row-check-end', isRowChecked);
        });
        that.$.toggleClass('has-row-checked', checkCount > 0).find('.check-all').toggleClass('checked', !!(rowsCount && checkCount === rowsCount));
        that.updateStatistic();
        that.options.onCheckChange && that.options.onCheckChange();
    };

    // default options
    Table.DEFAULTS = {
        checkable: true,
        ajaxForm: false,
        selectable: true,
        fixHeader: true,
        fixFooter: true,
        iframeWidth: 900,
        replaceId: 'self',
        hot: false,
        iframeModalTrigger: '.iframe'
    };

    // Extense jquery element
    $.fn.table = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(NAME);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(NAME, (data = new Table(this, options)));

            if(typeof option == 'string') data[option]();
        });
    };

    Table.NAME = NAME;

    $.fn.table.Constructor = Table;

    // Auto call table after document load complete
    $(function() {
        $('[data-ride="table"]').table();
    });
}(jQuery, undefined));


(function ($, window, undefined) {

    $.fn._ajaxForm = $.fn.ajaxForm;

    var DEFAULT = {
        timeout: window.config ? window.config.timeout : 0,
        dataType: 'json',
    };
    var ajaxFormUrl = '';

    $.fn.enableForm = function(enable, final) {
        if (enable === undefined) enable = true;
        return this.each(function() {
            var $form = $(this);
            if (!final) {
                $form.find('[type="submit"]').attr('disabled', enable ? null : 'disabled');
            }
            if ($form.hasClass('load-indicator')) {
                $form.toggleClass('loading', !enable);
            }
        });
    };

    var showMessage = function(mesage, options, method) {
        if (typeof options === 'string') {
            method = options;
            options = null;
        }
        method = method || 'show';
        if ($.zui.messager) {
            $.zui.messager[method](mesage, options);
        } else {
            alert(mesage);
        }
    };

    $.ajaxForm = function(form, options) {
        var $form = $(form);
        if ($form.length > 1) {
            return $form.each(function() {
                $.ajaxForm(this, options);
            });
        }
        if ($.isFunction(options)) {
            options = {complete: options};
        }
        options = $.extend({}, DEFAULT, $form.data(), options);
        var beforeSubmit = options.beforeSubmit;
        var onError = options.error;
        var onSuccess = options.success;
        var onFinish = options.finish;
        delete options.finish;
        delete options.success;
        delete options.onError;
        delete options.beforeSubmit;
        options = $.extend({
            beforeSubmit: function(data, $thisForm, option) {
                $form.enableForm(false);
                if ((beforeSubmit && beforeSubmit(data, $thisForm, option)) === false) return;

                var feature = {};
                var $files = $thisForm.find('[type="file"]');
                feature.fileapi = $files.length && $files[0].files !== undefined;
                feature.formdata = window.FormData !== undefined;
                var fileInputs = feature.fileapi && $thisForm.find('input[type="file"]:enabled').filter(function() { return $(this).val() !== ''; });
                var hasFileInputs = fileInputs.length;
                var mp = 'multipart/form-data';
                var multipart = ($thisForm.attr('enctype') == mp || $thisForm.attr('encoding') == mp);

                var fileAPI = feature.fileapi && feature.formdata;
                var shouldUseFrame = (hasFileInputs || multipart) && !fileAPI;

                /* Append HTTP_X_REQUESTED_WITH on url when shouldUseFrame is true. */
                if(shouldUseFrame)
                {
                    if(ajaxFormUrl == '') ajaxFormUrl = option.url;
                    if(option.url != ajaxFormUrl) option.url = ajaxFormUrl;
                    option.url = option.url.indexOf('&') >= 0 ? option.url + '&HTTP_X_REQUESTED_WITH=XMLHttpRequest' : option.url + '?HTTP_X_REQUESTED_WITH=XMLHttpRequest';
                }
            },
            success: function(response, textStatus, jqXHR) {
                if ((onSuccess && onSuccess(response, textStatus, jqXHR, $form)) === false) return;

                /* try parse to json when response is json's string. */
                try{if(typeof response == 'string') response = JSON.parse(response);}catch(e){}

                /* The response is not an object, some error occers, alert it. */
                if(response === null || typeof response !== 'object') {
                    if(response) return alert(response);
                    return showMessage('No response.', 'danger');
                }

                /* The response.result is success. */
                var $responser = options.responser ? $(options.responser) : $form.find('.form-responser');
                if (!$responser.length) {
                    $responser = $('#responser');
                }
                var message = response.message;
                if (response.result === 'success') {
                    $form.enableForm(true, 1);
                    if (message) {
                        var $submitBtn = $form.find('[type="submit"]');
                        var messageShowed = false;
                        if ($submitBtn.length) {
                            $submitBtn.popover({
                                container: 'body',
                                trigger: 'manual',
                                content: message,
                                tipClass: 'popover-in-modal popover-success text-success',
                                placement: response.placement || options.popoverPlacement || 'right'
                            }).popover('show');
                            setTimeout(function() {
                                $submitBtn.popover('destroy');
                            }, options.popoverTime || 2000);
                            messageShowed = true;
                        }

                        if ($responser.length) {
                            $responser.html('<span class="small text-success">' + message + '</span>').show().delay(3000).fadeOut(100);
                            messageShowed = true;
                        }

                        if (!messageShowed) {
                            showMessage(message, 'success');
                        }
                    }

                    if (onFinish) {
                        return onFinish(response, true, $form);
                    }

                    if (options.closeModal || response.closeModal) {
                        setTimeout($.zui.closeModal, options.closeModalTime || 2000);
                    }

                    if(response.callback) {
                        var rcall = window[response.callback];
                        if($.isFunction(rcall)) {
                            if(rcall() === false) return;
                        }
                    }

                    var locate = options.locate || response.locate;
                    if(locate) {
                        if(locate == 'loadInModal') {
                            var $modal = $('.modal');
                            setTimeout(function() {
                                $modal.load($modal.attr('ref'), function(){$(this).find('.modal-dialog').css('width', $(this).data('width')); $.zui.ajustModalPosition()});
                            }, 1000);
                        }
                        else {
                            var reloadUrl = locate == 'reload' ? window.location.href : locate;
                            setTimeout(function(){window.location.href = reloadUrl;}, 1200);
                        }
                    }

                    var ajaxReload = options.ajaxReload || response.ajaxReload;
                    if(ajaxReload) {
                        var $target = $(ajaxReload);
                        if($target.length) {
                            $target.load(window.location.href + ' ' + ajaxReload, function() {
                                $target.find('[data-toggle="modal"]').modalTrigger();
                            });
                        }
                    }
                } else {
                    $form.enableForm();
                    // The response.result is fail.
                    if (typeof message === 'string') {
                        if ($responser.length) {
                            $responser.html('<span class="text-small text-red">' + message + '</span>').show().delay(3000).fadeOut(100);
                        } else {
                            showMessage(message, 'danger');
                        }
                    } else if (typeof message === 'object') {
                        var focusFirst = false;
                        var unShowedMessages = [];
                        $.each(message, function(key, value) {
                            var controlMessage = $.isArray(value) ? value.join(';') : value;
                            var $control = $('#' + key);

                            if (!$control.length) {
                                unShowedMessages.push(controlMessage);
                                return;
                            }

                            var errMsgLabelId = key + 'Label';

                            var $errLabel = $('#' + errMsgLabelId);
                            if (!$errLabel.length) {
                                var isInputGroup = $control.closest('.input-group').length;
                                $errLabel = $('<div id="' + errMsgLabelId + '" class="text-danger help-text" />').appendTo(isInputGroup ? $control.closest('.input-group').parent() : $control.parent());
                            }
                            $errLabel.empty().append(controlMessage);
                            $control.addClass('has-error');

                            var removeLabel = function() {
                                var $theLabel = $('#' + errMsgLabelId);
                                if ($theLabel.length) {
                                    $theLabel.remove();
                                    $control.removeClass('has-error');
                                    return true;
                                }
                            };

                            $control.on('change input mousedown', removeLabel);

                            var $chosen = $('#' + key + '_chosen');
                            if ($chosen.length) {
                                $chosen.find('.chosen-single,.chosen-choices').addClass('has-error').on('mousedown', function() {
                                    if (removeLabel() === true) {
                                        $(this).removeClass('has-error');
                                    }
                                });
                            }

                            if (!focusFirst) {
                                $control.focus();
                                focusFirst = true;
                            }
                        });
                        if (unShowedMessages.length) {
                            showMessage(unShowedMessages.join(';'), 'danger');
                        }
                    }
                    if (onFinish) {
                        return onFinish(response, false, $form);
                    }
                }
            },
            /* When error occers, alert the response text, status and error. */
            error: function(jqXHR, textStatus, errorThrown) {
            if ((onError && onError(jqXHR, textStatus, errorThrown, $form)) === false) return;
                $form.enableForm();
                var errorMessage = (textStatus == 'timeout' || textStatus == 'error') ? (window.lang ? window.lang.timeout : textStatus) : (jqXHR.responseText + textStatus + errorThrown);
                showMessage(errorMessage, 'danger');
            }
        }, options);
        $form._ajaxForm(options);

        $form.on('click', '[data-form-action]', function() {
            $form.attr('action', $(this).data('formAction')).submit();
        });
    };

    $.fn.ajaxForm = function(options) {
        return this.each(function() {
            $.ajaxForm(this, options);
        });
    };

    $.fn.setInputRequired = function() {
        return this.each(function() {
            var $ctrl = $(this);
            var $parent = $ctrl.parent();
            if ($parent.is('.input-control,td')) {
                $parent.addClass('required');
            } else if ($ctrl.is('.chosen')) {
                $ctrl.attr('required', null).next('.chosen-container').addClass('required');
            } else {
                $parent.addClass('required');
            }
            $ctrl.attr('required', null);
            var $inputGroup = $parent.closest('.input-group');
            if($inputGroup.length && $inputGroup.find('.required,input[required],select[required]').length === 1) {
                $inputGroup.addClass('required');
            }
        });
    };

    $(function() {
        $('.form-ajax,form[data-type="ajax"]').ajaxForm();

        // Set required
        setTimeout(function() {
            var requiredFields = window.config.requiredFields;
            var $forms = $('form');
            if (requiredFields) requiredFields = requiredFields.split(',');
            if (requiredFields && requiredFields.length) {
                $.each(requiredFields, function(idx, requiredField) {
                    $forms.find('#' + requiredField).attr('required', 'required');
                });
            }

            $forms.find('input[required],select[required]').setInputRequired();
        }, 400);
    });
}(jQuery, window, undefined));

(function($) {
    'use strict';

    var NAME = 'zui.searchList'; // model name

    var isSearchMatch = function(keyArr, filter) {
        if (keyArr && keyArr.length) {
            for(var i = 0; i < keyArr.length; ++i) {
                if (filter.indexOf(keyArr[i]) < 0) {
                    return false;
                }
            }
        }
        return true;
    };

    // The SearchList model class
    var SearchList = function(element, options) {
        var that = this;
        that.name = NAME;
        var $element = that.$ = $(element);

        options = that.options = $.extend({}, SearchList.DEFAULTS, this.$.data(), options);
        var $searchBox = $element.find(options.searchBox);
        if ($searchBox.length) {
            $searchBox.searchBox({
                onSearchChange: function(searchKey) {
                    that.search(searchKey);
                },
                onKeyDown: function(e) {
                    var keyCode = e.which;
                    if (keyCode === 13) {
                        var $activeItem = that.getActiveItem();
                        if (options.onSelectItem) {
                            options.onSelectItem($activeItem);
                        } else {
                            window.location.href = $activeItem.attr('href');
                        }
                        e.preventDefault();
                    } else if (keyCode === 38) { // up key pressed
                        var $activeItem = that.getActiveItem();
                        $activeItem.removeClass('active');
                        var $prevItem = $activeItem.prev(':not(.hidden)');
                        if (!$prevItem.length) {
                            $prevItem = that.getItems().not('.hidden').last();
                        }
                        that.scrollTo($prevItem.addClass('active'));
                        e.preventDefault();
                    } else if (keyCode === 40) { // down key pressed
                        var $activeItem = that.getActiveItem();
                        $activeItem.removeClass('active');
                        var $nextItem = $activeItem.next(':not(.hidden)');
                        if (!$nextItem.length) {
                            $nextItem = that.getItems().not('.hidden').first();
                        }
                        that.scrollTo($nextItem.addClass('active'));
                        e.preventDefault();
                    }
                }
            });
            that.searchBox = $searchBox.data('zui.searchBox');
            that.search(that.searchBox.getSearch());
        }

        var $dropdownMenu = that.$menu = $element.closest('.dropdown-menu');
        if ($dropdownMenu.length) {
            that.isDropdown = true;
            $element.on('click', function(e) {
                if (!$(e.target).closest(options.selector).length) {
                    e.stopPropagation();
                }
            });
            var $dropdown = $dropdownMenu.parent();
            $dropdown.on($dropdown.hasClass('dropdown-hover') ? 'mouseenter' : 'shown.zui.dropdown', function() {
                that.tryLoadRemote(function() {
                    setTimeout(function() {
                        that.searchBox.focus();
                    }, 50);
                });
            });
        }

        $element.on('mouseenter', options.selector, function() {
            $element.find(that.options.selector).not('.hidden').removeClass('active');
            $(this).addClass('active');
        });
    };

    SearchList.prototype.tryLoadRemote = function(callback) {
        var that = this;
        var options = that.options;
        if (options.url || options.ajax) {
            if (that.isLoaded) {
                callback();
            } else {
                that.loadRemote(callback);
            }
        } else {
            callback();
        }
    };

    SearchList.prototype.loadRemote = function(callback) {
        var that = this;
        var options = that.options;
        that.$menu.addClass('load-indicator loading').find('.list-group').remove();
        that.isLoaded = false;
        $.ajax($.extend({
            url: options.url,
            type: 'GET',
            dataType: 'html',
            success: function(data, textStatus, jqXHR) {
                var $data = $(data);
                if (!$data.hasClass('list-group')) {
                    $data = $('<div class="list-group"></div>').append($data);
                }
                that.$menu.append($data);
                that.$menu.removeClass('loading');
                that.isLoaded = true;
                callback && callback(true);
            },
            error: function() {
                that.$menu.removeClass('loading').append('<div class="list-group"><div class="text-error has-padding">' + (options.errorText || (window.lang &&  window.lang.timeout)) + '</div></div>');
                callback && callback(false);
            },
        }, options.ajax));
    };

    SearchList.prototype.scrollTo = function($item) {
        if ($item.length) {
            $item[0].scrollIntoView({behavior: 'smooth'});
        }
    };

    SearchList.prototype.getItems = function() {
        return this.$.find(this.options.selector);
    };

    SearchList.prototype.getActiveItem = function() {
        return this.getItems().filter('.active:first');
    };

    SearchList.prototype.search = function(searchKey) {
        var that = this;
        var isEmpty = searchKey === undefined || searchKey === null || searchKey === '';
        var $items = that.getItems().removeClass('active');
        if (isEmpty) {
            $items.removeClass('hidden');
        } else {
            var keyArr = $.trim(searchKey).split(' ');
            $items.each(function() {
                var $item = $(this);
                var filter = $item.text() + ' ' + ($item.data('key') || $item.data('filter'));
                $item.toggleClass('hidden', !isSearchMatch(keyArr, filter));
            });
        }
        that.scrollTo($items.not('.hidden').first().addClass('active'));
    };

    // default options
    SearchList.DEFAULTS = {
        selector: '.list-group a',
        searchBox: '.search-box',
        onSelectItem: null
    };

    // Extense jquery element
    $.fn.searchList = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(NAME);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(NAME, (data = new SearchList(this, options)));

            if(typeof option == 'string') data[option]();
        });
    };

    SearchList.NAME = NAME;

    $.fn.searchList.Constructor = SearchList;

    // Auto call searchList after document load complete
    $(function() {
        $('[data-ride="searchList"]').searchList();
    });
}(jQuery));


(function($) {
    'use strict';

    var NAME = 'zui.labelSelector'; // model name

    // The LabelSelector model class
    var LabelSelector = function(element, options) {
        var that = this;
        that.name = NAME;
        that.$ = $(element);

        options = that.options = $.extend({}, LabelSelector.DEFAULTS, this.$.data(), options);

        that.$.hide();
        that.update();
    };

    LabelSelector.prototype.select = function(val) {
        val = val + '';
        this.$wrapper.find('.label.active').removeClass('active');
        this.$wrapper.find('.label[data-value="' + val + '"]').addClass('active');
        this.$.val(val).trigger('change');
    };

    LabelSelector.prototype.update = function() {
        var that = this;
        var options = that.options;
        var $wrapper = that.$wrapper;
        if (!$wrapper) {
            if (!options.wrapper) {
                var $next = that.$.next();
                if ($next.hasClass('.label-selector')) {
                    $wrapper = $next;
                } else {
                    $wrapper = $('<div class="label-selector"></div>');
                }
            } else {
                $wrapper = $(options.wrapper);
            }
            if (!$wrapper.parent().length) {
                that.$.after($wrapper);
            }
            that.$wrapper = $wrapper;
            $wrapper.on('click', '.label', function(e) {
                var currentValue = that.$.val();
                var value = $(this).data('value');
                if (that.hasEmptyValue !== false && value == currentValue) {
                    value = that.hasEmptyValue;
                }
                that.select(value);
                e.preventDefault();
            });
        }

        $wrapper.empty();
        var currentValue = that.$.val();
        that.hasEmptyValue = false;
        that.$.children('option').each(function() {
            var $option = $(this);
            var option = {
                label: $option.text(),
                value: $option.val()
            };
            var isEmptyValue = option.value === '' || option.value === '0';
            if (isEmptyValue) {
                that.hasEmptyValue = option.value;
                if (!option.label) {
                    return;
                }
            }
            var $label = $(options.labelTemplate || '<span class="label"></span>');
            if (options.labelClass && !isEmptyValue) {
                $label.addClass(options.labelClass);
            }
            if (options.labelCreator) {
                $label = options.labelCreator($label);
            } else {
                $label.data('option', option).attr('data-value', option.value);
                if (isEmptyValue && !option.label) {
                    $label.addClass('empty').append('<i class="icon icon-trash"></i>')
                } else {
                    $label.text(option.label).toggleClass('active', currentValue === option.value);
                }
            }
            $wrapper.append($label);
        });
    };


    // default options
    LabelSelector.DEFAULTS = {
        // labelCreator: null,
        // labelClass: 'label-severity',
        // dropdown: false,
        // wrapper: '<div class="label-selector"></div>'
    };

    // Extense jquery element
    $.fn.labelSelector = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(NAME);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(NAME, (data = new LabelSelector(this, options)));

            if(typeof option == 'string') data[option]();
        });
    };

    LabelSelector.NAME = NAME;

    $.fn.labelSelector.Constructor = LabelSelector;

    // Auto call labelSelector after document load complete
    $(function() {
        $('[data-provide="labelSelector"]').labelSelector();
    });
}(jQuery));


(function($) {
    'use strict';

    var NAME = 'zui.fileInput'; // model name

    var BYTE_UNITS = $.BYTE_UNITS = {
        B: 1,
        KB: 1024,
        MB: 1024 * 1024,
        GB: 1024 * 1024 * 1024,
        TB: 1024 * 1024 * 1024 * 1024,
    };
    var formatBytes = $.formatBytes = function(size, fixed, unit) {
        if (fixed === undefined) fixed = 2;
        if (!unit) {
            if (size < BYTE_UNITS.KB) {
                unit = 'B';
            } else if (size < BYTE_UNITS.MB) {
                unit = 'KB';
            } else if (size < BYTE_UNITS.GB) {
                unit = 'MB';
            } else if (size < BYTE_UNITS.TB) {
                unit = 'GB';
            } else {
                unit = 'TB';
            }
        }

        return (size / BYTE_UNITS[unit]).toFixed(fixed) + unit;
    };

    var getBytesValue = function(bytes) {
        if (typeof bytes === 'string') {
            bytes = bytes.toUpperCase();
            var unit = bytes.replace(/\d+/, '');
            bytes = parseFloat(bytes.replace(unit, ''));
            bytes *= (BYTE_UNITS[unit] || BYTE_UNITS[unit + 'B']);
            bytes = Math.floor(bytes);
        }
        return bytes;
    };

    // The FileInput model class
    var FileInput = function(element, options) {
        var that = this;
        that.name = NAME;
        var $element = that.$ = $(element);

        options = that.options = $.extend({}, FileInput.DEFAULTS, this.$.data(), options);
        if (options.fileMaxSize && typeof options.fileMaxSize === 'string') {
            options.fileMaxSize = getBytesValue(options.fileMaxSize);
        }

        var $input = that.$input = $element.find('input[type="file"]');

        $element.on('click', '.file-input-btn', function() {
            $input.trigger('click');
        }).on('click', '.file-input-rename', function() {
            that.oldName = $element.addClass('edit').find('.file-editbox').focus().val();
        }).on('click', '.file-input-delete', function() {
            $input.val('');
            that.update();
            options.onDelete && options.onDelete(that);
        }).on('click', '.file-name-cancel', function() {
            $element.removeClass('edit').find('.file-editbox').focus().val(that.oldName);;
        }).on('click', '.file-name-confirm', function() {
            var $editbox = $element.find('.file-editbox');
            var newName = $.trim($editbox.val());
            if (!newName.length) {
                $editbox.focus();
            } else {
                $element.removeClass('edit').find('.file-title').text(newName);
            }
        }).on('change input paste', '.file-editbox', function() {
            var $editbox = $(this);
            $editbox.attr('size', Math.max(5, $editbox.val().length));
        });

        $input.on('change', function() {
            var file = that.getFile();
            if (file && options.fileMaxSize && file.size > options.fileMaxSize) {
                $input.val('');
                (window.bootbox || window)['alert'](options.fileSizeError.format(formatBytes(options.fileMaxSize)));
            }
            that.update();
        });

        that.update();
    };

    FileInput.prototype.getFile = function() {
        var files = this.$input.prop('files');
        return files && files[0];
    };

    FileInput.prototype.update = function(val) {
        var that = this;
        var $ele = that.$;
        var file = that.getFile();
        var isFileEmpty = !file;
        $ele.toggleClass('normal', !isFileEmpty).toggleClass('empty', isFileEmpty);
        if (file) {
            that.oldName = file.name;
            $ele.find('.file-title').text(file.name).attr('title', file.name);
            $ele.find('.file-size').text(formatBytes(file.size));
            $ele.find('.file-editbox').val(file.name).attr('size', file.name.length);
            that.options.onSelect && that.options.onSelect(file, that);
        } else {
            $ele.find('.file-editbox').val('');
        }
    };

    // default options
    FileInput.DEFAULTS = {
        fileMaxSize: 0,
        fileSizeError: '无法上传大于 {0} 的文件。'
    };

    // Extense jquery element
    $.fn.fileInput = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(NAME);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(NAME, (data = new FileInput(this, options)));

            if(typeof option == 'string') data[option]();
        });
    };

    FileInput.NAME = NAME;

    $.fn.fileInput.Constructor = FileInput;

    // Auto call fileInput after document load complete
    $(function() {
        $('[data-provide="fileInput"]').fileInput();
    });

    var LIST_NAME = 'zui.fileInputList';

    // File input list
    // The FileInputList model class
    var FileInputList = function(element, options) {
        var that = this;
        that.name = LIST_NAME;
        var $element = that.$ = $(element);

        options = that.options = $.extend({}, FileInputList.DEFAULTS, this.$.data(), options);

        that.$template = $element.find('.file-input').detach();
        that.add();
    };

    FileInputList.prototype.add = function() {
        var that = this;
        var options = that.options;
        var $new = that.$template.clone();
        if (options.appendWay === 'before') {
            that.$.prepend($new);
        } else {
            that.$.append($new);
        }
        $new.fileInput({
            fileMaxSize: options.eachFileMaxSize,
            fileSizeError: options.fileSizeError,
            onDelete: function(fileInput) {
                fileInput.$.remove();
                that.options.onDelete && that.options.onDelete(fileInput, that);
            },
            onSelect: function(file, fileInput) {
                that.add();
                that.options.onSelect && that.options.onSelect(file, fileInput, that);
            }
        });
    };


    // default options
    FileInputList.DEFAULTS = {
        fileMaxSize: 0,
        eachFileMaxSize: 0,
        appendWay: 'after',
        fileSizeError: '无法上传大于 {0} 的文件。'
    };

    // Extense jquery element
    $.fn.fileInputList = function(option) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data(LIST_NAME);
            var options = typeof option == 'object' && option;

            if(!data) $this.data(LIST_NAME, (data = new FileInputList(this, options)));

            if(typeof option == 'string') data[option]();
        });
    };

    FileInputList.NAME = LIST_NAME;

    $.fn.fileInputList.Constructor = FileInputList;

    // Auto call fileInputList after document load complete
    $(function() {
        $('[data-provide="fileInputList"]').fileInputList();
    });
}(jQuery));


(function($) {
    if (!window.config) window.config = {};

    /**
    * Create link.
    *
    * @param  string $moduleName
    * @param  string $methodName
    * @param  string $vars
    * @param  string $viewType
    * @access public
    * @return string
    */
    $.createLink = window.createLink = function createLink(moduleName, methodName, vars, viewType, isOnlyBody)
    {
        if(!viewType)   viewType   = config.defaultView;
        if(!isOnlyBody) isOnlyBody = false;
        if(vars)
        {
            vars = vars.split('&');
            for(i = 0; i < vars.length; i ++)
            {
                var splited = vars[i].split('=');
                vars[i] = [splited.shift(), splited.join('=')];
            }
        }
        var link;
        if(config.requestType != 'GET')
        {
            if(config.requestType == 'PATH_INFO')  link = config.webRoot + moduleName + config.requestFix + methodName;
            if(config.requestType == 'PATH_INFO2') link = config.webRoot + 'index.php/'  + moduleName + config.requestFix + methodName;
            if(vars)
            {
                for(i = 0; i < vars.length; i ++) link += config.requestFix + vars[i][1];
            }
            link += '.' + viewType;
        }
        else
        {
            link = config.router + '?' + config.moduleVar + '=' + moduleName + '&' + config.methodVar + '=' + methodName + '&' + config.viewVar + '=' + viewType;
            if(vars) for(i = 0; i < vars.length; i ++) link += '&' + vars[i][0] + '=' + vars[i][1];
        }

        /* if page has onlybody param then add this param in all link. the param hide header and footer. */
        if((config.onlybody !== undefined && config.onlybody == 'yes') || isOnlyBody)
        {
            var onlybody = config.requestType != 'GET' ? "?onlybody=yes" : '&onlybody=yes';
            link = link + onlybody;
        }
        return link;
    };

    // Handle fade effection
    $(function() {
        var $main = $('#main,#mainContent,#mainRow,.auto-fade-in');
        if ($main.length && $main.hasClass('fade')) {
            setTimeout(function() {
                $main.addClass('in');
            }, $main.data('fadeTime') || 200);
        }
    });

    // Send score with ajax request
    $.ajaxSendScore = function(method) {
        $.get($.createLink('score', 'ajax', 'method=' + method));
    };

    var convertTimeToNum = function(timeStr) {
        var number = 0;
        if (timeStr) {
            var valArr = timeStr.split(':');
            number += parseInt(valArr[0]) * 60;
            number += parseInt(valArr[1]);
        }
        return number;
    };

    var convertNumToTime = function(number) {
        number = number%(24*60);
        var hour = Math.floor(number/60);
        var min = number%60;
        if (hour < 10) hour = '0' + hour;
        if (min < 10) min = '0' + min;
        return hour + ':' + min;
    };

    var createTime = function(time) {
        if (typeof time === 'string') {
            time = convertTimeToNum(time);
        }
        if (typeof time ===  'number') {
            if (time < 100000) {
                var time2 = new Date();
                time2.setHours(Math.floor(time/60)%24);
                time2.setMinutes(time%60);
                time = time2;
            } else {
                time = new Date(time);
            }
        }
        return time;
    };

    var initTimeSelect = function($select, beginTime) {
        var now = beginTime ? createTime(beginTime) : new Date();
        var nowHour = now.getHours();
        var nowMin = Math.floor(now.getMinutes()/10)*10 + 10;
        for(var i = 0; i < 24; ++i) {
            var hour = (i + nowHour)%24;
            if (hour < 5) continue;
            for (var j = 0; j < 6; ++j) {
                var itemTime = convertNumToTime(hour*60+j*10 + nowMin);
                $select.append('<option value="' + itemTime + '">' + itemTime + '</option>');
            }
        }
        if (!$select.val()) {
            time = convertTimeToNum(now.format('hh:mm'));
            time = time - (time%10) + 10;
            $select.val(convertNumToTime(time));
        }
    };

    $.fn.timeSpanControl = function(options) {
        return this.each(function() {
            var $element = $(this);
            var setting = $.extend({}, options, $element.data());
            var $beginInput = $element.find('[name="begin"],.control-time-begin');
            var $endInput = $element.find('[name="end"],.control-time-end');
            var updateEndInput = function() {
                var startVal = $beginInput.val();
                $element.find('.hide-empty-begin').toggleClass('hide', !startVal);
                if (startVal) {
                    var end = convertNumToTime(convertTimeToNum(startVal) + 30);
                    if ($endInput.find('option[value="' + end + '"]').length) $endInput.val(end);
                    setting.onChange && setting.onChange($endInput, end);
                }
            };

            if ($element.data('timeSpanControlInit')) {
                if (setting.begin) {
                    var begin = createTime(setting.begin).format('hh:mm');
                    if ($beginInput.find('option[value="' + begin + '"]').length) $beginInput.val(begin);
                    setting.onChange && setting.onChange($beginInput, begin);
                }
                if (setting.end) {
                    var end = createTime(setting.end).format('hh:mm');
                    if ($endInput.find('option[value="' + end + '"]').length) $endInput.val(end);
                    setting.onChange && setting.onChange($endInput, end);
                }
            } else {
                $beginInput.on('change', updateEndInput);
                initTimeSelect($beginInput, setting.begin);
                initTimeSelect($endInput, setting.end);
                $element.data('timeSpanControlInit', true)
            }

            if (!setting.end) updateEndInput();
        });
    };

    $.timeSpanControl = {
        convertTimeToNum: convertTimeToNum,
        convertNumToTime: convertNumToTime,
        initTimeSelect: initTimeSelect,
        createTime: createTime
    };

    // Searchbox
    var setSearchType = $.setSearchType = function(type, ignoreFocus) {
        var $searchType = $('#searchType');
        if (!type) {
            type = $searchType.val();
        }
        type = type || 'bug';
        $searchType.val(type);
        var $searchTypeMenu = $('#searchTypeMenu');
        $searchTypeMenu.find('li.selected').removeClass('selected');
        var $typeItem = $searchTypeMenu.find('a[data-value="' + type + '"]');
        var typeText = $typeItem.text();
        $typeItem.parent().addClass('selected');
        $('#searchTypeName').text(typeText);
        if(!ignoreFocus) $('#searchInput').focus();
    };
    $.gotoObject = function(type, id) {
        if (!type) type = $('#searchType').val();
        if (!id) id = $('#searchInput').val();
        if (id && type) {
            window.location.href = $.createLink(type, type === 'testsuite' ? 'library' : 'view', 'id=' + id);
        }
    };
    $(function() {
        setSearchType(null, true);
        $(document).on('keydown', function(e) {
            if (e.ctrlKey && e.keyCode === 71) { // Ctrl + G
                $('#searchInput').val('').focus();
                e.stopPropagation();
                e.preventDefault();
            }
        });
    });

    $.removeAnchor = window.removeAnchor = function(url) {
        var hashIndex = url.lastIndexOf('#');
        return hashIndex > -1 ? url.substr(0, hashIndex) : url;
    };

    $.refreshPage = function() {
        location.href = removeAnchor(location.href);
    };

    $.selectLang = window.selectLang = function(lang) {
        $.cookie('lang', lang, {expires: config.cookieLife, path: config.webRoot});
        $.ajaxSendScore('selectLang');
        $.refreshPage();
    };

    // theme
    $.selectTheme = window.selectTheme = function(theme) {
        $.cookie('theme', theme, {expires: config.cookieLife, path: config.webRoot});
        $.ajaxSendScore('selectTheme');
        $.refreshPage();
    };


    $.chosenDefaultOptions = {
        disable_search_threshold: 1,
        // width: null,
        allow_single_deselect: true,
        placeholder_text_single: ' ',
        placeholder_text_multiple: ' ',
        search_contains: true
    };

    $.chosenSimpleOptions = $.extend({}, $.chosenDefaultOptions, {
        disable_search_threshold: 6
    });

    $.fn._chosen = $.fn.chosen;
    $.fn.chosen = function(options) {
        if (typeof options === 'string') {
            return this._chosen(options);
        }
        return this.each(function() {
            var $this = $(this).addClass('chosen-controled');
            return $this._chosen($.extend({}, $this.hasClass('chosen-simple') ? $.chosenSimpleOptions : $.chosenDefaultOptions, $this.data(), options));
        });
    };

    $(function() {
        $('.chosen,.chosen-simple').each(function() {
            var $this = $(this);
            if ($this.closest('.template').length) {
                return;
            }
            $this.chosen();
        });
    });

    // Set pager
    $.extend($.fn.pager.Constructor.DEFAULTS, {
        maxNavCount: 8,
        prevIcon: 'icon-angle-left',
        nextIcon: 'icon-angle-right',
        firstIcon: 'icon-first-page',
        lastIcon: 'icon-last-page',
        navEllipsisItem: '…',
        menuDirection: 'dropup',
        pageSizeOptions: [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 100, 200, 500, 1000, 2000],
        elements: ['total_text', 'size_menu', 'first_icon', 'prev_icon', '<div class="pager-label"><strong>{page}</strong>/<strong>{totalPage}</strong></div>', 'next_icon', 'last_icon'],
        onPageChange: function(state, oldState) {
            if (state.recPerPage !== oldState.recPerPage) {
                $.cookie(this.options.pageCookie, state.recPerPage, {expires:config.cookieLife, path:config.webRoot});
            }
            if (state.recPerPage !== oldState.recPerPage) {
                window.location.href = this.createLink();
            }

        }
    });


    // Set messager
    $.zui.Messager.DEFAULTS.cssClass = 'messagger-zt';


    /* reverseOrder : jQuery order reverser plugin
    *
    * Written by Corey H Maass for Arc90
    * (c) Arc90, Inc.
    *
    * http://www.arc90.com
    * http://lab.arc90.com/2008/05/22/jquery-reverse-order-plugin/#licensing
    *
    * Licensed under:
    * Creative Commons Attribution-Share Alike 3.0 http://creativecommons.org/licenses/by-sa/3.0/us/
    *
    * Gotta love a plugin with more comments than actual code. :-)
    * items need to all be in the same parent like:
    * <div>
    * 	<div class="item">item 1</div>
    * 	<div class="item">item 2</div>
    * 	<div class="item">item 3</div>
    * </div>
    *
    * Then call the plugin with the items to reverse:
    * $('.item').reverseOrder();
    *
    */
    $.fn.reverseOrder = function() {
        return this.each(function() {
            var $ele = $(this);
            $ele.prependTo($ele.parent());
        });
    };


    // History list
    var initHistories = function(element, options) {
        var $element = $(element);
        options = $.extend({}, $element.data(), options);
        var $list = $element.find('.histories-list');
        var ascOrder = true, expandAll = false;
        $element.on('click', '.btn-reverse', function() {
            $list.children('li').reverseOrder();
            ascOrder = !ascOrder;
            $(this).find('.icon').toggleClass('icon-arrow-up', ascOrder).toggleClass('icon-arrow-down', !ascOrder);
        }).on('click', '.btn-expand-all', function() {
            var $icon = $(this).find('.icon');
            expandAll = !expandAll;
            $icon.toggleClass('icon-plus', !expandAll).toggleClass('icon-minus', expandAll);
            $list.children('li').toggleClass('show-changes', expandAll);
        }).on('click', '.btn-expand', function() {
            $(this).closest('li').toggleClass('show-changes');
        }).on('click', '.btn-strip', function() {
            var $btn = $(this);
            var $icon = $btn.find('.icon');
            var isOriginal = $icon.hasClass('icon-code');
            $icon.toggleClass('icon-code', !isOriginal).toggleClass('icon-text', isOriginal);
            $btn.attr('title', isOriginal ? options.original : options.textdiff);
            $btn.closest('li').toggleClass('show-original', isOriginal);
        });
        $list.find('.btn-strip').attr('title', options.original);

        var $commentModal = $element.find('.modal-comment').modal({
            show: false,
            // backdrop: false,
            // position: function(modal) {
            //     var bouding = $list[0].getBoundingClientRect();
            //     var $dialog = modal.$element.find('.modal-dialog');
            //     var modalWidth = Math.min(670, $element.outerWidth());
            //     var modalHeight = $dialog.outerHeight();
            //     return {
            //         width: modalWidth,
            //         top: Math.min(window.innerHeight - modalHeight, bouding.top - 40),
            //         left: bouding.left + bouding.width - modalWidth,
            //         margin: 0
            //     };
            // }
        }).on('shown.zui.modal', function() {
            var $comment = $commentModal.find('#comment');
            if ($comment.length) {
                $comment.focus();
                window.editor && window.editor.comment && window.editor.comment.focus();
            }
        }).on('show.zui.modal', function() {
            var $comment = $commentModal.find('#comment');
            if ($comment.length) {
                if (!$comment.data('keditor') && $.fn.kindeditor) {
                    $comment.kindeditor();
                }
            }
        });
        $element.on('click', '.btn-comment', function(e) {
            $commentModal.modal('toggle');
            e.preventDefault();
        }).on('click', '.btn-edit-comment,.btn-hide-form', function() {
            $(this).closest('li').toggleClass('show-form');
        });

        var $commentEditForm = $element.find('.comment-edit-form');
        $commentEditForm.ajaxForm({
            success: function(response, textStatus, jqXHR, $form) {
                setTimeout(function() {
                    $commentEditForm.closest('li').removeClass('show-form');
                }, 2000)
            }
        })
    };

    $.fn.histories = function(options) {
        return this.each(function() {
            initHistories(this, options);
        });
    };

    $(function() {
        $('.histories').histories();
    });


    // Sidebar
    var toggleSiebarTask = 0;
    var sidebarId = 0;
    $.toggleSidebar = function(toggle) {
        var $sidebar = $('#sidebar');
        if (!$sidebar.length) return;

        var $main = $('main');
        if (toggle === undefined) toggle = $main.hasClass('hide-sidebar');
        else if (toggle && !$main.hasClass('hide-sidebar')) return;
        $main.toggleClass('hide-sidebar', !toggle);

        clearTimeout(toggleSiebarTask);
        if (toggle) {
            $sidebar.addClass('showing');
            toggleSiebarTask = setTimeout(function() {
                $sidebar.removeClass('showing');
                $sidebar.trigger('sidebar.toggle', toggle);
            }, 210);
        } else {
            $sidebar.trigger('sidebar.toggle', toggle);
        }
        $.zui.store.set(sidebarId, toggle);
    };
    var initSidebar = $.initSidebar = function() {
        var $sidebar = $('#sidebar');
        if (!$sidebar.length) return;
        if ($sidebar.data('init')) return true;
        sidebarId = 'sidebar:' + ($sidebar.data('id') || (config.currentModule + '/' + config.currentMethod));
        var $main = $('main');
        $main.on('click', '.sidebar-toggle', function() {
            $.toggleSidebar($main.hasClass('hide-sidebar'));
        });
        var initToggle = $.zui.store.get(sidebarId, $sidebar.data('hide') !== false);
        if (initToggle === false) $sidebar.addClass('no-animate');
        $.toggleSidebar(initToggle);
        if (initToggle === false) {
            setTimeout(function() {
                $sidebar.removeClass('no-animate');
            }, 500);
        }
        var fixAnglePos = function() {
            var $toggle = $sidebar.find('.sidebar-toggle');
            if ($toggle.length) {
                var toggleBound = $toggle[0].getBoundingClientRect();
                var winHeight = $(window).height();
                var angleTop = Math.max(0, Math.floor((Math.min(winHeight - 40, toggleBound.top + toggleBound.height) - Math.max(toggleBound.top, 0)))/2) + (toggleBound.top < 0 ? (0 - toggleBound.top) : 0);
                $toggle.find('.icon').css('top', angleTop);
            }
        };
        fixAnglePos();
        $(window).on('resize', fixAnglePos).on('scroll', fixAnglePos);
        $sidebar.data('init', 1);
        return true;
    };
    if (!initSidebar()) $(initSidebar);


    // Query box
    $.toggleQueryBox = function(toggle, queryTag) {
        var $queryBox = $(queryTag || '#queryBox');
        if (!$queryBox.length) return;
        if (toggle === undefined) toggle = !$queryBox.hasClass('show');
        $queryBox.toggleClass('show', !!toggle);

        if (!$queryBox.data('init')) {
            $queryBox.addClass('load-indicator loading').data('init', 1);
            $.get($.createLink('search', 'buildForm'), function(data) {
                $queryBox.html(data).removeClass('loading');
            });
        }

        $('.querybox-toggle').toggleClass('querybox-opened', toggle);
    };

    $(function() {
        var $queryBox = $('#queryBox');
        if (!$queryBox.length) return;
        $(document).on('click', '.querybox-toggle', function() {
            $.toggleQueryBox();
        });
        if ($queryBox.hasClass('show')) {
            $.toggleQueryBox(true);
        }
    });


    // Set color picker
    $.extend($.fn.colorPicker.Constructor.DEFAULTS, {
        colors: ['#3DA7F5', '#75C941', '#2DBDB2', '#797EC9', '#FFAF38', '#FF4E3E']
    });


    // set checked rows of main table in cookie
    window.setCheckedCookie = function() {
        var checkeds = [];
        var $checkboxes = $('#mainContent .main-table tbody>tr input[type="checkbox"]:checked');
        $checkboxes.each(function() {
            var id = parseInt($(this).val(), 10);
            if(id !== NaN) {
                checkeds.push(id);
            }
        });
        $.cookie('checkedItem', checkeds.join(','), {expires: config.cookieLife, path: config.webRoot});
    };


    // Init modal trigger for iframe class
    $.extend($.zui.ModalTrigger.DEFAULTS, {
        scrollInside: true,
        backdrop: 'static'
    });
    $(function() {
        $('a.iframe,.export').each(function() {
            var $a = $(this);
            if ($a.parents('[data-ride="table"],.skip-iframe-modal').length) return;
            var options = {type: 'iframe'};
            if ($a.hasClass('export')) {
                $.extend(options, {
                    width: 800,
                    shown: setCheckedCookie
                });
            }
            $a.modalTrigger(options);
        });
    });


    // Expand or collapse text
    var limitText = function() {
        var $text = $(this);
        var fullText, limitText;
        var options = $.extend({
            limitSize: 40,
            // toggleBtn: null,
            suffix: '…'
        }, $text.data());

        var text = $text.text();
        if (text.length > options.limitSize) {
            fullText = text;
            limitText = text.substr(0, options.limitSize) + options.suffix;
            $text.text(limitText).addClass('limit-text-on');

            var $toggleBtn = options.toggleBtn ? $(options.toggleBtn) : $text.next('.text-limit-toggle');
            $toggleBtn.text($toggleBtn.data('textExpand'));
            $toggleBtn.on('click', function() {
                var isLimitOn = $text.toggleClass('limit-text-on').hasClass('limit-text-on');
                $text.text(isLimitOn ? limitText : fullText);
                $toggleBtn.text($toggleBtn.data(isLimitOn ? 'textExpand' : 'textCollapse'));
            });
        } else {
            (options.toggleBtn ? $(options.toggleBtn) : $text.next('.text-limit-toggle')).hide();
        }
    };
    $.fn.textLimit = function() {
        return this.each(limitText);
    };
    $(function() {
        $('.text-limit').textLimit();
    });

    /**
     * Fixed table head in div box.
     *
     * @param  string $boxObj
     * @access public
     * @return void
     */
    $.fixedTableHead = window.fixedTableHead = function(table, container)
    {
        var $table = $(table);
        if (!$table.is('table')) {
            $table = $table.find('table');
        }
        if (!$table.length) {
            return;
        }
        var $container = $(container || window);
        var lastTheadWidth = null;
        var fixHead = function() {
            var $thead = $table.children('thead');
            var bounds = $thead[0].getBoundingClientRect();
            var $tableFixedHead = $table.next('.fixed-head-table');
            if (bounds.top < 0) {
                var theadWidth = $thead.width();
                if (!$tableFixedHead.length) {
                    var $tableFixedHead = $("<table class='table fixed-head-table' style='position:fixed; top: 0;'></table>").addClass($table.attr('class'));
                    var $fixedHead = $thead.clone();
                    var $fixedHeadThs = $fixedHead.find('th');
                    $thead.find('th').each(function(idx) {
                        $fixedHeadThs.eq(idx).width($(this).width());
                    });
                    $tableFixedHead.append($fixedHead).insertAfter($table);
                } else {
                    if (lastTheadWidth !== theadWidth) {
                        lastTheadWidth = theadWidth;
                        var $fixedHeadThs = $tableFixedHead.find('th');
                        $thead.find('th').each(function(idx) {
                            $fixedHeadThs.eq(idx).width($(this).width());
                        });
                    }
                }
                $tableFixedHead.css({left: bounds.left, width: bounds.width}).show();
            } else {
                $tableFixedHead.hide();
            }
        };
        $container.on('scroll', fixHead).on('resize', fixHead);
        fixHead();
    };


    // Set tr[data-url] clickable
    $(document).on('click', 'tr[data-url]', function() {
        var $this = $(this);
        var url = $this.data('href') || $this.data('url');
        if (url) window.location.href = url;
    });


    // Check onlybody modal frame
    if (config.onlybody === 'yes' && self === parent) {
        window.location.href = window.location.href.replace('?onlybody=yes', '').replace('&onlybody=yes', '');
    }

    // Add module class to body element
    $(function() {
        $('body').addClass('m-{currentModule}-{currentMethod}'.format(config));
    });


    // Adjust #subNavbar position
    var $subNavbar, $pageNav, $pageActions, $subNavbarNav, subNavbarNavWidth;
    var adjustSubNavbar = function() {
        if (!$subNavbar) {
            $subNavbar = $('#subNavbar');
            $pageNav = $('#pageNav');
            $pageActions = $('#pageActions');
            $subNavbarNav = $subNavbar.children('.nav');
            subNavbarNavWidth = $subNavbarNav.outerWidth();
        }
        var subNavbarWidth = $subNavbar.outerWidth();
        var pageNavWidth = $pageNav.outerWidth() || 0;
        var pageActionsWidth = $pageActions.outerWidth() || 0;

        pageNavWidth = pageNavWidth ? (pageNavWidth + 15) : 0;
        pageActionsWidth = pageActionsWidth ? (pageActionsWidth + 15) : 0;
        if (!pageNavWidth && !pageActionsWidth) {
            $subNavbarNav.css({
                maxWidth: null,
                left: null,
                position: 'static'
            });
            return;
        }
        var maxWidth = Math.max(300, subNavbarWidth - pageNavWidth - pageActionsWidth);
        var realWidth = Math.min(maxWidth, subNavbarNavWidth);
        var normalLeft = (subNavbarWidth - realWidth)/2;
        var bestLeft = (pageNavWidth && normalLeft < pageNavWidth) ? pageNavWidth : (pageActionsWidth && normalLeft < pageActionsWidth) ? (subNavbarWidth - realWidth - pageActionsWidth) : 0;
        $subNavbarNav.css({
            maxWidth: maxWidth,
            left: bestLeft ? (bestLeft - normalLeft) : 0,
            position: 'relative'
        });
    };


    // Hanlde window resize event
    var handleWindowResize = function() {
        $.cookie('windowWidth',  window.innerWidth);
        $.cookie('windowHeight', window.innerHeight);
        adjustSubNavbar();
    };
    $(handleWindowResize);
    $(window).on('resize', handleWindowResize);


    // Handle Detail view Hotkeys
    var gotoBack = function() {
        var backLink = $('#back').attr('href');
        if (backLink) window.location.href = backLink;
    };
    var ajaxSendLastNext = function() {
        if(!$.cookie('ajax_lastNext'))
        {
            $.cookie('ajax_lastNext', 'on', {expires: config.cookieLife, path: config.webRoot});
            $.ajaxSendScore('lastNext');
        }
    };
    var gotoPre = function() {
        var preLink = $('#prevPage').attr('href');
        if (preLink) window.location.href = preLink;
        ajaxSendLastNext();
    };
    var gotoNext = function() {
        var nextLink = $('#nextPage').attr('href');
        if (nextLink) window.location.href = nextLink;
        ajaxSendLastNext();
    };
    $(document).on('keydown', function(e) {
        if (e.altKey && e.keyCode === 38) { // Alt + up
            gotoBack();
        } else if (e.keyCode === 37) { // left
            gotoPre();
        } else if (e.keyCode === 39) { // right
            gotoNext();
        }
    });


    // Init tree default options
    $.fn.tree.Constructor.DEFAULTS.initialState = 'preserve';


    // Bing closeModalMethod
    $.closeModal = function(callback, redirect, modal) {
        $.zui.closeModal(modal, callback, redirect);
    };


    // Get theme color
    $.getThemeColor = function(name) {
        if (!$.themeColor) {
            var $mainHeader = $('#mainHeader');
            if ($mainHeader.length) {
                $.themeColor = {
                    primary: $mainHeader.css('border-top-color'),
                    pale: $mainHeader.css('border-bottom-color'),
                    secondary: $mainHeader.css('background-color')
                };
            }
        }
        return name ? ($.themeColor && $.themeColor[name]) : $.themeColor;
    };


    // Fix controls border in input-group
    var fixInputGroup = function(element) {
        var $element = $(element);
        var $last, lastType;
        var $controls = $element.children('.input-group-addon,.form-control:not(.chosen-controled),.chosen-container,.btn,.input-control,.input-group-btn,.datepicker-wrapper');
        $controls.each(function(idx) {
            var $this = $(this);
            var type = $this.is('.input-group-addon') ? 'addon' : $this.is('.chosen-container') ? 'chosen' : $this.is('.btn') ? 'btn' : $this.is('.input-control,.datepicker-wrapper') ? 'insideInput' : $this.is('.input-group-btn') ? 'insideBtn' : 'input';
            var isFirst = !$last;
            var isLast = idx === ($controls.length - 1);
            var style = {};
            style.borderTopLeftRadius = 0;
            style.borderBottomLeftRadius = 0;
            style.borderTopRightRadius = 0;
            style.borderBottomRightRadius = 0;
            if (isFirst) {
                if(type === 'addon') style.borderLeftWidth = 1;
                style.borderTopLeftRadius = 2;
                style.borderBottomLeftRadius = 2;
            }
            if (isLast) {
                if(type === 'addon') style.borderRightWidth = 1;
                style.borderTopRightRadius = 2;
                style.borderBottomRightRadius = 2;
            }
            if (lastType) {
                if ((lastType === 'chosen' || lastType === 'input' || lastType === 'btn' || lastType === 'insideInput' || lastType === 'insideBtn')
                && (type === 'chosen' || lastType === 'input' || type === 'btn' || type === 'insideInput' || type === 'insideBtn')) {
                    style.borderLeftColor = 'transparent';
                }
            }
            (type === 'insideBtn' ? $this.find('.btn') : type === 'insideInput' ? $this.find('.form-control') : type === 'chosen' ? $this.find('.chosen-single,.chosen-choices') : $this).css(style);
            $last = $this;
            lastType = type;
        });
    };

    $.fn.fixInputGroup = function() {
        return this.each(function() {
            fixInputGroup(this);
        });
    };

    var fixMainActionsToolbar = function() {
        var $toolbar = $('#mainActions>.btn-toolbar');
        if ($toolbar.length) {
            var hasChild = false, $pre = null, $this;
            $toolbar.children().each(function() {
                $this = $(this);
                var isDivider = $this.is('.divider');
                if (isDivider && !$pre) {
                    $this.hide();
                }
                if (!hasChild && !isDivider) hasChild = true;
                $pre = isDivider ? null : $this;
            });
            if ($this.is('.divider')) $this.hide();
            if (!hasChild) $toolbar.hide();
        }
    };

    $(function() {
        $('.input-group,.btn-group').fixInputGroup();
        fixMainActionsToolbar();
    });


    // Set placeholder
    if (window.holders) {
        $.each(window.holders, function(key) {
            var $input = $('#' + key);
            if ($input.length && $input.is('input')) {
                $input.attr('placeholder', window.holders[key]);
            }
        });
    }


    // Init help
    var initHelpLink = function()
    {
        var zentaoUrl = config.clientLang == 'en' ? 'http://www.zentao.pm/book/zentaomanual/8.html?fullScreen=zentao' : 'http://www.zentao.net/book/zentaopmshelp.html?fullScreen=zentao';
        var $mainNav = $('#navbar > .nav').first();
        var showLoadingError;
        var timeout = 10000;

        var clearLoadingError = function()
        {
            clearTimeout(showLoadingError);
            $('#helpContent').removeClass('show-error');
        };

        var openHelp = $.openHelp = function()
        {
            clearLoadingError();
            var $oldActiveItem = $mainNav.children('li.active:not(#helpMenuItem)').removeClass('active').addClass('close-help-tab');
            var $helpMenuItem = $('#helpMenuItem').addClass('active');
            var $help = $('#helpContent');
            if(!$help.length)
            {
                $help = $('<div id="helpContent"><div class="load-error text-center"><h4 class="text-danger">' + lang.timeout + '</h4><p><a href="###" class="open-help-tab"><i class="icon icon-arrow-right"></i> ' + zentaoUrl + '</a></p></div><iframe id="helpIframe" name="helpIframe" src="' + zentaoUrl + '" frameborder="no" allowtransparency="true" scrolling="auto" hidefocus="" style="width: 100%; height: 100%; left: 0px;"></iframe></div>');
                $('#header').after($help);
                var frame = $('#helpIframe').get(0);
                showLoadingError = setTimeout(function()
                {
                    $('#helpContent').addClass('show-error');
                }, timeout);
                frame.onload = frame.onreadystatechange = function()
                {
                    if(this.readyState && this.readyState != 'complete') return;
                    clearLoadingError();
                }
            } else if($('body').hasClass('show-help-tab'))
            {
                $('#helpIframe').get(0).contentWindow.location.replace(zentaoUrl);
                return;
            }
            $('body').addClass('show-help-tab');
        };

        var closeHelp = $.closeHelp = function()
        {
            $('body').removeClass('show-help-tab');
            $('#helpMenuItem').removeClass('active');
            $mainNav.find('li.close-help-tab').removeClass('close-help-tab').addClass('active').find('a').focus();
        };

        $(document).on('click', '.open-help-tab', function(e)
        {
            var $helpMenuItem = $('#helpMenuItem');
            if(!$helpMenuItem.length)
            {
                $helpMenuItem = $('<li id="helpMenuItem"><a href="javascript:;" class="open-help-tab">' + $(this).text() + ' <i class="icon icon-close close-help-tab icon-sm"></i></a></li>');
                $mainNav.append('<li class="divider"></li>').append($helpMenuItem);
            }
            openHelp();
            e.preventDefault();
        }).on('click', '.close-help-tab', function(e)
        {
            closeHelp();
            e.stopPropagation();
            e.preventDefault();
        });
    };

    $(initHelpLink);
}(jQuery));
