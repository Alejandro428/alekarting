<!DOCTYPE html>
<html lang="es">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Confirmada</title>
    <link rel="icon" href="<?= base_url('assets/imagenes/logo_karting.png') ?>" type="image/png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .confirmation-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }
        .confirmation-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            padding: 2rem 1rem;
        }
        .confirmation-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            animation: bounce 1s;
        }
        .details-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }
    </style>
</head>
<?php
date_default_timezone_set('Europe/Madrid'); 
?>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card confirmation-card">
                    <div class="confirmation-header text-center text-white">
                        <i class="bi bi-check-circle-fill confirmation-icon"></i>
                        <h1 class="fw-bold">¡Reserva Confirmada!</h1>
                        <p class="lead mb-0">Tu pago se ha procesado correctamente</p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <p class="text-muted">Hemos enviado los detalles a tu correo electrónico</p>
                        </div>
                        
                        <!-- Detalles de la transacción -->
                        <div class="details-box">
                            <h5 class="fw-bold mb-4 text-center"><i class="bi bi-receipt"></i> Resumen de tu reserva</h5>
                            
                            <div class="detail-item">
                                <span class="text-muted">Número de referencia:</span>
                                <span class="fw-bold"><?= esc($payment_intent_id) ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="text-muted">Fecha de pago:</span>
                                <span class="fw-bold"><?= date('d/m/Y H:i') ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="text-muted">Método de pago:</span>
                                <span class="fw-bold">
                                    <?php 
                                    // Mapeo de métodos de pago a nombres legibles
                                    $nombresMetodos = [
                                        'card' => 'Tarjeta de crédito/débito',
                                        'paypal' => 'PayPal',
                                        'amazon' => 'Amazon Pay'
                                    ];
                                    
                                    // Mostrar el nombre correspondiente o el valor original si no está en el mapeo
                                    echo $nombresMetodos[$metodo_pago] ?? ucfirst($metodo_pago ?? 'Tarjeta');
                                    ?>
                                </span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="text-muted">Estado:</span>
                                <span class="badge bg-success">Completado</span>
                            </div>
                        </div>
                        
                        <!-- Mensaje adicional -->
                        <div class="alert alert-info mt-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle-fill me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">¿Necesitas ayuda?</h6>
                                    <p class="mb-0 small">Si tienes alguna pregunta sobre tu reserva, contacta con nuestro equipo de soporte.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="d-grid gap-3 d-md-flex justify-content-md-center mt-4">
                            <a href="<?= base_url('Perfil') ?>" class="btn btn-primary px-4 py-2">
                                <i class="bi bi-calendar-check me-2"></i> Ver mi perfil
                            </a>
                            <a href="<?= base_url("") ?>" class="btn btn-outline-secondary px-4 py-2">
                                <i class="bi bi-house me-2"></i> Volver al inicio
                            </a>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="card-footer bg-white text-center py-3">
                        <p class="small text-muted mb-0">Recibirás un correo de confirmación con todos los detalles de tu reserva.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    var base_url = "<?= base_url() ?>";
    </script>
    <script src="<?= base_url('assets/jquery-3.7.1.js') ?>"></script>
    <script src="<?= base_url('assets/js/completado.js') ?>"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>