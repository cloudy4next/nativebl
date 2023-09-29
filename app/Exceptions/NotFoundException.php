<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{

    public function __construct($customMessage = null)
    {
        if (is_array($customMessage)) {
            $message = json_encode($customMessage); // Serialize the array to a JSON string
        } else {
            $message = $customMessage ?? 'Resource not found';
        }

        parent::__construct($message, 404);
    }

    // public function render($request)
    // {
    //     $errorMessage = is_array($this->getMessage()) ? json_decode($this->getMessage()) : [$this->getMessage()];

    //     return response()->json(['errors' => $errorMessage], 404);
    // }

    public function render($request)
    {
        $test = json_decode($this->getMessage());
        if (isset($test->email)) {
            return redirect()->back()->withErrors(['email' => $test->email]);
        }

        return redirect()->back()->withErrors(['errors' => $this->getMessage()]);

    }
}
