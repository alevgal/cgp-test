import 'jquery'
import 'popper.js/dist/popper'
import 'bootstrap/js/dist/modal';
import 'bootstrap/js/dist/dropdown';
import ready from "./util/ready";
const { __ } = wp.i18n;

ready(() => {
    //Check if user is logged in
    const loggedIn = document.body.classList.contains( 'logged-in' );
    const links = document.querySelectorAll('a');

    let linkClicked = false,
        linkClickedTime = 0;

    links.forEach(link => {
        link.addEventListener('click', (e)  => {
            linkClicked = true;
            linkClickedTime = new Date().getTime();
        })

    });

    //check if user is logged in and do not have returned discount
    if( !loggedIn || 'true' !== window.localStorage.getItem('applyDiscount') ) {

        const showDiscountDialog  = () => {
          const dialog = document.createElement('div');
          dialog.classList.add('alert', 'alert-info');
          dialog.setAttribute("style", "position: fixed; left: 0; right: 0; bottom:0; z-index: 999; padding: 40px 30px; text-align: center; display: flex; flex-wrap: wrap; justify-content: center; margin: 0;");
          dialog.innerHTML = `<p style="margin-bottom: 30px; font-size:30px; width: 100%;">${__(`Get Your Discount`)} - ${CGP.discount}%</p>`;

          const confirmBtn = document.createElement('a');
                confirmBtn.classList.add('btn', 'btn-primary', 'btn-lg');
                confirmBtn.setAttribute('href', `${CGP.homeUrl}?applyDiscount`);
                confirmBtn.innerText = __('Got it!');
                confirmBtn.style.marginRight = '20px';

                dialog.append(confirmBtn);
                document.body.prepend(dialog);

                confirmBtn.addEventListener('click', () => {
                    window.removeEventListener('beforeunload', onWindowClose);
                    window.localStorage.setItem('applyDiscount', 'true');
                })
        };

        const onWindowClose = (event) => {
            //Check if unload event was not fired with link click
            if ( ! ( linkClicked && new Date().getTime() - linkClickedTime < 100 ) ) {
                setTimeout(showDiscountDialog, 1000);
                event.preventDefault();
                event.returnValue = '';
            }
        };

        window.addEventListener('beforeunload', onWindowClose);
    }
});