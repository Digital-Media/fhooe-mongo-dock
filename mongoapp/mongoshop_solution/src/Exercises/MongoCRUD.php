<?php
namespace Exercises;

use Utilities\Utilities;
use MongoDB\Client;

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
     * @var object twig provides a Twig object to display hmtl templates
     */
    private object $collection;

    /**
     * @var array twigParams is used to set variables passed to Twig
     */
    private array $twigParams = [];

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
        $this->collection = (new Client('mongodb://mongo:27017'))->test->users;
    }

    /**
     * Validates the user input
     *
     * email and password are required fields.
     * The combination of email + password is checked against database in @see Login::authenitcateUser()
     *
     * Error messages are stored in the array $messages[].
     * Calls MongoCRUD::business() if all input fields are valid.
     *
     * @return void Returns nothing
     */
    public function isValid(): void
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
                $this->business();
        } else {
            echo "ich war hier";
            $this->twigParams['email'] = $_POST['email'];
            $this->twigParams['name'] = $_POST['name'];
            $this->twigParams['messages'] = $this->messages;
            $this->twig->display("mongocrud.html.twig", $this->twigParams);
        }
    }

    /**
     * Process the user input, sent with a POST request
     *
     * @return void Returns nothing
     */
    protected function business(): void
    {
        $insertOneResult = $this->collection->insertOne([
            'role' => 'admin',
            'email' => $_POST['email'],
            'name' => $_POST['name'],
        ]);
        $this->twigParams['emails'] = $this->fillEmails();
        $this->twig->display("mongocrud.html.twig", $this->twigParams);
    }

    /**
     * Returns all emails of the table onlineshop.users in an array.
     *
     * @return mixed Array that returns rows of onlineshop.product_category. false in case of error
     */
    public function fillEmails(): array
    {
        $result = [];
        $emails = $this->collection->find(
            [
                'role' => 'admin',
            ],
            [
                'projection' => [
                    'email' => 1,
                ]
            ]);
        foreach ($emails as $document) {
            $result[]['email'] = $document['email'];
        }
        return $result;
    }

}
