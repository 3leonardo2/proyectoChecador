<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta tus horas registradas</title>
    <link rel="stylesheet" href="css/consulta_horas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <a href="#" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1>Consulta tus horas registradas</h1>
    </div>

    <div class="main-container">
        <div class="left-panel">
            <div class="input-section">
                <input type="text" id="codigoInput" placeholder="Ingresa tu código...">
                <button class="consultar-button">CONSULTA TUS HORAS</button>
            </div>
            <div class="practicante-data">
                <p>Nombre: <span id="practicanteNombre">Leonardo Alatorre Esparza</span></p>
                <p>Horas Totales: <span id="horasTotales">232</span></p>
                <p>Horas Registradas: <span id="horasRegistradas">139</span></p>
                <p>Horas Faltantes: <span id="horasFaltantes">139</span></p>
            </div>
        </div>

        <div class="right-panel">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha <i class="fa-solid fa-caret-down sort-icon"></i></th>
                            <th>Tipo</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>23/05/2025</td>
                            <td>Entrada</td>
                            <td>8:00 am</td>
                        </tr>
                        <tr class="highlighted-row">
                            <td>23/05/2025</td>
                            <td>Entrada Comedor</td>
                            <td>8:00 am</td>
                        </tr>
                        <tr>
                            <td>23/05/2025</td>
                            <td>Salida Comedor</td>
                            <td>8:00 am</td>
                        </tr>
                        <tr class="highlighted-row">
                            <td>23/05/2025</td>
                            <td>Salida</td>
                            <td>8:00 am</td>
                        </tr>
                        <tr>
                            <td>23/05/2025</td>
                            <td>Entrada</td>
                            <td>8:00 am</td>
                        </tr>
                        <tr class="highlighted-row">
                            <td>23/05/2025</td>
                            <td>Entrada Comedor</td>
                            <td>8:00 am</td>
                        </tr>
                        <tr><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td></tr>
                        <tr><td></td><td></td><td></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>