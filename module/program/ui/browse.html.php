<?php
namespace zin;

page
(
    set    ('title', $title),
    h      ('h1', 'hello2')->setClass('text-danger'),
    btn
    (
        'Primary',
        set('.', array('text-danger' => true)),
        set('active', true),
        set('icon', 'flag'),
    ),
    icon('project'),
    icon(set('name', 'project')),
    div
    (
        icon('star'),
        setClass ('primary-pale'),
        h2       ('Headings2'),
        h3       ('Headings3'),
        html     ('<div>test</div>'),
        p
        (
            'lorem',
            strong('bold'),
        )
    ),
    to
    (
        'body',
        html('<style>body{color:red}</style>'),
    ),
    $listChildren
);

/*
page
(
  set('title', $title),
  pageheader
  (
    to('header', pageheading()),
    pageheading(set()),
    pagenavbar(),
    pagetoolbar()
  ),
  pagemain
  (
    mainmenu
    (
      set($mainMenuOptions),
      to
      (
        'toolbar',
        toolbar
        (
          $mainMenuToolbar,
          set('items', array(array('text' => 'copy'), array('type' => 'divider'))),
          item(array('text' => 'copy')),
          item(array('type' => 'divider'))
        )
      )
    ),
    dtable
    (
      set($dtableOptions)
    )
  )
); */

