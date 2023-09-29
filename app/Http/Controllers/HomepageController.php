<?php

namespace App\Http\Controllers;

use App\Models\DhcpEntry;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index()
    {
        return view('home', [
            'dhcpEntries' => DhcpEntry::latest('updated_at')->get(),
        ]);
    }
}
