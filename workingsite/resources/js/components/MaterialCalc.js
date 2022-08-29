import React from 'react';
import ReactDOM from 'react-dom';

import axios from 'axios';

import Utilities from './Utilities';

class MaterialCalcForm extends React.Component {

    constructor(props) {
      super(props);
      this.state = {width: '', iwidth: this.props.width, wwidth: this.props.width, length: '', sqfoot: '', calculate: '', resultClass: ''};

      this.handleWidth = this.handleWidth.bind(this);
      this.handleiWidth = this.handleiWidth.bind(this);
      this.handlewWidth = this.handlewWidth.bind(this);
      this.handleLength = this.handleLength.bind(this);
      this.handleSqfoot = this.handleSqfoot.bind(this);
      this.handleLength = this.handleLength.bind(this);
      this.handleSubmit = this.handleSubmit.bind(this);
      this.resultClass = 'result hide';
    }

    get_url() {
      let url = "/item/";
      if(this.props.type == "item") {
        url += this.props.item;
      }
      else url += this.props.item + "/" + this.props.sku.toLowerCase();
      return url;
    }

    handleiWidth(event) {
      this.setState({iwidth: event.target.value.replace(/[^0-9]/g, '')});
    }
    handlewWidth(event) {
      this.setState({wwidth: event.target.value.replace(/[^0-9]/g, '')});
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
      const itemWidth = parseInt(this.state.iwidth);
      const wallWidth = parseInt(this.state.wwidth);

      //console.log("item width:" + itemWidth);

      // ((A x B)x144)/(36 x ITEM WIDTH)
      // console.log("Width: " + this.state.width); console.log("Length: " + this.state.length);
      let aa = 0;


      if(itemWidth == NaN)
        return false;

      if(itemWidth != NaN && itemWidth != 0 && this.state.width !='' && this.state.length !='')
        aa = Math.ceil( ((this.state.width * this.state.length) * 144) / (36 * itemWidth ) );

      // (C x 144)/(36 x ITEM WIDTH)
      //console.log("SQfoot: " + this.state.sqfoot);
      let cc = 0;
      if(wallWidth != NaN && wallWidth != 0 && this.state.sqfoot != '' && this.state.sqfoot != NaN)
        cc = Math.ceil( (this.state.sqfoot * 144) / (36 * wallWidth ) );

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

    // console.log('aa:' + aa); console.log('cc:' + cc);

      //console.log(rr);
      this.setState({ calculate: rr});

      event.preventDefault();
    }


    render() {
      return (
        <div>
          <form onSubmit={this.handleSubmit} action="javascript:void(0);" >
            <label htmlFor="item-width">Width of Wallcovering (inches)</label>
            <input id="item-width" name="iwidth" type="text" placeholder="Width of Wallcovering (inches)" value={this.state.iwidth} onChange={this.handleiWidth}/>
            <label htmlFor="width-of-room">Width of Room (feet)</label>
            <input id="width-of-room" name="width" type="text" placeholder="Width of Room (feet)" value={this.state.width} onChange={this.handleWidth} />
            <label htmlFor="length-of-room">Height of Room (feet)</label>
            <input id="length-of-room" name="length" type="text" placeholder="Height of Room (feet)" value={this.state.length} onChange={this.handleLength} />
            <p>Please enter the numbers in total feet. If the measurement is in inches, please round up to the nearest full foot for both height and width.</p>
            <p>OR</p>
            <label htmlFor="width-of-wall">Width of Wallcovering (inches)</label>
            <input id="width-of-wall" type="text" placeholder="Width of Wallcovering (inches)"value={this.state.wwidth} onChange={this.handlewWidth} />
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
            </div>
          </div>
          {this.props.type ? <div class="row"><h5 class="skuTitle mx-auto"><a class="mx-auto" href={this.get_url()}><i class="fa fa-undo"></i>&nbsp;Back to main product page</a></h5></div> : ''}
        </div>
      );
    }
  }

  if (document.getElementById('materialcalc-container')) {
    let type = document.getElementById('materialcalc-container').dataset.type;
    let item = document.getElementById('materialcalc-container').dataset.item;
    let sku = document.getElementById('materialcalc-container').dataset.sku;
    let width = document.getElementById('materialcalc-container').dataset.width;

    ReactDOM.render(<MaterialCalcForm type={type} item={item} sku={sku} width={width}/>, document.getElementById('materialcalc-container'));
  }
