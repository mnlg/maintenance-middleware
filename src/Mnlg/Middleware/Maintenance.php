<?php
/**
 * Slim Maintenance Middleware
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Mnlg\Middleware;

/**
 * Maintenance Middleware
 *
 * This middleware renders a maintenance when the Slim application
 * mode is set to 'maintenance'.
 */
class Maintenance extends \Slim\Middleware
{
    /**
     * @var callable
     */
     protected $callable;

    /**
     * Constructor
     * @param callable $callable
     */
    public function __construct($callable = null)
    {
        if (null === $callable) {
            $this->callable = array($this, 'defaultMaintenancePage');
        } else {
            if (!is_callable($callable)) {
                throw new \InvalidArgumentException('argument callable must be callable');
            } else {
                $this->callable = $callable;
            }
        }
    }

    /**
     * Call
     */
    public function call()
    {
        $mode = $this->app->getMode();
        if ('maintenance' === $mode) {
            call_user_func($this->callable);
        } else {
            $this->next->call();
        }
    }

    /**
     * Default maintenance callback
     */
    public function defaultMaintenancePage()
    {
        $body = "<html><head><title>Maintenance</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana,sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}strong{display:inline-block;width:65px;}</style></head><body><h1>Maintenance</h1><p>Please try again later.</p></body></html>";
        $this->app->contentType('text/html');
        $this->app->response()->status(503);
        $this->app->response()->body($body);
    }
}
