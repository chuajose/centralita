angular.module('trocoWeb')
.config(function ($stateProvider, $urlRouterProvider, $httpProvider){

        $stateProvider
        	.state('app.reports', {
	            abstract: true,
	            url: "reports",
                templateUrl: 'modules/reports/views/index.html',
	        })
            .state('app.reports.list', {
                url: '/list',
                templateUrl: 'modules/reports/views/list.html',
                controller: 'ReportsController as ReportsCntrl',
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