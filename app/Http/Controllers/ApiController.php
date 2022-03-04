<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Todo;

class ApiController extends Controller
{
    public function pong()
    {
        return ["pong" => true];
    }

    public function add(Request $request) {
        $data = $request->all();
        if($data['title'] == '') {
            return ["error" => "Título deve ser preenchido"];
        }
        $id = Todo::insertGetId($data);
        $todo = Todo::find($id);
        if(!$todo) {
            return ["error" => "Cadastro não efetuado!"];
        }
        return [
            "id" => $todo['id'],
            "todo" => $todo['title'],
            "done" => $todo['done']
        ];
    }

    public function getAll() {
        return Todo::orderBy('id')->paginate(2);
    }

    public function getById($id) {
        $todo = Todo::find($id);
        if(!$todo) {
            return ["error" => "Registro Inexistente!"];
        }
        return [
            "id" => $todo['id'],
            "todo" => $todo['title'],
            "done" => $todo['done']
        ];
    }

    public function update($id, Request $request) {
        $todo = Todo::find($id);
        if(!$todo) {
            return ["error" => "Registro Inexistente!"];
        }

        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                "title" => "min:1",
                "done" => "boolean"
            ]
        );

        if($validator->fails()) {
            return ["error" => $validator->messages()];
        }

        Todo::where('id', $id)->update($data);
        $todo = Todo::find($id);

        if($todo) {
            return [
                "id" => $todo['id'],
                "todo" => $todo['title'],
                "done" => $todo['done']
            ];
        }

    }

    public function delete($id) {
        $todo = Todo::find($id);
        if(!$todo) {
            return ["error" => "Registro Inexistente!"];
        }
        Todo::find($id)->delete();
        return [
            "success" => "Registro deletado com sucesso!"
        ];
    }

}
