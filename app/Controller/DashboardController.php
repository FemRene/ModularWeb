<?php
namespace App\Controller;

use App\Models\Setting;
use Illuminate\Support\Facades\Route;

class DashboardController
{
    public function getIndex() {
        $enabled = Setting::get('registration_enabled');
        return view('admin.dash.index', compact('enabled'));
    }
}
