<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'avatar' . DS . 'v1.php';

class userAvatar extends wg
{
    protected static $defineProps = array
    (
        'className?:string',
        'style?:array',
        'size?:number=32',
        'circle?:bool=true',
        'rounded?:string|number',
        'background?:string',
        'foreColor?:string',
        'text?:string',
        'code?:string',
        'maxTextLength?:number=2',
        'hueDistance?:number=43',
        'saturation?:number=0.4',
        'lightness?:number=0.6',
        'src?:string',
        'avatar?:string',
        'account?:string',
        'realname?:string',
        'user?:array|object'
    );

    public function onAddChild($child)
    {
        if(is_object($child) && isset($child->account))
        {
            $this->props->set('user', $child);
            return false;
        }
        return parent::onAddChild($child);
    }

    protected function build()
    {
        list($user, $avatar, $account, $realname) = $this->prop(array('user', 'avatar', 'account', 'realname'));
        if(is_array($user))
        {
            $avatar   = isset($user['avatar'])   ? $user['avatar']   : $avatar;
            $account  = isset($user['account'])  ? $user['account']  : $account;
            $realname = isset($user['realname']) ? $user['realname'] : $realname;
        }
        elseif(is_object($user))
        {
            $avatar   = isset($user->avatar)   ? $user->avatar   : $avatar;
            $account  = isset($user->account)  ? $user->account  : $account;
            $realname = isset($user->realname) ? $user->realname : $realname;
        }

        return avatar
        (
            set::src($avatar),
            set::code($account),
            set::text(empty($realname) ? $account : $realname),
            set($this->props->skip(array('avatar', 'account', 'realname', 'user')))
        );
    }
}
