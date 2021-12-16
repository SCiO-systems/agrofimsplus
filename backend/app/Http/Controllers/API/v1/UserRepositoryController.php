<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRepositories\CreateUserRepositoryRequest;
use App\Http\Requests\UserRepositories\DeleteUserRepositoryRequest;
use App\Http\Requests\UserRepositories\ListAllUserRepositoriesRequest;
use App\Http\Requests\UserRepositories\ListUserRepositoryRequest;
use App\Http\Requests\UserRepositories\ShowUserRepositoryRequest;
use App\Http\Requests\UserRepositories\UpdateUserRepositoryRequest;
use App\Http\Resources\v1\UserRepositoryResource;
use App\Models\User;
use App\Models\UserRepository;

class UserRepositoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListUserRepositoryRequest $request, User $user)
    {
        $repositories = UserRepository::where('user_id', $user->id)->paginate();

        return UserRepositoryResource::collection($repositories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRepositoryRequest $request, User $user)
    {
        $repository = UserRepository::create([
            'name' => $request->name,
            'type' => $request->type,
            'api_endpoint' => $request->api_endpoint,
            'client_secret' => $request->client_secret,
            'user_id' => $user->id,
        ]);

        return new UserRepositoryResource($repository);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowUserRepositoryRequest $request, User $user, UserRepository $repository)
    {
        return new UserRepositoryResource($repository);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateUserRepositoryRequest $request,
        User $user,
        UserRepository $repository
    ) {
        // Filter null and falsy values.
        // TODO: Check for SQLi.
        $data = collect($request->all())->filter()->all();

        // Update the user details with the new ones.
        $repository->update($data);

        return new UserRepositoryResource($repository);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        DeleteUserRepositoryRequest $request,
        User $user,
        UserRepository $repository
    ) {
        $repository->delete();

        return response()->json([], 204);
    }

    /**
     * Return all the user repositories.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(ListAllUserRepositoriesRequest $request, User $user)
    {
        $all = UserRepository::where('user_id', $user->id)->get();

        return UserRepositoryResource::collection($all);
    }
}
