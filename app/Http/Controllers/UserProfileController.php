<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class UserProfileController extends Controller
{
    public function show()
    {
        return view('pages.user-profile', [
            'bankAccount' => auth()->user()->bankAccount,
        ]);
    }

    public function update(Request $request)
    {
        // dd($request);
        $attributes = $request->validate([
            'username' => ['required','max:255', 'min:2'],
            'firstname' => ['max:100'],
            'lastname' => ['max:100'],
            'email' => ['required', 'email', 'max:255',  Rule::unique('users')->ignore(auth()->user()->id),],
            'address' => ['max:100'],
            'city' => ['max:100'],
            'country' => ['max:100'],
            'postal' => ['max:100'],
            'about' => ['max:255']
        ]);
     
        auth()->user()->update([
            'username' => $request->get('username'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'email' => $request->get('email') ,
            'phone' => $request->get('phone') ,
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'country' => $request->get('country'),
            'postal' => $request->get('postal'),
            'about' => $request->get('about')
        ]);

        if ($request->hasFile('image')) {
            Storage::delete('public/'.auth()->user()['image']);
            $image = $request->file('image')->store('uploads','public');
            auth()->user()->update([
                'image' => $image         
            ]);
        }

        $all = [];

        if ($request->facebook_url) {
            $all['facebook_url'] = $request->facebook_url;
        }
        if ($request->instagram_url) {
            $all['instagram_url'] = $request->instagram_url;
        }
        if ($request->web_url) {
            $all['web_url'] = $request->web_url;
        }
        auth()->user()->update($all);

        return back()->with('succes', 'Profile succesfully updated');
    }
}
