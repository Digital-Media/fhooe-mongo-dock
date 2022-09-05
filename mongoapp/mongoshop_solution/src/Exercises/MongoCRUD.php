<?php
namespace Exercises;

use Utilities\Utilities;
use MongoDB\Client;
use MongoDB\BSON\ObjectId;

/**
 * The class MongoCRUD implements basic CRUD operations against MongoDB.
 *
 * User credentials are written to test.users
 *
 * @author  Martin Harrer <martin.harrer@fh-hagenberg.at>
 */
final class MongoCRUD
{
    /**
     * @var array messages is used to display error and status messages after a form was sent an validated
     */
    private array $messages = [];

    /**
     * @var object twig provides a Twig object to display hmtl templates
     */
    private object $twig;

    /**
     * @var array twigParams is used to set variables passed to Twig
     */
    private array $twigParams = [];

    /**
     * @var object users provides a object a connection to mongodb.
     */
    private object $connection;

    /**
     * @var object db_test provides a object to access the database test.
     */
    private object $db_test;

    /**
     * @var object users provides a object to access the collection users.
     */
    private object $users;

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
        $this->connection = new Client('mongodb://mongo:27017');
        $this->db_test = $this->connection->test;
        $this->users = $this->db_test->users;
        // short form, but less flexible,
        // if you handle more than one database or collection within one class/project
        // $this->collection = (new Client('mongodb://mongo:27017'))->test->users;
    }

    public function displayForm(string $route = "/createuser"): void
    {
        $this->twigParams['route'] = $route;
        $this->twigParams['users'] = $this->fillUsersArray();
        $this->twig->display("mongocrud.html.twig", $this->twigParams);
    }

    /**
     * Validate and process user input, sent with a POST request.
     *
     * @return void Returns nothing
     */
    public function insertUser(): void
    {
        if ($this->isValid()) {
            $insertOneResult = $this->users->insertOne([
            'role' => 'admin',
            'email' => $_POST['email'],
            'name' => $_POST['name'],
            ]);
            $this->twigParams['messages']['status'] = "Document with _id " . $insertOneResult->getInsertedId() . " inserted";
        } else {
            $this->twigParams['email'] = $_POST['email'];
            $this->twigParams['name'] = $_POST['name'];
        }
        $this->displayForm();
    }

    /**
     * Process the user input, sent with a POST request
     *
     * @return void Returns nothing
     */
    public function updateUser(): void
    {
        if (isset ($_GET['uid'])) {
            $this->twigParams['uid']['oid']= $_GET['uid'];
            $form_fields = $this->getUserFields();
            $this->twigParams['email'] = $form_fields['email'];
            $this->twigParams['name'] = $form_fields['name'];
            $this->displayForm("/updateuser");
        } else {
            $uid= array_keys($_POST['uid']);
            if ($this->isValid()) {
                $updateResult = $this->users->updateOne(
                    ['_id' => new ObjectId($uid[0])],
                    ['$set' => ['email' => $_POST['email'], 'name' => $_POST['name']]]
                );
                $this->twigParams['messages']['status'] = $updateResult->getMatchedCount() . " document updated";
                $this->displayForm();
            } else {
                $this->twigParams['uid']['oid']= $uid[0];
                $this->twigParams['email'] = $_POST['email'];
                $this->twigParams['name'] = $_POST['name'];
                $this->displayForm("/updateuser");
            }

        }
    }

    /**
     * Returns all emails of the collection test.users in an array.
     *
     * @return mixed Array that returns rows of test.users. false in case of error
     */
    private function fillUsersArray(): array
    {
        $users = $this->users->find(
            [
            ],
            [
                'projection' => [
                    'email' => 1,
                ]
            ]);
        return iterator_to_array($users);
    }

    /**
     * Returns all keys of the collection test.users in an array.
     *
     * @return mixed Array that returns rows of test.users. false in case of error
     */
    private function getUserFields(): array
    {
        $result = [];
        $user = $this->users->findOne(
            [
                '_id' => new ObjectId($_GET['uid']),
            ],
            [
                'projection' => [
                    'email' => 1,
                    'name' => 1,
                ]
            ]);
        $result['userid'] = $user->_id;
        $result['email'] = $user->email;
        $result['name'] = $user->name;
        return $result;
    }

    /**
     * Deletes an user identified by his email from the collection test.users.
     *
     * @return mixed Array that returns rows of onlineshop.product_category. false in case of error
     */
    public function deleteUser(): void
    {
        $deleteResult = $this->users->deleteOne(
            [
                '_id' => new ObjectId($_GET['uid']),
            ]);
        $this->twigParams['messages']['status'] = $deleteResult->getDeletedCount() . " document deleted";
        $this->displayForm();
    }

    /**
     * Validates the user input
     *
     * email and password are required fields.
     * It checks if email is a valid email.
     *
     * Error messages are stored in the array $messages[].
     *
     * @return bool Returns true if user input is valid, otherwise false.
     */
    private function isValid(): bool
    {
        if (Utilities::isEmptyString($_POST['email'])) {
            $this->messages['email'] = "Please enter your email.";
        }
        if (!Utilities::isEmptyString($_POST['email']) && !Utilities::isEmail($_POST['email'])) {
            $this->messages['email'] = "Please enter a valid email.";
        }
        if (Utilities::isEmptyString($_POST['name'])) {
            $this->messages['name'] = "Please enter your name.";
        }
        if ((count($this->messages) === 0)) {
            return true;
        } else {
            $this->twigParams['messages'] = $this->messages;
            return false;
        }
    }
}
