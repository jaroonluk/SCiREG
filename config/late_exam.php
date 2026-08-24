<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Late exam department dropdown overrides
    |--------------------------------------------------------------------------
    |
    | Suggestions on /late-exam/record come from eoffice.tbldepartment, then
    | these rules are applied before showing in the autocomplete list.
    |
    */

    'departments' => [

        'extra' => [
            'สาขาวิชาวัสดุศาสตร์และนาโนเทคโนโลยี',
            'สาขาวิชาวิทยาศาสตร์แบตเตอรี่และพลังงานใหม่',
        ],

        'rename' => [
            'สาขาวิชาสถิต' => 'สาขาวิชาสถิติและวิทยาการข้อมูล',
            'สาขาวิชาสถิติ' => 'สาขาวิชาสถิติและวิทยาการข้อมูล',
        ],

        'exclude_contains' => [
            'วิทยาการข้อมูลและปัญญาประดิษฐ์',
            'วิทยาการคอมพิวเตอร์',
        ],

    ],

];
