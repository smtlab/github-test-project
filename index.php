<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">

    <title>Hello, world!</title>

  </head>
  <body>

    <div class="container">
      <div class="row">
        <div class="co-md-6 offset-md-3 my-4 ">
          <h1>Github Symfony Repositories!</h1>
          <strong id="loading">Loading..</strong>
          <ul class="list-unstyled" id="repos">
          </ul>
          <ul id="pagination" class="pagination-sm"></ul>
        </div>
      </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/assets/jquery.twbsPagination.min.js"></script>

    <script>
    $(document).ready(function() {

      var load = function(page) {
        $('#repos').empty();
        $('#loading').show();
        $.ajax({
          url: '/api.php',
          type: 'get',
          data: {
            page: page
          },
          success: function(d) {
            $('#loading').hide();
            $.each(d.data, function(i,v) {

              $('#repos').append(
                '<li class="media">'+
                  '<div class="media-body">'+
                    '<h5 class="mt-0 mb-1">'+v.full_name+'</h5>'+
                    '<p>'+v.name+'</p>'+
                  '</div>'+
                '</li>'
              );

            })
          }
        });

      }

        var $pagination = $('#pagination').twbsPagination({
            totalPages: 10,
            visiblePages: 5,
            onPageClick: function (event, page) {
              console.log('onPageCLick')
                load(page);
            }
        });


    } );
    </script>
  </body>
</html>
