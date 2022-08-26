<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e) {
            // Custom SQL Error response
            if ($e instanceof QueryException) {
                $errorMsg = $e->getMessage();
                if (strpos($errorMsg, 'Integrity constraint violation: 1451') !== false) {
                    return back()->with('custom_errors', 'Can not Delete this item. This item is linked with other items in project. First delete the child items related to this.');
                }
            }
        });
    }
}
