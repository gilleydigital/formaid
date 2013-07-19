## Formaid for Kohana 3.3

Simple form generation module for Kohana 3.3.

Setup
----------
Install like any other Kohana module:

1. Clone or download it into your Kohana modules folder.
2. Add it to the Kohana::modules call in bootstrap.php in your application folder.

[See the Kohana documentation for more detailed instructions](http://kohanaframework.org/3.3/guide/kohana/modules)

Usage
----------
Formaid::factory()
	->text('name')->label('Name')
	->submit()
	->render();
