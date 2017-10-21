<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, KernelAwareContext
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    protected static $routesMapping = [
        "signup" => "fos_user_registration_register"
    ];
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

    protected function nameToRoute($name)
    {
        if(isset(self::$routesMapping[$name]))
        {
            return self::$routesMapping[$name];
        }

        throw new \Exception(sprintf("Page <%s> does not exist!", $name));
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $em = $this->kernel->getContainer()->get("doctrine")->getManager();
        $em->createQuery('DELETE FROM GlobalismUserBundle:User')->execute();
    }

    /**
     * @Given I am on the :page page
     * @param $page
     */
    public function iAmOnThePage($page)
    {
        $this->getSession()->visit($this->generateUrl($this->nameToRoute($page)));
    }

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return RouterInterface
     */
    protected function getRouter()
    {
        return $this->kernel->getContainer()->get('router');
    }

    /**
     * @param string  $route
     * @param array   $parameters
     * @param bool $absolute
     *
     * @return string
     */
    protected function generateUrl($route, array $parameters = [], $absolute = false)
    {
        return $this->locatePath($this->getRouter()->generate($route, $parameters, $absolute));
    }


    /**
     * @AfterStep
     */
    public function takeScreenshotAfterFailedStep($event)
    {
        if ($event->getTestResult()->getResultCode() === \Behat\Testwork\Tester\Result\TestResult::FAILED) {
            $driver = $this->getSession()->getDriver();
            if ($driver instanceof \Behat\Mink\Driver\Selenium2Driver) {
                $stepText = $event->getStep()->getText();
                $fileName = preg_replace('#[^a-zA-Z0-9\._-]#', '', $stepText).'.png';
                $filePath = sys_get_temp_dir();
                $this->saveScreenshot($fileName, $filePath);
                print "Screenshot for '{$stepText}' placed in ".$filePath.DIRECTORY_SEPARATOR.$fileName."\n";
            }else
            {
                $this->showLastResponse();
            }
        }
    }
}
