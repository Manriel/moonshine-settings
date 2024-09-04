<?php

declare(strict_types=1);

namespace MoonShineSettings\Pages;

use MoonShine\Components\FlexibleRender;
use MoonShine\Components\FormBuilder;
use MoonShine\Components\Layout\Div;
use MoonShine\Decorations\Decoration;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Field;
use MoonShine\Pages\Page;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Traits\Fields\WithFormElementAttributes;
use MoonShine\Traits\WithComponentAttributes;
use MoonShine\Traits\WithFields;
use MoonShine\Traits\WithUniqueId;
use MoonShineSettings\Http\Controllers\SettingsController;
use MoonShine\Traits\Fields\SelectTrait;
use MoonShine\Traits\Fields\WithDefaultValue;
use MoonShine\Traits\WithHint;
use MoonShine\Traits\WithLabel;
use MoonShineSettings\Repository\SettingsRepository;

#[Icon('heroicons.outline.cog-8-tooth')]
class SettingsPage extends Page
{
    
    protected SettingsRepository $repository;
    
    /**
     * @return array<string, string>
     */
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title(),
        ];
    }
    
    public function title(): string
    {
        return __('moonshine-settings::settings.settings');
    }
    
    public function repository(): SettingsRepository
    {
        if (!isset($this->repository)) {
            $this->repository = new SettingsRepository();
        }
        
        return $this->repository;
    }
    
    public function fields(): array
    {
        $config = $this->repository()->getConfig();
        
        if (array_key_exists('fields', $config) && is_array($config['fields'])) {
            return [
                Tabs::make($this->parseConfigFields($config['fields'])),
            ];
        }
        
        return [];
    }
    
    protected function parseConfigFields(array $items, $namespace = ''): array
    {
        $fields = [];
        foreach ($items as $fieldName => $params) {
            $children = [];
            if (!isset($params['type'])) {
                continue;
            }
            
            /**
             * @var Field|Decoration $field
             */
            $field = new $params['type']();
            if (method_exists($field, 'setColumn')) {
                $field->setColumn($namespace.$fieldName);
            }
            if (in_array(WithUniqueId::class, class_uses_recursive(get_class($field))) ) {
                $field->uniqueId($namespace.$fieldName);
            }
            if (in_array(WithLabel::class, class_uses_recursive(get_class($field))) ) {
                $field->setLabel($params['label'] ?? $namespace.$fieldName);
            }
            if (in_array(WithHint::class, class_uses_recursive(get_class($field)))) {
                $field->hint($params['description'] ?? '');
            } elseif (in_array(WithFields::class, class_uses_recursive(get_class($field)))) {
                $children[] = Div::make([
                                            FlexibleRender::make($params['description'] ?? ''),
                                        ])->customAttributes(['class' => 'form-group']);
            }
            if (in_array(WithFormElementAttributes::class, class_uses_recursive(get_class($field))) ) {
                $field->setId($namespace.$fieldName);
                $field->setName($namespace.$fieldName);
            }
            if (in_array(WithDefaultValue::class, class_uses_recursive(get_class($field)))) {
                $field->default($params['value'] ?? '');
            }
            if (in_array(SelectTrait::class, class_uses_recursive(get_class($field)))) {
                $field->options($params['options'] ?? []);
            }
            if (in_array(WithComponentAttributes::class, class_uses_recursive(get_class($field)))) {
                $field->customAttributes($params['attributes'] ?? []);
            }
            
            if (in_array(WithFields::class, class_uses_recursive(get_class($field))) && isset($params['fields']) && is_array($params['fields'])) {
                $field->fields(array_merge($children, $this->parseConfigFields($params['fields'], $namespace.$fieldName.'_')));
            }
            
            $fields[] = $field;
        }
        
        return $fields;
    }

    /**
     * @return list<MoonShineComponent>
     */
    public function components(): array
	{
		return [
            FormBuilder::make(action([SettingsController::class, 'store']))
                ->name('settings-form')
                ->method("POST")
                ->fields($this->fields())
                ->fill($this->repository()->get())
                ->async(),
        ];
	}
}
