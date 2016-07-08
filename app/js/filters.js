trocoWeb.filter('secondsToDateTime', [function() {
    return function(seconds) {
    	if(!seconds) seconds = 0;
        return new Date(1970, 0, 1).setSeconds(seconds);
    };
}])