trocoWeb

    .service('ErrorsService', ['$state', function ($state) {

    this.getError = function (error) {
        var text = "Se ha producido un error. Intentelo mas tarde";
        if (typeof error != 'undefined') {
            var response = null;
            if (typeof error.response != 'undefined') {
                response = error.response;
            } else if (typeof error.data != 'undefined' && typeof error.data.response != 'undefined') {
                response = error.data.response;
            }

            if (response) {
                if (typeof response.description == 'string') {
                    //ToastService.show(response.description, 'danger');
                    return;
                } else if (typeof response.description == 'object') {
                    for (var i = 0; i < response.description.length; i++) {
                        //ToastService.show(response.description[i].error, 'danger');
                    }
                    return;
                }
            }
        } else {
            //$state.go('app.dashboard')
        }
        //ToastService.show(text, 'danger');
    }
    }])

    .service('tokenService', ['store', function (store) {

        var setToken = function(token){
             token_old = store.get('token');
                store.set('token', token);
                console.log('actualizado el  token'+token_old+' a '+token);
        }

        var getToken = function(){
                token = store.get('token');
                console.log('get  token'+token);
        }

        return {
            setToken: setToken,
            getToken: getToken
        }
            
    }])
        // =========================================================================
        // General functions
        // =========================================================================

    .service('GeneralService', [ 'CONFIG', function ( CONFIG) {

        // =========================================================================
        // Return timestamp date
        // =========================================================================
        var transformDate = function (date) {
            date = new Date(date.split("-").join("-")).getTime();
            return date / 1000;
        }

        return {
            transformDate: transformDate,
        }
    }])
;


