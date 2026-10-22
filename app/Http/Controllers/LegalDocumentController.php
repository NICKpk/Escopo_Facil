<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalDocumentController extends Controller
{
    public function terms(Request $request, ?string $version = null)
    {
        return $this->document($request, 'terms', $version);
    }

    public function privacy(Request $request, ?string $version = null)
    {
        return $this->document($request, 'privacy', $version);
    }

    private function document(Request $request, string $type, ?string $version)
    {
        $version ??= config('legal.version');
        $entry = config('legal.documents')[$version] ?? [];
        $view = $entry[$type] ?? null;
        abort_unless(is_string($view), 404);
        $title = $type === 'terms' ? 'Termos e Condições de Uso' : 'Aviso de Privacidade';
        $publishedOn = $entry['published_on'] ?? $version;
        $data = compact('type', 'version', 'title', 'publishedOn');
        $data['documentView'] = $view;

        return $request->expectsJson()
            ? response()->json(['title' => $title, 'version' => $version, 'html' => view('legal.content', $data)->render()])->header('Cache-Control', 'no-store')
            : view('legal.show', $data);
    }
}
