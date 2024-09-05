<?php 
/*
   * Note:
   * This file should only be edit by a backend PHP developer.
   * Thanks to PHP
   */


    use Database\ConnectionPool;
    use Router\Route;
    use template\Loader;



    require __DIR__ . '/classes/LoggerInterface.php';
    require __DIR__ . '/classes/NullLogger.php';
    require __DIR__ . '/classes/ConnectionPool.php';
    require __DIR__ . '/classes/Route.php';
    require __DIR__ . '/classes/Template.php';


    
    /*
    * Set globally the application configurations.
    */
    $GLOBALS['config'] = parse_ini_file(__DIR__ . '/config.ini');


     /*
    * Extraction common properties from the global configuration.
    */
    define('CURRENT_HOSTED_DOMAIN', $GLOBALS['config']['CURRENT_HOSTED_DOMAIN']);
    define('TIMEZONE', $GLOBALS['config']['TIME_ZONE']);
    define('ROOT_DOCUMENT', __DIR__);



    //Make sure all cookies are sent in secure connection
    session_set_cookie_params([
        'lifetime' => (86400 * 365), // Session cookie lifetime (1 day)
        'path' => '/',
        'domain' => CURRENT_HOSTED_DOMAIN,
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax' // Change SameSite to Lax
    ]);

    session_start();


    
    /*
    * Turn on or off error reporting.
    * by setting the:
    * ini_set("display_errors", 1);
    * To:
    * ini_set("display_errors", 0);
    * Will automatically turn of error reporting and warnings in the application.
    */
    $error_status = (int) $GLOBALS['config']['SHOW_ERRORS'];
    error_reporting(E_ALL);
    ini_set("display_errors", $error_status);


    try {

        $database_server = $GLOBALS['config']['DB_SERVER_NAME'];
        $database_user = $GLOBALS['config']['DB_USERNAME'];
        $database_name = $GLOBALS['config']['DB_NAME'];
        $database_password = $GLOBALS['config']['DB_PASSWORD'];
     
        // Initialize connection pool (typically done once in application setup)
        new ConnectionPool($database_server, $database_user, $database_password, $database_name);
     
        // Get a connection from the pool
        $GLOBALS['connection'] = ConnectionPool::get_connection();
     
        // Register a shutdown function to release the connection when the script ends
        register_shutdown_function(function() {
           if (isset($GLOBALS['connection'])) {
              ConnectionPool::release_connection($GLOBALS['connection']);
           }
        });
    } catch (Exception $e) {
        // Handle exception (e.g., connection pool limit reached)
        error_log($e->getMessage());
    }



    //Sync timezone with database
    date_default_timezone_set(TIMEZONE);
    $now = new DateTime();
    $mins = $now->getOffset() / 60;
    $sgn = ($mins < 0 ? -1 : 1);
    $mins = abs($mins);
    $hrs = floor($mins / 60);
    $mins -= $hrs * 60;
    $offset = sprintf('%+d:%02d', $hrs * $sgn, $mins);
    $GLOBALS['connection']->query("SET time_zone='$offset';");


    //Get the current host request address
    $current_scheme = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') ? 'https' : 'http';
    $current_host = $_SERVER["HTTP_HOST"];
    $GLOBALS['HOST_ADDR'] = $current_scheme . '://' . $current_host . $_SERVER['REQUEST_URI'];
    $GLOBALS['HOST'] = $current_scheme . '://' . $current_host;


    #=============================================================

    $route = new Route();

    #=============================================================
    
    //Map client routes
    $route->for('/', function() {  }, 'GET');
    $route->for('/results/polling_units', function() {  }, 'GET');
    $route->for('/results/polling_units/add', function() {  }, 'GET');
    $route->for('/results/display', function() {  }, 'GET');
    $route->for('/results/lga', function() {  }, 'GET');
    $route->for('/results/lga/display', function() {  }, 'GET');
    
    #=============================================================

    //Contents
    $route->for('/content/home', function() { include __DIR__ .  '/includes/contents/home.php'; exit; }, 'GET');
    $route->for('/content/results/polling_units', function() { include __DIR__ .  '/includes/contents/results/polling_units.php'; exit; }, 'GET');
    $route->for('/content/results/polling_units/add', function() { include __DIR__ .  '/includes/contents/add/add_polling_unit_results.php'; exit; }, 'GET');
    $route->for('/content/results/display', function() { include __DIR__ .  '/includes/contents/results/display_results.php'; exit; }, 'GET');
    $route->for('/content/results/lga', function() { include __DIR__ .  '/includes/contents/results/lgs.php'; exit; }, 'GET');
    $route->for('/content/results/lga/display', function() { include __DIR__ .  '/includes/contents/results/display_lga_results.php'; exit; }, 'GET');
    
    //Actions
    $route->for('/actions/fetch/lga', function() { include __DIR__ .  '/includes/actions/fetch/fetch_lga.php'; exit; }, 'GET');
    $route->for('/actions/fetch/polling_units', function() { include __DIR__ .  '/includes/actions/fetch/fetch_polling_units.php'; exit; }, 'GET');
    $route->for('/actions/results/display', function() { include __DIR__ .  '/includes/actions/fetch/fetch_polling_units.php'; exit; }, 'GET');
    $route->for('/actions/results/polling_units/add/save', function() { include __DIR__ .  '/includes/actions/add/save_results.php'; exit; }, 'POST');

    //Root content
    $route->addFilter('/*', function() {
  
        $template = new Loader('app');
        $template->show_errors = true;
        $template->show_warnings = true;

        $template->render();

    }, 'after');
    
    #=============================================================

    //Request error section
    $route->error_page(function() {
        http_response_code(404);
        echo 'Resource Not Found!';
    });

    #=============================================================

    $route->run();

    #=============================================================