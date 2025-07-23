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

        @page {
            size: 7.6cm 19.7cm ;
            /*size: 6.7cm 19.7cm ;*/
            margin: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            display: flex; /* Use flexbox for the body */
            justify-content: center; /* Center the content horizontally */
            align-items: center; /* Center the content vertically */
            height: 100vh; /* Take full viewport height */
            padding: 20pt;
        }

        .credencial-container {
            display: flex; /* This container will hold the front and back side-by-side */
            width: 12cm; /* Approx. 2 * 5cm width for both cards */
            height: 8cm;
        }

        .frente, .dorso {
            width: 4.3cm;
            height: 6.6cm;
            padding: 12pt;
            border: 2pt solid black;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .frente {
            background-color: #58585a;
            color: white;
        }

        .logo-frente {
            width: 110pt;
            margin-bottom: 2pt;
        }

        .nombre-practicante {
            font-size: 8pt;
            font-weight: bold;
            letter-spacing: 1pt;
            margin: 5pt 0;
            text-align: center;
            text-transform: uppercase;
        }

        .trainee {
            text-align: center;
            font: bold;
            font-size: 7pt;
            letter-spacing: 1pt;
            margin-bottom: 10pt;
            font-weight: 300;
            color: #ffffff;
        }

        .foto-espacio {
            width: 2.5cm;
            height: 3.3cm;
            background-color: #d1d2d4;
            border: 2pt solid white;
            border-radius: 4pt;
            margin: 0 auto 8pt auto;
            overflow: hidden;
            /* Esto evita que la imagen se salga del contenedor */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .footer-texto {
            font-family: 'Times New Roman', Times, serif;
            font-size: 8pt;
            font: bold;
            text-align: center;
            margin-top: auto;
            color: rgb(255, 255, 255);
            line-height: 1.2;
            padding-top: 5px;
        }

        /* ESTILOS PARA EL DORSO */
        .dorso {
            margin-top: 2pt;
            background-color: white;
            color: black; /* Ensure text is black for dorso */
            
        }

        .logo-dorso {
            width: 110pt;
            margin: 0 auto 10pt auto;
        }

        .info-dorso {
            font-size: 8pt;
            line-height: 1.3;
            margin: 5pt 0;
            text-align: center;
            text-transform: uppercase;
        }

        .info-dorso strong {
            font-weight: bold;
        }

        .footer-info {
            font-size: 8pt;
            line-height: 1.2;
            border-top: 1pt solid #ddd;
            padding-top: 8pt;
            margin-top: auto;
            color: black;
            text-align: center;
        }

        .barcode-container {
            text-align: center;
            padding-top: 6pt;
        }

        .barcode-container img {
            height: 22pt;
            width: auto;
        }

        .clave-texto {
            font-size: 7pt;
            letter-spacing: 1pt;
            margin-top: 2pt;
            color: black;
        }
    </style>
</head>

<body>
    <div class="credencial-container">
        <div class="frente">
            <img src="{{ $logoFrentePath }}" alt="Logo" class="logo-frente">
            <div class="nombre-practicante">{{ $nombreCompleto }}</div>
            <div class="trainee">TRAINEE</div>
            <div class="foto-espacio">
                @if ($imagen)
                    <img src="data:image/jpeg;base64,{{ $imagen }}" alt="Foto del practicante"
                        style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                @endif
            </div>
            <div class="footer-texto">En grupo Presidente, nuestra pasión será su mejor experiencia</div>
        </div>

        <div class="dorso">
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
            <div class="barcode-container">
                <img src="data:image/png;base64,{{ $barcodeImage }}" alt="Barcode">
                <div class="clave-texto">{{ $clave }}</div>
            </div>
        </div>
    </div>
</body>

</html>
