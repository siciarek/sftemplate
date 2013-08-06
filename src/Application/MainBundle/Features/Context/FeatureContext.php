<?php
namespace Application\MainBundle\Features\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\Mink\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Application\MainBundle\Entity as E;

define('BEHAT_ERROR_REPORTING', E_ERROR | E_WARNING | E_PARSE);

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';


class FeatureContext extends MinkContext implements KernelAwareInterface
{
    /**
     * @var Container $container
     * @var Kernel $kernel
     * @var \Doctrine\ORM\EntityManager $em
     * @var Session $session
     */
    private $kernel, $container, $em, $parameters, $session;


    /**
     * Switches to specific iFrame.
     *
     * @param string $name iframe name (null for switching back)
     */
    public function switchToIFrame($name = null)
    {
        $driver = $this->getSession()->getDriver();

        if (!($driver instanceof Selenium2Driver)) {
            return;
        }

        $driver->switchToIFrame($name);
    }

    /**
     * @Given /^(?:że )?wykonam skrypt "([^"]*)"$/
     */
    public function executeScript($script)
    {
        $driver = $this->getSession('selenium2')->getDriver();

        if (!($driver instanceof Selenium2Driver)) {
            return;
        }

        $driver->executeScript($script);
    }

    /**
     * @Given /^(?:że )?usunę wszystki(?:e|ch) "([^"]*)"$/
     */
    public function usuneWszystkieElementy($elem)
    {
        $map = array(
            'załączniki' => 'Attachment',
            'notatki' => 'Note',
        );

        if (!array_key_exists($elem, $map)) {
            throw new \Exception('W metodzie ' . __METHOD__ . "\n" . ' brak zdefiniowanej mapy klasy dla "' . $elem . '", ' . json_encode($map));
        }

        /**
         * @var EntityManager $em
         */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $attachments = $em->getRepository('ApplicationMainBundle:' . $map[$elem])->findAll();
        foreach ($attachments as $att) {
            $em->remove($att);
        }
        $em->flush();
    }

    /**
     * @Given /^nie zobaczę dłuższego tekstu$/
     */
    public function nieZobaczeDluzszegoTekstu(PyStringNode $string)
    {
        $this->assertResponseNotContains($string->getRaw());
    }


    /**
     * @Given /^zobaczę dłuższy tekst$/
     */
    public function nieZobaczeDluzszyTekst(PyStringNode $string)
    {
        $this->assertResponseContains($string->getRaw());
    }

    /**
     * @Given /^wypełnię pole edytora tekstem$/
     */
    public function wypelniePoleEdytoraTekstem(PyStringNode $string)
    {
        $text = $string->getRaw();
        $text = preg_replace('/\n/', '\n', trim($text));

        $script = 'jQuery("iframe").contents().find(".cke_editable.cke_editable_themed").find("*").remove();';
        $script .= 'jQuery("iframe").contents().find(".cke_editable.cke_editable_themed").append("' . $text . '");';

        $script = trim($script);
        $this->executeScript($script);
    }

    /**
     * @Given /^wypełnię pole "([^"]*)" tekstem$/
     */
    public function wypelniePoleTekstem($field, PyStringNode $string)
    {
        $this->fillField($field, $string->getRaw());
    }


    /**
     * @Given /^lista "([^"]*)" powinna mieć wybraną opcję o wartości "([^"]*)"$/
     */
    public function listaPowinnaZawieracTekst($locator, $value)
    {
        $session = $this->getSession(); // get the mink session
        $page = $session->getPage();
        $fieldElement = $page->find('named',
            array('select', $this->getSession()->getSelectorsHandler()->xpathLiteral($locator))
        );

        assertEquals($value, $fieldElement->getValue());
    }

