<?php
namespace Exercises;

use Documents\Country;
use Utilities\Utilities;

/*
 * The class Register implements a registration of a user at WebShop.
 *
 * If user credentials are valid, they are stored in the table onlineshop.user.
 *
 * @author Martin Harrer <martin.harrer@fh-hagenberg.at>
 */
final class AddCountry extends Country
{
    /**
     * @var array messages is used to display error and status messages after a form was sent an validated
     */
    private array $messages = [];

    /**
     * @var object country provides a MongoDB ODM object to get and persist documents
     */
    private object $country;

    /**
     * @var object twig provides a Twig object to display hmtl templates
     */
    private object $twig;

    /**
     * @var object dm provides a MongoDB
     */
    private object $dm;

    /**
     * @var array twigParams is used to set variables passed to Twig
     */
    private array $twigParams = [];

    /**
     * Register Constructor.
     *
     * Initializes Twig
     * Creates a database handler for the database connection.
     */
    public function __construct($twig, $dm)
    {
        $this->twig=$twig;
        $this->dm=$dm;
        $this->country= new Country();
    }


    /**
     * Validates the user input
     *
     * name of country and ISOcode are validated with a regex. You can use Utilities::isString() to do so.
     *
     * Error messages are stored in the array $messages[].
     * Calls AddCountry::business() if all input fields are valid.
     *
     * @return void Returns nothing
     */
    public function isValid(): void
    {
        if (Utilities::isEmptyString($_POST['country'])) {
            $this->messages['country'] = "Please enter a country name.";
        }
        if (Utilities::isEmptyString($_POST['isocode'])) {
            $this->messages['isocode'] = "Please enter an isocode with exactly 2 letters for country above.";
        }
        if (!Utilities::isEmptyString($_POST['isocode']) && strlen($_POST['isocode']) !== 2) {
            $this->messages['isocode'] = "Please use exactly 2 letters for isocode.";
        }
        if ((count($this->messages) === 0)) {
            $this->business();
        } else {
            $this->twigParams['country'] = $_POST['country'];
            $this->twigParams['isocode'] = $_POST['isocode'];
            $this->twigParams['countries'] = $this->fillCountry();
            $this->twigParams['messages']= $this->messages;
            $this->twig->display("addcountry.html.twig", $this->twigParams);
        }
    }

    /**
     * Process the user input, sent with a POST request
     *
     * Writes the data with addUser() into table onlineshop.user.
     * On success the user is redirected to index.html.twig.
     *
     * @return void Returns nothing
     */
    protected function business(): void
    {
        $this->addCountry();
        $this->twigParams['countries'] = $this->fillCountry();
        $this->twig->display("addcountry.html.twig", $this->twigParams);
    }

    /**
     * Returns all entries of the table onlineshop.product_category in an array.
     *
     * @return mixed Array that returns rows of onlineshop.product_category. false in case of error
     */
    public function fillCountry(): array
    {
        $result = [];
        //$result = $this->dm->findAll(Country::class);
        return $result;
    }

    /**
     * Stores the data in the table onlineshop.country
     *
     * @return void Returns nothing
     */
    private function addCountry(): void
    {
        $this->country->setName($_POST['country']);
        $this->country->setISOcode($_POST['isocode']);
        $this->dm->flush();
    }
}
