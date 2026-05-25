<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error en el Pago</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/x-icon">
    <style>
        .error-container {
            max-width: 600px;
            margin: 2rem auto;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .error-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
        }
        .error-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .error-message {
            font-size: 1.1rem;
            line-height: 1.6;
        }
        .error-code {
            font-family: monospace;
            background-color: #e9ecef;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
        }
        .payment-method-icon {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
    </style>
</head>
<?php
date_default_timezone_set('Europe/Madrid'); 
?>
<body class="bg-light">
    <div class="container py-5">
        <div class="error-container bg-white p-4 p-md-5">
            <div class="text-center">
                <!-- Icono de error -->
                <div class="error-icon text-danger">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                
                <!-- Título principal -->
                <h1 class="h2 mb-3">
                    <?php 
                    switch($error_code) {
                        case 'card_declined':
                            echo 'Tarjeta rechazada, no es válida';
                            break;
                        case 'expired_card':
                            echo 'Tarjeta vencida';
                            break;
                        case 'insufficient_funds':
                            echo 'Fondos insuficientes';
                            break;
                        case '3ds_authentication_failed':
                            echo 'Autenticación 3D Secure fallida o cancelada';
                            break;
                        case 'paypal_cancelled':
                            echo 'Paypal cancelado';
                            break;    
                        default:
                            echo 'Error en el proceso de pago';
                    }
                    ?>
                </h1>

                <div class="error-message mb-4">
                    <p><?= esc($error) ?></p>

                    <!-- Mostrar icono según método de pago -->
                    <?php if($is_paypal_error): ?>
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <span>Error con PayPal</span>
                    </div>
                <?php endif; ?>
                </div>


                <!-- Detalles técnicos -->
                <div class="error-details">
                    <h2 class="h5 mb-3">Información del error:</h2>
                    <ul class="list-unstyled">
                        <li class="mb-2"><strong>Tipo de error:</strong> <span class="error-code"><?= esc($error) ?></span></li>
                        
                        <?php if ($payment_id): ?>
                        <li class="mb-2"><strong>Referencia:</strong> <?= esc($payment_id) ?></li>
                        <?php endif; ?>
                        
                        <?php if ($stripe_code): ?>
                        <li class="mb-2"><strong>Código técnico:</strong> <?= esc($stripe_code) ?></li>
                        <?php endif; ?>
                        
                        <li><strong>Fecha:</strong> <?= date('d/m/Y H:i:s') ?></li>
                    </ul>
                </div>
                
                <!-- Solo botón para volver al inicio -->
                <div class="mt-4">
                    <a href="<?= base_url() ?>" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-house-door me-2"></i> Volver al inicio
                    </a>
                </div>
                
                <!-- Soporte -->
                <div class="mt-4 pt-3 border-top">
                    <p class="text-muted small mb-2">¿Necesitas ayuda?</p>
                    <a href="<?= base_url('contacto') ?>" class="btn btn-link text-decoration-none">
                        <i class="bi bi-headset me-1"></i> Contactar soporte
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap JS Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mostrar error en consola para desarrollo -->
    <script>
        console.error("Error de pago:", {
            code: "<?= $error_code ?>",
            message: "<?= addslashes($error) ?>",
            paymentId: "<?= $payment_id ?? 'N/A' ?>",
            stripeCode: "<?= $stripe_code ?? 'N/A' ?>",
            timestamp: new Date().toISOString()
        });
    </script>
</body>
</html>