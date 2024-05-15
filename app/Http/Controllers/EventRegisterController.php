<?php

namespace App\Http\Controllers;

use App\Models\eventRegister;
use App\Http\Requests\StoreeventRegisterRequest;
use App\Http\Requests\UpdateeventRegisterRequest;
use App\Models\User;

class EventRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::count();

        $widget = [
            'users' => $users,
        ];

        return view('event', compact('widget'));
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
     * @param  \App\Http\Requests\StoreeventRegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreeventRegisterRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\eventRegister  $eventRegister
     * @return \Illuminate\Http\Response
     */
    public function show(eventRegister $eventRegister)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\eventRegister  $eventRegister
     * @return \Illuminate\Http\Response
     */
    public function edit(eventRegister $eventRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateeventRegisterRequest  $request
     * @param  \App\Models\eventRegister  $eventRegister
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateeventRegisterRequest $request, eventRegister $eventRegister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\eventRegister  $eventRegister
     * @return \Illuminate\Http\Response
     */
    public function destroy(eventRegister $eventRegister)
    {
        //
    }
}
