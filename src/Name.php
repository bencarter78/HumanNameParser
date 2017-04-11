<?php

namespace Tck\HumanNameParser;

class Name
{
    /**
     * Regex patterns to tun the string over
     *
     * @var array
     */
    private $patterns = [
        "#^\s*#u" => "",
        "#\s*$#u" => "",
        "#\s+#u" => " ",
        "#,$#u" => " ",
    ];

    /**
     * @var
     */
    private $string;

    /**
     * Name constructor.
     *
     * @param $string
     */
    function __construct($string)
    {
        $this->setString($string);
    }

    /**
     * Checks encoding, normalizes whitespace/punctuation, and sets the name string.
     *
     * @param String $string a utf8-encoding string.
     * @return Bool True on success
     */
    private function setString($string)
    {
        $this->isUTF8Encoded($string);
        $this->string = $string;
        $this->normalize();
    }

    /**
     * @param $string
     */
    private function isUTF8Encoded($string)
    {
        if ( ! mb_check_encoding($string)) {
            throw new Exception("Name is not encoded in UTF-8");
        }
    }

    /**
     * @return mixed
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * Uses a regex to chop off and return part of the name string
     * There are two parts: first, it returns the matched substring,
     * and then it removes that substring from $this->str and normalizes.
     *
     * @param string  $regex matches the part of the name string to chop off
     * @param integer $index which of the parenthesized submatches to use
     * @param string  $flags optional regex flags
     * @return string    the part of the name string that got chopped off
     */
    public function chopWithRegex($regex, $index = 0, $flags = '')
    {
        // unicode + case-insensitive
        $regex = $regex . "ui" . $flags;
        preg_match($regex, $this->string, $matches);
        $subset = isset($matches[$index]) ? $matches[$index] : '';

        if ($subset) {
            $this->string = preg_replace($regex, ' ', $this->string, -1, $numReplacements);

            if ($numReplacements > 1) {
                throw new Exception("The regex being used to find the name has multiple matches.");
            }

            $this->normalize();

            return $subset;
        }

        return '';
    }

    /**
     * Flips the front and back parts of a name with one another.
     * Front and back are determined by a specified character somewhere in the
     * middle of the string. e.g. O'Malley, Björn
     *
     * @param String $char the character(s) demarcating the two halves you want to flip.
     * @return bool
     */
    public function flip($char)
    {
        $substrings = preg_split("/$char/u", $this->string);

        if (count($substrings) == 2) {
            $this->string = $substrings[1] . " " . $substrings[0];

            return $this->normalize();
        }

        if (count($substrings) > 2) {
            throw new Exception("Can't flip around multiple '$char' characters in name string.");
        }
    }

    /**
     * Removes extra whitespace and punctuation
     * Strips whitespace chars from ends, strips redundant whitespace, converts whitespace chars to " ".
     */
    private function normalize()
    {
        foreach ($this->patterns as $regex => $replacement) {
            $this->string = preg_replace($regex, $replacement, $this->string);
        }
    }
}

?>
