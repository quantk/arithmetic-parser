# Arithmetic Parser
[![Build Status](https://travis-ci.org/drumser/arithmetic-parser.svg?branch=master)](https://travis-ci.org/drumser/arithmetic-parser)
[![codecov](https://codecov.io/gh/drumser/arithmetic-parser/branch/master/graph/badge.svg)](https://codecov.io/gh/drumser/arithmetic-parser)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/drumser/arithmetic-parser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/drumser/arithmetic-parser/?branch=master)

Simple interpreter of arithmetic expressions

# Usage

### Simple arithmetic operations with operation priority 
```php
<?php
//1.0
$result = \App\Interpreter::evaluate('(1.0 + 2.0 * 3.0 / ( 6.0*6.0 + 5.0*44.0)) - 0.0234375');
?>
```

### Simple functions such as sqrt (also supported cos,sin,tan)
```php
<?php
//2.0
$result = \App\Interpreter::evaluate('sqrt(4)');
//3.0
$result = \App\Interpreter::evaluate('sqrt(2+2*2+3)');
?>
```

### Power operator
```php
<?php
//32
$result = \App\Interpreter::evaluate('2**5');
//32
$result = \App\Interpreter::evaluate('2^5');
//36
$result = \App\Interpreter::evaluate('(2+2*2)^2');
?>
```