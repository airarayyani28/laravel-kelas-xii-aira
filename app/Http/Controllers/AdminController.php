<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{User, Genre, Cast, Film, Peran};

class AdminController extends Controller
{
    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->role_id != 2) {
            abort(403, 'Akses ditolak. Halaman ini khusus Administrator.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdmin();

        $tab = $request->get('tab', 'films');
        $users = User::with(['role', 'profile'])->orderBy('id', 'asc')->get();
        $genres = Genre::orderBy('id', 'asc')->get();
        $casts = Cast::orderBy('id', 'asc')->get();
        $films = Film::with('genre')->orderByDesc('id')->get();
        $perans = Peran::with(['film', 'cast'])->orderByDesc('id')->get();

        return view('components.admin-dashboard', compact('tab', 'users', 'genres', 'casts', 'films', 'perans'));
    }

    // ==================== CRUD GENRE ====================
    public function storeGenre(Request $request)
    {
        $this->checkAdmin();
        $request->validate(['name' => 'required|string|max:45']);
        Genre::create(['name' => $request->name]);
        return redirect()->route('admin.dashboard', ['tab' => 'genres'])->with('success', 'Genre berhasil ditambahkan!');
    }

    public function updateGenre(Request $request, Genre $genre)
    {
        $this->checkAdmin();
        $request->validate(['name' => 'required|string|max:45']);
        $genre->update(['name' => $request->name]);
        return redirect()->route('admin.dashboard', ['tab' => 'genres'])->with('success', 'Genre berhasil diperbarui!');
    }

    public function destroyGenre(Genre $genre)
    {
        $this->checkAdmin();
        if ($genre->films()->count() > 0) {
            return redirect()->route('admin.dashboard', ['tab' => 'genres'])->withErrors(['error' => 'Genre tidak bisa dihapus karena masih digunakan oleh film!']);
        }
        $genre->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'genres'])->with('success', 'Genre berhasil dihapus!');
    }

    // ==================== CRUD CAST ====================
    public function storeCast(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'name' => 'required|string|max:45',
            'age' => 'required|integer|min:1',
            'bio' => 'required|string',
        ]);
        Cast::create($request->only('name', 'age', 'bio'));
        return redirect()->route('admin.dashboard', ['tab' => 'casts'])->with('success', 'Cast berhasil ditambahkan!');
    }

    public function updateCast(Request $request, Cast $cast)
    {
        $this->checkAdmin();
        $request->validate([
            'name' => 'required|string|max:45',
            'age' => 'required|integer|min:1',
            'bio' => 'required|string',
        ]);
        $cast->update($request->only('name', 'age', 'bio'));
        return redirect()->route('admin.dashboard', ['tab' => 'casts'])->with('success', 'Cast berhasil diperbarui!');
    }

    public function destroyCast(Cast $cast)
    {
        $this->checkAdmin();
        if ($cast->perans()->count() > 0) {
            return redirect()->route('admin.dashboard', ['tab' => 'casts'])->withErrors(['error' => 'Cast tidak bisa dihapus karena masih terdaftar di data Peran!']);
        }
        $cast->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'casts'])->with('success', 'Cast berhasil dihapus!');
    }

    // ==================== CRUD FILM ====================
    public function storeFilm(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'title' => 'required|string',
            'sinopsis' => 'required|string',
            'year' => 'required|integer|min:1900|max:2100',
            'genre_id' => 'required|exists:genres,id',
            'poster' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $path = $request->file('poster')->store('images', 'public');

        Film::create([
            'title' => $request->title,
            'sinopsis' => $request->sinopsis,
            'year' => $request->year,
            'genre_id' => $request->genre_id,
            'poster' => 'storage/' . $path,
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'films'])->with('success', 'Film berhasil ditambahkan!');
    }

    public function updateFilm(Request $request, Film $film)
    {
        $this->checkAdmin();
        $request->validate([
            'title' => 'required|string',
            'sinopsis' => 'required|string',
            'year' => 'required|integer|min:1900|max:2100',
            'genre_id' => 'required|exists:genres,id',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $posterPath = $film->poster;
        if ($request->hasFile('poster')) {
            $path = $request->file('poster')->store('images', 'public');
            $posterPath = 'storage/' . $path;
        }

        $film->update([
            'title' => $request->title,
            'sinopsis' => $request->sinopsis,
            'year' => $request->year,
            'genre_id' => $request->genre_id,
            'poster' => $posterPath,
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'films'])->with('success', 'Film berhasil diperbarui!');
    }

    public function destroyFilm(Film $film)
    {
        $this->checkAdmin();
        $film->perans()->delete();
        $film->kritiks()->delete();
        $film->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'films'])->with('success', 'Film berhasil dihapus!');
    }

    // ==================== CRUD PERAN ====================
    public function storePeran(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'film_id' => 'required|exists:films,id',
            'cast_id' => 'required|exists:casts,id',
            'actor' => 'required|string|max:255',
        ]);
        Peran::create($request->only('film_id', 'cast_id', 'actor'));
        return redirect()->route('admin.dashboard', ['tab' => 'perans'])->with('success', 'Peran berhasil ditambahkan!');
    }

    public function updatePeran(Request $request, Peran $peran)
    {
        $this->checkAdmin();
        $request->validate([
            'film_id' => 'required|exists:films,id',
            'cast_id' => 'required|exists:casts,id',
            'actor' => 'required|string|max:255',
        ]);
        $peran->update($request->only('film_id', 'cast_id', 'actor'));
        return redirect()->route('admin.dashboard', ['tab' => 'perans'])->with('success', 'Peran berhasil diperbarui!');
    }

    public function destroyPeran(Peran $peran)
    {
        $this->checkAdmin();
        $peran->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'perans'])->with('success', 'Peran berhasil dihapus!');
    }
}