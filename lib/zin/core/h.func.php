<?php
declare(strict_types=1);
/**
 * The html helper methods file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'h.class.php';

function h(mixed ...$args): h          {return h::create(...$args);}
function div(mixed ...$args): h        {return h::div(...$args);}
function span(mixed ...$args): h       {return h::span(...$args);}
function code(mixed ...$args): h       {return h::code(...$args);}
function canvas(mixed ...$args): h     {return h::canvas(...$args);}
function br(mixed ...$args): h         {return h::br(...$args);}
function a(mixed ...$args): h          {return h::a(...$args);}
function p(mixed ...$args): h          {return h::p(...$args);}
function img(mixed ...$args): h        {return h::img(...$args);}
function button(mixed ...$args): h     {return h::button(...$args);}
function h1(mixed ...$args): h         {return h::h1(...$args);}
function h2(mixed ...$args): h         {return h::h2(...$args);}
function h3(mixed ...$args): h         {return h::h3(...$args);}
function h4(mixed ...$args): h         {return h::h4(...$args);}
function h5(mixed ...$args): h         {return h::h5(...$args);}
function h6(mixed ...$args): h         {return h::h6(...$args);}
function ul(mixed ...$args): h         {return h::ul(...$args);}
function li(mixed ...$args): h         {return h::li(...$args);}
function template(mixed ...$args): h   {return h::template(...$args);}
function formHidden(mixed ...$args): h {return h::formHidden(...$args);}
function fieldset(mixed ...$args): h   {return h::fieldset(...$args);}
function legend(mixed ...$args): h     {return h::legend(...$args);}
function rawContent(): text            {return h::comment('{{RAW_CONTENT}}');}
