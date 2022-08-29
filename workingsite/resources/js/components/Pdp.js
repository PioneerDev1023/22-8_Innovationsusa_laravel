import React from 'react';
import ReactDOM from 'react-dom';

import axios from 'axios';

import Utilities from './Utilities';


function addMyRedDot(v1){

  // desktop
  let skuUL = document.getElementsByClassName('skuUL');
  for (var i = 0; i < skuUL.length; ++i) {
    let li = skuUL[i].getElementsByTagName("li");
    for (var j = 0; j < li.length; ++j) {
      let itemNum = li[j].getElementsByClassName('itemNum')[0].innerText;
      if(itemNum==v1){
        li[j].classList.add("cart1");
      }
      //console.log(itemNum);
    }
  }

  // mobile
  let mobileItem = document.getElementsByClassName('mobileItem');
  for (var k = 0; k < mobileItem.length; ++k) {
    let itemNum = mobileItem[k].getElementsByClassName('itemNum')[0].innerText;
    if(itemNum==v1.toUpperCase()){
      mobileItem[k].classList.add("cart1");
    }
  }

}

class PdpSampleBtn extends React.Component {
  constructor() {
    super();
    this.state = { itemnum: "", itemname: "", itemcolor: "", itemPdfId : "", isLoggedIn : "" };
    this.util = new Utilities();
  }

  componentDidMount() {
    const pdpSpec = document.getElementById("pdp-spec");
    this.setState({
      itemnum: pdpSpec.getAttribute("data-itemnum"),
      itemPdfId: pdpSpec.getAttribute("data-idPdf"),
      itemname: pdpSpec.getAttribute("data-itemname"),
      itemcolor: pdpSpec.getAttribute("data-itemcolor"),
      itemprice: pdpSpec.getAttribute("data-price"),
      isLoggedIn: pdpSpec.getAttribute("data-isLoggedIn"),
    });

    let myClass = document.getElementById('pdp-sample-btn').classList.contains('cart1');
    //console.log(myClass);
    if(myClass==true){
      this.refs.samplebtn.setAttribute("disabled", "disabled");
    }
  }

  _handleClick(v1, v2, v3, v4, v5){

    document.getElementById('order_limit_alert').style.display = 'none';
    let cartSampleCount = document.getElementById("cart-count");
    let mobileCartSampleCount = document.getElementById("mobile-cart-count");
    //let csrftoken = document.querySelector('meta[name="csrf-token"]').content;

    // make button non click
    this.refs.samplebtn.setAttribute("disabled", "disabled");    

    let url = '/item-add';

    let params = {
      "itemnum" : v1,
      "itemname" : v2,
      "itemcolor" : v3,
      "itemunit" : "Yard",
      "price" : v4,
      "itempdfid" : v5,
      "qty" : 1,
      "type" : "sample",
    };

    axios({
      method: 'post',
      url: url,
      data: params,
      config: { headers: {
        'Content-Type': 'application/json',
        //'X-CSRF-TOKEN': csrftoken,
      }}
    })
    .then( response =>{
      if(response.data.error != undefined && response.data.error == "sampleOrder") {
        console.log('error');
        document.getElementById('order_limit_alert').style.display = 'block';
        this.refs.samplebtn.parentNode.parentNode.classList.add("cart0");
        return false;
      }
        //this.setState(
        //cartSampleCount.innerHTML = response.data
        if(Number(response.data)>0){
          cartSampleCount.classList.add("red-dot");
          mobileCartSampleCount.classList.add("red-dot");
        }else{
          cartSampleCount.classList.remove("red-dot");
          mobileCartSampleCount.classList.remove("red-dot");
        }

        addMyRedDot(v1);

        this.refs.samplebtn.parentNode.parentNode.classList.add("cart1");

      //)
    })
    .catch(function (error) {
        console.log(error);
    });
  }

