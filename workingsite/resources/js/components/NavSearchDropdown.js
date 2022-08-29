import React, { useState, useEffect } from "react";
import ReactDOM from 'react-dom';


class NavSearchDropdown extends React.Component {

  /*constructor() {
    super();
  }*/

  makeList( searchStr, elem, navsearch, items ) {
        
    //      //      //      //      //
    // https://stackoverflow.com/questions/42036865/react-how-to-navigate-through-list-by-arrow-keys
    // https://codesandbox.io/s/react-hooks-navigate-list-with-keyboard-eowzo
    //      //      //      //      //

    const useKeyPress = function(targetKey) {
      const [keyPressed, setKeyPressed] = useState(false);

      function downHandler({ key }) {
        if (key === targetKey) {
          setKeyPressed(true);
        }
      }

      const upHandler = ({ key }) => {
        if (key === targetKey) {
          setKeyPressed(false);
        }
      };

      React.useEffect(() => {
        window.addEventListener("keydown", downHandler);
        window.addEventListener("keyup", upHandler);

        return () => {
          window.removeEventListener("keydown", downHandler);
          window.removeEventListener("keyup", upHandler);
        };
      });

      return keyPressed;
    };
    
    let hrefStr = '';
    
    const hrefGo = (str) =>{
      str = str.toLowerCase();
      str = str.replace(/\s/g, '-'); // replace empty with dash
      str = encodeURI(str);
      window.location.href = str;
    }

    // search <input />
    navsearch.addEventListener("keyup", function(event) {
      if (event.keyCode === 13) {
        // /search page
        let myVal = this.value;
        let myHref = '/search/' + myVal;

        if(hrefStr!=''){
          myHref = hrefStr;
        }  
        
        hrefGo(myHref);
      }
    });
    
    const slideOutBtn = document.querySelectorAll(".slide-out-search-btn");


    for (var ii=0; ii<slideOutBtn.length; ii++) {
      slideOutBtn[ii].onclick = function() {
        event.preventDefault();
        let myVal = navsearch.value;
        let myHref = '/search/' + myVal;

        if(hrefStr!=''){
          myHref = hrefStr;
        }  

        hrefGo(myHref);
      };
    }


    const onClickFunc = (setSelected, setCursor, item, index) => {
      setSelected(item); 
      setCursor(index);

      //navsearch.value = item.s1; //item.item_name + ' ' + item.item_number;
      //navsearch.focus();

      //hrefStr = '/item/' + item.item_name + '/' + item.item_number;
      hrefStr = item.s4;  
      hrefGo(hrefStr);  
    }

    // return html list
    const ListItem = ({ item, active, setSelected, setHovered, setCursor, index }) => (
      <div
        className={`item ${active ? "active" : ""}`}
        onClick={() =>  onClickFunc(setSelected, setCursor, item, index)}
        onMouseEnter={() => setHovered(item)}
        onMouseLeave={() => setHovered(undefined)}
      >
        <span dangerouslySetInnerHTML={{__html: item.s1}} />&nbsp;<span dangerouslySetInnerHTML={{__html: item.s2}} />
      </div>
    );

    const ListExample = () => {
      const [selected, setSelected] = useState(undefined);
      const downPress = useKeyPress("ArrowDown");
      const upPress = useKeyPress("ArrowUp");
      const enterPress = useKeyPress("Enter");
      const [cursor, setCursor] = useState(-1); // 0 selects first item in arr
      const [hovered, setHovered] = useState(undefined);

      useEffect(() => {
        if (items.length && downPress) {
          setCursor(prevState => prevState < items.length - 1 ? prevState + 1 : prevState);
          
          let ii = cursor < items.length - 1 ? cursor + 1 : cursor;
          //navsearch.value = items[ii].s1;

          //navsearch.blur(); // moves the page up/down
        }
      }, [downPress]);
      useEffect(() => {
        if (items.length && upPress) {
          setCursor(prevState => prevState > 0 ? prevState - 1 : prevState);

          let ii = cursor > 0 ? cursor - 1 : cursor;
          //navsearch.value = items[ii].s1;

          //navsearch.blur();
        }
      }, [upPress]);
      useEffect(() => {
        if (items.length && enterPress) {
          setSelected(items[cursor]);
          
          // PDP
          //hrefStr = '/item/' + items[cursor].item_name + '/' + items[cursor].item_number;
          hrefStr = items[cursor].s4;
          
          navsearch.focus();
          //hrefGo(hrefStr); // doesnt work
          //window.location.href = hrefStr;
          //console.log(items[cursor]); //Object { item_number: "ELP-010", item_name: "El Paso" }
        }
      }, [enterPress]); // cursor, 
      useEffect(() => {
        if (items.length && hovered) {
          //let ii = items.indexOf(hovered);
          //setCursor(ii);
          // navsearch.value = items[ii].item_name + ' ' + items[ii].item_number;
        }
      }, [hovered]);
      
      if(items.length > 0){
        return (
          <div>
            {items.map((item, ii) => (
              <ListItem
                key={ii} // item.key
                active={ii === cursor}
                item={item}
                setSelected={setSelected}
                setHovered={setHovered}
                setCursor={setCursor}
                index={ii}
              />
            ))}
          </div>
        );

      }else{
        // <--span>Selected: {selected ? selected.item_name + ' ' + selected.item_number : "none"}</span-->
        return (
          <div>
            <p>
              <small>
                Please try a different search phrase.
              </small>
            </p>
          </div>
        );

      }

    };
    ReactDOM.render(<ListExample />, elem);
    

  }// makeList()
  
  

}
export default NavSearchDropdown;
//const util = new Utilities();