'use strict';

/**
 * @ngdoc overview
 * @name panelApp
 * @description
 * # panelApp
 *
 * Main module of the application.
 */
var trocoWeb = angular.module('trocoWeb', [
    'ui.router',                    // Routing
    'oc.lazyLoad',                  // ocLazyLoad
    'ui.bootstrap',                 // Ui Bootstrap
    'pascalprecht.translate',       // Angular Translate
    'ngIdle',                       // Idle timer
    'ngSanitize',                   // ngSanitize
    'angular-jwt',
    'angular-storage',
])
