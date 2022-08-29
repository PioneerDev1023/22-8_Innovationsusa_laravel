import React, { useState, useEffect } from "react";
import ReactDOM from 'react-dom';

import axios from 'axios';

const DownloadImage = () => {

  // https://stackoverflow.com/questions/58131035/download-file-from-the-server-laravel-and-reactjs
  function handleClick(ee){
    ee.preventDefault();

    var imageUrl = ee.currentTarget.parentNode.getAttribute('data-downimage');
    var imageName = imageUrl.split("/").pop();

    if (imageName.indexOf("?") >= 0)
      imageName = imageName.substring(0, imageName.indexOf('?'));

    let params = {
      "imageUrl" : imageUrl,
    }

    axios({
      url: '/download-image',
      method: 'post',
      data: params,
      responseType: 'arraybuffer',
    })
    .then(function (response) {
          //console.log(response);
          let blob = new Blob([response.data], { type: 'application/jpg' });
          let link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = imageName;
          link.click();
    })
    .catch(error => {
      console.log(error)
    });
  }

  return (
    <a href="#" onClick={handleClick}>Download Image</a>
  );
}

if ( document.getElementById('productcontainer') || 
     document.getElementById('itemcontainer') ) {

  // PLP preview
  const elem = document.getElementById('download-image');
  ReactDOM.render(<DownloadImage />, elem );

  // PDP main img
  const elem2 = document.getElementById('download-image-2');
  ReactDOM.render(<DownloadImage />, elem2 );

  // PDP gallery
  Array.prototype.forEach.call(
    document.getElementsByClassName('download-image-gallery'),
    function(el) {
      ReactDOM.render(<DownloadImage />, el)
    }
  )
}