<?php
namespace Exercises;

use Utilities\Utilities;

/*
 * The class Register implements a registration of a user at WebShop.
 *
 * If user credentials are valid, they are stored in the table onlineshop.user.
 *
 * @author Martin Harrer <martin.harrer@fh-hagenberg.at>
 */
final class Countries
{
    /**
     * @var array messages is used to display error and status messages after a form was sent an validated
     */
    private array $messages = [];

    /**
     * @var object countries provides a object to access the collection countries.
     */
    private object $countries;

    /**
     * @var object twig provides a Twig object to display hmtl templates
     */
    private object $twig;

    /**
     * @var array twigParams is used to set variables passed to Twig
     */
    private array $twigParams = [];

    /**
     * Countries Constructor.
     *
     * Initializes Twig
     * Creates a database handler for the database connection.
     */
    public function __construct($twig)
    {
        $this->twig=$twig;
    }

    public function displayForm(string $route = "/createcountry"): void
    {
        $this->twigParams['route'] = $route;
        $this->twigParams['countries'] = $this->fillCountryArray();
        $this->twig->display("countries.html.twig", $this->twigParams);
    }

    /**
     * Returns all entries of the table onlineshop.product_category in an array.
     *
     * @return mixed Array that returns rows of onlineshop.product_category. false in case of error
     */
    private function fillCountryArray(): array
    {
        $result[]= ['name' => 'Homeland', 'ISOcode' => 'HL'];
        return $result;
    }

    /**
     * Stores the data in the table onlineshop.country
     *
     * @return void Returns nothing
     */
    public function insertCountry(): void
    {
        $insertOneResult = 1;
        $this->twigParams['messages']['status'] = "Country with _id " . $insertOneResult . " inserted";
        $this->twigParams['countries'] = $this->fillCountryArray();
        $this->twig->display("countries.html.twig", $this->twigParams);
    }

    /**
     * Validates the user input
     *
     * name of country and ISOcode are validated with a regex. You can use Utilities::isString() to do so.
     *
     * Error messages are stored in the array $messages[].
     *
     * @return bool Returns true if user input is valid, otherwise false.
     */
    private function isValid(): bool
    {
        if ((count($this->messages) === 0)) {
            return true;
        } else {
            $this->twigParams['messages'] = $this->messages;
            return false;
        }
    }
}
