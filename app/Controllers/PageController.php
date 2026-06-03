<?php

class PageController extends BaseController
{
    public function about(): void
    {
        $this->view('public/about', [
            'title' => 'Sobre Nós - FerramentasFácil',
        ]);
    }

    public function contact(): void
    {
        $this->view('public/contact', [
            'title' => 'Contato - FerramentasFácil',
        ]);
    }
}
