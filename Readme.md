## Famelo.Features

This Package provides a Service and a ViewHelper to help you roll out new features
easily.

## Creating a new Feature

Features are specified in a separate Configuration file called "Features.yaml"

Here's an example:


```yaml
-
  name: OnyForAdminstators
  condition: hasRole('My.Package:Administrator')

-
  name: OnlyForMneuhaus
  condition: isUser('mneuhaus')

-
  name: OnlyAfterThisDate
  condition: afterDate('22.11.2013')

-
  name: onlyGuests
  condition: isGuest()

-
  name: onlyLoggedIn
  condition: isNotGuest()

-
  name: only25PercentOfTheUsers
  condition: userPercentage(25)

-
  name: onlyWithMatchingClientIp
  condition: clientIp('127.0.0.1')

-
  name: combination
  condition: hasRole('My.Package:Administrator') && userPercentage(25)

-
  name: combination2
  condition: hasRole('My.Package:Administrator') || afterDate('22.11.2013')
```

## Using the FeatureService

you can easily inject the featureService like this:

```php
	/**
	 * The featureService
	 *
	 * @var \Famelo\Features\FeatureService
	 * @Flow\Inject
	 */
	protected $featureService;
```

Then you can ask it if the feature you want to act upon is activated for the current
user:

```php
if ($this->featureService->isFeatureActive("myFeature")) {
	do some cool stuff
}
```

## Using the ActiveViewHelper

For convenience there is a wrapper for the featureService for fluid:

```html
{namespace feature=Famelo\Features\ViewHelpers}
<feature:active feature="myFeature">
	show my cool feature
</feature:active>
```

alternatively you can use it like the ifViewHelper with then/else

```html
<feature:active feature="myFeature">
	<f:then>
		show my cool feature
	</f:then>
	<f:else>
		show some other stuff
	</f:else>
</feature:active>
```

## Settings

```yaml
Famelo:
  Features:
    # You can change this setting to use your own ConditionMatcher with more specific functions
    # you might need
    conditionMatcher: \Famelo\Features\Core\ConditionMatcher

    # What should happen if the service is asked about a feature it doesn't now?
    # can be: active, inactive, exception
    # exception is the default and will throw an exception because you probably
    # mistyped some feature name
    noMatchBehavior: exception
```

## Additional ConditionMatchers

You can add as many custom conditionMatches as you like. All you have to do
is implement a ConditionMatcher based on the "\Famelo\Features\Core\ConditionMatcherInterface".
You can add as many methods and injected properties as you like. Any method of that
class will be available as an eel method under a variable called as the NAME
constant you specified. You can add any number of parameters for the methods as well.

**Example:**

```php
<?php
namespace My\Package\Features;
use TYPO3\Flow\Annotations as Flow;

class MyConditionMatcher implements \Famelo\Features\Core\ConditionMatcherInterface{
    /**
     * contains short name for this matcher used
     * for reference in the eel expression
     */
    const NAME = 'my';

    /**
     * @param string $foo
     * @return boolean
     */
    public function someCondition($foo) {
        return $foo == 'bar';
    }

}
```

This matcher will then be available to be used like this automatically:

```
-
  name: MyCustomMatcher
  condition: my.someCondition('bar')
  # will be true

-
  name: MyCustomMatcher2
  condition: my.someCondition('guz')
  # will be false
```