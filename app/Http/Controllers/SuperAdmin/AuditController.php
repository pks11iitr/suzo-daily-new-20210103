<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function audits(Request $request){

        $audits=Audit::orderBy('id', 'desc')
        ->paginate(20);
        return view('admin.audits.audits', compact('audits'));

    }
}
