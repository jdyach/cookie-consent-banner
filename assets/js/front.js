/* global ccbConfig */
( function () {
    'use strict';

    const COOKIE_NAME = 'ccb_consent';
    const days        = ( ccbConfig && ccbConfig.cookieDays ) ? parseInt( ccbConfig.cookieDays, 10 ) : 365;
    const banner      = document.getElementById( 'ccb-banner' );
    const acceptBtn   = document.getElementById( 'ccb-accept' );

    if ( ! banner || ! acceptBtn ) { return; }

    // Check for existing consent cookie.
    function hasConsent() {
        return document.cookie.split( ';' ).some( function ( c ) {
            return c.trim().startsWith( COOKIE_NAME + '=' );
        } );
    }

    function setConsent() {
        const expires = new Date();
        expires.setDate( expires.getDate() + days );
        document.cookie = COOKIE_NAME + '=1; expires=' + expires.toUTCString() + '; path=/; SameSite=Lax';
    }

    if ( ! hasConsent() ) {
        banner.hidden = false;
    }

    acceptBtn.addEventListener( 'click', function () {
        setConsent();
        banner.hidden = true;
    } );
}() );
