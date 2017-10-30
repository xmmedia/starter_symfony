<?php

use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class BaseContext extends MinkContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given take screenshot
     */
    public function takeScreenshot()
    {
        $filePath = './var/screenshots';
        if (!is_dir($filePath)) {
            mkdir($filePath);
        }

        $this->saveScreenshot(null, $filePath);

        echo 'Took screenshot; saved in: '.$filePath;
    }

    /**
     * @AfterStep
     */
    public function takeScreenshotAfterFailedStep(AfterStepScope $scope)
    {
        if (99 === $scope->getTestResult()->getResultCode()) {
            $this->takeScreenshot();
        }
    }

    /**
     * @param closure $lambda
     * @param int     $wait
     *
     * @return bool
     * @throws Exception
     */
    public function spin($lambda, $wait = 15)
    {
        for ($i = 0; $i < $wait; $i++)
        {
            try {
                if ($lambda($this)) {
                    return true;
                }
            } catch (Exception $e) {
                // do nothing
            }

            sleep(1);
        }

        throw new Exception('Timed out while spinning.');
    }
}
