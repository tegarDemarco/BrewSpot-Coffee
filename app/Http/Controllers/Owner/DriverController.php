<?php
namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller {
    public function index() {
        $drivers = User::where('role','driver')->orderByDesc('created_at')->paginate(10);
        return view('owner.driver.index', compact('drivers'));
    }
    public function create() { return view('owner.driver.create'); }
    public function store(Request $r) {
        $r->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);
        User::create(['name'=>$r->name,'email'=>$r->email,'phone'=>$r->phone,'password'=>Hash::make($r->password),'role'=>'driver','is_active'=>true]);
        return redirect()->route('owner.driver.index')->with('success','Driver berhasil ditambahkan.');
    }
    public function edit(User $driver) {
        abort_unless($driver->role==='driver',404);
        return view('owner.driver.edit', compact('driver'));
    }
    public function update(Request $r, User $driver) {
        abort_unless($driver->role==='driver',404);
        $r->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,'.$driver->id,
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ]);
        $driver->update(['name'=>$r->name,'email'=>$r->email,'phone'=>$r->phone,'is_active'=>$r->boolean('is_active')]);
        if ($r->filled('password')) $driver->update(['password'=>Hash::make($r->password)]);
        return redirect()->route('owner.driver.index')->with('success','Data driver diperbarui.');
    }
    public function toggleStatus(User $driver) {
        abort_unless($driver->role==='driver',404);
        $driver->update(['is_active' => !$driver->is_active]);
        return back()->with('success','Status driver diperbarui.');
    }
}
