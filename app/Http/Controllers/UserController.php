<?php

namespace App\Http\Controllers;

use App\Http\Util\Helpper;
use App\Models\Image;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
class UserController extends Controller
{
    private $mensagens;
    private $helpper ;
    private $msgView = null;

    public function __construct(){
        $this->helpper = $this->recuperarFuncoes();
        $this->mensagens = $this->helpper->recuperarMensagensPadrao();
    }


     

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Usuario::all();
        foreach ($users as $user){
            $user->cpf_cnpj = $this->helpper->formata($user->cpf_cnpj);
        }
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
        if($this->helpper->valida($request->cpf) && $this->verificarUnicidade($request)){
            if($request->id == null){
                $this->msgView = $this->mensagens['cadastro'];
                if ($request->hasFile("selfie")) {
                    $file = $request->file("selfie");
                    $imageName = time() . '_' . $file->getClientOriginalName();
                    $file->move(\public_path("selfie/"), $imageName);

                    $user = new Usuario([
                        "nome" => $request->nome,
                        "selfie" => $imageName,
                        "cpf_cnpj" => $request->cpf

                    ]);
                    $user->save();
                }
            }else{
                $this->msgView = $this->mensagens['edicao'];
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
                    "cpf_cnpj" => $request->cpf

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
     }else{
        $this->msgView = $this->mensagens['cpfInvalido'];
    
    }
        

        return redirect("/")->with('msg', $this->msgView);
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
    public function recuperarFuncoes(){
        return new Helpper();
    }

    private function verificarUnicidade(Request $request){
        if( count(Usuario::where("cpf_cnpj", $request->cpf)->get()) > 0){
            return false;
        }
        return true;
    }
}
