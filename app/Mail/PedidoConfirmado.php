<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PedidoConfirmado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Pedido $pedido)
    {
        $this->pedido->loadMissing('cliente', 'items.producto');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'VitaMind - Confirmacion de tu compra #' . $this->pedido->id,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.pedido-confirmado');
    }

    /**
     * Adjunta los archivos de los productos digitales (guias/recetarios)
     * si el PDF existe en el servidor. Si aun no se ha subido, se omite.
     */
    public function attachments(): array
    {
        $adjuntos = [];

        foreach ($this->pedido->items as $item) {
            $prod = $item->producto;
            if (! $prod || $prod->tipo !== 'digital' || empty($prod->archivo_url)) {
                continue;
            }

            foreach ([public_path($prod->archivo_url), storage_path('app/public/' . $prod->archivo_url)] as $ruta) {
                if (is_file($ruta)) {
                    $adjuntos[] = Attachment::fromPath($ruta);
                    break;
                }
            }
        }

        return $adjuntos;
    }
}