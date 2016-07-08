trocoWeb
    .run(['jwtHelper', 'store', '$rootScope', '$state', '$location', '$templateCache', 'CONFIG', 'authFactory',  '$timeout', '$window', function(jwtHelper, store, $rootScope, $state, $location, $templateCache,  CONFIG, authFactory, $timeout, $window) {

    $rootScope.BASEURL = CONFIG.BASEURL;
    $rootScope.myUser = store.get('my_user');
}])


.config(['$stateProvider', '$urlRouterProvider', '$httpProvider', 'jwtInterceptorProvider', '$locationProvider', '$provide', '$sceDelegateProvider',  'CONFIG', '$ocLazyLoadProvider', function ($stateProvider, $urlRouterProvider, $httpProvider, jwtInterceptorProvider, $locationProvider, $provide, $sceDelegateProvider, CONFIG, $ocLazyLoadProvider) {

    $sceDelegateProvider.resourceUrlWhitelist([
        // Allow same origin resource loads.
        'self',
        // Allow loading from our assets domain.  Notice the difference between * and **.
        'http://localhost/**'
    ]);

    jwtInterceptorProvider.tokenGetter = ['config', function (config) {
            // Skip authentication for any requests ending in .html
        if (config.url.substr(config.url.length - 5) === '.html') {
            return null;
        }
        return localStorage.getItem('token');
    }];

    $httpProvider.interceptors.push('jwtInterceptor');

    //$locationProvider.html5Mode(true).hashPrefix('!');
    $httpProvider.interceptors.push(function ($q, $injector) {

        return {

            'response': function (response) {
                /*//Will only be called for HTTP up to 300
                console.log('Response',response.data.token);*/
                if (response.data.token) {
                    var tokenService = $injector.get('tokenService'); //Injecta el services tokenService
                    tokenService.setToken(response.data.token); //Si existen token lo actualizo
                }
                return response;
            },
            'responseError': function (rejection) {
                if(rejection.status === 405 && rejection.data && rejection.data.code === -16) {
                    $injector.get('$state').transitionTo('app.locked');
                    rejection.data.response.description = "cuenta bloqueada";
                }
                if(rejection.status === 401) {
                    localStorage.clear();
                    document.location.href = 'http://192.168.0.8/centralita/app/#/login';
                 }
                if (rejection.status >= 400) {
                    //Si se da un error superior a 400 cargo  el service ErrorsService aqui configurado y ejecuto el getError de ese servico
                    var ErrorsService = $injector.get('ErrorsService'); //Injecta el services ErrorsService
                    ErrorsService.getError(rejection);
                    //console.log('ErrorRuta',rejection);

                }
                return $q.reject(rejection);
            }
        };
    });



    $urlRouterProvider.otherwise("/dashboard");
    $urlRouterProvider.when('/', '/dashboard');


    $ocLazyLoadProvider.config({
        // Set to true if you want to see what and when is dynamically loaded
        debug: false
    });

    $stateProvider
        .state('app', {
            url: '/',
            abstract: true,
            views: {
                'app': {
                    templateUrl: "views/common/content.html",
                    controller: 'MainCtrl as main',
                },
                
            }
        })
        .state('app.dashboard', {
            url: 'dashboard',
            templateUrl: "views/dashboard_1.html",
            resolve: {
                    loadPlugin: function ($ocLazyLoad) {
                        return $ocLazyLoad.load([
                            {

                                serie: true,
                                name: 'angular-flot',
                                files: [ 'bower_components/plugins/flot/jquery.flot.js', 'bower_components/plugins/flot/jquery.flot.time.js', 'bower_components/plugins/flot/jquery.flot.tooltip.min.js', 'bower_components/plugins/flot/jquery.flot.spline.js', 'bower_components/plugins/flot/jquery.flot.resize.js', 'bower_components/plugins/flot/jquery.flot.pie.js', 'bower_components/plugins/flot/curvedLines.js', 'bower_components/plugins/flot/angular-flot.js', ]
                            },
                            {
                                name: 'angles',
                                files: ['bower_components/plugins/chartJs/angles.js', 'bower_components/plugins/chartJs/Chart.min.js']
                            },
                            {
                                name: 'angular-peity',
                                files: ['bower_components/plugins/peity/jquery.peity.min.js', 'bower_components/plugins/peity/angular-peity.js']
                            }
                        ]);
                    }
                }
        })

        
        

    
}])

.constant('CONFIG', {
       // APIURL: 'https://www.trocobuy.com/api/v2/',
      //  BASEURL: 'https://www.trocobuy.com/',
        APIURL: 'http://localhost/centralita/api/v1/',
        BASEURL: 'http://localhost/centralita/'
    })
    

;
