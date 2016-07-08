angular.module('trocoWeb')
.config(function ($stateProvider, $urlRouterProvider, $httpProvider){

        $stateProvider

            .state('login', {
                url: '/login',
                views: {
                    'app': {
                        templateUrl: 'views/login.html',
                        controller: 'LoginController as LoginCntrl'
                    },
                }
         })
         .state('app.register', {
                url: 'register',
                templateUrl: 'modules/auth/views/register.html',
                controller: 'RegisterController as RegisterCntrl',
                
         })
         
    });
