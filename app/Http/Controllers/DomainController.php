<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = DB::table('domains')->paginate(50);
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
        $domainChecks = DB::table('domain_checks')
            ->where('domain_id', $id)
            ->orderByDesc('created_at')
            ->get();
            
        return view('domain.show', compact('domain', 'domainChecks'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check($id)
    {
        $domain = DB::table('domains')->find($id);
        $name = $domain->name;
        $statusCode = Http::get($name)->status();
        $updated_at = Carbon::now()->toDateTimeString();
        $created_at = Carbon::now()->toDateTimeString();
        $document = new Document($name, true);
        $keywords = $document->first('meta[name=keywords]');
        $keywordsContent = $keywords ? $keywords->getAttribute('content') : null;
        $description = $document->first('meta[name=description]');
        $descriptionContent = $description ? $description->getAttribute('content') : null;
        $h1 = $document->first('h1');
        $h1Text = $h1 ? $h1->text() : null;
        $row = DB::table('domain_checks')->insertGetId([
            'domain_id' => $id,
            'status_code' => $statusCode,
            'h1' => $h1Text,
            'keywords' => $keywordsContent,
            'description' => $descriptionContent,
            'updated_at' => $updated_at,
            'created_at' => $created_at,
            ]);
        flash('Website has been checked!')->success();
        return redirect()->route('domains.show', $id);
    }
}
