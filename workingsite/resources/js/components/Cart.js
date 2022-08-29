import React from 'react';
import ReactDOM from 'react-dom';

import axios from 'axios';

import Utilities from './Utilities';

function calcCart(v1, v2){
  var n = v1 * v2;
  return n.toFixed(2);
}

function setSubtotal(container, v1){
  container.querySelector('.sub-total > .right').innerHTML = '$'+v1;
}

function calculateTotal(){

  const shopContainer = document.getElementById("shopping-container");
  if( shopContainer ){

    let cartUL = document.getElementById('cartUL'); 
    let cartLi = cartUL.getElementsByClassName('row'); // 
    //let qtys = document.getElementsByClassName('cart-qty');

    let subtotal = 0;
    for (var i = 0; i < cartLi.length; ++i) {
      if(cartLi[i].classList.contains("hide") ){}
      else{
        let qtys = cartLi[i].querySelector('.cart-qty');
        let v1 = qtys.getAttribute("data-qty");
        let v2 = qtys.parentNode.parentNode.parentNode.querySelector('.price').getAttribute("data-price");
        subtotal += parseFloat(calcCart(v1, v2));
      }
      //console.log(v1, v2);
    }

    setSubtotal(shopContainer, subtotal.toFixed(2));
  }

}

class InputQty extends React.Component {

  constructor(props) {
    super(props);
    this.state = { value: "" };
    this._handleChange = this._handleChange.bind(this);
    this.util = new Utilities();
  }
  
  componentDidMount() {
    this.setState({
      value: ReactDOM.findDOMNode(this).parentNode.getAttribute("data-qty"),
      //itemnum: ReactDOM.findDOMNode(this).parentNode.parentNode.parentNode.parentNode.querySelector('span').getAttribute("data-itemnum"),
      //sampleQty: ReactDOM.findDOMNode(this).parentNode.parentNode.querySelector('.qty').value
      //sampleQty: ReactDOM.findDOMNode(this).parentNode.previousElementSibling.value
    });

    calculateTotal();
  }
  
  _handleChange(event){

    let cartSampleCount = document.getElementById("cart-count");

    let itemNum = ReactDOM.findDOMNode(this).parentNode.getAttribute("data-itemnum");
    //console.log(v1);

    this.setState({value: event.target.value});
    let sampleQty = this.refs.qtyField.value; // = sampleQty;

    //let sampleQty = ReactDOM.findDOMNode(this).parentNode.getAttribute('data-qty');
    let carttype =  document.getElementById('cart').getAttribute("data-carttype");

    let params = {
      "itemnum" : itemNum,
      "qty" : sampleQty,
      "type" : carttype,
    };

    let url = '/cart-update';
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
      //console.log(response)

      if(Number(response.data)>0){
        cartSampleCount.classList.add("red-dot");
      }else{
        cartSampleCount.classList.remove("red-dot");
      }

      if(sampleQty==0 && sampleQty!=''){

        // [!] class Utilities

        let elem = this.util.getClosest(ReactDOM.findDOMNode(this), 'li');
        elem.classList.add('hide');
      }

      // update <inout/> val
      this.refs.qtyField.value = sampleQty;
      this.refs.qtyField.parentNode.setAttribute("data-qty", sampleQty);



      var shopContainer = document.getElementById("shopping-container")
      if( shopContainer ){

        let qtys = document.getElementsByClassName('cart-qty');
        let subtotal = 0;

        for (var i = 0; i < qtys.length; ++i) {
          let v1 =  qtys[i].getAttribute("data-qty");
          let v2 =  qtys[i].parentNode.parentNode.parentNode.querySelector('.price').getAttribute("data-price");
          
          subtotal += parseFloat(calcCart(v1, v2));

        }

        subtotal = subtotal.toFixed(2);
        setSubtotal(shopContainer, subtotal);

        // let v1 = document.querySelector('.cart-qty').getAttribute("data-qty");
        // console.log(v1);
        /*let v1 = this.refs.qtyField.parentNode.getAttribute("data-qty");
        let v2 = this.refs.qtyField.parentNode.parentNode.parentNode.querySelector('.price').getAttribute("data-price");
        let subtotal = 0;
        subtotal += parseFloat(calcCart(v1, v2));

        subtotal = subtotal.toFixed(2);
        //console.log(subtotal); 

        setSubtotal(shopContainer, subtotal);*/

      }
      //this.setState(
        //cartSampleCount.innerHTML = response.data 
      //)
    })
    .catch(function (error) {
        console.log(error);
    });
  }

  render() {
    return (
        <input ref="qtyField" className="qty"  type="text" 
                value={this.state.value} 
                onChange={this._handleChange}
        />
    );
  }
}