  render() {
    return (
        <div>
          {this.state.isLoggedIn == "false" ? <button ref="samplebtn" data-target="#guestModal" data-toggle="modal" type="button" className="btn btn-primary" >ORDER A SAMPLE</button> :  <button ref="samplebtn" onClick={(e) => this._handleClick( this.state.itemnum, this.state.itemname, this.state.itemcolor, this.state.itemprice, this.state.itemPdfId )} type="button" className="btn btn-primary" >ORDER A SAMPLE</button>}
          <span className="msg-1">SAMPLE HAS BEEN<br/>PLACED IN YOUR CART</span>
          <span className="msg-0" style={{color:"rgb(249, 61, 11)"}}>YOU HAVE REACHED<br/>YOUR CARTS LIMIT</span>
        </div>
    );
  }
}

class PdpGuestBtn extends React.Component {
  constructor() {
    super();
    this.state = { itemnum: "", itemname: "", itemcolor: "", itemPdfId : "", isLoggedIn : "" };
    this.util = new Utilities();
    this._handleClick.bind(this);
  }

  componentDidMount() {
    const pdpSpec = document.getElementById("pdp-spec");
    this.setState({
      itemnum: pdpSpec.getAttribute("data-itemnum"),
      itemPdfId: pdpSpec.getAttribute("data-idPdf"),
      itemname: pdpSpec.getAttribute("data-itemname"),
      itemcolor: pdpSpec.getAttribute("data-itemcolor"),
      itemprice: pdpSpec.getAttribute("data-price"),
      isLoggedIn: pdpSpec.getAttribute("data-isLoggedIn"),
    });

    //let myClass = document.getElementById('pdp-sample-btn').classList.contains('cart1');
    //console.log(myClass);
  }

  async _handleClick(v1, v2, v3, v4, v5){

    let flag = false;
    let self = this;
    document.getElementById('guest_alert').style.display = "none";
    document.getElementById('guest_alert').innerHTML = "Either e-mail is invalid or password is too weak.  Please try again.";

    let cartSampleCount = document.getElementById("cart-count");
    let mobileCartSampleCount = document.getElementById("mobile-cart-count");
    //let csrftoken = document.querySelector('meta[name="csrf-token"]').content;

    // make button non click


    if(this.state.isLoggedIn === 'false') {
      let email = document.getElementById("email").value;
      let password = document.getElementById("password").value;
      let name = document.getElementById("name").value;
      let company = document.getElementById("company").value;
      let newsletter = document.getElementById("newsletter").value == 'on' ? 1 : 0;      

      // register guest
      if(email.trim() === "" || password.trim() === "" || name.trim() === "" || company.trim() === "" ) {

        if(name.trim() === "") {
          document.getElementById('guest_alert').innerHTML = "Please include name.";
        }
        else if(password.trim() === "") {
          document.getElementById('guest_alert').innerHTML = "Please include password.";
        }
        else if(company.trim() === "") {
          document.getElementById('guest_alert').innerHTML = "Please include company name.";
        }
        else {
          document.getElementById('guest_alert').innerHTML = "Please include email.";
        }
        // error message
        document.getElementById('guest_alert').style.display = "block";
        flag = true;
        return false;
      }
      else {
        //console.log('event passed');
        let guest_url = '/register-guest';
        let guest_params = {
          "email" : email,
          "password" : password,
          "name" : name,
          "company" : company,
          "newsletter" : newsletter,
        };
        console.log('event passed');
        await axios({
          method: 'post',
          url: guest_url,
          data: guest_params,
          config: { headers: {
            'Content-Type': 'application/json',
            //'X-CSRF-TOKEN': csrftoken,
          }}
        })
        .then( response =>{
          //console.log('response', response);
          let res = response.data;
          console.log(res);
          if (res.success == 'false') {
            // error message
            flag = true;
            return false;
          }
        })
        .catch(function (error) {

            console.log(error.response.data);            
            if(error.response.status == 422) {
              if(error.response.data.errors.password) {
                
                document.getElementById('guest_alert').innerHTML = error.response.data.errors.password[0];
              }
              if(error.response.data.errors.email && error.response.data.errors.email.includes('The email has already been taken.')) {
                
                document.getElementById('guest_alert').innerHTML = "We already have an account for this e-mail.  Would you like to <a href='/login?directOrder=true&pdfId="+self.state.itemPdfId+"'>log in</a>?";
              }
              else if(error.response.data.errors.email && error.response.data.errors.email.includes('The email must be a valid email address.')) {
                document.getElementById('guest_alert').innerHTML = "The email must be a valid email address.";
              }              
            }
            // error message            
            flag = true;
            return false;
        });
      }

    }
    if(flag) {
      // error message
      document.getElementById('guest_alert').style.display = "block";
      return false;
    }

    document.getElementById('guest_spinner').style.display = "block";

    let url = '/item-add';

    let params = {
      "itemnum" : v1,
      "itemname" : v2,
      "itemcolor" : v3,
      "itemunit" : "Yard",
      "price" : v4,
      "itempdfid" : v5,
      "qty" : 1,
      "type" : "sample",
    };

    await axios({
      method: 'post',
      url: url,
      data: params,
      config: { headers: {
        'Content-Type': 'application/json',
        //'X-CSRF-TOKEN': csrftoken,
      }}
    })
    .then( response =>{
     // console.log(response.data)
      //this.setState(
        //cartSampleCount.innerHTML = response.data
        if(Number(response.data)>0){
          cartSampleCount.classList.add("red-dot");
          mobileCartSampleCount.classList.add("red-dot");
        }else{
          cartSampleCount.classList.remove("red-dot");
          mobileCartSampleCount.classList.remove("red-dot");
        }

        addMyRedDot(v1);

      //)
    })
    .catch(function (error) {
        console.log(error);
        return false;
    });
    location.reload();
    return true;
  }

