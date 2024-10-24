<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="{{ asset('assets/jquery.js') }}"></script>
    <link rel="stylesheet" type="text/css" media="all"
        href="//cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <script type="text/javascript" src="//cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <!--Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <title>Dashboard</title>
</head>

<body>
    <div class="container">
        <h2>Dashboard</h2>

        <div>
            <a href="#" class="logout">Cerrar sesión</a>
        </div>

        <form method="GET" action="">
            <input type="text" name="YEAR" placeholder="Filtrar por año">
            <button type="submit">Buscar</button>
        </form>

        <div class="error-message" style="color: red; display: none;">
            Error: No se encontraron resultados.
        </div>
        <div class="col-lg-6">
            anio:
            <select name="anio" id="anios" style="width: 23%"></select>

            <table id="tbl">
                <thead>
                    <tr>
                        <th>Serial_Number</th>
                        <th>Build_code</th>
                        <th>Create_Ts</th>
                        <th>ShipSerial</th>
                        <th>ShipLabelTimeStamp</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


    <script type="text/javascript" src="{{ asset('assets/report.js') }}"></script>

</body>

</html>
