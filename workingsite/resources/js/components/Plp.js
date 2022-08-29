import React from 'react';
import ReactDOM from 'react-dom';

import axios from 'axios';

import Utilities from './Utilities';

import InfiniteScroll from 'react-infinite-scroller'

class PlpSampleBtn extends React.Component {
  constructor() {
    super();
  }

  _handleClick(){

    let cartSampleCount = document.getElementById("cart-count");
    // make button non click
    this.refs.samplebtn.setAttribute("disabled", "disabled");

    let url = '/item-add';

    let params = {
      "itemnum" : this.props.itemNumber,
      "itemname" : this.props.itemName,
      "itemcolor" : this.props.itemColor,
      "price" : this.props.itemPrice,
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
     // console.log(response.data)
      //this.setState(
        if(Number(response.data)>0){
          cartSampleCount.classList.add("red-dot");
        }else{
          cartSampleCount.classList.remove("red-dot");
        }
      //)
    })
    .catch(function (error) {
        console.log(error);
    });
  }

  render() {
    return (
        <button ref="samplebtn" onClick={(e) => this._handleClick()} type="button" className="btn btn-primary" >ORDER A SAMPLE</button>
    );
  }
}

class PlpPreviewBtn extends React.Component {

  constructor() {
    super();
    this.state = { datatitle: "", producttype:"" };
  }

  componentDidMount() {
    this.setState({
      datatitle: ReactDOM.findDOMNode(this).parentNode.getAttribute("data-title"),
      producttype: ReactDOM.findDOMNode(this).parentNode.getAttribute("data-producttype"),
    });
  }

  _handleClick(v1, v2){

    const plpModalImg1 = document.getElementById("plp-modal-img1");

    const downloadImage = document.getElementById("download-image");
    const downloadImage2 = document.getElementById("download-image-2");

    const plpItemUl = document.getElementById("plp-item-ul");
    const plpProductUl = document.getElementById("plp-product-ul");

    let plpModalTitle = document.getElementsByClassName("plp-modal-title")[0];
    let plpModalNumColor = document.getElementById("plp-modal-num-color");
    const plpModalPrice = document.getElementById("plp-modal-price");


    let url = '/item/' + v1;
    if(v2=='item'){
      // or v2 = 'product'
      url = '/item-filter/' + this.urlNameStr(v1);
    }

    axios
    .post(url)
    .then(response => {
      //console.log(response)


      if(v2=='item'){
        //this.setState(

          plpModalImg1.setAttribute('src', this.urlStrImg(response.data[0].item_number, v2));

          downloadImage.setAttribute('data-downimage', this.urlStrImg(response.data[0].item_number, v2));
          downloadImage2.setAttribute('data-downimage', this.urlStrImg(response.data[0].item_number, v2));

          plpModalImg1.onload = function () {
            plpItemUl.classList.remove('hide');
          }

          plpModalTitle.innerText = response.data[0].item_name;
          plpModalNumColor.innerText = response.data[0].item_number + ' ' + response.data[0].color_name;

          ReactDOM.render(<PlpSampleBtn
                              itemNumber={response.data[0].item_number}
                              itemName={response.data[0].item_name}
                              itemColor={response.data[0].color_name}
                              itemPrice={response.data[0].wholesale_price}
                          />, document.getElementById('plp-sample-btn'));

          //this.util = new Utilities();

          let itemName = response.data[0].item_name.replace(/\s+/g, '-');
          let learnUrl = '/item/' + itemName.toLowerCase() + '/' + response.data[0].item_number.toLowerCase();

          let learnmore = document.getElementById('plp-item-ul').querySelector('.learnmore');
          learnmore.onclick = function(e){
            window.location.href = learnUrl;
          };

        //)
      }else{
        // product
        // console.log(response);
        //this.setState(
          // response.data[0].fabricName
          plpModalImg1.setAttribute('src', this.urlStrImg(v1, v2));

          //console.log(this.urlStrImg(v1, v2));
          downloadImage.setAttribute('data-downimage', this.urlStrImg(v1, v2));
          downloadImage2.setAttribute('data-downimage', this.urlStrImg(v1, v2));

          plpModalImg1.onload = function () {
            plpProductUl.classList.remove('hide');
          }

          plpModalTitle = document.getElementsByClassName("plp-modal-title")[1];

          plpModalTitle.innerText = response.data['sku'][0].fabricName;

          //console.log(response.data['sku'][0].fabricName);

          let productName = response.data['sku'][0].fabricName.replace(/\s+/g, '-');
          let learnUrl = '/item/' + productName.toLowerCase();

          let learnmore = document.getElementById('plp-product-ul').querySelector('.learnmore');
          learnmore.onclick = function(e){
            window.location.href = learnUrl;
          };

        //)
      }

    })
    .catch(function (error) {
        console.log(error);
    });
  }

  titleCase(str) {
   var splitStr = str.toLowerCase().split(' ');
   for (var i = 0; i < splitStr.length; i++) {
      splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
   }

   return splitStr.join(' ');
  }

  urlNameStr(v1){
    let v2 = v1.replace(/\s+/g, '-').toLowerCase();
    return v2;
  }

