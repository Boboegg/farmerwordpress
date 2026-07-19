( function() {
    'use strict';

    const topicMap = document.querySelector( '[data-topic-map]' );
    if ( ! topicMap ) {
        return;
    }

    const entries = Array.from( topicMap.querySelectorAll( '[data-topic-entry]' ) );
    if ( ! entries.length ) {
        return;
    }

    const highlight = entry => {
        entries.forEach( candidate => {
            candidate.classList.toggle( 'is-highlighted', candidate === entry );
        } );
        topicMap.dataset.activeTopic = entry.dataset.topic || '';
    };

    const clearHighlight = () => {
        entries.forEach( entry => entry.classList.remove( 'is-highlighted' ) );
        delete topicMap.dataset.activeTopic;
    };

    entries.forEach( entry => {
        entry.addEventListener( 'pointerenter', () => highlight( entry ) );
        entry.addEventListener( 'focusin', () => highlight( entry ) );
    } );

    topicMap.addEventListener( 'pointerleave', clearHighlight );
    topicMap.addEventListener( 'focusout', event => {
        if ( ! topicMap.contains( event.relatedTarget ) ) {
            clearHighlight();
        }
    } );
}() );
