<?php

namespace App\Controllers;

class PageController
{
    public function about(): void
    {
        view('about', ['pageTitle' => 'About Us']);
    }

    public function about2(): void
    {
        view('about-2', ['pageTitle' => 'About Us Two']);
    }

    public function services(): void
    {
        view('services', ['pageTitle' => 'Our Services']);
    }

    public function serviceDetails(): void
    {
        view('service-details', ['pageTitle' => 'Service Details']);
    }

    public function faq(): void
    {
        view('faq', ['pageTitle' => 'Frequently Asked Questions']);
    }

    public function privacy(): void
    {
        view('privacy', ['pageTitle' => 'Privacy Policy']);
    }

    public function pricing(): void
    {
        view('pricing', ['pageTitle' => 'Pricing']);
    }

    public function team(): void
    {
        view('team', ['pageTitle' => 'Our Team']);
    }

    public function teamDetails(): void
    {
        view('team-details', ['pageTitle' => 'Team Details']);
    }

    public function project(): void
    {
        view('project', ['pageTitle' => 'Our Portfolio']);
    }

    public function projectDetails(): void
    {
        view('project-details', ['pageTitle' => 'Project Details']);
    }

    public function checkout(): void
    {
        view('checkout', ['pageTitle' => 'Checkout']);
    }

    public function shopCart(): void
    {
        view('shop-cart', ['pageTitle' => 'Shopping Cart']);
    }

    public function shopDetails(): void
    {
        view('shop-details', ['pageTitle' => 'Shop Details']);
    }

    public function shopGrid(): void
    {
        view('shop-grid', ['pageTitle' => 'Shop']);
    }
}
