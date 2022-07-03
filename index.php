<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>file</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&amp;subset=cyrillic" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
  <script>
    $(document).ready(
      function(e) {
        var form = $('#myform');
        var message = $('#myform_status');
        form.on('submit', function() {
          var formData = new FormData();
          if (($('#myfile')[0].files).length != 0) {
            $.each($('#myfile')[0].files, function(i, file) {
              formData.append("file[" + i + "]", file);
            });
          } else {
            message.text('Нужно выбрать файлы');
            return false;
          }
          $.ajax({
            type: "POST",
            url: "file-upload.php",
            cache: false,
            dataType: "json",
            contentType: false,
            processData: false,
            data: formData,
            beforeSend: function() {
              console.log('Запрос начат');
              message.text('Запрос начат');
              form.find('input').prop("disabled", true);
            },
            success: function(data) {
              console.log('А тут отработать если ок');
              if (data.status == 'ok') {
                $('#myfile').val('');
                message.text('Файлы загружены');
              } else {
                message.text('Что-то не так с загрузкой');
              }
            },
            complete: function(data) {
              console.log('Запрос закончен');
              form.find('input').prop("disabled", false);

            },
            error: function(xhr, ajaxOptions, thrownError) {
              console.log(thrownError);
              message.html('Была ошибка');
            }
          });
          return false;
        });
      }
    );

    function downloadFile(urlToSend) {
      
      const req = new XMLHttpRequest();
      req.open("GET", urlToSend, true);
      req.responseType = "blob";

      req.onload = function(event) {
        const blob = req.response;
        const fileName = req.getResponseHeader("Content-Disposition").split('filename=')[1];//if you have the fileName header available
        const link = document.createElement('a');
        
        link.href = window.URL.createObjectURL(blob);
        link.download = fileName;
        link.click();
      };

      req.send();
    }
  </script>
</head>

<body>
  <div class="container" id="container">
      <button onclick="downloadFile('file-download.php')">get file</button>
    <form id="myform" method="POST" enctype="multipart/form-data">
      <input type="file" name="uploadfile" id="myfile" multiple="multiple">
      <input type="submit">
      <div id="myform_status"></div>
    </form>
  </div>
  <style>
    body {
      background: #777;
      font-family: sans-serif;
      font-size: 1em;
    }

    .container {
      overflow: hidden;
      width: 50%;
      height: 100%;
      margin: 0 auto;
      background: #fff;
    }

    form {
      border: 1px solid #777;
      padding: 2em;
      margin: 2em;
    }

    #myform_status {
      margin-top: 1em;
      font-size: 0.85em;
    }
  </style>
</body>

</html>