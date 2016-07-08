angular.module('trocoWeb')
.config(function ($stateProvider, $urlRouterProvider, $httpProvider){

        $stateProvider
        	.state('app.extension', {
	            abstract: true,
	            url: "extension",
                templateUrl: 'modules/extension/views/index.html',
	        })
            .state('app.extension.list', {
                url: '/list',
                templateUrl: 'modules/extension/views/list.html',
                controller: 'ExtensionController as ExtensionlCntrl',
            })

         
    });