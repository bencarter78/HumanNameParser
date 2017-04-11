<?php

namespace Tck\HumanNameParser;

class Parser
{
    /**
     * @var
     */
    private $string;

    /**
     * @var
     */
    private $leadingInitial = '';

    /**
     * @var
     */
    private $firstName = '';

    /**
     * @var
     */
    private $nicknames = '';

    /**
     * @var
     */
    private $middleName = '';

    /**
     * @var
     */
    private $surname = '';

    /**
     * @var
     */
    private $suffix = '';

    /**
     * @var
     */
    private $suffixes = ['esq', 'esquire', 'jr', 'sr', '2', 'ii', 'iii', 'iv'];

    /**
     * @var
     */
    private $prefixes = [
        'bar', 'ben', 'bin', 'da', 'dal', 'de la', 'de', 'del', 'der', 'di', 'ibn', 'la',
        'le', 'san', 'st', 'ste', 'van', 'van der', 'van den', 'vel', 'von',
    ];

    /**
     * Parser constructor.
     *
     * @param null $string
     */
    public function __construct($string = null)
    {
        $this->setString($string);
        $this->parse();
    }

    /**
     * Sets name string and parses it.
     * Takes Name object or a simple string (converts the string into a Name obj),
     * parses and loads its constituant parts.
     *
     * @param    mixed $string Either a name as a string or as a Name object.
     */
    public function setString($string = null)
    {
        $this->string = new Name($string);
    }

    /**
     * Parse the name into its constituent parts. Sequentially captures each name-part,
     * working in from the ends and trimming the name string as it goes.
     */
    private function parse()
    {
        $this->setNicknames();
        $this->setSuffix();
        $this->string->flip(",");
        $this->setSurname();
        $this->setLeadingInit();
        $this->setFirstName();
        $this->setMiddleName();
    }

    /**
     * @return mixed
     */
    public function leadingInitial()
    {
        return $this->leadingInitial;
    }

    /**
     * @return void
     */
    private function setLeadingInit()
    {
        $regex = "/^(.\.*)(?= \p{L}{2})/"; // note the lookahead, which isn't returned or replaced
        $this->leadingInitial = $this->string->chopWithRegex($regex, 1);
    }

    /**
     * @return mixed
     */
    public function firstName()
    {
        return $this->firstName;
    }

    /**
     * @return void
     */
    private function setFirstName()
    {
        $regex = "/^[^ ]+/";
        $this->firstName = $this->string->chopWithRegex($regex, 0);
    }

    /**
     * @return mixed
     */
    public function nicknames()
    {
        return $this->nicknames;
    }

    /**
     * @return void
     */
    private function setNicknames()
    {
        $regex = "/ ('|\"|\(\"*'*)(.+?)('|\"|\"*'*\)) /"; // names that starts or end w/ an apostrophe break this
        $this->nicknames = $this->string->chopWithRegex($regex, 2);
    }

    /**
     * @return mixed
     */
    public function middleName()
    {
        return $this->middleName;
    }

    /**
     * @return void
     */
    private function setMiddleName()
    {
        $this->middleName = $this->string->getString();
    }

    /**
     * @return mixed
     */
    public function surname()
    {
        return $this->surname;
    }

    /**
     * @return void
     */
    private function setSurname()
    {
        $prefixes = implode(" |", $this->prefixes) . " ";
        $regex = "/(?!^)\b([^ ]+ y |$prefixes)*[^ ]+$/";
        $this->surname = $this->string->chopWithRegex($regex, 0);
    }

    /**
     * @return mixed
     */
    public function suffix()
    {
        return $this->suffix;
    }

    /**
     * @return void
     */
    private function setSuffix()
    {
        $suffixes = implode("\.*|", $this->suffixes) . "\.*";
        $regex = "/,* *($suffixes)$/";
        $this->suffix = $this->string->chopWithRegex($regex, 1);;
    }
}
