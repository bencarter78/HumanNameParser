# Human Name Parser
Forked from [https://github.com/jasonpriem/HumanNameParser.php]

### Description
Takes human names of arbitrary complexity and various wacky formats like:

* J. Walter Weatherman 
* de la Cruz, Ana M. 
* James C. ('Jimmy') O'Dell, Jr.

and parses out the:

* leading initial (Like "J." in "J. Walter Weatherman")
* first name (or first initial in a name like 'R. Crumb')
* nicknames (like "Jimmy" in "James C. ('Jimmy') O'Dell, Jr.")
* middle names
* surname (including compound ones like "van der Sar' and "Ortega y Gasset"), and
* suffix (like 'Jr.', 'III')

### Example Usage

```php
$parser = new Tck\HumanNameParser\Parser("John Q. Smith");
echo  $parser->surname() . ", " . $parser->firstName();
// returns "Smith, John"
```