    /**
     * Click on the element with the provided xpath query
     *
     * @When /^kliknę na link xpath "([^"]*)"$/
     */
    public function klikneNaLinkXpath($xpath)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', $xpath)
        ); // runs the actual query and returns the element

        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate XPath: "%s"', $xpath));
        }

        // ok, let's click on it
        $element->click();
    }

    /**
     * @Given /^wypełnię pole "([^"]*)" prawidłowym emailem$/
     */
    public function wypelniePolePrawidlowymEmailem($field)
    {
        $value = 'user' . time() . '@example.com';
        $this->fillField($field, $value);
    }


    /**
     * @Given /^obiekt (\d+) powienien zwrócić (\d+) element(?:y|ów|) po wywołaniu metody "([^"]*)"$/
     */
    public function obiektPowienienZwrocicElementyPoWywolaniuMetody($index, $count, $methods)
    {
        $refresh = array(
            'Application\MainBundle\Entity\Contact',
        );

        $obj = $this->data['objects'][$index];
        $this->em->refresh($obj);

        $methods = preg_replace('/\(\)/', '', $methods);
        $methods = preg_replace('/\W+/', ';', $methods);

        $m = explode(';', $methods);

        $given = $obj;

        foreach ($m as $method) {
            $given = $given->$method();
            if (in_array(get_class($given), $refresh)) {
                $this->em->refresh($given);
            }
        }

        assertEquals($count, $given->count());
    }

    /**
     * @Given /^(?:że )?mam utworzony nowy obiekt typu "([^"]*)"$/
     */
    public function zeMamUtworzonyNowyObiektTypu($type)
    {
        switch ($type) {
            case "Contact":
                $obj = new E\Contact();
                break;
            case "Client":
                $obj = new E\Client();
                $obj->setName('Mink Test Client ' . time());
                $obj->setType('individual');
                $obj->setClientNumber($this->container->get('app.main.client.number.generator'));

                $places = $this->em->getRepository('ApplicationMainBundle:Place')
                    ->createQueryBuilder('p')
                    ->select('p')
                    ->setMaxResults(30)
                    ->getQuery()->getResult();

                $address = new E\Address();
                $address->setPlace($places[array_rand($places)]);
                $obj->setAddress($address);

                $contact = new E\Contact();
                $contact->addEntry(new E\ContactEntry('phone', '+48 345 684 321'));
                $obj->setContact($contact);

                break;
            default:
                assertTrue(false, "Class " . $type . " is not supported");
                break;
        }

        $this->em->persist($obj);
        $this->em->flush();
        $this->dodalemOstationObiektTypu($type);
    }

    /**
     * @Given /^(?:że )?dodałem ostatnio obiekt typu "([^"]*)"$/
     */
    public function dodalemOstationObiektTypu($type)
    {
        $query = $this->em->getRepository('ApplicationMainBundle:' . $type)
            ->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery();

        $obj = $query->getSingleResult();
        assertNotNull($obj);
        $this->em->refresh($obj);
        $this->data['id'] = $obj->getId();

        switch ($type) {
            case "Client":
                $this->data['client_number'] = $obj->getClientNumber();
                $this->data['slug'] = $obj->getSlug();
                break;
            case "Contact":
                break;
        }

        $this->data['objects'][] = $obj;
    }

    /**
     * @Given /^(?:że )?odwiedzę stronę zawierającą formularz edycji "([^"]*)"$/
     */
    public function odwiedzeStroneZawierajacaFormularzEdycji($page)
    {
        $match = array();
        preg_match_all('/__(\w+?)__/', $page, $match);

        $arguments = $match[0];
        $keys = array_map('strtolower', $match[1]);

        for ($i = 0; $i < count($arguments); $i++) {
            $page = preg_replace('/' . $arguments[$i] . '/', $this->data[$keys[$i]], $page);
        }

        $this->getSession()->visit($this->locatePath($page));
    }


    /**
     * @Given /^poczekam aż strona się załaduje$/
     */
    public function poczekamAzStronaSieZaladuje()
    {
        $seconds = 30;
        /**
         * @var Session $ses
         */
        $ses = $this->getMink()->getSession();

        $driver = $this->getSession()->getDriver();

        if (!($driver instanceof Selenium2Driver)) {
            return;
        }
        $ses->wait($seconds * 1000, 'typeof window.jQuery == "function"');
    }

    /**
     * Selects option in select field with specified id|name|label|value.
     *
     * @When /^wybiorę opcję "(?P<option>(?:[^"]|\\")*)" z listy "(?P<select>(?:[^"]|\\")*)"$/
     */
    public function selectOptionFromList($select, $option)
    {
        $select = $this->fixStepArgument($select);
        $option = $this->fixStepArgument($option);

        $page = $this->getSession()->getPage();

        $field = $page->findField($select);

        if (null === $field) {
            throw new ElementNotFoundException(
                $this->getSession(), 'form field', 'id|name|label|value', $select
            );
        }

        $multiple = false;

        // ------------------------------------------

        // rozwinięcie metody:
        // $field->selectOption($option, false);

        if ('select' !== $field->getTagName()) {
            $field->getSession()->getDriver()->selectOption($field->getXpath(), $option, $multiple);

            return;
        }


        $items = $field->findAll('named', array(
            'option', $this->xpathLiteral($option)
        ));

        foreach ($items as $it) {
            if ($it->getText() === $option) {
                $opt = $it;
            }
        }

        if (null === $opt) {
            throw new ElementNotFoundException(
                $field->getSession(), 'select option', 'value|text', $option
            );
        }

        $field->getSession()->getDriver()->selectOption(
            $field->getXpath(), $opt->getValue(), $multiple
        );
    }


    /**
     * Take screenshot when step fails.
     * Works only with Selenium2Driver.
     *
     * @AfterStep
     */
    public function takeScreenshotAfterFailedStep($event)
    {
        if (4 === $event->getResult()) {
            $driver = $this->getSession()->getDriver();

            if (!($driver instanceof Selenium2Driver)) {
//                throw new UnsupportedDriverActionException('Taking screenshots is not supported by %s, use Selenium2Driver instead.', $driver);
                return;
            }

            $screenshotDir = $this->kernel->getRootDir() . '/../temp/behat/';

            if (!file_exists($screenshotDir)) {
                mkdir($screenshotDir, 0777, true);
            }

            $screenshotFile = $screenshotDir . microtime(true) . '.png';

            $screenshot = $driver->getWebDriverSession()->screenshot();
            file_put_contents($screenshotFile, base64_decode($screenshot));
        }
    }

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }


    /**
     * @Given /^kliknę guzik "([^"]*)"$/
     */
    public function klikneGuzik($button)
    {
        $this->pressButton($button);
    }


    public function getSymfonyProfile()
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof SymfonyDriver) {
            throw new UnsupportedDriverActionException(
                'You need to tag the scenario with ' .
                '"@mink:symfony". Using the profiler is not ' .
                'supported by %s', $driver
            );
        }

        $profile = $driver->getClient()->getProfile();
        if (false === $profile) {
            throw new \RuntimeException(
                'Emails cannot be tested as the profiler is ' .
                'disabled.'
            );
        }

        return $profile;
    }

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @Given /^sprawdzę jej zawartość$/
     */
    public function sprawdzeJejZawartosc()
    {
        $content = $this->getSession()->getPage()->getContent();
        $this->data = json_decode($content, true);
    }

    /**
     * Sprawdzam czy dane zwaracane są poprawnie
     *
     * @Then /^powinna zawierać dane zasobów$/
     */
    public function powinnaZawieracDaneZasobow()
    {
        assertTrue(is_array($this->data));
        assertArrayHasKey("type", $this->data);
        assertEquals("data", $this->data["type"]);

        assertArrayHasKey("success", $this->data);
        assertArrayHasKey("data", $this->data);
    }

    /**
     * Waiting
     *
     * @Given /^I wait (\d+) seconds$/
     */
    public function iWaitSeconds($seconds)
    {
        $this->getMink()->getSession()->wait($seconds * 1000);
    }

    /**
     *
     * @Given /^poczekam (\d+) sekund(y|ę)?$/
     */
    public function poczekamSekund($arg1)
    {
        $this->iWaitSeconds($arg1);
    }

    /**
     * @Given /^strona powinna posiadać tytuł "([^"]*)"$/
     */
    public function stronaPowinnaPosiadacTytul($title)
    {
        $selector = "/head/title";
        $this->assertSelector($selector, $title);
    }

    protected function assertSelector($selector, $value = null)
    {
        $xpath = $this->getSession()->getSelectorsHandler()->selectorToXpath('xpath', $selector);
        $element = $this->getSession()->getPage()->find('xpath', $xpath);
        assertNotNull($element);
        if ($value !== null) {
            assertEquals($value, $element->getHtml());
        }
    }

    /**
     * Creates a file with specified name and context in current workdir.
     *
     * @Given /^(?:there is )?a file named "([^"]*)" with:$/
     *
     * @param   string $filename   name of the file (relative path)
     * @param   PyStringNode $content    PyString string instance
     */
    public function aFileNamedWith($filename, PyStringNode $content)
    {
        file_put_contents($filename, $content->getRaw());
    }


    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->kernel->boot();
        $this->container = $this->kernel->getContainer();
        $this->em = $this->container->get("doctrine.orm.entity_manager");
    }

    protected $username;
    protected $user;

    /**
     * @Given /^I have a user with username "([^"]*)"$/
     */
    public function iHaveAUserWithUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @When /^I search for that user in database$/
     */
    public function iSearchForThatUserInDatabase()
    {
        $this->user = $this->em->getRepository("ApplicationSonataUserBundle:User")->findOneByUsername($this->username);
    }

    /**
     * @Then /^I should find him$/
     */
    public function iShouldFindHim()
    {
        assertInstanceOf('Application\Sonata\UserBundle\Entity\User', $this->user);
        assertEquals("admin", $this->user->getUsername());
    }


    /**
     * @Given /^I am in a directory "([^"]*)"$/
     */
    public function iAmInADirectory($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        chdir($dir);
    }

    /** @Given /^I have a file named "([^"]*)"$/ */
    public function iHaveAFileNamed($file)
    {
        touch($file);
    }

    /** @When /^I run "([^"]*)"$/ */
    public function iRun($command)
    {
        exec($command, $output);
        $this->output = trim(implode("\n", $output));
    }


    /** @Then /^I should get:$/ */
    public function iShouldGet(PyStringNode $string)
    {
        assertEquals($string->getRaw(), $this->output, "Actual output is:\n" . $this->output);
    }


    protected $numbers = array();
    protected $result = 0;

    /**
     * @Given /^wprowadzenie do kalkulatora liczby (\d+)$/
     */
    public function wprowadzenieDoKalkulatoraLiczby($number)
    {
        $this->numbers[] = $number;
    }

    /**
     * @When /^nacisnę (.+)$/
     */
    public function nacisne($guzik)
    {
        switch ($guzik) {
            case "dodaj":
                $this->result = array_sum($this->numbers);
                break;
            case "mnóż":
                $this->result = array_product($this->numbers);
                break;
        }
    }

    /**
     * @Then /^rezultat (\d+) wyświetli się na ekranie$/
     */
    public function rezultatWyswietliSieNaEkranie($result)
    {
        assertEquals($result, $this->result);
    }

    /**
     * @Given /^nazwę użytkownika "([^"]*)"$/
     */
    public function nazweUzytkownika($arg1)
    {
        $this->iHaveAUserWithUsername($arg1);
    }

    /**
     * @Given /^przeszukuję listę użytkowników$/
     */
    public function przeszukujeListeUzytkownikow()
    {
        $this->iSearchForThatUserInDatabase();
    }

    /**
     * @Given /^powinienem go znaleźć na tej liście$/
     */
    public function powinienemGoZnalezcNaTejLiscie()
    {
        $this->iShouldFindHim();
    }

    /**
     * @Given /^poczekam na listę z podpowiedziami$/
     */
    public function poczekamNaListeZPodpowiedziami()
    {
        $timeout = 5 * 1000;
        $condition = "$('.ui-autocomplete:visible').children().length > 0";

        $this->getMink()->getSession()->wait($timeout, $condition);
    }

    /**
     * @Given /^zobaczę na liscie z podpowiedziami "([^"]*)"$/
     */
    public function zobaczeNaLiscieZPodpowiedziami($regexp)
    {
        $script = "return /$regexp/.test($('.ui-autocomplete:visible').html())";
        $found = $this->getMink()->getSession()->evaluateScript($script);
        assert($found);
    }


    protected function xpathLiteral($s)
    {
        if (false === strpos($s, "'")) {
            return sprintf("'%s'", $s);
        }

        if (false === strpos($s, '"')) {
            return sprintf('"%s"', $s);
        }

        $string = $s;

        $parts = array();
        while (true) {
            if (false !== $pos = strpos($string, "'")) {
                $parts[] = sprintf("'%s'", substr($string, 0, $pos));
                $parts[] = "\"'\"";
                $string = substr($string, $pos + 1);
            } else {
                $parts[] = "'$string'";
                break;
            }
        }

        return sprintf("concat(%s)", implode($parts, ','));
    }
}
