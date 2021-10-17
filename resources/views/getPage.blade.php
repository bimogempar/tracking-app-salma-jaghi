<!DOCTYPE html>
<html>

<head>
    <title>Laravel 8|7 Datatables Tutorial</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="mb-4">Laravel 7|8 Yajra Datatables Example</h2>
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>No. Agenda</th>
                    <th>No. Surat</th>
                    <th>Tanggal</th>
                    <th>Alamat Penerima</th>
                    <th>Perihal</th>
                    <th>Status</th>
                    <th>Pengirim Surat</th>
                    <th>Penerima Surat</th>
                    <th>Seksi</th>
                    <th>Bukti</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    $(function () {

    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('surats.list') }}",
        columns: [
            // {data: 'id', name: 'id'},
            {data: 'no_agenda', name: 'no_agenda'},
            {data: 'no_surat', name: 'no_surat'},
            {data: 'tanggal', name: 'tanggal'},
            {data: 'alamat_penerima', name: 'alamat_penerima'},
            {data: 'perihal', name: 'perihal'},
            {data: 'arsip', name: 'arsip'},
            {data: 'pengirim_surat', name: 'pengirim_surat'},
            {data: 'penerima_surat', name: 'penerima_surat'},
            {data: 'seksi', name: 'seksi'},
            {data: 'bukti', name: 'bukti'},
            {data: 'file', name: 'file'},
        ]
    });

  });
</script>

</html>
