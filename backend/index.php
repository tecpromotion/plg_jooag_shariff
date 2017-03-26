<?php

require_once __DIR__.'/vendor/autoload.php';

use Heise\Shariff\Backend;

/**
 * Demo Application using Shariff Backend
 */
class Application
{
    /**
     * Sample configuration
     *
     * @var array
     */
    private static $configuration = [
        'cache' => [
            'ttl' => 60,
            'cacheDir' => '/home/webwze6un/html/jug-hamburg.de/cache/plg_jooag_shariff'
        ],
        'domains' => [
            'jug-hamburg.de',
            'www.jug-hamburg.de'
        ],
        'services' => [
            'GooglePlus',
            'Facebook',
            'LinkedIn',
            'Reddit',
            'StumbleUpon',
            'Flattr',
            'Pinterest',
            'Xing',
            'AddThis'
        ]
    ];

    public static function run()
    {
        $file = file_get_contents(__DIR__ . '/shariff.json');
        $config = json_decode($file, true);

        header('Content-type: application/json');

        $url = isset($_GET['url']) ? $_GET['url'] : '';
        if ($url) {
            $shariff = new Backend($config);
            echo json_encode($shariff->get($url));
        } else {
            echo json_encode(null);
        }
    }
}

Application::run();
