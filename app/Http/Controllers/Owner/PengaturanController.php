<?php
namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PengaturanController extends Controller {
    public function index() {
        $totalDriver = User::where('role','driver')->where('is_active',true)->count();
        $totalLokasi = \App\Models\LokasiMangkal::where('is_active',true)->count();
        return view('owner.pengaturan', compact('totalDriver','totalLokasi'));
    }

    public function updateProfile(Request $r) {
        $r->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.auth()->id(),
            'phone' => 'nullable|string|max:20',
        ]);
        auth()->user()->update($r->only('name','email','phone'));
        return back()->with('success','Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $r) {
        $r->validate([
            'current_password' => 'required',
            'password'         => ['required','confirmed', Password::min(8)],
        ]);
        if (!Hash::check($r->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }
        auth()->user()->update(['password' => Hash::make($r->password)]);
        return back()->with('success','Password berhasil diubah.');
    }
}
