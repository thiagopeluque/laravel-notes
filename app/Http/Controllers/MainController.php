<?php

namespace App\Http\Controllers;

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

    public function newNoteSubmit() {
        echo "I'm creating a new note";
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
