/**
 * Native replace for jQuery ready function
 */

export default fn => {
    if ( typeof fn === 'function' ) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }
}
