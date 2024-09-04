<?php

namespace MoonShineSettings\Http\Controllers;

use Illuminate\Http\Request;
use MoonShine\Http\Controllers\MoonShineController;
use MoonShine\MoonShineUI;
use MoonShineSettings\Pages\SettingsPage;

class SettingsController extends MoonShineController
{
    public function index(Request $request)
    {
        return SettingsPage::make();
    }
    
    public function store(Request $request)
    {
        $data = $request->except(['_token', '_method', '_component_name']);
        $page = new SettingsPage();
        $page->repository()->set($data);
        
        MoonShineUI::toast(__('moonshine::ui.saved'),'success');
        
        return redirect()->route('moonshine.settings.index');
    }
}
