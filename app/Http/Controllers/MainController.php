<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Http\Request;


class MainController extends Controller
{
    public function index()
    {
        // Load Users Notes
        $id = session('user.id');
        $notes = User::find($id)->notes()
                                ->whereNull('deleted_at')
                                ->get()
                                ->toArray();

        // Show Home View
        return view('home', ['notes' => $notes]);
    }

    public function newNote()
    {
        return view('new_note');
    }

    public function newNoteSubmit(Request $request)
    {
        // Form Validation
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000'
            ],
            [
                'text_title.required' => 'O Título é obrigatório.',
                'text_title.min' => 'O título deve ter no mínimo :min caracteres.',
                'text_title.max' => 'O título deve ter no mínimo :max caracteres.',
                'text_note.required' => 'A Nota é obrigatória.',
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

    public function editNote($id)
    {
        $id = Operations::decryptId($id);

        if ($id === null){
            return redirect()->route('home');
        }

        $note = Note::find($id);
        return view ('edit_note', ['note' => $note]);
    }

    public function editNoteSubmit(Request $request)
    {
        // Form Validation
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000'
            ],
            [
                'text_title.required' => 'O Título é obrigatório.',
                'text_title.min' => 'O título deve ter no mínimo :min caracteres.',
                'text_title.max' => 'O título deve ter no mínimo :max caracteres.',
                'text_note.required' => 'A Nota é obrigatória.',
                'text_note.min' => 'A nota deve ter no máximo :min caracteres.',
                'text_note.max' => 'A nota deve ter no máximo :max caracteres.'
            ]
        );
        
        $id = Operations::decryptId($request->note_id);

        if ($id === null){
            return redirect()->route('home');
        }

        $note = Note::find($id);
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        return redirect()->route('home');
    }

    public function deleteNote($id)
    {
        $id = Operations::decryptId($id);

        if ($id === null){
            return redirect()->route('home');
        }

        $note = Note::find($id);

        return view('delete_note', ['note' => $note]);
    }

    public function deleteNoteConfirm($id)
    {
        $id = Operations::decryptId($id);

        if ($id === null){
            return redirect()->route('home');
        }

        $note = Note::find($id);
        
        // Hard Delete (com o model alterado para usar SoftDelete) ele mantém o soft delete
        $note->delete();

        // Soft Delete Manual (sem alterar o Model)
        // $note->deleted_at = date('Y-m-d H:i:s');
        // $note->save();

        return redirect()->route('home');
    }
}
