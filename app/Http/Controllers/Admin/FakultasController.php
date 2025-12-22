<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function index()
    {
        // TODO: Implement index method
        return view('admin.fakultas.index');
    }

    public function create()
    {
        // TODO: Implement create method
        return view('admin.fakultas.create');
    }

    public function store(Request $request)
    {
        // TODO: Implement store method
        return redirect()->route('admin.fakultas.index');
    }

    public function show($id)
    {
        // TODO: Implement show method
        return view('admin.fakultas.show', compact('id'));
    }

    public function edit($id)
    {
        // TODO: Implement edit method
        return view('admin.fakultas.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update method
        return redirect()->route('admin.fakultas.index');
    }

    public function destroy($id)
    {
        // TODO: Implement destroy method
        return redirect()->route('admin.fakultas.index');
    }
}
