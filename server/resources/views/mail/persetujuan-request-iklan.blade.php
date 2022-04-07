<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Permintaan Periklanan</title>
</head>
<body>
    <p>Halo {{ $item->user->nama }}.</p>
    <p>Permintaan periklanan anda, yaitu :</p>
    
    <p>
        <table>
            <tr>
                <td>Jenis</td>
                <td>:</td>
                <td>{{ $item->iklan->jenis }}</td>
            </tr>
            <tr>
                <td>Link Video</td>
                <td>:</td>
                <td>{{ $item->link_video }}</td>
            </tr>
            <tr>
                <td>Tarif</td>
                <td>:</td>
                <td>{{ $item->iklan->tarif_detail }}</td>
            </tr>
            <tr>
                <td>Durasi</td>
                <td>:</td>
                <td>{{ $item->iklan->durasi_detail }}</td>
            </tr>
            <tr>
                <td>Jumlah Tayang</td>
                <td>:</td>
                <td>{{ $item->jumlah_tayang }}</td>
            </tr>
            <tr>
                <td>Biaya Pembuatan</td>
                <td>:</td>
                <td>{{ $item->dibuatkan == '1' ? $item->iklan->harga_pembuatan_detail : 'Rp.0' }}</td>
            </tr>
            <tr>
                <td>Total</td>
                <td>:</td>
                <td>{{ $item->total }}</td>
            </tr>
        </table>
    </p>

    @if ($item->status == '1')
        <p>disetujui oleh admin.</p>
    @else
        <p>ditolak oleh admin.</p>
    @endif
</body>
</html>