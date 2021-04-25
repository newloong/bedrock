<?php

return [
    'editor-color-palette' => [
        [
            'name'  => __('White', 'gds'),
            'slug'  => 'white',
            'color' => 'var(--gds-color-white)',
        ],
        [
            'name'  => __('Light gray', 'gds'),
            'slug'  => 'ui-background-01',
            'color' => 'var(--gds-color-ui-background-01)',
        ],
        [
            'name'  => __('Light gray 50%', 'gds'), // @todo style guide has outdated value
            'slug'  => 'ui-background-02',
            'color' => 'var(--gds-color-ui-background-02)',
        ],
        [
            'name'  => __('Medium gray', 'gds'),
            'slug'  => 'ui-01',
            'color' => 'var(--gds-color-ui-01)',
        ],
        [
            'name'  => __('Dark gray', 'gds'),
            'slug'  => 'ui-02',
            'color' => 'var(--gds-color-ui-02)',
        ],
        [
            'name'  => __('Black', 'gds'),
            'slug'  => 'black',
            'color' => 'var(--gds-color-black)',
        ],
        [
            'name'  => __('Green', 'gds'),
            'slug'  => 'ui-03',
            'color' => 'var(--gds-color-ui-03)',
        ],
        [
            'name'  => __('Blue', 'gds'),
            'slug'  => 'ui-04',
            'color' => 'var(--gds-color-ui-04)',
        ],
        [
            'name'  => __('Red', 'gds'),
            'slug'  => 'ui-05',
            'color' => 'var(--gds-color-ui-05)',
        ],
        [
            'name'  => __('Pink', 'gds'),
            'slug'  => 'ui-06',
            'color' => 'var(--gds-color-ui-06)',
        ],
        [
            'name'  => __('Purple', 'gds'),
            'slug'  => 'ui-07',
            'color' => 'var(--gds-color-ui-07)',
        ],
    ],
    'editor-font-sizes' => [
        [
            'name' => __('S paragraph', 'gds'),
            'slug' => 's-paragraph',
            'size' => 'var(--gds-paragraph-s-font-size)',
        ],
        [
            'name' => __('M paragraph', 'gds'),
            'slug' => 'm-paragraph',
            'size' => 'var(--gds-paragraph-m-font-size)',
        ],
        [
            'name' => __('L paragraph', 'gds'),
            'slug' => 'l-paragraph',
            'size' => 'var(--gds-paragraph-l-font-size)',
        ],
        [
            'name' => __('S heading', 'gds'),
            'slug' => 's-heading',
            'size' => 'var(--gds-heading-s-font-size)',
        ],
        [
            'name' => __('M heading', 'gds'),
            'slug' => 'm-heading',
            'size' => 'var(--gds-heading-m-font-size)',
        ],
        [
            'name' => __('L heading', 'gds'),
            'slug' => 'l-heading',
            'size' => 'var(--gds-heading-l-font-size)',
        ],
        [
            'name' => __('XL heading', 'gds'),
            'slug' => 'xl-heading',
            'size' => 'var(--gds-heading-xl-font-size)',
        ],
        [
            'name' => __('XXL heading', 'gds'),
            'slug' => 'xxl-heading',
            'size' => 'var(--gds-heading-2xl-font-size)',
        ],
    ],
    'editor-gradient-presets' => [
        [
            'name'     => __('Green radial gradient', 'gds'),
            'slug'     => 'gradient-01',
            'gradient' => 'var(--gradient-01)',
        ],
    ],
    // 'patterns' => [
    //     [
    //         'name' => 'cta-banner', // resources/patterns/cta-banner.html
    //         'title' => __('Call to action banner', 'gds'),
    //         'categories' => ['header'],
    //         'description' => __('The colored block at the end of pages prompting the visitor to take action.', 'gds'),
    //     ],
    // ]
];
