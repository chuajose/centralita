angular.module('trocoWeb')
.config(function ($stateProvider, $urlRouterProvider, $httpProvider){

        $stateProvider
        	.state('app.call', {
	            abstract: true,
	            url: "calls",
                templateUrl: 'modules/call/views/index.html',
	        })
            .state('app.call.list', {
                url: '/list',
                templateUrl: 'modules/call/views/list.html',
                controller: 'CallController as CallCntrl',
                resolve: {
                loadPlugin: function ($ocLazyLoad) {
                    return $ocLazyLoad.load([
                     
                        {
                            name: 'datePicker',
                            files: ['css/plugins/datapicker/angular-datapicker.css','bower_components/plugins/datapicker/angular-datepicker.js']
                        },
                        {
                            files: ['bower_components/plugins/jasny/jasny-bootstrap.min.js']
                        },
                         
                        

                    ]);
                }
            }
            })

         
    });