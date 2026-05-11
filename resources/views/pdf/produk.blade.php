<h1>Daftar Produk</h1>
<table border="1" width="100%" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produk as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->nama_produk }}</td>
            <td>IDR {{ number_format($p->harga, 0, ',', '.') }}</td>
            <td>{{ $p->stok }}</td>
        </tr>
        @endforeach
    </tbody>
</table>