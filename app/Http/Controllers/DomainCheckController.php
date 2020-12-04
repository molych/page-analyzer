<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

class DomainCheckController extends Controller
{
      /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store($id)
    {
        $domain = DB::table('domains')->find($id);
        abort_unless($domain, 404);
        try {
            $response  = Http::get($domain->name);
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->route('domains.show', $id);
        }
        $responseBody = $response->body();
        $statusCode = $response->status();
        $document = new Document($responseBody);
        $keywords = optional($document->first('meta[name=keywords]'))->getAttribute('content');
        $description = optional($document->first('meta[name=description]'))->getAttribute('content');
        $h1 = optional($document->first('h1'))->text();
        DB::table('domain_checks')->insertGetId([
            'domain_id' => $id,
            'status_code' => $statusCode,
            'h1' => $h1,
            'keywords' => $keywords,
            'description' => $description,
            'updated_at' => Carbon::now()->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        flash('Website has been checked!')->success();
        return redirect()->route('domains.show', $id);
    }
}