  render() {
    return (
          <button type="button" id="register_btn" className="btn btn-primary" onClick={(e) => this._handleClick( this.state.itemnum, this.state.itemname, this.state.itemcolor, this.state.itemprice, this.state.itemPdfId)} type="button" className="btn btn-primary">Register for a Guest Account</button>                 
    );
  }
}

class PdpPurchaseBtn extends React.Component {
  constructor() {
    super();
    this.state = { itemnum: "", itemname: "", itemcolor: "", itemprice:"", itemPdfId : "", itemUnit : ""};
    this.util = new Utilities();
  }

  componentDidMount() {
    const pdpSpec = document.getElementById("pdp-spec");
    // analyzing the cut fee
    let cutFee = pdpSpec.getAttribute("data-cut_fee");
    let itemcutFee = "None";
    if (cutFee == "Fee A") {
      itemcutFee = 3;
    }
    else if(cutFee == "Fee B") {
      itemcutFee = 30;
    }

    this.setState({
      itemnum: pdpSpec.getAttribute("data-itemnum"),
      itemPdfId: pdpSpec.getAttribute("data-idPdf"),
      itemUnit: pdpSpec.getAttribute("data-unit"),
      itemname: pdpSpec.getAttribute("data-itemname"),
      itemcolor: pdpSpec.getAttribute("data-itemcolor"),
      itemprice: pdpSpec.getAttribute("data-price"),
      itemcutFee: itemcutFee,
    });
  }

