## Illuminate Validator Outside Laravel

### Installation

`composer require xaamin/$validator`

### Usage

````
use Xaamin\Validator\Validator;

$validator = new Validator;
````

##### Database presence verifier

Using the [Illuminate Database Capsule](https://github.com/laravel/framework/tree/master/src/Illuminate/Database) set the database connection instance:

````
$db = $capsule->getDatabaseManager();

$validator->setConnection($db);
````

##### Providing a custom translator

To provide a custom translator pass an instance of `Illuminate\Container\Container` with the translator bound to `translator`.

The translator must implement `Symfony\Component\Translation\TranslatorInterface`.

````
$container = new Illuminate\Container\Container;

$container['translator'] = new CustomTranslator();

$validator = new Validator($container);
````

##### Creating validators

````
$validator = Validator::make(
    [
        'name' => 'John',
        'last_name' => 'Doe'
    ],
    [
        'name' => ['required', 'min:3'],
        'last_name' => ['required', 'min:3']
    ]
);
````

##### Working with error messages

After calling the errors method on a Validator instance, you will receive an `Illuminate\Support\MessageBag` instance, which has a variety of convenient methods for working with error messages.

Retrieving The First Error Message For A Field

To retrieve the first error message for a given field, use the first method:
````
$messages = $validator->errors();

echo $messages->first('email');
````

###### Retrieving all error messages for a field

If you wish to simply retrieve an array of all of the messages for a given field, use the get method:

````
foreach ($messages->get('email') as $message) {
    //
}
````

###### Retrieving all error messages for all fields

To retrieve an array of all messages for all fields, use the all method:

````
foreach ($messages->all() as $message) {
    //
}
````

###### Determining if messages exist for a field

````
if ($messages->has('email')) {
    //
}
````

###### Retrieving an error message with a format

````
echo $messages->first('email', '<p>:message</p>');
Retrieving All Error Messages With A Format

foreach ($messages->all('<li>:message</li>') as $message) {
    //
}
````


See all avalilable rules and methods at [Laravel validations](https://laravel.com/docs/5.0/validation).