'use strict';

/**
 * Requirejs config
 */
requirejs.config(
    {
        
        baseUrl    : 'bundles/app',
        waitSeconds: 30,
        
        /**
         * Modules ids.
         */
        paths: {
            'domReady'            : 'lib/requirejs-domready/domReady',
            'angular'             : 'lib/angular/angular',
            'angular-messages'    : 'lib/angular-messages/angular-messages',
            'angular-ui-bootstrap': 'lib/angular-ui-bootstrap/ui-bootstrap-tpls',
            'angular-ui-router'   : 'lib/angular-ui-router/angular-ui-router',
            'jquery'              : 'lib/jquery/jquery',
            'rpc'                 : 'js/rpc'
        },
        
        /**
         * for libs that either do not support AMD out of the box, or
         * require some fine tuning to dependency mgt'
         */
        shim: {
            'angular'             : {
                exports: 'angular'
            },
            'angular-messages'    : {
                deps: ['angular']
            },
            'angular-ui-router'   : {
                deps: ['angular']
            },
            'angular-ui-bootstrap': {
                deps: ['angular']
            },
            'rpc'                 : {
                deps: ['angular']
            }
        },
        
        deps: [
            'js/main'
        ]
        
    }
);