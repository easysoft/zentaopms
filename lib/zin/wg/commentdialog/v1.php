<?php
declare(strict_types=1);
namespace zin;

class commentDialog extends wg
{
    protected static array $defineProps = array(
        'title?:string',
        'url?:string',
        'name?:string="comment"',
        'method?:string="post"'
    );

    protected function build(): wg
    {
        global $lang;
        $title  = $this->prop('title');
        $name   = $this->prop('name');
        $url    = $this->prop('url');
        $method = $this->prop('method');
        if(empty($title)) $title = $lang->action->create;

        return modal
        (
            set::id('comment-dialog'),
            set::title($title),
            commentForm
            (
                set::url($url),
                set::method($method),
                set::name($name),
            )
        );
    }
}