  _handleClick(v1, v2, v3, v4, v5, v6, v7) {

    ReactDOM.findDOMNode(this).parentNode.parentNode.querySelector('#qty_alert').style.display = "none";
    ReactDOM.findDOMNode(this).parentNode.parentNode.querySelector('#cart_success').style.display = "none";
    let cartSampleCount = document.getElementById("cart-count");
    let mobileCartSampleCount = document.getElementById("mobile-cart-count");

    let qty = ReactDOM.findDOMNode(this).parentNode.parentNode.querySelector('#qty').value;
    let min_order = ReactDOM.findDOMNode(this).parentNode.parentNode.querySelector('#min_order').value;
    if(qty-min_order < 0) {
      ReactDOM.findDOMNode(this).parentNode.parentNode.querySelector('#qty_alert').style.display = "block";
      return false;
    }
    console.log(qty);

        // make button non click
    this.refs.shoppingBtn.setAttribute("disabled", "disabled");

    let url = '/item-add';

    let params = {
      "itemnum" : v1,
      "itemname" : v2,
      "itempdfid" : v6,
      "itemunit" : v7,
      "itemcolor" : v3,
      "price" : v4,
      "qty" : qty,
      'cutFee': v5,
      "type" : "shopping",
    };

    axios({
      method: 'post',
      url: url,
      data: params,
      config: { headers: {
        'Content-Type': 'application/json',
        //'X-CSRF-TOKEN': csrftoken,
      }}
    })
    .then( response =>{
      console.log("purchase button response=", response.data)
      //this.setState(
        //cartSampleCount.innerHTML = response.data
        if(Number(response.data)>0){
          cartSampleCount.classList.add("red-dot");
          mobileCartSampleCount.classList.add("red-dot");
        }else{
          cartSampleCount.classList.remove("red-dot");
          mobileCartSampleCount.classList.remove("red-dot");
        }
      //)
    })
    .catch(function (error) {
        console.log(error);
    });

    ReactDOM.findDOMNode(this).parentNode.parentNode.querySelector('#cart_success').style.display = "block";

  }

  render() {
    return (
        <button ref="shoppingBtn" className="btn purchase_btn col-12" onClick={(e) => this._handleClick(this.state.itemnum, this.state.itemname, this.state.itemcolor, this.state.itemprice, this.state.itemcutFee, this.state.itemPdfId, this.state.itemUnit)} type="button" >PURCHASE</button>
    );
  }
}

class PdpYardageBtn extends React.Component {
  constructor(props){
    super(props);
  }

  get_url() {
    //console.log('calc yardage');
    let url = "/yardage-calculator/";
    if(this.props.pageType == 'sku')
      url += this.props.itemname+"/"+this.props.itemnum+"?width="+parseInt(this.props.width);
    else url += this.props.itemname+"?width="+parseInt(this.props.width);
    return url;
  }

  render() {
    return (
        // <button onClick={(e) => this._handleClick()} type="button" className="btn btn-primary" data-toggle="modal" data-target="#modal-pdp-yardage-container">MATERIAL CALCULATOR</button>
        <a type="button" href={this.get_url()} className="btn btn-primary" >YARDAGE CALCULATOR</a>
    );
  }
}

class PdpYardageCalcForm extends React.Component {

  constructor(props) {
    super(props);
    this.state = {width: '', length: '', sqfoot: '', calculate: '', resultClass: ''};

    this.handleWidth = this.handleWidth.bind(this);
    this.handleLength = this.handleLength.bind(this);
    this.handleSqfoot = this.handleSqfoot.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
    this.resultClass = 'result hide';
  }


  handleWidth(event) {
    this.setState({width: event.target.value.replace(/[^0-9]/g, '')});
  }
  handleLength(event) {
    this.setState({length: event.target.value.replace(/[^0-9]/g, '')});
  }
  handleSqfoot(event) {
    this.setState({sqfoot: event.target.value.replace(/[^0-9]/g, '')});
  }
  handleSubmit(event) {

    this.resultClass = 'result';

    const itemWidth = parseInt(document.getElementById('itemwidth').innerHTML);
    //console.log("item width:" + itemWidth);

    // ((A x B)x144)/(36 x ITEM WIDTH)
    // console.log("Width: " + this.state.width); console.log("Length: " + this.state.length);
    const aa = Math.ceil( ((this.state.width * this.state.length) * 144) / (36 * itemWidth) );

    // (C x 144)/(36 x ITEM WIDTH)
    //console.log("SQfoot: " + this.state.sqfoot);
    const cc = Math.ceil( (this.state.sqfoot * 144) / (36 * itemWidth) );

    // console.log('aa:' + aa); console.log('cc:' + cc);
    let rr = aa;
    if( (aa==''||aa==0) || (this.state.sqfoot!=0||this.state.sqfoot!='') ){
      rr = cc;
      // this.myWidth.current.value = '';
      // this.myLength.current.value = '';
      this.setState({width: '', length: ''});
    }//else{
      //this.mySqfoot.current.value = '';
      //this.setState({sqfoot: ''});
    //}

    //console.log(rr);
    this.setState({ calculate: rr  });

    event.preventDefault();
  }


