<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function get(Request $request)
    {
        $users = $this->getUsers();

        return response()->json([
            "status" => "success",
            "users" => $users
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = User::find($id);

        return response()->json([
            "status" => "success",
            "user" => $user
        ]);
    }

    public function getLeaders()
    {
        $leaders = User::where([
            "role" => UserRole::TEAM_LEADER->value
        ])->get();

        return response()->json([
            "status" => "success",
            "leaders" => $leaders
        ]);
    }

    public function getAgents(Request $request)
    {
        $user = $request->user();

        $leaders = User::where([
            "role" => 'Agent',
            "team_leader" => $user->id
        ])->get();

        return response()->json([
            "status" => "success",
            "leaders" => $leaders
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' =>  ['required', new Enum(UserRole::class)],
            'status' => ['required', new Enum(UserStatus::class)],
        ]);

        if ($request->role == UserRole::AGENT->value) {
            $request->validate([
                'team_leader' => 'required|integer',
            ]);
        }

        $user = new User([
            'username'  => $request->username,
            'name'  => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'team_leader' => $request->role == UserRole::AGENT->value ? $request->team_leader : null,
            'caller_id' => $request->caller_id,
            'contact_number' => $request->contact_number,
            'password' => bcrypt($request->password),
        ]);

        if ($user->save()) {
            return response()->json([
                'message' => 'success'
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'username' => 'required|string',
            'role' =>  ['required', new Enum(UserRole::class)],
            'status' => ['required', new Enum(UserStatus::class)],
        ]);

        if ($request->role == UserRole::AGENT->value) {
            $request->validate([
                'team_leader' => 'required|integer',
            ]);
        }

        $user = User::find($request->id);

        $user->username  = $request->username;
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->team_leader = $request->role == UserRole::AGENT->value ? $request->team_leader : null;
        $user->caller_id = $request->caller_id;
        $user->contact_number = $request->contact_number;
        $user->password = bcrypt($request->password);

        if ($user->save()) {
            return response()->json([
                'message' => 'success'
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'password' => 'required|string',
            'new_password' => 'required|string',
        ]);

        if (Hash::check($request->password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'message' => 'success'
            ]);
        } else {
            return response()->json(['error' => 'Current Password doesn\'t match']);
        }
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::find($id);
        $user->password = Hash::make("123456");
        $user->save();

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function activate(Request $request, $id)
    {
        $user = User::find($id);
        $user->status = $user->status == UserStatus::ACTIVE ?
            UserStatus::INACTIVE->value :
            UserStatus::ACTIVE->value;

        $user->save();

        $users = $this->getUsers();

        return response()->json([
            "status" => "success",
            "users" => $users
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        $user->delete();

        $users = $this->getUsers();

        return response()->json([
            "status" => "success",
            "users" => $users
        ]);
    }


    private function getUsers()
    {
        $me = Auth::user();
        $filter = [];
        if ($me->role != UserRole::ADMIN)
            $filter = [
                'role' => UserRole::AGENT->value,
                'team_leader' => $me->id,
            ];

        $users = User::with('leader')->where($filter)->get();

        return $users;
    }
}
