<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use DiDom\Document;

class DomainCheckController extends Controller
{
    public function store($id)
    {
        $domain = DB::table('domains')->find($id);

        abort_unless($domain, 404);

        try {
            $data  = Http::get($domain->name);
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->route('domains.show', $id);
        }

            $responseBody = $data->body();
            $statusCode = $data->status();
            $updated_at = Carbon::now()->toDateTimeString();
            $created_at = Carbon::now()->toDateTimeString();
            $document = new Document($responseBody);
            $keywords = $document->first('meta[name=keywords]');
            $keywordsContent = $keywords ? $keywords->getAttribute('content') : null;
            $description = $document->first('meta[name=description]');
            $descriptionContent = $description ? $description->getAttribute('content') : null;
            $h1 = $document->first('h1');
            $h1Text = $h1 ? $h1->text() : null;
            DB::table('domain_checks')->insertGetId([
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
