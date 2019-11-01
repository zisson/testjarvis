<?php
use Behat\Behat\Context\Context;
use \Behat\MinkExtension\Context\MinkContext;
/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class WebContext extends MinkContext
{

    /**
     * @When /^I wait for (\d+) secondes$/
     */
    public function iWaitForSecondes($arg1)
    {
        $this->getSession()->wait($arg1 * 1000);
    }
}