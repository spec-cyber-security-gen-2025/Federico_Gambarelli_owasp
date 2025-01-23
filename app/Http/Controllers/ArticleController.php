<?php

namespace App\Http\Controllers;

use DOMDocument;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\HtmlFilterService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        // UNSECURE (XSS)
        $articles = Article::latest()->where('published',true)->get();

        // SECURE
        // $articles = $htmlFilterService->filterHtmlCollectionByField($articles,'content');

        return view('articles.index', compact('articles'));
    }

    public function search(Request $request){
        // UNSECURE (SQLi)
        // $articles = Article::whereRaw("title like '%{$request->search}%'")->get();

        $articles = Article::where('title', 'LIKE', "%{$request->search}%")
                            ->orWhere('content', 'LIKE', "%{$request->search}%")
        ->get();

        $searchTerm = $request->search;

        return view('articles.index',compact('articles','searchTerm'));
    }

    // UNSECURE (XSS)
    public function show(Article $article, Request $request, HtmlFilterService $htmlFilterService)
    {

        $filteredContent = $htmlFilterService->filterHtml($article->content);

        $article->content = $filteredContent;

        return view('articles.show', compact('article'));
    }


    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request,HtmlFilterService $htmlFilterService)
    {

        $articleData = $request->validate([
            'title'=>"required|min:3|max:50",
            'content'=>"required|min:20|max:1000"
        ]);

        $articleData['content'] = $htmlFilterService->filterHtml($articleData['content']);

        if(!key_exists('user_id',$articleData)){
            $articleData['user_id']= Auth::id();
        }

        Article::create($articleData);

        return redirect()->route('articles.index');
    }

    public function edit(Article $article)
    {

        Gate::authorize('edit',$article);

        return view('articles.edit',compact('article'));
    }

    public function update(Article $article,Request $request,HtmlFilterService $htmlFilterService)
    {

        $articleData = $request->all();

        Gate::authorize('update',$article);

        $articleData['content'] = $htmlFilterService->filterHtml($articleData['content']);


        $article->update($articleData);

        return redirect()->route('articles.index');
    }

    public function destroy(Article $article, Request $request)
    {

        Gate::authorize('delete',$article);

        $article->delete();

        return redirect()->route('home')->with('message','Article deleted successfully');
    }

    public function importXml(Request $request)
    {
        $request->validate([
            'xml_file' => 'required|file|mimes:xml',
        ]);

        $xmlFile = $request->file('xml_file')->getRealPath();

        // try {
            // Caricamento e parsing del file XML con le entità esterne disabilitate
            $xmlContent = file_get_contents($xmlFile);
            $dom = new \DOMDocument();
            $dom->resolveExternals = true;
            $dom->substituteEntities = true;
            // UNSECURE
                //Disabilitare entità esterne e DTD

            //$dom->loadXML($xmlContent, LIBXML_NONET | LIBXML_NOENT | LIBXML_DTDLOAD);

            // SECURE

            $dom->loadXML($xmlContent);

            // Estrarre titolo e contenuto dall'XML
            $title = $dom->getElementsByTagName('title')->item(0)->textContent ?? 'No Title';
            $content = $dom->getElementsByTagName('content')->item(0)->textContent ?? 'No Content';

            // Salva i dati nella sessione per la visualizzazione
        //     session(['article' => ['title' => $title, 'content' => $content]]);
        // } catch (\Exception $e) {
        //     return redirect()->route('articles.create')->withErrors(['xml_file' => 'Invalid XML file.']);
        // }
        $user_id = Auth::id();

        $article = Article::create(compact('title','content','user_id'));

        if ($request->wantsJson()) {
            return response()->json($article, 201);
        }

        return redirect()->route('articles.index');
    }

    public function exportXml($id)
    {
        // Trova l'articolo per ID
        $article = Article::findOrFail($id);

        // Crea un nuovo documento XML
        $dom = new \DOMDocument('1.0', 'UTF-8');

        // Aggiungi elementi XML
        $root = $dom->createElement('article');
        $dom->appendChild($root);

        $title = $dom->createElement('title', htmlspecialchars($article->title));
        $root->appendChild($title);

        $content = $dom->createElement('content', htmlspecialchars($article->content));
        $root->appendChild($content);

        // Imposta l'header di risposta
        $response = Response::make($dom->saveXML(), 200);
        $response->header('Content-Type', 'application/xml');
        $response->header('Content-Disposition', 'attachment; filename="article_' . $article->id . '.xml"');

        return $response;
    }
}
