<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Iklan as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IklanController extends Controller
{
    public function index()
    {
        $builder = new Model;

        $data = $builder->orderBy('created_at', 'desc')->get();

        return [
            'error' => false,
            'data' => $data,
        ];
    }

    public function show($id)
    {
        $item = Model::find($id);

        return [
            'error' => false,
            'data' => $item,
        ];
    }

    public function store(Request $req)
    {
        $schema = Validator::make($req->all(), Model::rules);

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all(),
            ];
        }

        $data = $schema->validated();
        Model::create($data);

        return [
            'error' => false,
            'message' => 'data berhasil disimpan',
        ];
    }

    public function update(Request $req, $id)
    {
        $schema = Validator::make($req->all(), Model::rules);

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all(),
            ];
        }

        $data = $schema->validated();
        Model::find($id)->update($data);

        return [
            'error' => false,
            'message' => 'data berhasil diedit',
        ];
    }

    public function destroy($id)
    {
        Model::destroy($id);

        return [
            'error' => false,
            'message' => 'data berhasil dihapus',
        ];
    }
}
