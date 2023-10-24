<?php
declare(strict_types=1);
namespace zin;

class commentDialog extends wg
{
    protected static array $defineProps = array(
        'id?: string="comment-dialog"',
        'title?:string',
        'url?:string',
        'name?:string="comment"',
        'method?:string="post"',
        'load?: bool|string',
    );

    protected function build(): wg
    {
        global $lang;
        $id     = $this->prop('id');
        $title  = $this->prop('title');
        $name   = $this->prop('name');
        $url    = $this->prop('url');
        $method = $this->prop('method');
        $load   = $this->prop('load');
        if(empty($title)) $title = $lang->action->create;

        return modal
        (
            setID($id),
            set::modalProps(array('title' => $title)),
            commentForm
            (
                set::url($url),
                set::method($method),
                set::name($name),
                set::closeModal(true),
                set::load($load)
            )
        );
    }
}
