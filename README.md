# baethon/union

Provides utilities to define tagged unions.

# Tagged unions?

I'm not good with words, yet folks from [Folktale](https://folktale.origamitower.com/api/v2.1.0/en/folktale.adt.union.html) describe it nicely:

> Modelling data is important for a range of reasons. From performance to correctness to safety. Tagged unions give you a way of modelling choices that forces the correct handling of them, unlike predicate-based branching, such as the one used by if statements and other common control flow structures.

# Installation

```bash
composer require baethon/union
```

# Usage

To create a tag union it's required to create a class which extends `Baethon\Union\AbstractUnion`.

```php
class Maybe extends \Baethon\Union\AbstractUnion
{
}
```

Also you need to define tags which will represent state of the union.

```php
class Maybe extends \Baethon\Union\AbstractUnion
{
	const SOME = 'Some:x';

	const NONE = 'None';
}
```

## Signatures

Each tag has a definition (called _signature_), which can be defined using following syntax:

```
{Name}[:[param1[, param2[, ...[, paramN]]]]]
```

Parameters define whether a tag will hold any value(s) (called _arguments_). They're optional.

## Working with unions

Union can be invoked using static constructor:

```php
$some = Maybe::Some(1);
```

To work with the state of the union you should use `matchWith()`. It will return the value returned by the matching callback:

```php
function addTen(Maybe $maybe) {
	return $maybe->matchWith([
		'Some' => function ($x) {
			return $x + 10;
		},
		'None' => function () {
			throw new \Exception('Sorry, can\'t add a number to nothing');
		}
	]);
}

addTen($some); // 11
```

`matchWith()` will check if all possible branches are mapped:

* if a tag is not covered by map `UnderflowException` will be thrown
* if map covers more tags than defined ones it will throw 'OverflowException'. 

It's possible to use wildcard map to cover all other cases:

```php
$some->matchWith([
	'*' => function () {
		return 100;
	}
]); // 100
```

You can use defined const values in mapping:

```php
$some->matchWith([
	Maybe::SOME => function () {},
	Maybe::NONE => function () {}
]);
```

# Testing

```
./vendor/bin/phpunit
```
