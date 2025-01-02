<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $users = DB::select("SELECT * FROM users WHERE usertype = 'user' AND (name LIKE ? OR email LIKE ?)", 
                ["%{$searchTerm}%", "%{$searchTerm}%"]);
        } else {
            $users = DB::select("SELECT * FROM users WHERE usertype = 'user'");
        }
        
        return view('admin.user', compact('users'));
    }

    public function edit($id)
    {
        $user = DB::select("SELECT * FROM users WHERE id = ? LIMIT 1", [$id]);
        if (!empty($user)) {
            return view('admin.userEdit', ['user' => $user[0]]);
        }
        return redirect()->route('admin.user')->with('error', 'User not found');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
        ]);

        $affected = DB::update("UPDATE users SET name = ?, email = ? WHERE id = ?", 
            [$request->name, $request->email, $id]);
            
        if ($affected) {
            return redirect()->route('admin.user')->with('success', 'User updated successfully');
        }
        return redirect()->route('admin.user')->with('error', 'Failed to update user');
    }

    public function destroy($id)
    {
        $affected = DB::delete("DELETE FROM users WHERE id = ?", [$id]);
        
        if ($affected) {
            return redirect()->route('admin.user')->with('success', 'User deleted successfully');
        }
        return redirect()->route('admin.user')->with('error', 'Failed to delete user');
    }
}
?>