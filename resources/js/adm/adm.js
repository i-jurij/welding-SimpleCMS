document.addEventListener("DOMContentLoaded", function() {
       /*
    * event listener for info div in adm/change_pass
    */
    const PRO = document.querySelector('#p_pro');
    if (PRO) {
        PRO.addEventListener('click', function(e) {
            document.querySelector('#pro').classList.toggle('display_none');
        });
    }

    /*
    * event listener for delete, change user form in adm/change_pass/delete or change
    */
    const SUB = document.querySelector('#del_ch')
    if (SUB) {
        SUB.addEventListener('click', function(ev) {
            ev.preventDefault();
            let form_data = new FormData(document.querySelector(".form_del_ch"));
            if ( form_data.has("user_id[]"))
            {
                //document.querySelector("#chk_option_error").style.visibility = "hidden";
                document.querySelector(".form_del_ch").submit();
            }
            else
            {
                if (!document.querySelector("#ermes")) {
                    document.querySelector(".form_del_ch").insertAdjacentHTML('afterbegin','<div style="color:red;" id="ermes">Please select at least one user.</div>');
                } else {
                    document.querySelector("#ermes").innerHTML = "Please select at least one user.";
                }
                //document.getElementById("chk_option_error").style.visibility = "visible";
            }
        });
    }
        /*
    * event listener for delete, change user form paginator
    */
        const PAG = document.querySelector('#paginator')
        if (PAG && PAG.innerHTML !== '') {
            PAG.classList.add('margintb1');
            PAG.style.width = '30rem';
        }
});
