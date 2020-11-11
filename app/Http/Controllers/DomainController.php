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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = DB::table('domains')->paginate();
        return view('domain.index', compact('domains'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url = $request->input('domain');
        $validator = Validator::make($url, [
            'name' => 'required|url',
        ]);

        if ($validator->fails()) {
            flash('url is not valid')->error();
            return redirect()->route('domains.create');
        }

        $parsedUrl = parse_url($url['name']);
        $parsedUrl = "{$parsedUrl['scheme']}://{$parsedUrl['host']}";
        $updated_at = Carbon::now()->toDateTimeString();
        $created_at = Carbon::now()->toDateTimeString();

        $query = DB::table('domains')->where('name', $parsedUrl)->get()->first();
        if ($query) {
            flash('Domain already exists')->info();
            return redirect()->route('domains.show', $query->id);
        }

        $id = DB::table('domains')->insertGetId([
            'name' => $parsedUrl,
            'updated_at' => $updated_at,
            'created_at' => $created_at
            ]);
        flash('Url has been added')->success();

        return redirect()->route('domains.show', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $domain = DB::table('domains')->find($id);
        return view('domain.show', compact('domain'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