  jpgNameStr(v1){
    let v2 = this.titleCase(v1);
    let v3 = v2.replace(/\s+/g, '-');
    return v3;
  }

  urlStrImg(t1, t2){
    const mainurl = (process.env.NODE_ENV === 'development')
    ? '//dev1.innovationsusa.com'
    : 'https://www.innovationsusa.com';
    const imgdir = 'storage';
    let url_1;

    if(t2=='item'){
      url_1 = mainurl+'/'+imgdir+'/sku/900x900/' + t1 + '.jpg';
    }else{
      // t2= 'product'
      //t1 = this.jpgNameStr(t1);
      t1 = t1.toLowerCase();

      url_1 = mainurl+'/'+imgdir+'/product/900x900/' + t1 + '.jpg';
    }

    return url_1;
  }

  render() {
    return (
        <a onClick={(e) => this._handleClick(this.state.datatitle, this.state.producttype)} type="button" className="preview" data-toggle="modal" data-target="#modal-product-container">Preview</a>
    );
  }
}

class ProductFilterBtn extends React.Component {
  constructor() {
    super();
    this.state = { filterType: "", filterClear: "" };
  }

  componentDidMount() {
    this.setState({
      filterType: ReactDOM.findDOMNode(this).parentNode.getAttribute("data-filter"),
      filterClear: ReactDOM.findDOMNode(this).parentNode.getAttribute("data-filter"),
    });
  }

  _handleClick_1(v1){


    //let arr = [];
    let url = window.location.href,
        filterUrl = '';


      // filterType
      if(v1=='all-wallcovering'){

        // aggregate all filters
        const filterContainer = document.getElementsByClassName('filter-container');
        for(var ii=0; ii<filterContainer.length; ii++){
          let idVal = filterContainer[ii].id;

          let aInputs = filterContainer[ii].getElementsByTagName('input'),
              checkboxes = filterContainer[ii].querySelectorAll('input[type="checkbox"]'),
              labels = filterContainer[ii].getElementsByTagName('label');

          filterUrl = filterUrl.replace(/\+(\s+)?$/, ''); // remove +

          let omit = 0;
          //if (checkboxes[ii].checked == true){
            filterUrl += '&' + idVal + '=';
          //}

          for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked == true){
              //arr.push(labels[i].innerHTML);  //checkboxes[i].checked = true;
              let str = labels[i].innerHTML.replace(/\s/g, '-');
              //str = str.replace("/", '-'); // this only grabs first /
              str = str.replace(/\//g, '-'); // all instance
              filterUrl +=  str + '+';
              // &color=Blue+Brown+Copper&material=Vinyl&pattern=Organic+Geometric-Linear&texture=&collection=2020-Spring
            }else{
              omit++;
            }
          }

          // remove empty filters
          if(checkboxes.length == omit){
            var xx = '&' + idVal + '=';
            filterUrl = filterUrl.replace(xx,'');
          }

        } // for(var ii=0; ii<filterContainer.length; ii++)

        if(filterUrl!=''){
          // knock out everything in url after filter title
          let url_1 = url.substring(0, url.indexOf(v1));
          filterUrl = filterUrl.replace(/\+(\s+)?$/, ''); // remove last +
          filterUrl = filterUrl.substring(1); // remove first char of str
          filterUrl = '?' + filterUrl.toLowerCase();

          //console.log(filterUrl);
          window.location.href = url_1 + v1 +filterUrl;
        } // if(filterUrl!='')

      }else{

        let filterCheckboxContainer = document.getElementById("product-filter");

        let aInputs = filterCheckboxContainer.getElementsByTagName('input'),
            checkboxes = filterCheckboxContainer.querySelectorAll('input[type="checkbox"]'),
            labels = filterCheckboxContainer.getElementsByTagName('label');

        for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].checked == true){
            //arr.push(labels[i].innerHTML);  //checkboxes[i].checked = true;
            let str = labels[i].innerHTML.replace(" ", '-');
            //str = str.replace("/", '-');
            str = str.replace(/\//g, '-');
            filterUrl += str + '+';
          }
        }

        if(filterUrl!=''){
          // knock out everything in url after filter title
          let url_1 = url.substring(0, url.indexOf(v1));
          filterUrl = filterUrl.replace(/\+(\s+)?$/, ''); // remove last +
          window.location.href = url_1 + v1+ '/' +filterUrl.toLowerCase();
        }
    }

  }
  _handleClick_2(v1){
    let filterCheckboxContainer = document.getElementById("product-filter");

    let aInputs = filterCheckboxContainer.getElementsByTagName('input'),
        checkboxes = filterCheckboxContainer.querySelectorAll('input[type="checkbox"]'),
        labels = filterCheckboxContainer.getElementsByTagName('label');
    let url = window.location.href,
        filterUrl = '';

    for (var i = 0; i < checkboxes.length; i++) {
      checkboxes[i].checked = false;
    }

    let url_1 = url.substring(0, url.indexOf(v1));

    window.location.href = url_1 + v1;
  }
  render() {
    return (
      <div>
        <button onClick={(e) => this._handleClick_1(this.state.filterType)} type="button" className="btn btn-primary" >Apply</button>
        <button onClick={(e) => this._handleClick_2(this.state.filterClear)} type="button" className="btn btn-primary simple" style={{marginBottom:"150px"}}>Clear All</button>
      </div>
    );
  }
}

