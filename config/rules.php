<?php
$rules = [
        'author/get-author-list/<page:\d+>' => 'author/index',
        'author/' => 'author/index',
    ];

return $rules;