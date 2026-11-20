<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserBankAccountController extends Controller
{
    public function update(Request $request)
    {
        $request->merge([
            'account_number' => preg_replace('/\s+/', '', (string) $request->input('account_number')),
        ]);

        $attributes = $request->validate([
            'bank_name' => ['required', 'string', 'min:2', 'max:100'],
            'account_holder' => ['required', 'string', 'min:2', 'max:150'],
            // 10-11 digit account number or 18 digit CLABE.
            'account_number' => ['required', 'regex:/^(\d{10,11}|\d{18})$/'],
        ], [
            'account_number.regex' => 'The account number must be 10 or 11 digits, or an 18 digit CLABE.',
        ], [
            'bank_name' => 'bank',
            'account_holder' => 'account holder',
            'account_number' => 'account number / CLABE',
        ]);

        auth()->user()->bankAccount()->updateOrCreate([], $attributes);

        return redirect()->route('profile')->with('succes', 'Bank account succesfully updated');
    }
}
