<?php
declare(strict_types=1);
namespace zin;

class chatBtn extends wg
{
    public static function getPageCSS(): string|false
    {
        return <<<CSS
            #chat-container {position: fixed; left: 96px; right: 0; height: calc(100% - 40px); display: none;}
            #ai-chat-view {position: fixed; right: 0; width: 330px; bottom: 40px; top: 49px; outline: 1px solid; outline-color: rgba(var(--color-border-rgb),var(--tw-border-opacity))}
            #ai-chat-frame {height: 100%; width: 100%;}
        CSS;
    }

    public static function getPageJS(): string|false
    {
        $aiChatURL = createLink('ai', 'chat');
        $chatContainer = <<<HTML
            <div id="chat-container">
                <div id="ai-chat-view">
                    <iframe id="ai-chat-frame" src="{$aiChatURL}" frameborder="no" allowtransparency="true" scrolling="auto" hidefocus></iframe>
                </div>
            </div>
        HTML;
        return <<<JAVASCRIPT
            window.toggleChatContainer = () => {
                if(!$('#chat-container').length)
                {
                    $('body').append(`{$chatContainer}`);
                    $('#ai-chat-frame').on('load', () => {window.toggleChatContainer();});
                    return;
                }
                $('#chat-btn').toggleClass('active');
                $('#chat-container').toggle();
            }
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
                on::click('window.toggleChatContainer()')
            )
        );
    }
}
