<?php

namespace App\Policies;

use App\Client;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Client $client): Response
    {
        return $user->id === $client->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to view this client.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Client  $client
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Client $client): Response
    {
        return $user->id === $client->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to delete this client.');
    }
}
