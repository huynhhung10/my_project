
function Validator(options){

var selectorRules = {};

    function validate(inputEle, rule){
        let rules = selectorRules[rule.selector];
        
        let errorMessage;
        let parentEle = inputEle.parentElement;
        let mesEle = parentEle.querySelector(options.errorMes); 

        for(let i = 0; i < rules.length; i++){
            errorMessage =  rules[i](inputEle.value);
            if(errorMessage) {
                break;
            }
        }

        if(errorMessage) {
            mesEle.innerHTML = errorMessage;
            parentEle.classList.add('form-invalid')
        } else{
            mesEle.innerHTML = '';
            parentEle.classList.remove('form-invalid')
        }  
    }

     let formEle = document.querySelector(options.form);
     if(formEle){

        formEle.onsubmit = function(e){
            // e.preventDefault();
            options.rules.forEach(function(rule){
                let inputEle = formEle.querySelector(rule.selector);
                validate(inputEle, rule);
            });
        }

        options.rules.forEach(function(rule){

            if(Array.isArray(selectorRules[rule.selector])){
                selectorRules[rule.selector].push(rule.test);
            }else{
                selectorRules[rule.selector] = [rule.test];
            }

            let inputEle = formEle.querySelector(rule.selector);
            if(inputEle){
                inputEle.onblur = function(){
                    validate(inputEle, rule);
                }

                inputEle.oninput = function(){
                    validate(inputEle, rule);
                }
            }
        });
     }
}

//rules
//nguyên tắc của rules:
//1. Khi có lỗi => trả ra mess lỗi
//2. Khi hợp lệ => Không trả gì hết
Validator.isRequired = function(selector, message){
    return {
        selector: selector,
        test: function(value){
             if(value.trim()){
                return undefined;
             }else{
                return message || 'Xin vui lòng hãy nhập trường này!'
             }
        }
    };
}

Validator.isEmail = function(selector, message){
    return {
        selector: selector,
        test: function(value){
            let regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if(regex.test(value)){
                return undefined;
            }else{
                return message || 'Vui lòng nhập email hợp lệ!';
            }
        }
    };
}

Validator.minLength = function(selector, min, message){
    return {
        selector: selector,
        test: function(value){
            if(value.length >= min){
                return undefined;
            }else{
                return message || 'Tối thiểu 8 ký tự!';
            }
        }
    };
}

Validator.isPhone = function(selector, message){
    return {
        selector: selector,
        test: function(value){
            let regex = /(84|0[3|5|7|8|9])+([0-9]{8})\b/;
            if(regex.test(value)){
                return undefined;
            }else{
                return message || 'Vui lòng nhập số điện thoại hợp lệ!';
            }
        }
    };
}
Validator.isConfirmed  = function(selector, getPassword, message){
    return {
        selector: selector,
        test: function(value){
            
            if(value === getPassword()){
                return undefined;
            }else{
                return message || 'Giá trị nhập vào không đúng!';
            }
        }
    };
}