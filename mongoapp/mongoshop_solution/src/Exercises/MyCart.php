<?php
namespace Exercises;

use Utilities\Utilities;

/*
 * The class MyCart stores product data in onlineshop.product.
 *
 * @author Martin Harrer <martin.harrer@fh-hagenberg.at>
 */
final class MyCart
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
     * MyCart constructor.
     *
     * Initializes Twig
     * Creates a database handler for the database connection.
     */
    public function __construct($twig)
    {
        $this->twig=$twig;
    }

    /**
     * Validates the user input
     *
     * All fields are required.
     *
     * @return bool Returns nothing
     *
     * Error messages are stored in the array $messages[].
     *
     * Price can be validated with Utilities::isPrice().
     */
    private function isValid(): bool
    {
        if ((count($this->messages) === 0)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns all entries of the table onlineshop.product_category in an array.
     *
     * @return mixed Array that returns rows of onlineshop.product_category. false in case of error
     */
    public function displayForm($route = "/mycart"): void
    {
        $this->twigParams['route'] = $route;
        $this->twigParams['order_items'] = $this->fillCartArray();
        $this->twig->display("mycart.html.twig", $this->twigParams);
    }

    /**
     * Returns all emails of the collection test.users in an array.
     *
     * @return mixed Array that returns rows of test.users. false in case of error
     */
    public function fillCartArray(): array
    {
        $result[]= ['pid' => 1, 'product_name' => 'My favorite Book', 'price' => 10, 'quantity' => 1];
        return $result;
    }

    /**
     * Stores the product data in the table onlineshop.product.
     *
     * @return void Returns nothing
     */
    public function insertOrder(): void
    {
        $this->messages['status'] = "Your order has been processed successfully";
        $this->twigParams['order_items'] = $this->fillCartArray();
        $this->twigParams['messages']= $this->messages;
        $this->twig->display("mycart.html.twig", $this->twigParams);
    }
}