class DeleteBtn extends React.Component {

  constructor() {
    super();
    this.state = { itemnum: "", parentContainer: "" };
    this.util = new Utilities();
  }

  componentDidMount() {
    this.setState({
      itemnum: ReactDOM.findDOMNode(this).parentNode.parentNode.querySelector('span.cart-qty').getAttribute("data-itemnum"),
      parentContainer: this.util.getClosest(ReactDOM.findDOMNode(this), 'li'),
    });
  }

  _handleClick(e, v1, v2){
    e.preventDefault();

    let cartSampleCount = document.getElementById("cart-count");

    let carttype =  document.getElementById('cart').getAttribute("data-carttype");
    
    this.refs.x_btn.setAttribute("disabled", "disabled");

    let params = {
      "itemnum" : v1,
      "type" : carttype,
    };

    v2.classList.add('hide');

    if(carttype=='shopping')
      calculateTotal();

    let url = '/cart-delete';

    axios({
      method: 'post',
      url: url,
      data: params,
      config: { headers: {
        'Content-Type': 'application/json',
        //'X-CSRF-TOKEN': csrftoken,
      }}
    })
    .then(response =>{ //console.log(response)
      //this.setState(
        //cartSampleCount.innerHTML = response.data, 
      //)
        
      if(Number(response.data)>0){
        cartSampleCount.classList.add("red-dot");
      }else{
        cartSampleCount.classList.remove("red-dot");
      }

      v2.classList.add('hide');

    })
    .catch(function (error) {
        console.log(error);
    });
  }

  render() {
    return (
        <a href="" ref="x_btn" onClick={(e) => this._handleClick(e, this.state.itemnum,  this.state.parentContainer )} >Remove</a>
    );
  }
}


if (document.getElementById('cart')) {
  
  Array.prototype.forEach.call(
    document.getElementsByClassName('cart-qty'),
    function(el) {
      ReactDOM.render(<InputQty />, el)
    }
  )

  Array.prototype.forEach.call(
    document.getElementsByClassName('cart-x'),
    function(el) {
      ReactDOM.render(<DeleteBtn />, el)
    }
  )


  /*class UpdateBtn extends React.Component {

    constructor() {
      super();
      this.state = { itemnum: "", sampleQty: "" };
      this.util = new Utilities();
    }
    
    componentDidMount() {
      this.setState({
        itemnum: ReactDOM.findDOMNode(this).parentNode.parentNode.parentNode.parentNode.querySelector('span').getAttribute("data-itemnum"),
        //sampleQty: ReactDOM.findDOMNode(this).parentNode.parentNode.querySelector('.qty').value
        //sampleQty: ReactDOM.findDOMNode(this).parentNode.previousElementSibling.value
      });

    }

    _handleClick(v1){

      //console.log(v1);
      let cartSampleCount = document.getElementById("cart-count");
      
      let sampleQty = ReactDOM.findDOMNode(this).parentNode.parentNode.querySelector('.qty').value;
      let carttype =  document.getElementById('cart').getAttribute("data-carttype");


      let params = {
        "itemnum" : v1,
        "qty" : sampleQty,
        "type" : carttype,
      };

      let url = '/cart-update';
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
        //console.log(response)

        if(Number(response.data)>0){
          cartSampleCount.classList.add("red-dot");
        }else{
          cartSampleCount.classList.remove("red-dot");
        }

        if(sampleQty==0){

          // [!] class Utilities

          let elem = this.util.getClosest(ReactDOM.findDOMNode(this), 'li');
          elem.classList.add('hide');
        }
        //this.setState(
          //cartSampleCount.innerHTML = response.data 
        //)
      })
      .catch(function (error) {
          console.log(error);
      });
    }

    render() {
      return (
          <button onClick={(e) => this._handleClick(this.state.itemnum)} type="button" className="btn btn-primary" >Update</button>
      );
    }
  }*/
  /*Array.prototype.forEach.call(
    document.getElementsByClassName('cart-update'),
    function(el) {
      ReactDOM.render(<UpdateBtn />, el)
    }
  )*/

}