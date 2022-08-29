import React from "react";
import ReactDOM from 'react-dom';

import axios from 'axios';

import Utilities from './Utilities';
import NavSearchDropdown from './NavSearchDropdown';


class NavSearch extends React.Component {

  constructor(props) {
    super(props);
    this.state = { value: "" };
    this._handleChange = this._handleChange.bind(this);
    this.util = new Utilities();
    this.dropdown = new NavSearchDropdown();
  }

  componentDidMount() {
    this.setState({
      value: ReactDOM.findDOMNode(this).value,
      //itemnum: ReactDOM.findDOMNode(this).parentNode.parentNode.parentNode.parentNode.querySelector('span').getAttribute("data-itemnum"),
      //sampleQty: ReactDOM.findDOMNode(this).parentNode.parentNode.querySelector('.qty').value
      //sampleQty: ReactDOM.findDOMNode(this).parentNode.previousElementSibling.value
    });

  }

  _handleChange(event){

    // event.persist();
    this.setState({value: event.target.value});

    const searchResults = document.getElementById('search-results');
    //const resultElement = document.getElementById("search-results");

    this.timeout = setTimeout(() => {

        let searchStr = this.refs.navsearch.value;
        // leave only: alpha numeric and - + ' and compress empty space into 1
        searchStr = searchStr.replace(/[^-+'.a-zA-Z0-9]+/gi, " ");
        this.refs.navsearch.value = searchStr;

        if(searchStr.length <= 2){
          ReactDOM.unmountComponentAtNode(searchResults);
        }
        else if(searchStr.length > 2){

          let params = {
            "searchStr" : searchStr,
          };

          let url = '/nav-search';
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

            //searchResults.setState({
              //cartSampleCount.innerHTML = response.data

              /*const items = [
                { item_number: 1, item_name: searchTerm },
                { item_number: 2, item_name: "Sarah Weir" },
                { item_number: 3, item_name: "Alicia Weir" },
                { item_number: 4, item_name: "Doo Weir" },
                { item_number: 5, item_name: "Grooft Weir" }
              ];*/

              let items = response.data;

              this.dropdown.makeList(searchStr, searchResults, this.refs.navsearch, items);

           // })


          })
          .catch(function (error) {
              console.log(error);
          });

        }// if()

    }, 500 );

  }

  render() {
    return (
      <input
          id="search-input"
          ref="navsearch"
          type="text"
          placeholder="Search..."
          value={this.state.value}
          onChange={this._handleChange}
      />

    );
  }
}

class NavIO extends React.Component {
  constructor() {
    super();
    ReactDOM.render(<NavSearch />, document.getElementById('search-input-container'));
    //this.state = { searchInputContainer: "" };
  }

  componentDidMount() {
    this.setState({
      //searchInputContainer: document.getElementById.getElementById('search-input-container'),
    });
  }

  _handleClick(e){
    e.preventDefault();

    const searchbar = document.getElementById('searchbar'),
          searchInput = document.getElementById('search-input'),
          searchResults = document.getElementById('search-results');

    searchbar.classList.toggle('collapsed');

    if (searchbar.classList.contains('collapsed')) {
      searchResults.innerHTML = '';
      searchInput.value = '';
    }else{
      searchInput.focus();
    }

  }

  render() {
    return (
      <a href="#"  onClick={(e) => this._handleClick(e)} >
        <img className="search-ico" src="/images/icons/Search.svg" />
        <img className="x-ico" src="/images/icons/x.svg" />
      </a>
    );
  }

}
ReactDOM.render(<NavIO />, document.getElementById('navsearch-btn-1'));



class NavSearchMobile extends React.Component {

  constructor(props) {
    super(props);
    this.state = { value: "" };
    this._handleChange = this._handleChange.bind(this);
    this.util = new Utilities();
    this.dropdown = new NavSearchDropdown();
  }

