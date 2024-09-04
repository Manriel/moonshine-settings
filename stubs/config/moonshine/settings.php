<?php

return [
    
    'fields' => [
        'system' => [
            'label'       => 'moonshine-settings::settings.system.label',
            'description' => 'moonshine-settings::settings.system.description',
            'type'        => \MoonShine\Decorations\Tab::class,
            'fields'      => [
                'label'       => [
                    'label'       => 'moonshine-settings::settings.system-label.label',
                    'description' => 'moonshine-settings::settings.system-label.description',
                    'type'        => \MoonShine\Fields\Text::class,
                    'value'       => 'Example system label',
                ],
                'description' => [
                    'label'       => 'moonshine-settings::settings.system-description.label',
                    'description' => 'moonshine-settings::settings.system-description.description',
                    'type'        => \MoonShine\Fields\Text::class,
                    'value'       => 'Example system description',
                ],
            ],
        ],
        
        'examples' => [
            'label'       => 'moonshine-settings::settings.examples.label',
            'description' => 'moonshine-settings::settings.examples.description',
            'type'        => \MoonShine\Decorations\Tab::class,
            'fields'      => [
                'text'     => [
                    'label' => 'Text',
                    'type'  => \MoonShine\Fields\Text::class,
                    'value' => 'Example text string',
                ],
                'number'   => [
                    'label' => 'Number',
                    'type'  => \MoonShine\Fields\Number::class,
                    'value' => 1000,
                ],
                'date'     => [
                    'label' => 'Date',
                    'type'  => \MoonShine\Fields\Date::class,
                    'value' => \Carbon\Carbon::make('now'),
                ],
                'textarea' => [
                    'label' => 'Textarea',
                    'type'  => \MoonShine\Fields\Textarea::class,
                    'value' => 'Example' . PHP_EOL . 'Multiline text',
                ],
                'select'   => [
                    'label'   => 'Select',
                    'type'    => \MoonShine\Fields\Select::class,
                    'options' => [
                        'default'  => 'moonshine-settings::settings.default-value',
                        'example1' => 'moonshine-settings::settings.example',
                        'example2' => 'moonshine-settings::settings.example',
                        'example3' => 'moonshine-settings::settings.example',
                    ],
                    'value'   => 'default',
                ],
                'checkbox' => [
                    'label' => 'moonshine-settings::settings.checkbox',
                    'type'  => \MoonShine\Fields\Checkbox::class,
                    'value' => 1,
                ],
                'switcher' => [
                    'label' => 'moonshine-settings::settings.switcher',
                    'type'  => \MoonShine\Fields\Switcher::class,
                    'value' => 0,
                ],
            ],
        ],
    ],
];
