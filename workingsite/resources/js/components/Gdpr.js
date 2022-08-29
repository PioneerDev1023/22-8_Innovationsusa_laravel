import React, { useState, useEffect } from "react";
import ReactDOM from 'react-dom';

import axios from 'axios';


const Gdpr = () => {

  //console.log(bindEmail.value);

  const _handleClick = (ee) => {
    ee.preventDefault();

    let gdprDiv = document.getElementById("gdpr");

    let url = '/gdpr';
    axios({
      method: 'post',
      url: url,
      config: { headers: {
        'Content-Type': 'application/json',
        //'X-CSRF-TOKEN': csrftoken,
      }}
    })
    .then(response =>{ 
      //console.log(response.data);
      if(response.data=='gdpr_1'){
        gdprDiv.classList.add("accepted");
      }
    })
    .catch(function (error) {
        //console.log(error);
        //console.log('already exists');

    });

    
  }

  return (
    <a className='accept' href="#" onClick={_handleClick}>Accept</a>
  );
}

const elem = document.getElementById('accept-btn');
ReactDOM.render(<Gdpr />, elem );


