<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Http\Request;


class MainController extends Controller
{
    // Função usada somente nesse Controller por ser PRIVATE
    // Foi criado um SERVICE para atender aos demais Controllers criados
    
    // private function decryptId($id) {
    //     try {
    //         $id = Crypt::decrypt($id);
    //     } catch (DecryptException $error) {
    //         return redirect()->route('home');
    //     }
    //     return $id;
    // }

    public function index() {
        // Load Users Notes
        $id = session('user.id');
        $notes = User::find($id)->notes()->get()->toArray();

        // Show Home View
        return view('home', ['notes' => $notes]);
    }

    public function newNote() {
        return view('new_note');
    }

    public function newNoteSubmit(Request $request) {
        // Form Validation
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000'
            ],
            [
                'text_title.required' => 'O Título é obrigatório.',
                'text_note.required' => 'A Nota é obrigatória.',
                'text_title.min' => 'O título deve ter no mínimo :min caracteres.',
                'text_title.max' => 'O título deve ter no mínimo :max caracteres.',
                'text_note.min' => 'A nota deve ter no máximo :min caracteres.',
                'text_note.max' => 'A nota deve ter no máximo :max caracteres.'
            ]
        );

        // Get User ID from Session
        $id = session('user.id');

        // Save the Note
        $note = new Note();
        $note->user_id = $id;
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        // Redirect
        return redirect()->route('home');
    }

    public function editNote($id) {
        $id = Operations::decryptId($id);
        echo "I'm editing this note with ID = $id";
    }

    public function deleteNote($id) {
        $id = Operations::decryptId($id);
        echo "I'm deleting this note with ID = $id";
    }
}
