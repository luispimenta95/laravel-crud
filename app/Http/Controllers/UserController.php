<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Usuario::all();
        return view('index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->id == null){
            if ($request->hasFile("selfie")) {
                $file = $request->file("selfie");
                $imageName = time() . '_' . $file->getClientOriginalName();
                $file->move(\public_path("selfie/"), $imageName);

                $user = new Usuario([
                    "nome" => $request->nome,
                    "selfie" => $imageName,
                ]);
                $user->save();
            }
        }else{
            $user = Usuario::findOrFail($request->id);
            if ($request->hasFile("selfie")) {
                if (File::exists("selfie/" . $user->selfie)) {
                    File::delete("selfie/" . $user->selfie);
                }
                $file = $request->file("selfie");
                $user->selfie = time() . "_" . $file->getClientOriginalName();
                $file->move(\public_path("/selfie"), $user->selfie);
                $request['selfie'] = $user->selfie;
            }
    
            $user->update([
                "nome" => $request->nome,
                "selfie" => $user->selfie,
            ]);
    
            if ($request->hasFile("images")) {
                $files = $request->file("images");
                foreach ($files as $file) {
                    $imageName = time() . '_' . $file->getClientOriginalName();
                    $request["user_id"] = $request->id;
                    $request["image"] = $imageName;
                    $file->move(\public_path("images"), $imageName);
                    Image::create($request->all());
                }
            }
    
        }

        return redirect("/");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuario  $user
     * @return \Illuminate\Http\Response

     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Usuario  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->id;
        $users = Usuario::findOrFail($id);
        return view('propietarios.form')->with('users', $users);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $users = Usuario::findOrFail($id);

        if (File::exists("selfie/" . $users->selfie)) {
            File::delete("selfie/" . $users->selfie);
        }
        $images = Image::where("user_id", $users->id)->get();
        foreach ($images as $image) {
            if (File::exists("images/" . $image->image)) {
                File::delete("images/" . $image->image);
            }
        }
        $users->delete();
        return back();
    }

    public function deleteimage($id)
    {
        $images = Image::findOrFail($id);
        if (File::exists("images/" . $images->image)) {
            File::delete("images/" . $images->image);
        }

        Image::find($id)->delete();
        return back();
    }

    public function deleteselfie($id)
    {
        $selfie = Usuario::findOrFail($id)->selfie;
        if (File::exists("selfie/" . $selfie)) {
            File::delete("selfie/" . $selfie);
        }
        return back();
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        Usuario::find($id)->delete();
        return redirect("/");
    }
}
