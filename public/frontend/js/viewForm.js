formSign();
function formSign(){
    let formInEle = document.getElementById('form_signin');
    let formUpEle = document.getElementById('form_signup');
    let btnSignin = document.getElementById('header_signin');
    let btnSignUp = document.getElementById('header_signup');
    let underline = document.querySelector('.form-header__underline');

    btnSignin.onclick = function(){
        formInEle.classList.add('transform-left-380px');
        formUpEle.classList.add('transform-left-380px');
        underline.classList.add('transform-right-184px');
    }

    btnSignUp.onclick = function(){
        formInEle.classList.remove('transform-left-380px');
        formUpEle.classList.remove('transform-left-380px');
        underline.classList.remove('transform-right-184px');
    }
}