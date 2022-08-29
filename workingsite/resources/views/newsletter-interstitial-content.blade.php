<div id='fa21Popup'>

  <a href="{{$href}}" id="intContent"><img src="{{$img_src}}" class="intImg"></img></a>

</div>

<script>

var target = document.getElementById("fa21Popup");

// Register a function to the target's load event handlers:
target.addEventListener("load", doLoad);

// Declare the callback function:
async function doLoad(){
  let url = '/newsletter-interstitial';
  let promiseOfCookie = null;
  await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
  })
  .then((res)=>{
    promiseOfCookie = res;
    return false;
  })
  .catch((err)=> {
    promiseOfCookie = false;
  });
  console.log(promiseOfCookie);
  return promiseOfCookie;
}

//submit event
$('#mc-embedded-subscribe-form').on('submit', async function() {
  let url = '/newsletter-interstitial-submit';
  let promiseOfCookie = null;
  await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
  })
  .then((res)=>{
    promiseOfCookie = res;
    $('#loader').hide();
    return false;
  })
  .catch((err)=> {
    promiseOfCookie = false;
  });
});

// call the function
doLoad();

</script>
