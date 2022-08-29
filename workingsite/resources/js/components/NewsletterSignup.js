import React, { useState, useEffect } from "react";
import ReactDOM from 'react-dom';

import axios from 'axios';


const useInput = initialValue => {
  const [value, setValue] = useState(initialValue);

  return {
    value,
    setValue,
    setClass: (v1) => setValue(v1),
    reset: () => setValue(""),
    bind: {
      value,
      onChange: event => {
        setValue(event.target.value);
      }
    }
  };
};


const EmailNewsletterForm = () => {

  const { value:emailVal, bind:bindEmail, 
          reset:resetEmail, setClass:setClass } = useInput('');
  
  const [formClass, setFormClass] = useState('');
  const [msgClass, setMsgClass] = useState('hide');
  const [msg, setMsg] = useState('');
  //console.log(bindEmail.value);

  var regEx = new RegExp(/[^.@a-zA-Z0-9]+/gi);

  let emailStr = bindEmail.value.replace(regEx, "");  
  bindEmail.value = emailStr.toLowerCase();


  const handleSubmit = (ee) => {
    ee.preventDefault();
    
    let params = {
      "emailAddress" : `${emailVal}`,
    }

    //console.log(params);
    if( `${emailVal}` != '' ){

      let url = '/newsletter-signup';
      axios({
        method: 'post',
        url: url,
        data: params,
        config: { headers: {
          'Content-Type': 'application/json',
          //'X-CSRF-TOKEN': csrftoken,
        }}
      })
      .then(response =>{ 
        //console.log(response.data);
        resetEmail();
        setFormClass('hide');
        setMsgClass('');
        setMsg('Thank you for signing up!');
      })
      .catch(function (error) {
          //console.log(error);
          //console.log('already exists');
          resetEmail();
          setFormClass('hide');
          setMsgClass('');
          setMsg('Your email is already registered.');
      });

    }
  }

  return (
    <div>
      <form onSubmit={handleSubmit} className={formClass}>
          <label className="" htmlFor="email">NEWSLETTER SIGN UP</label>
          <div className="group"><input name="email" type="email" placeholder="Email address" {...bindEmail}/>
          <button type="submit" className="btn btn-primary">SUBSCRIBE</button></div>
      </form>
      <p className={msgClass}>{msg}</p>
    </div>
  );
}

const elem = document.getElementById('foot-newsletter-container');
ReactDOM.render(<EmailNewsletterForm />, elem );


