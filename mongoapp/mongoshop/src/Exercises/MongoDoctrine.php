<?php

namespace Exercises;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use MongoDB\Client;

//TODO provide full example
/*
 $loader = require_once('../../vendor/autoload.php');

$loader->add('Documents', __DIR__);
AnnotationRegistry::registerLoader([$loader, 'loadClass']); //TODO change code to not depricated version

$client = new Client('mongodb://mongo:27017', [], ['typeMap' => DocumentManager::CLIENT_TYPEMAP]);
$config = new Configuration();
$config->setProxyDir('../proxies');
$config->setProxyNamespace('Proxies');
$config->setHydratorDir('../hydrators');
$config->setHydratorNamespace('Hydrators');
$config->setMetadataDriverImpl(AnnotationDriver::create('../src/Documents'));
$config->setDefaultDB('test');

$dm = DocumentManager::create($client, $config);
$dm = DocumentManager::create(null, $config);
spl_autoload_register($config->getProxyManagerConfiguration()->getProxyAutoloader());

*/

class MongoDoctrine
{

    /**
     * MongoCRUD constructor.
     *
     * Initializes Twig
     * Creates a database handler for the database connection.
     */
    public function __construct($twig)
    {
        $this->twig=$twig;
        // simply testing the connection
        // $database = (new Client('mongodb://mongo:27017/'))->test;
        // $cursor = $database->command(['ping' => 1]);
        // var_dump($cursor->toArray()[0]);
        // $this->connection = new Client('mongodb://mongo:27017');
        // $this->db_test = $this->connection->test;
        // $this->users = $this->db_test->users;
        // short form, but less flexible,
        // if you handle more than one database or collection within one class/project
        // $this->collection = (new Client('mongodb://mongo:27017'))->test->users;
    }

    public function displayForm(string $route = "/mongodoctrine"): void
    {
        $this->twigParams['route'] = $route;
        $this->twigParams['users'] = $this->fillUsersArray();
        $this->twig->display("mongodoctrine.html.twig", $this->twigParams);
    }

    /**
     * Returns all emails of the collection test.users in an array.
     *
     * @return mixed Array that returns rows of test.users. false in case of error
     */
    public function fillUsersArray(): array
    {
        $result[]= ['email' => 'shopuser1@mongoshop.at', 'name' => 'Shop User1'];
        return $result;
    }

    /**
     * Validate and process user input, sent with a POST request.
     *
     * @return void Returns nothing
     */
    public function insertUser(): void
    {
        $insertOneResult = 1;
        $this->twigParams['messages']['status'] = "Country with _id " . $insertOneResult . " inserted";
        $this->displayForm();
    }
}