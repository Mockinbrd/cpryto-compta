<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetReferer
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /*
     * Get the referer from a Request and send it sanitized
     */
    public function referer(Request $request)
    {
        if ($request->headers->has('referer')) {
            return filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);
        }
        return $this->urlGenerator->generate('home');
    }
}