if (document.getElementById('productcontainer')) {
  Array.prototype.forEach.call(
    document.getElementsByClassName('plp-preview-button'),
    function(el) {
      ReactDOM.render(<PlpPreviewBtn />, el)
    }
  )
}

if (document.getElementById('product-filter-btn')) {
  ReactDOM.render(<ProductFilterBtn />, document.getElementById('product-filter-btn'));
}


class DynamicLoad extends React.Component {

  constructor(props) {
    super(props);

    this.state = {
        tracks: [],
        hasMoreItems: true,
        p1:2
        //nextHref: null,
    };
  }

  loadItems(page) {

    const baseUrl = (process.env.NODE_ENV === 'development')
    ? '//dev1.innovationsusa.com'
    : 'https://www.innovationsusa.com';
    const myUrl = window.location.href;
    var myFilter = myUrl.match(/product(.*)/);
    //console.log(myFilter[1]);

    var tt1 = 'product';
    if(myFilter){}
    else{
      tt1 = 'search';
      myFilter = myUrl.match(/search(.*)/);
    }
    var self = this;
    //var p1 = 2;

    let nn = myFilter[1].includes("?");

    let gVar = '&';
    if(nn == false)
      gVar = '?';

    var url = '/product' + myFilter[1] + gVar + 'page=' + this.state.p1;

    if(tt1=='search'){
      url = '/search' + myFilter[1] + gVar + 'page=' + this.state.p1;
    }

    //console.log(url);

    let params = {
      "lazyload" : "true",
    };

    axios({
      method: 'post',
      url: url,
      data: params,
      config: { headers: {
        'Content-Type': 'application/json',
        //'Access-Control-Allow-Methods': 'GET, POST'
        //'X-CSRF-TOKEN': csrftoken,
      }}
    })
    .then( response =>{
        //console.log(response.data);
        this.state.p1++;

        var arr = response.data.mainArr;
        //console.log(arr);
        if(tt1=='search'){
           arr = response.data;
        }
        //console.log(arr.length);
        for(var ii = 0; ii < arr.length; ii++){

          //filtersArr == '' ||
         if( arr[ii].type == 'product' || ( response.data.pageId == 'whats-new' ) ){
           let mainImage = arr[ii].mainImage;

           if(mainImage == 'NULL' || mainImage == "" || mainImage == null) {
            mainImage = arr[ii].jpgName1 + '.jpg?v=' + '0.01';
           } else {
            mainImage = '/storage/'+arr[ii].mainImage+ '?v=' + '0.01';
           }

            this.state.tracks.push({
              'displayname': arr[ii].displayName1,
              'dbname': arr[ii].dbName,
              'urlName': arr[ii].urlName1,
              'url': "/item/" + arr[ii].urlName1,
              'thumbType': 'product',
              'mainImgUrl': mainImage
            });

          }
          else{
            //imgdir = 'colordetail_184x184';
            //$img = $item['jpgName'] . '.jpg';

            if( arr[ii].displayName==null ||
                arr[ii].displayName==''){}
            else{
              this.state.tracks.push({
                'displayname': arr[ii].displayName,
                'dbname': arr[ii].dbName,
                'urlName': arr[ii].dbName,
                'url': "/item/" + arr[ii].itemName + '/' + arr[ii].urlName,
                'thumbType': 'item',
                'mainImgUrl': arr[ii].jpgName
              });
            }

          }

        }// for()

        //console.log(this.state.tracks);

       if(arr.length > 0) {
          self.setState({
              tracks: this.state.tracks,
              //nextHref: this.state.p1
          });
        } else {
          self.setState({
              hasMoreItems: false
          });
        }

    })
    .catch(function (error) {
        console.log(error);
    });

  }

  render() {
    const loader = <div className="loader" key={0} >Loading ...</div>;
    //console.log(this.state.tracks);
    var items = [];
    this.state.tracks.map( (track, ii) => {
        items.push(
          <li className='col-6 col-md-4 mb-0' key={ii}>
            <div className="inner">
              <a className='fabricName' href={ track.url } >
                <img src={ track.mainImgUrl }  />
                <p className="skuWallTitle">{ track.displayname }</p>
              </a>
              <span className="plp-preview-button" data-title={ track.urlName } data-producttype={track.thumbType}><PlpPreviewBtn /></span>
            </div>
          </li>
        );
    });
    return (
      <InfiniteScroll
          pageStart={this.state.p1}
          loadMore={this.loadItems.bind(this)}
          hasMore={this.state.hasMoreItems}
          initialLoad={false}
          >

          <ul className="row">
            {items}
          </ul>
      </InfiniteScroll>
    );
  }
};

if (document.getElementById('productcontainer')) {
  ReactDOM.render(
    <DynamicLoad /> , document.getElementById('dynamic-load')
  );
}
