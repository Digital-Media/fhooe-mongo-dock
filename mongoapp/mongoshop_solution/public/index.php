<?php

declare(strict_types=1);

use Fhooe\Router\Router;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use Exercises\Countries;
use Exercises\MongoCRUD;
use Exercises\MongoDoctrine;
use Exercises\MyCart;

require_once('../vendor/autoload.php');

session_start();

/**
 * Turn on debugging output to get more useful error messages while developing.
 */
const DEBUG = false;
if (DEBUG) {
    echo "<br>WARNING: Debugging is enabled. Set DEBUG to false for production use in " . __FILE__;
    echo "<br>Connect via SSH and send tail -f /var/log/apache2/error.log";
    echo " to see errors not displayed in Browser<br><br>";
    error_reporting(E_ALL);
    ini_set("html_errors", "1");
    ini_set("display_errors", "1");
    ini_set("display_startup_errors", "1");
}

try {
    /**
     * Instantiated Router invocation. Create an object, define the routes and run it.
     */
// Create a new Router object.
    $router = new Router();
#    $utilties = new Utilities();

// Create a monolog instance for logging in the skeleton. Pass it to the router to receive its log messages too.
    $logger = new Logger("skeleton-logger");
    $logger->pushHandler(new StreamHandler(__DIR__ . "/../logs/router.log"));
    $router->setLogger($logger);

// Create a new twig instance for advanced templates.
    $twig = new Environment(
        new FilesystemLoader("../views"),
        [
            "cache" => "../cache",
            "auto_reload" => true,
            "debug" => true
        ]
    );
    $twig->addFunction(new TwigFunction("url_for", [Router::class, "urlFor"]));
    $twig->addFunction(new TwigFunction("get_base_path", [Router::class, "getBasePath"]));
    $twig->addExtension(new \Twig\Extension\DebugExtension());

    if (isset($_SESSION)) {
        $twig->addGlobal("_session", $_SESSION);
    }

// Set a base path if your code is not in your server's document root.
    $router->setBasePath("/mongoshop_solution/public");

// Set a 404 callback that is executed when no route matches.
    $router->set404Callback(fn() => $twig->display("404.html.twig"));

// Set specific routes for WebShop
    $router->get("/", function () use ($twig) {
        $twig->display("index.html.twig");
    });

    $router->get("/createuser", function () use ($twig) {
        $mongocrud = new MongoCRUD($twig);
        $mongocrud->displayForm();
    });

    $router->get("/deleteuser", function () use ($twig) {
        $mongocrud = new MongoCRUD($twig);
        $mongocrud->deleteUser();
    });

    $router->get("/updateuser", function () use ($twig) {
        $mongocrud = new MongoCRUD($twig);
        $mongocrud->updateUser();
    });

    $router->post("/createuser", function () use ($twig) {
        $mongocrud = new MongoCRUD($twig);
        $mongocrud->insertUser();
    });

    $router->post("/updateuser", function () use ($twig) {
        $mongocrud = new MongoCRUD($twig);
        $mongocrud->updateUser();
    });

    $router->get("/mongodoctrine", function () use ($twig) {
        $mongodoctrine = new MongoDoctrine($twig);
        $mongodoctrine->displayForm();
    });

    $router->post("/mongodoctrine", function () use ($twig) {
        $mongodoctrine = new MongoDoctrine($twig);
        $mongodoctrine->insertUser();
    });
    $router->get("/createcountry", function () use ($twig) {
        $countries = new Countries($twig);
        $countries->displayForm();
    });

    $router->post("/createcountry", function () use ($twig) {
        $countries = new Countries($twig);
        $countries->insertCountry();
    });

    $router->get("/mycart", function () use ($twig) {
        $mycart = new MyCart($twig);
        $mycart->displayForm();
    });

    $router->post("/mycart", function () use ($twig) {
        $mycart = new MyCart($twig);
        $mycart->insertOrder();
    });

// Run the router to get the party started.
    $router->run();

} catch (Exception $e) {
    echo "<h1>Error Page for Debugging</h1>.";
    echo "<p><strong>Don't use that in a production environment!</strong></p>";
    echo "<p>There is an error in " . $e->getFile() . " on line " . $e->getLine() . ".</p>";
    echo "<p>Message: " . $e->getMessage() . "</p>";
    echo "<p>Code: " . $e->getCode() . "</p>";
    echo "<p>Trace: " . $e->getTraceAsString() . "</p>";
}
