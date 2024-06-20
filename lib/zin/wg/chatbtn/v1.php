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

        #xuan-chat-view {position: absolute; width: 100%; height: 100%; z-index: 10; display: none;}
        #xuan-chat-view #xx-embed-container {position: absolute; bottom: 0; left: 0; right: 0; top: 0;}

        .unconfigured {position: absolute; width: 330px; padding: 20px; right: 0; top: 0; bottom: 0; background: #fff; outline: 1px solid #eee;}
        .unconfigured > div {margin-bottom: 10px;}
        #xuan-chat-view .unconfigured {top: 50px;}

        #reload-ai-chat.disabled {cursor: wait; color: #999!important;}

        #chat-btn .notice-badge {position: absolute; bottom: 2px; right: 1px; line-height: 14px; height: 14px; min-width: 14px; text-align: center; display: inline-block; font-size: 12px; border-radius: 7px; opacity: 0; transform: scale(0); transition: .2s; transition-property: transform, opacity; padding: 0 2px;}
        #chat-btn .notice-badge.show {opacity: 1; transform: scale(1);}
        CSS;
    }

    public static function getPageJS(): ?string
    {
        global $app;

        $xuanUnconfiguredTip = sprintf($app->lang->index->chat->unconfiguredFormat, $app->lang->index->chat->chat, (common::hasPriv('setting', 'xuanxuan') ? sprintf($app->lang->index->chat->goConfigureFormat, helper::createLink('setting', 'xuanxuan'), $app->lang->index->chat->chat) : $app->lang->index->chat->contactAdminForHelp));

        $chatContainer = <<<HTML
        <div id="chat-container">
            <div id="xuan-chat-view">
                <div class="unconfigured text-gray">$xuanUnconfiguredTip</div>
            </div>
        </div>
        HTML;

        return <<<JAVASCRIPT
        window.toggleChatContainer = () =>
        {
            if(window.xuan) window.xuan[window.xuan.shown ? 'expand' : 'show']();
            $('#chat-btn').toggleClass('active');
            $('#chat-container').toggle();
            $('#xuan-chat-view').toggle();
        };

        /* Setup xuan web chat. */
        window.setupXuan = () =>
        {
            if(!window.xuan)
            {
                window.initXuan();
                return;
            }

            /* Move xuan web client into #xuan-chat-view. */
            if(!document.querySelector('#xuan-chat-view #xx-embed-container'))
            {
                document.querySelector('#xuan-chat-view').prepend(document.querySelector('#xx-embed-container'));

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
                                document.querySelector('#xx-embed-container iframe').contentDocument.querySelector('.app-chats-menu').style.cssText = 'width: 330px !important;';
                                document.querySelector('#xx-embed-container iframe').contentDocument.querySelector('.app-chats-cache').style.cssText = 'right: 330px !important;';
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
        (() =>
        {
            /* Insert chat views. */
            $('body').append(`{$chatContainer}`);

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