  componentDidMount() {
    this.setState({
      value: ReactDOM.findDOMNode(this).value,
      //itemnum: ReactDOM.findDOMNode(this).parentNode.parentNode.parentNode.parentNode.querySelector('span').getAttribute("data-itemnum"),
      //sampleQty: ReactDOM.findDOMNode(this).parentNode.parentNode.querySelector('.qty').value
      //sampleQty: ReactDOM.findDOMNode(this).parentNode.previousElementSibling.value
    });

  }

  _handleChange(event){

    // event.persist();
    this.setState({value: event.target.value});

    const searchResults = document.getElementById('search-results-mobile');

    this.timeout = setTimeout(() => {

        let searchStr = this.refs.navsearchMobile.value;
        // leave only: alpha numeric and - + ' and compress empty space into 1
        searchStr = searchStr.replace(/[^-+'a-zA-Z0-9]+/gi, " ");
        this.refs.navsearchMobile.value = searchStr;

        if(searchStr.length <= 2){
          ReactDOM.unmountComponentAtNode(searchResults);
        }
        else if(searchStr.length > 2){

          let params = {
            "searchStr" : searchStr,
          };

          let url = '/nav-search';
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
              // console.log(response.data);

              let items = response.data;

              this.dropdown.makeList(searchStr, searchResults, this.refs.navsearchMobile, items);

           // })


          })
          .catch(function (error) {
              console.log(error);
          });

        }// if()

    }, 500 );

  }

  render() {
    return (
      <input
          id="search-input-mobile"
          ref="navsearchMobile"
          type="text"
          placeholder="Search..."
          value={this.state.value}
          onChange={this._handleChange}
      />

    );
  }
}
class NavIOMobile extends React.Component {
  constructor() {
    super();
    ReactDOM.render(<NavSearchMobile />, document.getElementById('search-input-container-mobile'));
    //this.state = { searchInputContainer: "" };
  }

  componentDidMount() {
    this.setState({
      //searchInputContainer: document.getElementById.getElementById('search-input-container'),
    });
  }

  _handleClick(e){
    e.preventDefault();

    const searchBoxMobile = document.getElementById('search-box-mobile'),
          searchInput = document.getElementById('search-input-mobile'),
          searchResults = document.getElementById('search-results-mobile');

    const mobileHamburger = document.getElementById('mobile-hamburger'),
          navsearchBtnMobile = this.refs.mobileSearchBtn;

    searchBoxMobile.classList.toggle('collapsed');

    if (searchBoxMobile.classList.contains('collapsed')) {
      //console.log(111);
      searchResults.innerHTML = '';
      searchInput.value = '';
    }else{
      //console.log(222);
      searchInput.focus();

      document.getElementById('x-ico').onclick = function(e){
        e.preventDefault();
        searchBoxMobile.classList.toggle('collapsed');
      }
    }


    if(mobileHamburger.classList.contains('collapsed')){
      //console.log(333);
    }else{
      //console.log(11111);
      if (searchBoxMobile.classList.contains('collapsed')){}
      else{
        //console.log(444);
        // click hamburger btn to close
        const evt1 = new MouseEvent('click', {
          bubbles: true, cancelable: true, view: window
        });
        // If cancelled, don't dispatch our event
        const canceled1 = !mobileHamburger.dispatchEvent(evt1);
      }
    }


    mobileHamburger.onclick = function(e){
      if(searchBoxMobile.classList.contains('collapsed')){}
      else{

        if( mobileHamburger.classList.contains('collapsed')){
          const evt2 = new MouseEvent('click', {
            bubbles: true, cancelable: true, view: window
          });
          const canceled2 = !navsearchBtnMobile.dispatchEvent(evt2);
        }

      }
    }
  }

  render() {
    return (
      <a href="#" ref="mobileSearchBtn" onClick={(e) => this._handleClick(e)} >
        <img className="search-ico" src="/images/icons/Search.svg" />
      </a>
    );
  }

}

ReactDOM.render(<NavIOMobile />, document.getElementById('navsearch-btn-1-mobile'));
