<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Credencial {{ $clave }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Tamaño de página para impresión */
        @page {
            size: 10cm 25cm;
            margin: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            display: flex;
        }

        .frente {
            background-color: #58585a;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 6cm;
            height: 9cm;
            padding: 12pt;
            border: 2pt solid black;
        }

        .logo-frente {
            width: 120pt;
            margin-bottom: 10pt;
        }

        .nombre-practicante {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1pt;
            margin: 5pt 0;
            text-align: center;
            text-transform: uppercase;
        }

        .trainee {
            text-align: center;
            font:bold;
            font-size: 11pt;
            letter-spacing: 1pt;
            margin-bottom: 10pt;
            font-weight: 300;
            color: #ffffff;
        }

        .foto-espacio {
            width: 2.2cm;
            height: 3.2cm;
            background-color: #d1d2d4;
            border: 2pt solid white;
            border-radius: 8pt;
            margin: 0 auto 8pt auto;
        }
        .footer-texto {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            font: bold;
            text-align: center;
            margin-top: auto;
            color: rgb(255, 255, 255);
            line-height: 1.2;
            padding-top: 37px;
        }

        /* ESTILOS PARA EL DORSO */
        .dorso {
            margin-top: 1cm;
            background-color: white;
            border-left: 1pt dashed #ccc;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 6cm;
            padding: 12pt;
            height: 9cm;
            border: 2pt solid black;
        }

        .logo-dorso {
            width: 120pt;
            margin: 0 auto 10pt auto;
        }

        .info-dorso {
            font-size: 10pt;
            margin: 0 0 8pt 0;
            line-height: 1.3;
        }

        .info-dorso strong {
            font-weight: bold;
        }

        .footer-info {
            font-size: 9pt;
            line-height: 1.2;
            border-top: 1pt solid #ddd;
            padding-top: 8pt;
            margin-top: auto;
            color: black;
        }

        .barcode-container {
            text-align: center;
            margin-top: 10pt;
            padding-bottom: 5pt;
        }

        .barcode-container img {
            height: 28pt;
            width: auto;
        }

        .clave-texto {
            font-size: 8pt;
            letter-spacing: 1pt;
            margin-top: 2pt;
            color: black;
        }
    </style>
</head>

<body>
    <!-- Frente -->
    <div class=" frente">
        <img src="{{ $logoFrentePath }}" alt="Logo" class="logo-frente">
        <div class="nombre-practicante">{{ $nombreCompleto }}</div>
        <div class="trainee">TRAINEE</div>
        <div class="foto-espacio"></div>
        <div class="footer-texto">En grupo Presidente, nuestra pasión será su mejor experiencia</div>
    </div>

    <!-- Dorso -->
    <div class=" dorso">
        <img src="{{ $logoDorsoPath }}" alt="Logo" class="logo-dorso">

        <div>
            <p class="info-dorso"><strong>NOMBRE:</strong> {{ $nombreCompleto }}</p>
            <p class="info-dorso"><strong>DEPARTAMENTO:</strong> {{ $area }}</p>
        </div>

        <div class="footer-info">
            <p>En caso de accidente notificar al: <br><strong>(33) 3678 1234</strong> Recursos Humanos <br></p>
            <br>
            <p>Este gafete es propiedad de la empresa. Devolver al terminar prácticas.</p>
        </div>
        <!-- Código de barras al dorso de la credencial junto con clave-->
        <div class="barcode-container">
            <img src="data:image/png;base64,{{ $barcodeImage }}" alt="Barcode">
            <div class="clave-texto">{{ $clave }}</div>
        </div>
    </div>
</body>

</html>