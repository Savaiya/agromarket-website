<?php

namespace App\Controllers;

class ProductController
{
    public function index(): void
    {
        view('product', ['pageTitle' => 'Our Products']);
    }

    public function details(): void
    {
        view('product-details', ['pageTitle' => 'Product Details']);
    }
}
