<?php
declare(strict_types=1);
namespace zin;

class commentForm extends wg
{
    protected static array $defineProps = array(
        'url?:string',
        'name?:string="comment"',
        'closeModal?: bool',
        'load?: bool|string',
        'method?:string="POST"'
    );

    protected function build(): wg
    {
        global $lang;
        $url        = $this->prop('url');
        $name       = $this->prop('name');
        $method     = $this->prop('method');
        $closeModal = $this->prop('closeModal');
        $load       = $this->prop('load');
        if(empty($name)) $name = 'comment';

        return form
        (
            set::url($url),
            set::method($method),
            set::submitBtnText($lang->save),
            setData('close-modal', $closeModal),
            setData('load', $load),
            setClass('comment-form'),
            editor
            (
                setID($name),
                set::name($name)
            ),
            set::actions
            (
                array(
                    'submit',
                    array('data-dismiss' => 'modal', 'text' => $lang->close)
                )
            )
        );
    }
}
