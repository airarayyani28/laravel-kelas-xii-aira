<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kritik;
use App\Models\Film;

class KritikController extends Controller
{
    public function store(Request $request, Film $film)
    {
        if (!Auth::check() || Auth::user()->role_id == 2) {
            return back()->withErrors(['comment' => 'Administrator tidak diperbolehkan memberikan kritik pada film.']);
        }

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string'],
        ]);

        Kritik::create([
            'user_id' => Auth::id(),
            'film_id' => $film->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('movies.show', $film->id)->with('success', 'Kritik dan rating berhasil dikirim!');
    }
}