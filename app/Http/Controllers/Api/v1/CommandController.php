<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Command\{ IndexCommand, ShowCommand, StoreCommand, UpdateCommand, DestroyCommand };
use App\Http\Resources\Api\v1\Command\{ CommandIndexResource, CommandResource };
use App\Models\Command;

class CommandController extends Controller
{
    /**
     * Display a listing of the command.
     *
     * @param  \App\Http\Requests\Api\v1\Command\IndexCommand $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexCommand $request)
    {
        $fields = $request->validated();
        $commands = Command::constrained($fields)->get();

        return response(new CommandIndexResource($commands));
    }

    /**
     * Display the specified command.
     *
     * @param  \App\Models\Command  $command
     * @param  \App\Http\Requests\Api\v1\Command\ShowCommand  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Command $command, ShowCommand $request)
    {
        return response(new CommandResource($command));
    }

    /**
     * Store a newly created command in storage.
     *
     * @param  \App\Http\Requests\Api\v1\Command\StoreCommand  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommand $request)
    {
        $fields = $request->validated();
        $command = Command::create($fields)->fresh();

        return response(new CommandResource($command), 201);
    }

    /**
     * Update the specified command in storage.
     *
     * @param  \App\Models\Command  $command
     * @param  \App\Http\Requests\Api\v1\Command\UpdateCommand  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Command $command, UpdateCommand $request)
    {
        $fields = $request->validated();

        $command->fill($fields);
        $command->save();

        return response(new CommandResource($command));
    }

    /**
     * Remove the specified command from storage.
     *
     * @param  \App\Models\Command  $command
     * @param  \App\Http\Requests\Api\v1\Command\DestroyCommand  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Command $command, DestroyCommand $request)
    {
        $command->delete();
        return response(null, 204);
    }
}
