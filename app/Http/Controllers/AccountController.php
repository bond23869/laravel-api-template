<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return Account::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $account = Account::create([
            'name' => $request->name,
        ]);

        return response()->json($account, 201);
    }

    public function show($id)
    {
        return Account::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $account->update([
            'name' => $request->name,
        ]);

        return response()->json($account);
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return response()->json(null, 204);
    }
}
