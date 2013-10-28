## Famelo.Features

This Package provides a Service and a ViewHelper to help you roll out new features
easily.

## Creating a new Feature

Features are specified in a seperate Configurtion file called "Features.yaml"

Here's an example:


```
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

```
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

```
if ($this->featureService->isFeatureActive("myFeature")) {
	do some cool stuff
}
```

## Using the ActiveViewHelper

For convenience theres a wrapper for the featureService for fluid:

```
<feature:active feature="myFeature">
    show my cool feature
</feature:active>
```

alternatively you can use it like the ifViewHelper with then/else

```
<feature:active feature="myFeature">
	<then>
    	show my cool feature
    </then>
    <else>
    	show some other suff
    </else>
</feature:active>
```