  render() {
    return (
      <div>
        <form onSubmit={this.handleSubmit} action="javascript:void(0);" >
          <label htmlFor="width-of-room">WIDTH OF ROOM</label>
          <input id="width-of-room" name="width" type="text" placeholder="Width of Room" value={this.state.width} onChange={this.handleWidth} />
          <label htmlFor="length-of-room">HEIGHT OF ROOM</label>
          <input id="length-of-room" name="length" type="text" placeholder="Height of Room" value={this.state.length} onChange={this.handleLength} />
          <p>Please enter the numbers in total feet. If the measurement is in inches, please round up to the nearest full foot for both height and width.</p>
          <p>OR</p>
          <label htmlFor="sq-of-room">Square Footage of Room</label>
          <input id="sq-of-room" type="text" placeholder="Square Footage"value={this.state.sqfoot} onChange={this.handleSqfoot} />
          <input type="submit" className="btn btn-primary" value="CALCULATE" />
        </form>
        <hr />

        <div className={this.resultClass}>
          <div className="head">
            <div className="title">RESULT*</div>
            <div className="output">
              <p className="p1"><span>{this.state.calculate}</span> Linear Yards</p>
              <p className="p2"></p>
            </div>
          </div>
          <div className="foot">
            <p>*The result provided is only an estimate and does not factor in excess material that may be needed for pattern matching and wall irregularities. Ordering 10-15% overage is a fairly standard practice when purchasing wallcovering. We advise consulting with a professional wallcovering installer to confirm the amount of material needed for you job. Products sold by the roll and murals should be calculated by an installation professional.</p>
            <p>Products that are sold by the roll or are murals, should be calculated by an installation professional.</p>
          </div>
        </div>
      </div>
    );
  }
}

/*class PdpLoginBtn extends React.Component {

  _handleClick() {
    //console.log('login');
  }
  // D:\xampp-7.4.6\htdocs\innovations\innovationsusa\app\Http\Middleware\RedirectIfAuthenticated.php
  render() {
    return (
        <button onClick={(e) => this._handleClick()} type="button" className="btn" data-toggle="modal" data-target="#modal-pdp-login-container">LOGIN</button>
    );
  }
}

if (document.getElementById('pdp-login-btn')) {
  ReactDOM.render(<PdpLoginBtn />, document.getElementById('pdp-login-btn'));
}*/

if (document.getElementById('pdp-field-container')) {
  ReactDOM.render(<PdpYardageCalcForm />, document.getElementById('pdp-field-container'));
}

if (document.getElementById('pdp-sample-btn')) {
  ReactDOM.render(<PdpSampleBtn />, document.getElementById('pdp-sample-btn'));
}

if (document.getElementById('pdp-guest-btn')) {
  ReactDOM.render(<PdpGuestBtn />, document.getElementById('pdp-guest-btn'));
}

if (document.getElementById('pdp-yardage-btn')) {
  let itemname = document.getElementById('pdp-yardage-btn').dataset.itemname;
  let itemnum = document.getElementById('pdp-yardage-btn').dataset.itemnum;
  let width = document.getElementById('pdp-yardage-btn').dataset.width;
  let pageType = document.getElementById('pdp-yardage-btn').getAttribute('class');
  ReactDOM.render(<PdpYardageBtn itemname={itemname} itemnum={itemnum} width={width} pageType={pageType}/>, document.getElementById('pdp-yardage-btn'));
}
if (document.getElementById('pdp-purchase-btn')) {
  ReactDOM.render(<PdpPurchaseBtn />, document.getElementById('pdp-purchase-btn'));
}
