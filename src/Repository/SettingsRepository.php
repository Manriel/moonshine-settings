<?php

namespace MoonShineSettings\Repository;

use Illuminate\Support\Facades\DB;

class SettingsRepository
{
    protected $config;
    
    public function getConfig()
    {
        if ( !isset($this->config)) {
            $this->config = $this->resolveConfig(config('moonshine.settings'));
        }
        
        return $this->config;
    }
    
    private function resolveConfig(array $config = []): array
    {
        foreach ($config as $key => $value) {
            if (is_array($value)) {
                $config[$key] = $this->resolveConfig($value);
            } elseif (gettype($value) === 'string') {
                $config[$key] = __($value);
            } else {
                $config[$key] = $value;
            }
        }
        
        return $config;
    }
    
    private function getDefaults(): array
    {
        $config   = $this->getConfig();
        $defaults = [];
        if (array_key_exists('fields', $config)) {
            $defaults = $this->getDefaultsForFields($config['fields']);
        }
        
        return $defaults;
    }
    
    private function getDefaultsForFields(array $fields = [], string $namespace = ''): array
    {
        $defaults = [];
        foreach ($fields as $key => $value) {
            if (array_key_exists('fields', $value)) {
                $defaults = array_merge($defaults, $this->getDefaultsForFields($value['fields'], $namespace . $key . '_'));
            } elseif (array_key_exists('value', $value)) {
                $defaults[$namespace . $key] = $value['value'];
            } elseif (array_key_exists('values', $value) && is_array($value['values'])) {
                $defaults[$namespace . $key] = key($value['values']);
            } else {
                $defaults[$namespace . $key] = null;
            }
        }
        
        return $defaults;
    }
    
    public function get(null | array | string $key = null): mixed
    {
        if (is_array($key)) {
            return $this->getValues($key);
        }
        return $this->getValues([$key]);
    }
    
    public function set(array | string $key, mixed $value = null)
    {
        if (is_array($key)) {
            return $this->setValues($key);
        }
        return $this->setValues([$key => $value]);
    }
    
    protected function getValues(array $keys = [])
    {
        $keys     = array_filter($keys);
        $defaults = $this->getDefaults();
        $values   = [];
        if (empty($keys)) {
            $result = DB::table('settings')
                        ->select(['key', 'value'])
                        ->get()
                        ->mapWithKeys(fn($i) => [$i->key => $i->value])
                        ->toArray();
            foreach ($defaults as $key => $value) {
                if (array_key_exists($key, $result)) {
                    $values[$key] = $result[$key];
                }
            }
            return $values;
        }
        
        $result = DB::table('settings')
                    ->select(['key', 'value'])
                    ->whereIn('key', $keys)
                    ->get()
                    ->mapWithKeys(fn($i) => [$i->key => $i->value])
                    ->toArray();
        foreach ($keys as $key) {
            if (array_key_exists($key, $result)) {
                $values[$key] = $result[$key];
            } elseif (array_key_exists($key, $defaults)) {
                $values[$key] = $defaults[$key];
            } else {
                $values[$key] = null;
            }
        }
        
        return $values;
    }
    
    protected function setValues(array $values)
    {
        if (count($values) === 0) {
            return;
        }
        
        $inserts = [];
        foreach ($values as $key => $value) {
            $inserts[] = ['key' => $key, 'value' => $value, 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')];
        }
        
        DB::table('settings')->upsert($inserts, ['key'], ['value', 'updated_at']);
    }
}
