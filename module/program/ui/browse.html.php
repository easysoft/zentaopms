<?php
namespace zin;

page
(
    div('Hello world!', icon('flag', 'test')),
    icon('star', setClass('text-red'), 'ha', '123'),
    setClass('text-primary'),
    'Hello world!',
    btn('Click me', set('type', 'primary'))
)->print();
