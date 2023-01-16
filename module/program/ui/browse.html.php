<?php
button('BUTTON')->x();

h('h1', 'hello')->x();

btn('哈哈')->primary()->x();

$div = div(
    h2('Headings2'),
    h3('Headings3'),
    h5::p('lorem', h5::strong('bold'))
)->x();

a($div->children);
