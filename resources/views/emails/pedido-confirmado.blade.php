@php
    $tieneDigitales = $pedido->items->contains(fn ($i) => $i->producto && $i->producto->tipo === 'digital');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmacion de compra</title>
</head>
<body style="margin:0; padding:0; background:#f6f4ec; font-family:Arial,Helvetica,sans-serif; color:#1f3d2b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f4ec; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #e3ddcd;">

                    <!-- Encabezado -->
                    <tr>
                        <td style="background:#1f4023; padding:28px 32px; text-align:center;">
                            <span style="color:#cde85a; font-size:24px; font-weight:bold; letter-spacing:.5px;">VitaMind</span>
                        </td>
                    </tr>

                    <!-- Saludo -->
                    <tr>
                        <td style="padding:32px 32px 8px;">
                            <h1 style="margin:0 0 6px; font-size:22px; color:#1f6b3b;">&iexcl;Gracias por tu compra, {{ $pedido->cliente->nombre }}!</h1>
                            <p style="margin:0; font-size:15px; color:#5a7a64;">Tu pedido <strong>#{{ $pedido->id }}</strong> fue confirmado. Aqu&iacute; est&aacute; el resumen:</p>
                        </td>
                    </tr>

                    <!-- Items -->
                    <tr>
                        <td style="padding:20px 32px 0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                <tr>
                                    <th align="left"  style="padding:10px 0; border-bottom:2px solid #e3ddcd; font-size:13px; color:#8a9a8f; text-transform:uppercase;">Producto</th>
                                    <th align="center" style="padding:10px 0; border-bottom:2px solid #e3ddcd; font-size:13px; color:#8a9a8f; text-transform:uppercase;">Cant.</th>
                                    <th align="right" style="padding:10px 0; border-bottom:2px solid #e3ddcd; font-size:13px; color:#8a9a8f; text-transform:uppercase;">Subtotal</th>
                                </tr>
                                @foreach ($pedido->items as $item)
                                    <tr>
                                        <td style="padding:12px 0; border-bottom:1px solid #f0ece0; font-size:15px;">
                                            {{ $item->producto->nombre ?? 'Producto' }}
                                            @if ($item->producto && $item->producto->tipo === 'digital')
                                                <span style="display:inline-block; margin-left:6px; background:#eef7d8; color:#5b7a1f; font-size:11px; padding:2px 7px; border-radius:10px;">Digital</span>
                                            @endif
                                        </td>
                                        <td align="center" style="padding:12px 0; border-bottom:1px solid #f0ece0; font-size:15px;">{{ $item->cantidad }}</td>
                                        <td align="right" style="padding:12px 0; border-bottom:1px solid #f0ece0; font-size:15px;">Bs {{ number_format($item->precio_unitario * $item->cantidad, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" align="right" style="padding:16px 0 0; font-size:18px; font-weight:bold;">Total</td>
                                    <td align="right" style="padding:16px 0 0; font-size:18px; font-weight:bold;">Bs {{ number_format($pedido->total, 2) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Metodo de pago -->
                    <tr>
                        <td style="padding:18px 32px 0; font-size:14px; color:#5a7a64;">
                            M&eacute;todo de pago: <strong style="color:#1f3d2b; text-transform:capitalize;">{{ $pedido->metodo_pago }}</strong>
                        </td>
                    </tr>

                    @if ($tieneDigitales)
                        <!-- Aviso de digitales -->
                        <tr>
                            <td style="padding:20px 32px 0;">
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f7ee; border-radius:10px;">
                                    <tr>
                                        <td style="padding:16px 18px; font-size:14px; color:#3a5444;">
                                            📘 <strong>Tus productos digitales</strong><br>
                                            Encontrar&aacute;s las gu&iacute;as y recetarios que compraste <strong>adjuntos a este correo</strong> en formato PDF.
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endif

                    <!-- Pie -->
                    <tr>
                        <td style="padding:28px 32px 32px; text-align:center;">
                            <p style="margin:0 0 4px; font-size:13px; color:#8a9a8f;">Gracias por elegir VitaMind 🌿</p>
                            <p style="margin:0; font-size:12px; color:#b6ac98;">Si tienes dudas, responde a este correo.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>