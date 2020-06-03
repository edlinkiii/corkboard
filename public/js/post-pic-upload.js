$q().on('click','#img-upload-trigger', () => { $q('#img-upload').click(); });
$q().on('change', '#img-upload', (e) => {
  console.log(e);
  let $input = e.target;
  if($input.files.length == 0) {
    alert('Error: No file selected');
    return;
  }

  let file = $input.files[0];
  let mime_types = ['image/jpeg', 'image/png', 'image/gif'];
  if(!mime_types.includes(file.type)) {
    alert('Error : Incorrect file type ('+file.type+')');
    return;
  }

  if(file.size > (2 * 1024 * 1024)) {
    alert('Error: Exceeded size 2MB');
    return;
  }

  let data = new FormData();
  data.append('file', file);

  let request = new XMLHttpRequest();
  request.open('post', URLBASE + '/posts/pic');

  request.upload.addEventListener('progress', function(e) {
    let percent_complete = ((e.loaded / e.total) * 100);
    console.log(percent_complete);
  });

  request.addEventListener('load', function(e) {
    console.log(request.status);
    console.log(request.response);
    if(request.response.substring(0,5) === 'Error') {
      alert(request.response);
    }
    else {
      $q('#img-holder').html('<img class="post-pic" src="'+ URLBASE +'/images/post_pic/'+ request.response +'" /><br />');
      $q('input#img').val(request.response);
      console.log($q('input#img').val());
    }
  });

  request.send(data);
});
