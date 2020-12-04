<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $domains = DB::table('domains')->get();
        $lastChecks = DB::table('domain_checks')
            ->select('domain_id', 'created_at', 'status_code')
            ->orderBy('domain_id')
            ->orderByDesc('created_at')
            ->distinct('domain_id')
            ->get()
            ->keyBy('domain_id');
        return view('domain.index', compact('domains', 'lastChecks'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $url = $request->input('domain');
        $validator = Validator::make($url, [
            'name' => 'required|url',
        ]);
        if ($validator->fails()) {
            flash('Url is not valid')->error();
            return redirect()->back()->withInput($url)->withErrors($validator->errors());
        }
        $parsedUrl = parse_url($url['name']);
        $parsedUrl = "{$parsedUrl['scheme']}://{$parsedUrl['host']}";
        $lowUrl = strtolower($parsedUrl);
        $domain = DB::table('domains')->where('name', $lowUrl)->first();
        dd($domain);
        if (!empty($domain)) {
            flash('Domain already exists')->info();
            return redirect()->route('domains.show', $domain->id);
        }
        $id = DB::table('domains')->insertGetId([
            'name' => $lowUrl,
            'updated_at' => Carbon::now()->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString()
            ]);
        flash('Url has been added')->success();
        return redirect()->route('domains.show', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $domain = DB::table('domains')->find($id);
        $domainChecks = DB::table('domain_checks')
            ->where('domain_id', $id)
            ->orderByDesc('created_at')
            ->get();
        return view('domain.show', compact('domain', 'domainChecks'));
    }
}
