<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $fotoPath;

    public function __construct()
    {
        $this->fotoPath = public_path('/asset/user/foto/');
        @mkdir(public_path('/asset'));
        @mkdir(public_path('/asset/user'));
        @mkdir(public_path('/asset/user/foto'));
    }

    public function index()
    {
        $builder = new Model;

        $data = $builder->get();

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
        $schema = Validator::make($req->all(), array_merge(Model::rules, Model::authRules));

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all(),
            ];
        }

        $data = $schema->validated();
        $data['password'] = Hash::make($req->password);

        if (isset($req->foto)) {
            if (strpos($req->foto, ';base64,')) {
                $foto = explode(';base64,', $req->foto)[1];
                $dfotoNama = date('Ymdhis') . $this->randomString() . '.png';
    
                if (file_put_contents($this->fotoPath . $dfotoNama, base64_decode($foto))) {
                    $data['foto'] = $dfotoNama;
                } else {
                    return [
                        'error' => false,
                        'message' => ['foto gagal diupload'],
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'message' => ['format foto salah'],
                ];
            }
        }

        $item = Model::create($data);

        return [
            'error' => false,
            'message' => 'data berhasil disimpan',
            'data' => $item,
        ];
    }

    public function update(Request $req, $id)
    {
        if ($req->auth) {
            return $this->updateAuth($req, $id);
        }

        $schema = Validator::make($req->all(), Model::rules);

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all(),
            ];
        }

        $data = $schema->validated();
        $item = Model::find($id);

        if (isset($req->foto)) {
            if (strpos($req->foto, ';base64,')) {
                $foto = explode(';base64,', $req->foto)[1];
                $dfotoNama = date('Ymdhis') . $this->randomString() . '.png';
    
                if (file_put_contents($this->fotoPath . $dfotoNama, base64_decode($foto))) {
                    @unlink($this->fotoPath . $item->foto);
    
                    $data['foto'] = $dfotoNama;
                } else {
                    unset($data['foto']);
    
                    return [
                        'error' => false,
                        'message' => ['foto gagal diupload'],
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'message' => ['format foto salah'],
                ];
            }
        }

        $item->update($data);

        return [
            'error' => false,
            'message' => 'data berhasil diedit',
        ];
    }

    public function updateAuth(Request $req, $id)
    {
        $schema = Validator::make($req->all(), Model::authRules);

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all(),
            ];
        }

        $data = $schema->validated();
        
        if ($req->password) {
            $data['password'] = Hash::make($req->password);
        } else {
            unset($data['password']);
        }

        Model::find($id)->update($data);

        return [
            'error' => false,
            'message' => 'autentikasi berhasil diedit'
        ];
    }

    public function destroy($id)
    {
        $item = Model::find($id);

        @unlink($this->fotoPath . $item->foto);
        $item->delete();

        return [
            'error' => false,
            'message' => 'data berhasil dihapus',
        ];
    }

    public function login(Request $req)
    {
        $schema = Validator::make($req->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all(),
            ];
        }

        $data = Model::where('username', $req->username)->orWhere('email', $req->username)->get();

        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                if (Hash::check($req->password, $item->password)) {
                    return [
                        'error' => false,
                        'message' => 'berhasil login',
                        'data' => $item,
                    ];
                }
            }

            return [
                'error' => true,
                'message' => ['password salah'],
            ];
        } else {
            return [
                'error' => true,
                'message' => ['username tidak ditemukan'],
            ];
        }
    }
}
