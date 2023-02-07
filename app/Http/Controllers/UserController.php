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
    private $helpper;
    private $msgView = null;

    public function __construct()
    {
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
        foreach ($users as $user) {
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
        if ($this->helpper->valida($request->cpf) && $this->verificarUnicidade($request)) {
            $dadosImg = $this->recuperarDadosImagem($request);
            $this->msgView = $this->gerenciarDadosCadastro($request, $dadosImg);
            $this->salvarFotos($request);
        } else {
            echo "Error creating";
            die();
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
     
    public function delete(Request $request)
    {
        $id = $request->id;
        Usuario::find($id)->delete();
        return redirect("/");
    }
    public function recuperarFuncoes()
    {
        return new Helpper();
    }

    private function verificarUnicidade(Request $request)
    {
        if (count(Usuario::where("cpf_cnpj", $request->cpf)->where('id', '!=', $request->id)->get()) > 0) {
            return false;
        }
        return true;
    }
    private function salvarFotos(Request $request)
    {

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
    private function recuperarDadosImagem(Request $request): array
    {
        if ($request->hasFile("selfie")) {
            $file = $request->file("selfie");
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(\public_path("selfie/"), $imageName);
        }
        $dados = array(
            'imagem' => $imageName,
            'arquivo' => $file
        );
        return $dados;
    }
    private function gerenciarDadosCadastro(Request $request, $dados)
    {
        if ($request->id == null) {
            $this->msgView = $this->mensagens['cadastro'];
            $this->createUser($request,$dados);
        } else {
            $this->msgView = $this->mensagens['edicao'];
            $user = Usuario::findOrFail($request->id);
            $selfie = $this->atualizarSelifie($user,$request);
            $this->updateUser($request,$selfie);
        }
        return $this->msgView;
    }
    private function atualizarSelifie($user,Request $request){
            $this->deletarSelfieAnterior($request,$user); 
            $file = $request->file("selfie");
            $user->selfie = time() . "_" . $file->getClientOriginalName();
            $request['selfie'] = $user->selfie;

         return  $user->selfie;
    }

    private function createUser(Request $request, $dados){
        $user = new Usuario([
            "nome" => $request->nome,
            "selfie" => $dados['imagem'],
            "cpf_cnpj" => $request->cpf

        ]);
        $user->save();
    }
    private function updateUser(Request $request,$selfie){
        $user = Usuario::findOrFail($request->id);
        $user->update([
            "nome" => $request->nome,
            "selfie" => $selfie,
            "cpf_cnpj" => $request->cpf

        ]);
    }
    private function deletarSelfieAnterior(Request $request, $user){
        if ($request->hasFile("selfie")) {
            if (File::exists("selfie/" . $user->selfie)) {
                File::delete("selfie/" . $user->selfie);
            }
        }
    }
    

}
