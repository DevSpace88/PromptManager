<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings');
    }

    public function update(Request $request)
    {
        // Validiere und speichere die Einstellungen
        // ...

        return redirect()->route('settings.index')->with('success', 'Einstellungen wurden aktualisiert');
    }
}
