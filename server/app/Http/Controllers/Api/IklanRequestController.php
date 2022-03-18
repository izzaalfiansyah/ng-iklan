<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IklanRequest as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IklanRequestController extends Controller
{
    private $buktiPembayaranPath;

    public function __construct()
    {
        $this->buktiPembayaranPath = public_path('/asset/iklan/bukti-pembayaran/');
        @mkdir(public_path('/asset'));
        @mkdir(public_path('/asset/iklan'));
        @mkdir(public_path('/asset/iklan/bukti-pembayaran'));
    }

    public function index(Request $req)
    {
        $builder = new Model;

        if (isset($req->user_id)) {
            $builder = $builder->where('user_id', $req->user_id);
        }

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
        $schema = Validator::make($req->all(), Model::rules);

        if ($schema->fails()) {
            return [
                'error' => true,
                'message' => $schema->errors()->all(),
            ];
        }

        $data = $schema->validated();

        if ($req->bukti_pembayaran) {
            if (strpos($req->bukti_pembayaran, ';base64')) {
                $bukti_pembayaran = explode(';base64,', $req->bukti_pembayaran)[1];
                $bukti_pembayaranNama = date('Ymdhis') . $this->randomString() . '.jpg';
    
                if (file_put_contents($this->buktiPembayaranPath . $bukti_pembayaranNama, base64_decode($bukti_pembayaran))) {
                    $data['bukti_pembayaran'] = $bukti_pembayaranNama;
                } else {
                    return [
                        'error' => false,
                        'message' => ['bukti pembayaran gagal diupload'],
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'message' => ['format bukti pembayaran salah'],
                ];
            }
        }

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
        $item = Model::find($id);

        if ($req->bukti_pembayaran) {
            if (strpos($req->bukti_pembayaran, ';base64')) {
                $bukti_pembayaran = explode(';base64,', $req->bukti_pembayaran)[1];
                $bukti_pembayaranNama = date('Ymdhis') . $this->randomString() . '.jpg';
    
                if (file_put_contents($this->buktiPembayaranPath . $bukti_pembayaranNama, base64_decode($bukti_pembayaran))) {
                    @unlink($this->buktiPembayaranPath . $item->bukti_pembayaran);
    
                    $data['bukti_pembayaran'] = $bukti_pembayaranNama;
                } else {
                    unset($data['bukti_pembayaran']);
    
                    return [
                        'error' => false,
                        'message' => ['bukti pembayaran gagal diupload'],
                    ];
                }
            } else {
                return [
                    'error' => true,
                    'message' => ['format bukti pembayaran salah'],
                ];
            }
        }

        $item->update($data);

        return [
            'error' => false,
            'message' => 'data berhasil diedit',
        ];
    }

    public function destroy($id)
    {
        $item = Model::find($id);

        @unlink($this->buktiPembayaranPath . $item->bukti_pembayaran);
        $item->delete();

        return [
            'error' => false,
            'message' => 'data berhasil dihapus',
        ];
    }
}
