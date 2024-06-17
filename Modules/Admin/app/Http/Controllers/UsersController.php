<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsersController extends Controller
{

    /**
     * Get view for users admin index
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index()
    {
        return view('admin::users.index');
    }

    /**
     * Get view for editing a user
     *
     * @param int $user_id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function edit(int $user_id)
    {
        // set defaults
        $tmp = [];

        // get selected user
        $user = User::find($user_id);

        // if there is a user
        if ($user) {
            $tmp = $user->toArray();
            $tmp['has_profile'] = !is_null($user->profile);
        }

        // return view
        return view('admin::users.edit', ['user' => $tmp]);
    }

}
