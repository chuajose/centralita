angular.module('trocoWeb')
.config(function ($stateProvider, $urlRouterProvider, $httpProvider){

        $stateProvider
        	.state('app.commercial', {
	            abstract: true,
	            url: "commercial",
                templateUrl: 'modules/commercial/views/index.html',
	        })
            .state('app.commercial.list', {
                url: '/list',
                templateUrl: 'modules/commercial/views/list.html',
                controller: 'CommercialController as CommercialCntrl',
            })
            .state('app.commercial.show', {
                url: '/show/:Id',
                templateUrl: 'modules/commercial/views/show.html',
                controller: 'CommercialShowController as CommercialShowCntrl',
            })

         
    });