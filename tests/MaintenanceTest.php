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

class MaintenanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test maintenance middleware when 'maintenance' mode is not enabled
     */
    public function testMaintenanceDisabled()
    {
        \Slim\Environment::mock(array(
            'SCRIPT_NAME' => '/index.php',
            'PATH_INFO' => '/'
        ));
        $app = new \Slim\Slim(array());
        $app->get('/', function () {
            echo "Success";
        });
        $mw = new \Mnlg\Middleware\Maintenance();
        $mw->setApplication($app);
        $mw->setNextMiddleware($app);
        $mw->call();
        $this->assertEquals(200, $app->response()->status());
        $this->assertEquals('Success', $app->response()->body());
    }

    /**
     * Test maintenance middleware when 'maintenance' mode is enabled
     */
    public function testMaintenanceEnabled()
    {
        \Slim\Environment::mock(array(
            'SCRIPT_NAME' => '/index.php',
            'PATH_INFO' => '/'
        ));
        $app = new \Slim\Slim(array(
            'mode' => 'maintenance'
        ));
        $app->get('/', function () {
            echo "Success";
        });
        $mw = new \Mnlg\Middleware\Maintenance();
        $mw->setApplication($app);
        $mw->setNextMiddleware($app);
        $mw->call();
        $this->assertEquals(503, $app->response()->status());
        $this->assertNotEquals('Success', $app->response()->body());
    }

    /**
     * Test maintenance middleware when 'maintenance' mode is enabled with a custom callable
     */
    public function testMaintenanceEnabledCustomCallable()
    {
        \Slim\Environment::mock(array(
            'SCRIPT_NAME' => '/index.php',
            'PATH_INFO' => '/'
        ));
        $app = new \Slim\Slim(array(
            'mode' => 'maintenance'
        ));
        $app->get('/', function () {
            echo "Success";
        });
        $mw = new \Mnlg\Middleware\Maintenance(function() {
            echo 'Maintenance';
        });
        $mw->setApplication($app);
        $mw->setNextMiddleware($app);
        $mw->call();
        $this->assertEquals(200, $app->response()->status());
        $this->assertNotEquals('Maintenance', $app->response()->body());
    }
}
