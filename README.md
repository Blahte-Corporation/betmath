# betmath

## Example Usage  

```php
use BlahteSoftware\BetMath\MatchList;

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$matchList = new MatchList(...MatchList::getFootballMatchesArrayFrom2DArray([
    [
        'Manchester United',
        'Real Sociedad'
    ],
    [
        'Arsenal',
        'Brighton'
    ],
    [
        'Chelsea',
        'Hull City'
    ],
    [
        'Liverpool',
        'Leicester'
    ]
]));

echo $matchList->getThreeWayPermutationBetsTable();
```

