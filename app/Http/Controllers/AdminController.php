<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Services\HttpService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
	{
		return view("admin.dashboard");
	}

    public function articles()
    {
        $users = User::latest()->get();
        $articles = Article::latest()->get();
        return view('admin.articles', compact('articles'));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function getFinancialData(HttpService $httpService) {

        $financialData = json_decode($httpService->getRequest('http://localhost:8001/user-data.php'));
        return view('admin.financial', compact('financialData'));
    
    }
    
    public function toggleArticleStatus($id) {
        if(!Auth::user()->isAdmin()){
            return back()->withMessage("Operation not permitted");
        }
        $article = Article::find($id);
        $article->published = !$article->published;
        $article->save();
        return back();
    }

	public function toggleUsersAdmin($id)
	{
        $user = User::findOrFail($id);

        if(!$currentUser = Auth::user()){
            return back()->withMessage("Operation not permitted");
        }

        if(!$currentUser->isAdmin()){
            return back()->withMessage("Operation not permitted");
        }
		
        $user->is_admin = !$user->is_admin;
        $user->save();
        return back();
	}
}