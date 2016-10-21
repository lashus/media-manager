<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\DBAL\Types\Type;

use MediaManager\Model\File;
use MediaManager\Model\Library;
use MediaManager\Model\ValueObject\LibraryId;
use MediaManager\Service\LibraryService;
use MediaManager\Factory\FileFactory;

require_once(__DIR__.'/../vendor/autoload.php');

$dbconfig = array(
    'driver' => 'pdo_mysql',
    'host'=>'localhost',
    'port'=>3306,
    'dbname'=>'mediamanager',
    'user'=>'mediamanager',
    'password'=>'mediamanager'
);

Type::addType('library_id', 'MediaManager\Doctrine\Type\LibraryIdType');
Type::addType('library_uuid', 'MediaManager\Doctrine\Type\LibraryUuidType');
Type::addType('file_uuid', 'MediaManager\Doctrine\Type\FileUuidType');

$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/../lib/Doctrine/ORM/"), true);
$conn = \Doctrine\DBAL\DriverManager::getConnection($dbconfig, $config);

$em = EntityManager::create($conn, $config);
$adapter = new Gaufrette\Adapter\Local('tmp/data', true, 0750);
$fs = new \Gaufrette\Filesystem($adapter);

// IMPORTANT - remember to  register custom types for doctrine
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('library_id', 'library_id');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('library_uuid', 'library_uuid');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('file_uuid', 'file_uuid');

$ff = FileFactory::createFactory();
$libraryMetadata = new \Doctrine\ORM\Mapping\ClassMetadata(Library::class);
$fileMetadata = new \Doctrine\ORM\Mapping\ClassMetadata(File::class);

$repo = new \MediaManager\Repository\DoctrineLibraryRepository($em, $libraryMetadata, Library::class, File::class);

$mm = new LibraryService($ff, $repo, LibraryId::class, $fs);

$page = $_GET["p"];
$filters = $_GET['filters'];
$method = $_SERVER["REQUEST_METHOD"];
$resp = 0;

switch($page) {
    case 'add_file':
        $name = $_POST['file_name']; // XSS possible - you should filter all input data
        $library_id = (int)$_POST['file_library']; // XSS possible - you should filter all input data
        $file = $_FILES['file_file'];

        // handle file upload using gaufrette
        $library = $mm->getById(LibraryId::fromString($library_id));

        if(!$library) {
            http_response_code(500);
            $resp = array('error'=>"Library not found.");
        } else {

            $filename = $file['name'];
            $content = file_get_contents($file['tmp_name']);
            $file = FileFactory::create($library, $name, $filename, $content);
            $result = $mm->addFileToLibrary($file, $library);

            if($result) {
                $resp = 1;
            } else {
                http_response_code(500);
                $resp = array('error'=>"Couldn't add file. Check logs.");
            }

        }
        break;
    case 'add_library':
        $name = $_POST['library_name']; // XSS possible - you should filter all input data

        $result = $mm->addLibrary($name, $fs);
        if($result) {
            $resp = 1;
        } else {
            http_response_code(500);
            $resp = array('error'=>"Couldn't add library. Check logs.");
        }
    break;
    case 'library':

        $id = (int)$_GET['id'];
        $library = $mm->getById(LibraryId::fromString($id));

        $resp = array();
        if($library) {
            $dt = new \DateTime();
            $dt->setTimestamp($library->created());
            $libraryFiles = array();
            
            if(!empty($filters)) {
                $tmp = $mm->getFilteredLibraryFiles($library, $filters);
            } else {
                $tmp = $mm->getLibraryFiles($library);
            }

            foreach($tmp as $file) {
                $dtf = new \DateTime();
                $dtf->setTimestamp($file->created());
                $path = '/tmp/data/'.$file->filename();

                $libraryFiles[] = array(
                    'id'=>(string)$file->id(),
                    'name'=>$file->name(),
                    'filename'=>$file->filename(),
                    'size'=>$file->size(),
                    'created'=>$dtf->format('c'),
                    'mimetype'=>$file->mimetype(),
                    'path'=>$path
                );
            }
            
            $resp = array(
                'id'=>(string)$library->id(),
                'name'=>$library->name(),
                'count'=>$library->files()->count(),
                'created'=>$dt->format('c'),
                'files'=>$libraryFiles
            );
        }
    break;
    case 'libraries':

        if(!empty($filters)) {
            $libraries = $mm->getFilteredLibraries($filters);
        } else {
            $libraries = $mm->getLibraries();
        }

        $resp = array();
        if($libraries) {
            foreach($libraries as $lib) {
                $dt = new \DateTime();
                $dt->setTimestamp($lib->created());

                $resp[] = array(
                    'id'=>(string)$lib->id(),
                    'name'=>$lib->name(),
                    'count'=>$lib->files()->count(),
                    'created'=>$dt->format('c')
                );
            }
        }

    break;
    case 'file':

        $id = $_GET['id'];
        $file = $mm->getLibraryFile(\MediaManager\Model\ValueObject\FileUuid::fromString($id));
        $resp = array();

        if($file) {
            $dt = new \DateTime();
            $dt->setTimestamp($file->created());
            $path = '/tmp/data/'.$file->filename();

            $resp = array('file'=>array(
                'id' => (string)$file->id(),
                'name' => $file->name(),
                'filename' => $file->filename(),
                'path' => $path,
                'size' => $file->size(),
                'created' => $dt->format('c'),
                'mimetype' => $file->mimetype(),
            ));

        }
    break;
}

header('Content-type: Application/json');
echo json_encode($resp);