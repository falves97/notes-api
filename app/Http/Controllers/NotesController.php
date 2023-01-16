<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotesRequest;
use App\Http\Requests\UpdateNotesRequest;
use App\Models\Notes;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {

        return response()->json( Notes::whereBelongsTo(Auth::user())->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Notes
     */
    public function create( Request $request ) {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreNotesRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( StoreNotesRequest $request ) {
        $note              = new Notes();
        $note->title       = $request->title;
        $note->description = $request->description;
        $note->user()->associate( Auth::user() );

        $note->save();

        return response()->json( $note )->setStatusCode( 201 );
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Notes $notes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show( int $id ) {
        return response()->json( Notes::findOrFail( $id ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Notes $notes
     *
     * @return \Illuminate\Http\Response
     */
    public function edit( Notes $notes ) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateNotesRequest $request
     * @param \App\Models\Notes $notes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( UpdateNotesRequest $request, int $id ) {
        if(Auth::user()->cannot('update', Notes::find($id))) {
            abort(403);
        }

        $note = Notes::find($id);

        $note->title       = $request->title;
        $note->description = $request->description;

        $note->save();

        return response()->json( $note );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Notes $notes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy( int $id ) {
        if(Auth::user()->cannot('update', Notes::find($id))) {
            abort(403);
        }

        $note = Notes::find( $id );

        if ( $note ) {
            $note->delete();

            return response()->json( [] )->setStatusCode( 200 );
        } else {
            return response()->json( [] )->setStatusCode( 404 );
        }
    }
}
