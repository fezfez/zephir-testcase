# Zephir test case (.zept)

[![Build Status](https://travis-ci.org/fezfez/zephir-testcase.svg)](https://travis-ci.org/fezfez/zephir-testcase)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fezfez/zephir-testcase/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fezfez/zephir-testcase/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/32db2091-313b-4cc7-a4e6-ca77aee9d478/mini.png)](https://insight.sensiolabs.com/projects/32db2091-313b-4cc7-a4e6-ca77aee9d478)
[![Code Coverage](https://scrutinizer-ci.com/g/fezfez/zephir-testcase/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/fezfez/zephir-testcase/?branch=master)

Can run your zephir tests like PHPTestcase (.phpt)

## Install

```
composer require fezfez/zephir-testcase
```


## sample

```
--TEST--
Test that elsif is not evaluated
--FILE--
namespace ZephirBug;

class bug1
{
    public function whatsisMyvar(myvar)
    {
        if is_string(myvar) {
            return "is a var";
        } elseif is_string(myvar[0]) {
            return "is an array and the first element is a string";
        }

        return "unkown";
    }
}
--USAGE--
<?php
use ZephirBug\bug1;

$tmp = new bug1();

var_dump($tmp->whatsisMyvar("a string"));
var_dump($tmp->whatsisMyvar(['a string']));
var_dump($tmp->whatsisMyvar(10));
--EXPECT--
string(8) "is a var"
string(45) "is an array and the first element is a string"
string(6) "unkown"
```

### To run zephir-testcase

This command will run .zept with the compilation output
```
./vendor/bin/zephir-testcase mytestdirectory
```

If you dont want to see the compilation add --silent option
```
./vendor/bin/zephir-testcase mytestdirectory --silent
```
