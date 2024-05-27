<?php
declare(strict_types=1);
namespace zin;

class chatBtn extends wg
{
    public static function getPageCSS(): ?string
    {
        return <<<CSS
        #chat-container {position: fixed; left: 100px; right: 0; height: calc(100% - 36px); display: none;}
        .hide-menu #chat-container {left: 64px;}

        #chat-switch {position: absolute; width: 330px; height: 52px; right: 0; background: #fff; display: flex; justify-content: center; align-items: center; border-bottom: 1px solid #eee; z-index: 20;}
        .chat-switch-bg {display: flex; justify-content: center; align-items: center; background-color: #eff5ff; border-radius: 16px;}
        .chat-switch-item {width: 96px; padding: 4px 0; border-radius: 16px; text-align: center; color: #838a9c; position: relative; user-select: none; cursor: pointer;}
        .chat-switch-item:hover {color: #838a9c;}
        .chat-switch-item.active {font-weight: bold; color: #fff; background-color: #5999fc;}
        .chat-switch-item.has-notice::after {content: ''; position: absolute; right: 26px; top: 4px; width: 6px; height: 6px; border-radius: 50%; background-color: #ff535d;}

        #xuan-chat-view {position: absolute; width: 100%; height: 100%; z-index: 10; display: none;}
        #xuan-chat-view #xx-embed-container {position: absolute; bottom: 0; left: 0; right: 0; top: 0;}

        #ai-chat-view {position: fixed; right: 0; width: 330px; bottom: 36px; top: 49px; outline: 1px solid; outline-color: rgba(var(--color-border-rgb),var(--tw-border-opacity))}
        #ai-chat-frame {height: 100%; width: 100%;}

        .unconfigured {position: absolute; width: 330px; padding: 20px; right: 0; top: 0; bottom: 0; background: #fff; outline: 1px solid #eee;}
        .unconfigured > div {margin-bottom: 10px;}
        #xuan-chat-view .unconfigured {top: 50px;}

        #reload-ai-chat.disabled {cursor: wait; color: #999!important;}

        #chat-btn .notice-badge {position: absolute; bottom: 4px; right: 0; line-height: 14px; height: 14px; min-width: 14px; text-align: center; display: inline-block; font-size: 12px; border-radius: 7px; opacity: 0; transform: scale(0); transition: .2s; transition-property: transform, opacity; padding: 0 2px;}
        #chat-btn .notice-badge.show {opacity: 1; transform: scale(1);}
        CSS;
    }

    public static function getPageJS(): ?string
    {
        global $app;

        $isAIConfigured  = $app->control->loadModel('ai')->hasModelsAvailable();
        $hasAIChatPriv   = commonModel::hasPriv('ai', 'chat');

        $xuanUnconfiguredTip = sprintf($app->lang->index->chat->unconfiguredFormat, $app->lang->index->chat->chat, (common::hasPriv('setting', 'xuanxuan') ? sprintf($app->lang->index->chat->goConfigureFormat, helper::createLink('setting', 'xuanxuan'), $app->lang->index->chat->chat) : $app->lang->index->chat->contactAdminForHelp));
        $aiUnconfiguredTip   = sprintf($app->lang->index->chat->unconfiguredFormat, $app->lang->index->chat->ai, (common::hasPriv('ai', 'models') ? sprintf($app->lang->index->chat->goConfigureFormat, helper::createLink('ai', 'models') . '#app=admin', $app->lang->index->chat->ai) : $app->lang->index->chat->contactAdminForHelp));
        $aiUnauthorizedTip   = $app->lang->index->chat->unauthorized;
        $aiChatURL           = createLink('ai', 'chat');

        $aiChatView = "<iframe id='ai-chat-frame' src='$aiChatURL' frameborder='no' allowtransparency='true' scrolling='auto' hidefocus></iframe>";
        if(!$hasAIChatPriv)  $aiChatView = "<div class='unconfigured text-gray'>$aiUnauthorizedTip</div>";
        if(!$isAIConfigured) $aiChatView = "<div class='unconfigured text-gray'>$aiUnconfiguredTip</div>";

        $chatContainer = <<<HTML
        <div id="chat-container">
            <div id="chat-switch">
                <div class="chat-switch-bg">
                    <a class="chat-switch-item" data-value="chat">{$app->lang->index->chat->chat}</a>
                    <a class="chat-switch-item active" data-value="ai">{$app->lang->index->chat->ai}</a>
                </div>
            </div>
            <div id="xuan-chat-view">
                <div class="unconfigured text-gray">$xuanUnconfiguredTip</div>
            </div>
            <div id="ai-chat-view">$aiChatView</div>
        </div>
        HTML;

        $xuanDefaultOpener = ($hasAIChatPriv && $isAIConfigured) ? '' : 'if(window.xuan) $(`#chat-switch .chat-switch-item[data-value="chat"]`).trigger("click");';

        return <<<JAVASCRIPT
        window.toggleChatContainer = () => {
            if(window.xuan) window.xuan[window.xuan.shown ? 'expand' : 'show']();
            $('#chat-btn').toggleClass('active');
            $('#chat-container').toggle();
        };

        /* Setup xuan web chat. */
        window.setupXuan = () => {
            /* Move xuan web client into #xuan-chat-view. */
            if(window.xuan && !document.querySelector('#xuan-chat-view #xx-embed-container'))
            {
                document.querySelector('#xuan-chat-view').prepend(document.querySelector('#xx-embed-container'));

                /* Switch to xuan chat by default if AI is not available. */
                $xuanDefaultOpener

                /* Update badge on chat. */
                window.xuan._options.onNotice = function(notice)
                {
                    window.handleXuanNoticeChange(notice);
                    $('.chat-switch-item[data-value="chat"]').toggleClass('has-notice', !!notice.count);
                };

                /* Set style into xuan frame. */
                let tries = 0;
                const xuanFrameWaitLoop = setInterval(function()
                {
                    if(document.querySelector('#xx-embed-container iframe'))
                    {
                        clearInterval(xuanFrameWaitLoop);
                        tries = 0;
                        const chatsViewWaitLoop = setInterval(function()
                        {
                            if(document.querySelector('#xx-embed-container iframe').contentDocument.querySelector('.app-chats-menu'))
                            {
                                clearInterval(chatsViewWaitLoop);
                                document.querySelector('#xx-embed-container iframe').contentDocument.querySelector('.app-chats-menu').style.cssText = 'width: 330px !important; top: 48px;';
                                document.querySelector('#xx-embed-container iframe').contentDocument.querySelector('.app-chats-cache').style.cssText = 'right: 330px !important;';
                                document.querySelector('#xx-embed-container iframe').contentDocument.querySelector('.app-chats-menu-search').style.cssText = 'padding: 10px 8px !important;';
                                document.querySelector('#xx-embed-container iframe').contentDocument.querySelector('.app-chats-menu-search .btn:first-child').style.cssText = 'display: none;';
                            }
                            else
                            {
                                if(++tries > 20) clearInterval(chatsViewWaitLoop);
                            }
                        }, 200);
                    }
                    else
                    {
                        if(++tries > 20) clearInterval(xuanFrameWaitLoop);
                    }
                }, 200);
            }
        };
        (() => {
            /* Insert chat views. */
            $('body').append(`{$chatContainer}`);

            /* Handle switch events. */
            $('#chat-switch .chat-switch-item').on('click', e =>
            {
                const target = $(e.target);
                target.addClass('active').siblings().removeClass('active');
                $('#ai-chat-view').toggle(target.data('value') === 'ai');
                $('#xuan-chat-view').toggle(target.data('value') === 'chat');
            });

            /* Handle clicking outside. */
            $(document).on('click', e =>
            {
                if(!$('#chat-container').length || $('#chat-container').prop('style') && $('#chat-container').prop('style').display !== 'block') return;
                if($(e.target).closest('#chat-switch,#xuan-chat-view>.unconfigured,#ai-chat-view,#chat-btn').length) return;
                window.toggleChatContainer();
            });
        })();
        JAVASCRIPT;
    }

    protected function build()
    {
        return div
        (
            set::id('chat-btn-container'),
            btn
            (
                set::id('chat-btn'),
                setClass('ghost px-1'),
                set('square', false),
                set::icon(img(set('src', 'static/svg/ai-chat.svg'))),
                set('onclick', 'window.toggleChatContainer()'),
                span(setClass(array('badge', 'bg-danger', 'text-white', 'notice-badge')))
            )
        );
    }
}
