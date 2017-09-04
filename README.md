# laravel-eloquent-multilingualization

Add multilingual support to your laravel eloquent models in a breeze

## Installation

### Step 1: Install package

Executing the following command to add the package in your composer.json

```shell
composer require zai/laravel-eloquent-multilingualization
```

For laravel 5.4, add the service provider to app/config/app.php

```php
 Zai\Translate\TranslationServiceProvider::class,
```

For laravel 5.5, because of package auto-discovery, there is **no need** to add service provider to app/config/app.php

### Step 2: Migration

Executing the following commands to add translations table. Only one table is needed for translating any Eloquent models 

```command
php artisan translations:table

php artisan migrate
```

## Usage

## Use Translatable trait in the model you want to translate

Let's say the model is called Article. In the Article model

``` php
use Zai\Translate\Translatable;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use Translatable;
}
```

## Define translatables property in the model

Take the same Article model as example. We want to translate title and body of an article

``` php
protected $translatables = [
    'title',
    'body'
];
```

## Add Translation

Using method: **addTranslation**. It takes an **associate** array as parameter. Simply put the translation data in the array, including which **language** it is using key **locale**.

```php
// create a new article
$article = Article::create([
    'title' => 'Hello',
    'body' => 'laravel is awesome!'
]);

// add translation to the article
$article->addTranslation([
    'locale' => 'fr',
    'title' => 'Bonjour',
    'body' => 'laravel est génial!'
]);
```

The method only adds keys that exist in the translatables property of the model. Any other keys will be **ignored**. If a key in the translatables property is missing in the parameter. It will still be **inserted**, but the value will become **empty string**.

If you submit the translation data in the form. In your ArticleTranslationsController, simply using

```php
public function store(Article $article)
{
    $article->addTranslation(request()->all();
}
```

Remember to include **locale input filed** in your form, so it can be posted in the request. If **addTranslation** method is applied to an **existing** translation, it will **update** the existing translation with values in the parameter.

### Display translation

Using **translation** attribute provided by the trait, e.g, **$article->translation->title**.  It will return correct translation based on what the **current locale** is (the value returned by **App::getLocale()**)

For default locale or if the translation of a locale is missing, it will return the values in the model.

```php
// create a new title
$article = Article::create([
    'title' => 'Hello',
    'body' => 'laravel is awesome!'
]);

// for default locale, or no translations existing
$article->translation->title; // the output is Hello
$article->translation->body;  // the out is laravel is awesome!

// after adding translation
$article->addTranslation([
    'locale' => 'fr',
    'title' => 'Bonjour',
    'body' => 'laravel est génial!'
]);

// set the locale to fr
App::setLocale('fr');

$article->translation->title; // the output is Bonjour
$article->translation->body;  // the out is laravel est génial!

// for a no existing locale
App::setLocale('zh');

$article->translation->title; // the output is Hello
$article->translation->body;  // the out is laravel is awesome!
```

### Update translation

Using method: **updateTranslation**. It takes an **associate array** as parameter. Simply put the updated translation data in the array, including which **language** it is.

``` php
// add translation to the article
$article->updateTranslation([
    'locale' => 'fr',
    'title' => 'updated title',
    'body' => 'updated body content here'
]);
```

If you update the translation using form, in your ArticleTranslationsController

```php
public function update(Article $article)
{
    $article->updateTranslation(request()->all());
}
```

If you update to a translation which **doesn't exist**, it will **insert** a new translation.

### Delete a translation

Using method **deleteTranslation**. It takes a string which specifies which language of the translation you want to delete as parameter.

```php
$article->deleteTranslation('fr');
```

### Delete all translations

Using method **deleteTranslations** which takes no parmaters

```php
$article->deleteTranslations();
```

### Check translations exist

Using method **hasTranslations** which takes no paramters. The method returns boolean to indicate if the record has translations or not.

```php
 $article->hasTranslations();
```

### Check translation of a locale exists

Using method **hasTranslation**. It takes a string as parameter which specifies the locale you are checking. The method returns boolean to indicate if the translation of a specific locale exists or not.

```php
$article->hasTranslation();
```

### Prevent N+1 problem

To prevent N+1 problem, eager load translations in your model

```php
class Article extends Model
{
    use Translatable;

    protected $with = ['translations'];

    protected $translatables = [
        'title',
        'body'
    ];
}
```

### Appending Values To JSON

Add the attribute **translation** to the appends property on the model

```php
class Article extends Model
{
    use Translatable;

    protected $with = ['translations'];

    protected $appends = ['translation'];

    protected $translatables = [
        'title',
        'body'
    ];
}
```
