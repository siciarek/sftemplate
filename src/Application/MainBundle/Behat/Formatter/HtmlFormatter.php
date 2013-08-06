<?php

namespace Application\MainBundle\Behat\Formatter;

use Behat\Behat\Definition\DefinitionInterface,
    Behat\Behat\DataCollector\LoggerDataCollector,
    Behat\Behat\Definition\DefinitionSnippet,
    Behat\Behat\Exception\UndefinedException;

use Behat\Gherkin\Node\AbstractNode,
    Behat\Gherkin\Node\FeatureNode,
    Behat\Gherkin\Node\BackgroundNode,
    Behat\Gherkin\Node\AbstractScenarioNode,
    Behat\Gherkin\Node\OutlineNode,
    Behat\Gherkin\Node\ScenarioNode,
    Behat\Gherkin\Node\StepNode,
    Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

/*
 * This file is extension of the Behat.
 * (c) Jacek Siciarek <siciarek@gmail.com>
 */

/**
 * HTML formatter.
 *
 * @author Jacek Siciarek <siciarek@gmail.com>
 */
class HtmlFormatter extends \Behat\Behat\Formatter\HtmlFormatter
{
    /**
     * Get HTML template style.
     *
     * @return string
     */
    protected function getHtmlTemplateStyle()
    {
        return file_get_contents(__DIR__.'/../../Resources/public/css/behat/layout.css');
    }
}
