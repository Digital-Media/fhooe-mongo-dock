<?php

namespace Exercises;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use MongoDB\Client;

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
class MongoDoctrine
{

}