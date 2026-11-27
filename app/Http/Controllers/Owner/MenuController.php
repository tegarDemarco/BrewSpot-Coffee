<?php
namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller {
    public function index() {
        $menus = Menu::orderByDesc('created_at')->paginate(12);
        return view('owner.menu.index', compact('menus'));
    }
    public function create() { return view('owner.menu.create'); }
    public function store(Request $r) {
        $r->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|numeric|min:0',
            'kategori'  => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);
        Menu::create($r->only('nama_menu','harga','kategori','deskripsi') + ['is_active'=>true]);
        return redirect()->route('owner.menu.index')->with('success','Menu berhasil ditambahkan.');
    }
    public function edit(Menu $menu) { return view('owner.menu.edit', compact('menu')); }
    public function update(Request $r, Menu $menu) {
        $r->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|numeric|min:0',
            'kategori'  => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);
        $menu->update($r->only('nama_menu','harga','kategori','deskripsi','is_active'));
        return redirect()->route('owner.menu.index')->with('success','Menu diperbarui.');
    }
    public function toggleStatus(Menu $menu) {
        $menu->update(['is_active' => !$menu->is_active]);
        return back()->with('success','Status menu diperbarui.');
    }
}
