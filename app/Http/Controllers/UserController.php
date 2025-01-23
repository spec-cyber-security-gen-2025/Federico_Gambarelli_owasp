<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Services\HttpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    // UNSECURE
    public function show()
	{
        if(!$user = Auth::user()){
            return redirect()->back()->withErrors("Not authorized");
        }
        return view('auth.profile',compact('user'));
	}

    public function update(Request $request, $id){
        $user = User::find($id);
        // validation
        $validatedData = $request->validate([
                                            'name'=>'required|min:3',
                                            'email'=>'required|email:rfc,dns'
                                            ]);

        $user->update($validatedData);

        return back()->with('message','User updated');
    }

    public function changeEmail(Request $request){

        if(!$user = Auth::user())
        return response()->json(['message' => 'Forbidden Operation'], 403);

        $user->email = $request->email;
        $user->save();

        return back()->with('message','Changed successfully');
    }

    public function changeName(Request $request)
    {
        if(!$user = Auth::user())
        return response()->json(['message' => 'Forbidden Operation'], 403);

        $user->name = $request->name;
        $user->save();

        return back()->with('message','Changed successfully');
    }

    public function changeImg(Request $request)
    {
        if(!$user = Auth::user()){
            return back()->with('message','Please Log In');
        }

        if(!$request->hasFile('avatar')) {
            return back()->with('message','Forbidden Operation');
        }

        if (!file_exists(storage_path("app/public/images/users/".$user->id))) {
            mkdir(storage_path("app/public/images/users/".$user->id), 0777, true);
        }

        // retrieve uploaded image
        $newImage = $request->file('avatar');
        // calculate hash

        // UNSECURE with md5
        $newImageHash = hash('sha256', $newImage);;

        // compare hash
        if($newImageHash == $user->avatar){
            return redirect()->back()->with('message','Image not updated, same');
        }
        // Define the path to store the image
        $path = "/public/images/users/".$user->id;

        // delete previous files
        Storage::deleteDirectory($path);

        // Store the image in the defined path
        $filePath = $newImage->storeAs($path, $newImageHash);

        // save new user avatar name
        $user->avatar = $newImageHash;
        $user->save();

        return redirect()->back()->with('message','Image updated');
    }

    public function privacyPolicy(){
        return response()->download('privacy.pdf');
    }

    public function cookiePolicy(){
        return response()->download('cookie-policy.pdf');
    }

    public function serviceAgreements(){
        return response()->download('service-agreements.pdf');
    }

    public function upload(Request $request)
    {
        if(!$user = Auth::user()){
            return back()->with('message','Please Log In');
        }

        if (!$request->hasFile('file')) {
            return back()->withErrors("Forbidden Operation");
        }
        $path = storage_path("app/public/docs/users/".$user->id);

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $extension = $file->getClientOriginalExtension();

        if (!in_array($extension, $allowedExtensions)) {
            return back()->withErrors('File not valid');
        }
        $filename = $file->getClientOriginalName();

        chmod($path, 0777);
        $file->move($path, $filename);
        chmod($path, 0644);

        File::create([
            'name'=> $filename,
            'user_id'=>$user->id
        ]);

        return back()->withMessage("Upload successful");
    }
}
