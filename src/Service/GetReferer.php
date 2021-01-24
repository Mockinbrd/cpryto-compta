<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class GetReferer
{
    /*
     * Get the referer from a Request and send it sanitized
     */
    public function referer(Request $request)
    {
        if ($request->headers->has('referer')) {
            return filter_var($request->headers->get('referer'), FILTER_SANITIZE_URL);
        }
        return 0;
    }
}
