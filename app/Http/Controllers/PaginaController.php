<?php

namespace App\Http\Controllers;

class PaginaController extends Controller
{
    // Pagina "Acerca de"
    public function acerca()
    {
        return view('pages.acerca');
    }

    // Pagina "Contacto"
    public function contacto()
    {
        return view('pages.contacto');
    }
}
