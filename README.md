# Arithmetic Parser
[![Build Status](https://travis-ci.org/drumser/arithmetic-parser.svg?branch=master)](https://travis-ci.org/drumser/arithmetic-parser)
[![codecov](https://codecov.io/gh/drumser/arithmetic-parser/branch/master/graph/badge.svg)](https://codecov.io/gh/drumser/arithmetic-parser)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/drumser/arithmetic-parser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/drumser/arithmetic-parser/?branch=master)

Simple interpreter of arithmetic expressions

# Usage
```php
<?php
$lexer = new \App\Lexer('(1.0 + 2.0 * 3.0 / ( 6.0*6.0 + 5.0*44.0)) - 0.0234375');
$parser = new \App\Parser($lexer);
$visitor = new \App\Visitor();
$interpreter = new \App\Interpreter($parser, $visitor);

$result = $interpreter->interpr(); //1.0
?>
```