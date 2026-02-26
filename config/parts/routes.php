<?php

return [
    'enablePrettyUrl' => true,
    'showScriptName'  => false,
    'rules'           => [
        'authors/<id:\d+>'                                => 'authors/view',
        'authors/<id:\d+>/update'                         => 'authors/update',
        'authors/<id:\d+>/delete'                         => 'authors/delete',
        'authors/<id:\d+>/subscribe-for-new-books'        => 'authors/subscribe-for-new-books',
        'books/<id:\d+>'                                  => 'books/view',
        'books/<id:\d+>/update'                           => 'books/update',
        'books/<id:\d+>/delete'                           => 'books/delete',
        'reports/top-10-authors-by-book-count/<year:\d+>' => 'reports/top-10-authors-by-book-count',
    ],
];
